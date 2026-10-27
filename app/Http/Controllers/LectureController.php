<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
use App\Models\LectureMaterial;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class LectureController extends Controller
{
    public function index(Request $request)
    {
        $query = Lecture::with('instructor')->latest();

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        $lectures = $query->paginate(10)->withQueryString();
        return view('lectures.index', compact('lectures'));
    }

    public function create()
    {
        return view('lectures.create', ['videoMaxMb' => $this->videoUploadMaxMb()]);
    }

    public function store(Request $request)
    {
        $this->checkServerUploadLimit($request);

        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'video_url'        => 'nullable|url|max:255',
            'video_file'       => 'nullable|file|mimes:mp4,webm,ogg,mov,m4v|max:' . $this->videoUploadMaxKb(),
            'category'         => 'nullable|string|max:100',
            'status'           => 'required|in:published,draft',
            'level'            => 'required|in:all,first,second,third',
            'duration_minutes' => 'nullable|integer|min:1',
            'leaderboard_limit' => 'nullable|integer|min:1|max:1000',
            'materials.*'      => 'nullable|file|mimes:pdf,ppt,pptx|max:20480', // 20MB max per file
        ], $this->uploadValidationMessages());

        // Resolve the video from an uploaded file, a pasted link, or neither.
        $validated = array_merge($validated, $this->resolveVideoInput($request, null));
        $validated['user_id'] = auth()->id();
        $validated['category'] = $request->filled('category') ? $request->category : 'General';

        $validated['show_leaderboard']  = $request->boolean('show_leaderboard');
        $validated['leaderboard_limit'] = $request->filled('leaderboard_limit') ? (int) $request->leaderboard_limit : null;

        $lecture = Lecture::create($validated);

        $this->handleMaterialUploads($request, $lecture);

        $this->enrollMatchingStudents($lecture);

        return redirect()->route('lectures.index')
            ->with('success', 'تم إنشاء المحاضرة بنجاح!');
    }

    public function show(Lecture $lecture)
    {
        $lecture->load(['students', 'quizzes', 'materials']);
        return view('lectures.show', compact('lecture'));
    }

    public function edit(Lecture $lecture)
    {
        $lecture->load('materials');
        return view('lectures.edit', compact('lecture') + ['videoMaxMb' => $this->videoUploadMaxMb()]);
    }

    public function update(Request $request, Lecture $lecture)
    {
        $this->checkServerUploadLimit($request);

        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'video_url'        => 'nullable|url|max:255',
            'video_file'       => 'nullable|file|mimes:mp4,webm,ogg,mov,m4v|max:' . $this->videoUploadMaxKb(),
            'category'         => 'nullable|string|max:100',
            'status'           => 'required|in:published,draft',
            'level'            => 'required|in:all,first,second,third',
            'duration_minutes' => 'nullable|integer|min:1',
            'leaderboard_limit' => 'nullable|integer|min:1|max:1000',
            'materials.*'      => 'nullable|file|mimes:pdf,ppt,pptx|max:20480',
        ], $this->uploadValidationMessages());

        $oldVideoPath = $lecture->video_provider === 'upload' ? $lecture->video_id : null;

        $validated = array_merge($validated, $this->resolveVideoInput($request, $lecture));
        $validated['category'] = $request->filled('category') ? $request->category : ($lecture->category ?: 'General');

        $validated['show_leaderboard']  = $request->boolean('show_leaderboard');
        $validated['leaderboard_limit'] = $request->filled('leaderboard_limit') ? (int) $request->leaderboard_limit : null;

        $lecture->update($validated);

        if ($oldVideoPath && $oldVideoPath !== $lecture->video_id) {
            Storage::disk('local')->delete($oldVideoPath);
        }

        $this->handleMaterialUploads($request, $lecture);

        $this->enrollMatchingStudents($lecture);

        return redirect()->route('lectures.index')
            ->with('success', 'تم تحديث المحاضرة بنجاح!');
    }

    public function destroy(Lecture $lecture)
    {
        foreach ($lecture->materials as $material) {
            Storage::disk('public')->delete($material->file_path);
        }
        if ($lecture->video_provider === 'upload' && $lecture->video_id) {
            Storage::disk('local')->delete($lecture->video_id);
        }
        $lecture->delete();
        return redirect()->route('lectures.index')
            ->with('success', 'تم حذف المحاضرة.');
    }

    // ── Delete a single material file ──
    public function destroyMaterial(LectureMaterial $material)
    {
        Storage::disk('public')->delete($material->file_path);
        $lectureId = $material->lecture_id;
        $material->delete();

        return redirect()->route('lectures.edit', $lectureId)
            ->with('success', 'تم حذف الملف.');
    }

    /**
     * Stream a self-hosted video (or redirect to a direct MP4 link) after
     * checking that the viewer is an admin or an entitled student.
     */
    public function streamVideo(Lecture $lecture)
    {
        $this->authorizeVideoAccess($lecture);

        if ($lecture->video_provider === 'url') {
            return redirect()->away($lecture->video_id);
        }

        if ($lecture->video_provider !== 'upload' || !$lecture->video_id) {
            abort(404);
        }

        $disk = Storage::disk('local');
        if (!$disk->exists($lecture->video_id)) {
            abort(404);
        }

        $path = $disk->path($lecture->video_id);
        $mime = mime_content_type($path) ?: 'video/mp4';

        $response = response()->file($path, [
            'Content-Type'          => $mime,
            'Content-Disposition'   => 'inline',
            'X-Content-Type-Options' => 'nosniff',
        ]);

        $response->setPrivate();
        $response->headers->set('Cache-Control', 'private, no-store, max-age=0');

        return $response;
    }

    private function authorizeVideoAccess(Lecture $lecture): void
    {
        $user = auth()->user();
        if (!$user) abort(403);

        if ($user->isAdmin()) return;

        $student = $user->student;
        if (!$student) abort(403);

        if ($student->hasActiveSubscription()) {
            if (!$student->lectures()->whereKey($lecture->id)->exists()) {
                abort(403);
            }
            return;
        }

        if (!$student->hasUnlockedLecture($lecture)) {
            abort(403);
        }
    }

    /**
     * Whenever a lecture is published (created or updated), enroll every
     * existing student whose level matches, or if the lecture is set to
     * "all levels". This mirrors the auto-enrollment that happens at
     * student registration time, so lectures added/published LATER also
     * reach students who already signed up.
     */
    private function enrollMatchingStudents(Lecture $lecture): void
    {
        if ($lecture->status !== 'published') {
            return;
        }

        $studentsQuery = Student::query();

        if ($lecture->level !== 'all') {
            $studentsQuery->where('level', $lecture->level);
        }

        $studentIds = $studentsQuery->pluck('id');

        if ($studentIds->isNotEmpty()) {
            $lecture->students()->syncWithoutDetaching($studentIds);
        }
    }

    private function uploadValidationMessages(): array
    {
        $isAr = app()->getLocale() === 'ar';

        return [
            'materials.*.max'   => $isAr
                ? 'حجم الملف ":input" أكبر من الحد المسموح (20 ميجابايت).'
                : 'The file ":input" is larger than the allowed limit (20MB).',
            'materials.*.mimes' => $isAr
                ? 'يجب أن يكون الملف من نوع PDF أو PPT أو PPTX فقط.'
                : 'The file must be a PDF, PPT, or PPTX.',
            'video_file.max'    => $isAr
                ? 'حجم ملف الفيديو أكبر من الحد المسموح على السيرفر (' . $this->videoUploadMaxMb() . ' ميجابايت). استخدم رابط MP4 مباشر للفيديوهات الكبيرة.'
                : 'The video file exceeds the server limit (' . $this->videoUploadMaxMb() . 'MB). Use a direct MP4 link for larger videos.',
            'video_file.mimes'  => $isAr
                ? 'يجب أن يكون ملف الفيديو بصيغة MP4 أو WebM أو OGG أو MOV.'
                : 'The video must be an MP4, WebM, OGG, or MOV file.',
        ];
    }

    /**
     * Highest video upload allowed by the server, in kilobytes. Uses the
     * smaller of upload_max_filesize / post_max_size and leaves headroom
     * for the rest of the multipart form.
     */
    private function videoUploadMaxKb(): int
    {
        $sizes = array_filter([
            $this->iniSizeToBytes(ini_get('upload_max_filesize')),
            $this->iniSizeToBytes(ini_get('post_max_size')),
        ], fn ($bytes) => $bytes > 0);

        $bytes = $sizes ? min($sizes) : 2 * 1048576;

        return max(1, intdiv($bytes, 1024) - 512);
    }

    private function videoUploadMaxMb(): float
    {
        return round($this->videoUploadMaxKb() / 1024, 1);
    }

    /**
     * Detect when the SERVER (not Laravel) silently truncated the upload because
     * the file exceeded php.ini's post_max_size / upload_max_filesize.
     * When this happens, $_POST and $_FILES arrive empty/incomplete even though
     * the form had data - this is the classic shared-hosting symptom.
     */
    private function checkServerUploadLimit(Request $request): void
    {
        $contentLength = (int) $request->server('CONTENT_LENGTH', 0);
        $postMaxBytes  = $this->iniSizeToBytes(ini_get('post_max_size'));

        if ($contentLength > 0 && $postMaxBytes > 0 && $contentLength > $postMaxBytes && empty($_POST) && $request->method() === 'POST') {
            $limitMb = round($postMaxBytes / 1048576);
            session()->flash('upload_error', app()->getLocale() === 'ar'
                ? "الملف كبير جداً بالنسبة لإعدادات السيرفر الحالية (الحد الأقصى المسموح به: {$limitMb} ميجابايت). يرجى ضغط الملف أو رفع ملف أصغر."
                : "The file is too large for the current server settings (max allowed: {$limitMb}MB). Please compress it or upload a smaller file.");

            redirect()->back()->withInput()->send();
            exit;
        }
    }

    private function iniSizeToBytes(string $val): int
    {
        $val = trim($val);
        $last = strtolower($val[strlen($val) - 1] ?? '');
        $num  = (int) $val;
        return match($last) {
            'g' => $num * 1073741824,
            'm' => $num * 1048576,
            'k' => $num * 1024,
            default => $num,
        };
    }

    /**
     * Decide where the lecture video comes from, in priority order:
     * uploaded file → "remove video" → pasted link → keep existing upload.
     */
    private function resolveVideoInput(Request $request, ?Lecture $lecture): array
    {
        if ($request->hasFile('video_file')) {
            $file = $request->file('video_file');

            if (!$file->isValid()) {
                throw ValidationException::withMessages([
                    'video_file' => app()->getLocale() === 'ar'
                        ? 'تعذر رفع ملف الفيديو. حاول مرة أخرى أو استخدم رابط MP4 مباشر.'
                        : 'The video upload failed. Try again or use a direct MP4 link.',
                ]);
            }

            $path = $file->store('lecture-videos', 'local');

            return [
                'video_provider' => 'upload',
                'video_id'       => $path,
                'video_url'      => null,
            ];
        }

        if ($request->boolean('remove_video')) {
            return [
                'video_provider' => 'youtube',
                'video_id'       => null,
                'video_url'      => null,
            ];
        }

        if ($request->filled('video_url')) {
            $videoData = Lecture::parseVideoUrl($request->video_url);

            if (!$videoData['id']) {
                $message = app()->getLocale() === 'ar'
                    ? 'رابط الفيديو غير مدعوم. الصق رابط يوتيوب أو فيميو صالح، أو رابط ملف MP4/WebM مباشر.'
                    : 'Unsupported video link. Paste a valid YouTube/Vimeo URL, or a direct MP4/WebM file link.';

                throw ValidationException::withMessages(['video_url' => $message]);
            }

            return [
                'video_provider' => $videoData['provider'],
                'video_id'       => $videoData['id'],
                'video_url'      => $request->video_url,
            ];
        }

        // Editing other fields of a lecture that already has an uploaded video:
        // keep the file instead of wiping it because the URL field is empty.
        if ($lecture && $lecture->video_provider === 'upload' && $lecture->video_id) {
            return [
                'video_provider' => 'upload',
                'video_id'       => $lecture->video_id,
                'video_url'      => null,
            ];
        }

        return [
            'video_provider' => 'youtube',
            'video_id'       => null,
            'video_url'      => null,
        ];
    }

    private function handleMaterialUploads(Request $request, Lecture $lecture): void
    {
        if (!$request->hasFile('materials')) {
            return;
        }

        foreach ($request->file('materials') as $file) {
            if (!$file->isValid()) continue;

            $extension = strtolower($file->getClientOriginalExtension());
            $fileType  = $extension === 'pdf' ? 'pdf' : 'pptx';

            $path = $file->store('lecture-materials/' . $lecture->id, 'public');

            LectureMaterial::create([
                'lecture_id' => $lecture->id,
                'title'      => $file->getClientOriginalName(),
                'file_path'  => $path,
                'file_type'  => $fileType,
                'file_size'  => $file->getSize(),
            ]);
        }
    }
}
