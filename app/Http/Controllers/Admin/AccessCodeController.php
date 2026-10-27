<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccessCode;
use App\Models\Lecture;
use App\Models\Student;
use Illuminate\Http\Request;

class AccessCodeController extends Controller
{
    public function index()
    {
        $codes = AccessCode::with(['lectures', 'usages'])
            ->withCount('usages')
            ->latest()
            ->paginate(15);

        return view('access-codes.index', compact('codes'));
    }

    public function create()
    {
        $lectures = Lecture::where('status', 'published')->orderBy('title')->get();

        return view('access-codes.create', compact('lectures'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'        => 'nullable|string|max:32|alpha_dash|unique:access_codes,code',
            'type'        => 'required|in:registration,lecture',
            'description' => 'nullable|string|max:255',
            'max_uses'    => 'required|integer|min:1|max:100000',
            'expires_at'  => 'nullable|date|after:now',
            'lectures'    => 'required_if:type,lecture|array|min:1',
            'lectures.*'  => 'exists:lectures,id',
        ], [
            'code.unique'        => 'هذا الكود مستخدم مسبقاً، اختر كوداً آخر.',
            'code.alpha_dash'    => 'الكود يُسمح فقط بالأحرف والأرقام والشرطات.',
            'lectures.required_if' => 'اختر محاضرة واحدة على الأقل يفتحها الكود.',
            'lectures.min'       => 'اختر محاضرة واحدة على الأقل يفتحها الكود.',
        ]);

        $code = AccessCode::create([
            'code'        => $request->filled('code')
                ? strtoupper(trim($request->code))
                : AccessCode::generateUniqueCode(),
            'type'        => $request->type,
            'description' => $request->description,
            'max_uses'    => (int) $request->max_uses,
            'expires_at'  => $request->expires_at,
            'is_active'   => true,
            'user_id'     => auth()->id(),
        ]);

        if ($code->type === 'lecture') {
            $code->lectures()->sync(array_map('intval', $request->lectures));
        }

        return redirect()->route('access-codes.show', $code)
            ->with('success', 'تم إنشاء الكود بنجاح — كودك: ' . $code->code);
    }

    public function show(AccessCode $accessCode)
    {
        $accessCode->load(['lectures', 'usages.student']);

        $usedStudentIds  = $accessCode->usages->pluck('student_id');
        $availableStudents = Student::where('status', 'active')
            ->whereNotIn('id', $usedStudentIds)
            ->orderBy('name')
            ->get();

        return view('access-codes.show', [
            'accessCode'       => $accessCode,
            'availableStudents' => $availableStudents,
        ]);
    }

    public function edit(AccessCode $accessCode)
    {
        $lectures = Lecture::where('status', 'published')->orderBy('title')->get();
        $accessCode->load('lectures');

        return view('access-codes.edit', compact('accessCode', 'lectures'));
    }

    public function update(Request $request, AccessCode $accessCode)
    {
        $validated = $request->validate([
            'description' => 'nullable|string|max:255',
            'max_uses'    => 'required|integer|min:1|max:100000',
            'expires_at'  => 'nullable|date',
            'lectures'    => 'required_if:type,lecture|array|min:1',
            'lectures.*'  => 'exists:lectures,id',
        ], [
            'max_uses.min' => 'الحد الأدنى هو استخدام واحد.',
            'lectures.min' => 'اختر محاضرة واحدة على الأقل يفتحها الكود.',
        ]);

        if ((int) $request->max_uses < $accessCode->usedCount()) {
            return back()->withErrors([
                'max_uses' => "لا يمكن تخفيض الحد الأقصى إلى {$request->max_uses} — الكود مستخدم بالفعل من {$accessCode->usedCount()} طالب.",
            ])->withInput();
        }

        $accessCode->update([
            'description' => $request->description,
            'max_uses'    => (int) $request->max_uses,
            'expires_at'  => $request->expires_at,
        ]);

        if ($accessCode->type === 'lecture') {
            $accessCode->lectures()->sync(array_map('intval', $request->lectures));
        }

        return redirect()->route('access-codes.show', $accessCode)
            ->with('success', 'تم تحديث الكود.');
    }

    public function toggle(AccessCode $accessCode)
    {
        $accessCode->update(['is_active' => !$accessCode->is_active]);

        return back()->with('success', $accessCode->is_active
            ? 'تم تفعيل الكود.'
            : 'تم إيقاف الكود — لن يتمكن أحد من استخدامه من الآن.');
    }

    public function destroy(AccessCode $accessCode)
    {
        $accessCode->delete();

        return redirect()->route('access-codes.index')
            ->with('success', 'تم حذف الكود بالكامل.');
    }

    // ── Manually grant the code to a student ──
    public function addStudent(Request $request, AccessCode $accessCode)
    {
        $request->validate(['student_id' => 'required|exists:students,id']);

        if ($reason = $accessCode->unusableReason()) {
            return back()->withErrors(['student_id' => $reason]);
        }

        $student = Student::findOrFail($request->student_id);

        if ($accessCode->usages()->where('student_id', $student->id)->exists()) {
            return back()->withErrors(['student_id' => 'هذا الطالب مستخدم الكود بالفعل.']);
        }

        $accessCode->grantTo($student);

        return back()->with('success', "تمت إضافة الطالب {$student->name} إلى الكود.");
    }

    // ── Revoke the code from a student ──
    public function removeStudent(AccessCode $accessCode, Student $student)
    {
        $accessCode->usages()->where('student_id', $student->id)->delete();

        return back()->with('success', "تم سحب الكود من الطالب {$student->name}.");
    }
}
