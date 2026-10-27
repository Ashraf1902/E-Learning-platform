@extends('layouts.app')
@section('title', __('app.add_lecture'))

@section('content')
<div class="page-head">
    <h2><x-icon name="plus" /> {{ __('app.add_lecture') }}</h2>
    <a href="{{ route('lectures.index') }}" class="btn btn-ghost">← {{ __('app.back') }}</a>
</div>

<div class="card" style="max-width:720px">
    <form method="POST" action="{{ route('lectures.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>{{ __('app.lecture_title') }} *</label>
            <input type="text" name="title" value="{{ old('title') }}" placeholder="مثال: مقدمة في الجبر" required>
            @error('title') <div class="error-msg">{{ $message }}</div> @enderror
        </div>
        <div class="grid-2">
            <div class="form-group">
                <label>{{ __('app.category') }}</label>
                <input type="text" name="category" value="{{ old('category') }}" placeholder="مثال: رياضيات، علوم، لغة عربية">
            </div>
            <div class="form-group">
                <label>{{ __('app.duration') }}</label>
                <input type="number" name="duration_minutes" value="{{ old('duration_minutes') }}" min="1" placeholder="مثال: 45">
            </div>
        </div>
        <div class="grid-2">
            <div class="form-group">
                <label>{{ __('app.level') }} *</label>
                <select name="level" required>
                    <option value="all"    {{ old('level') === 'all'    ? 'selected' : '' }}>{{ __('app.all_levels') }}</option>
                    <option value="first"  {{ old('level') === 'first'  ? 'selected' : '' }}>{{ __('app.first') }}</option>
                    <option value="second" {{ old('level') === 'second' ? 'selected' : '' }}>{{ __('app.second') }}</option>
                    <option value="third"  {{ old('level') === 'third'  ? 'selected' : '' }}>{{ __('app.third') }}</option>
                </select>
            </div>
            <div class="form-group">
                <label>{{ __('app.status') }}</label>
                <select name="status">
                    <option value="draft"     {{ old('status') === 'draft'     ? 'selected' : '' }}>{{ __('app.draft') }}</option>
                    <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>{{ __('app.published') }}</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label>{{ __('app.video_url') }}</label>
            <input type="url" name="video_url" value="{{ old('video_url') }}" placeholder="https://youtube.com/watch?v=... أو https://vimeo.com/... أو https://example.com/lecture.mp4">
            @error('video_url') <div class="error-msg">{{ $message }}</div> @enderror
            <div class="form-hint">
                <x-icon name="lock" /> {{ app()->getLocale() === 'ar' ? 'يُشغّل الفيديو في مشغل محمي داخل الموقع. يمكنك لصق رابط يوتيوب أو فيميو، أو رابط ملف MP4/WebM مباشر ليظهر بدون أي علامة يوتيوب.' : 'Plays in a secure in-site player. Paste a YouTube/Vimeo link, or a direct MP4/WebM file link to show it with no YouTube branding at all.' }}
            </div>
        </div>
        <div class="form-group">
            <label>{{ app()->getLocale() === 'ar' ? 'أو ارفع ملف الفيديو' : 'Or upload a video file' }}</label>
            <input type="file" name="video_file" accept="video/mp4,video/webm,video/ogg,video/quicktime,video/x-m4v">
            @error('video_file') <div class="error-msg">{{ $message }}</div> @enderror
            <div class="form-hint">
                {{ app()->getLocale() === 'ar'
                    ? 'MP4 / WebM — الحد الأقصى على السيرفر الحالي: ' . $videoMaxMb . ' ميجابايت. للفيديوهات الكبيرة استخدم رابط MP4 مباشر.'
                    : 'MP4 / WebM — current server limit: ' . $videoMaxMb . 'MB. For larger videos, use a direct MP4 link.' }}
            </div>
        </div>
        <div class="form-group">
            <label>{{ __('app.description') }}</label>
            <textarea name="description" placeholder="ماذا سيتعلم الطلاب في هذه المحاضرة؟">{{ old('description') }}</textarea>
        </div>

        {{-- Leaderboard settings --}}
        <div class="form-group">
            <label>{{ app()->getLocale() === 'ar' ? 'لوحة الترتيب (المسابقة)' : 'Leaderboard' }}</label>
            <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:10px 14px;border:1px solid var(--border);border-radius:10px;background:var(--surface2)">
                <input type="checkbox" name="show_leaderboard" value="1" style="accent-color:var(--gold);width:auto"
                       {{ old('show_leaderboard') ? 'checked' : '' }}>
                <span style="font-size:0.88rem">{{ app()->getLocale() === 'ar' ? 'إظهار لوحة الترتيب للطلاب' : 'Show leaderboard to students' }}</span>
            </label>
            <div class="form-hint">
                {{ app()->getLocale() === 'ar' ? 'عند التفعيل فقط سيظهر رابط لوحة الترتيب للطلاب في هذه المحاضرة.' : 'When enabled, the leaderboard link for this lecture becomes visible to students.' }}
            </div>
            <div style="margin-top:10px">
                <input type="number" name="leaderboard_limit" value="{{ old('leaderboard_limit') }}" min="1" max="1000"
                       placeholder="{{ app()->getLocale() === 'ar' ? 'عدد الطلاب المعروضين (اختياري)' : 'Number of students to show (optional)' }}"
                       style="max-width:260px">
                <div class="form-hint">
                    {{ app()->getLocale() === 'ar' ? 'اتركه فارغاً لعرض جميع الطلاب. مثال: 3 لعرض أول 3 طلاب فقط.' : 'Leave empty to show everyone. E.g. 3 to show only the top 3.' }}
                </div>
            </div>
        </div>

        {{-- PDF/PPTX Materials Upload --}}
        <div class="form-group">
            <label>{{ app()->getLocale() === 'ar' ? 'مرفقات المحاضرة (PDF / PowerPoint)' : 'Lecture Materials (PDF / PowerPoint)' }}</label>

            <div class="upload-zone" id="uploadZone-materials" onclick="document.getElementById('materialsInput').click()">
                <input type="file" id="materialsInput" name="materials[]" multiple accept=".pdf,.ppt,.pptx" style="display:none">

                <div class="upload-zone-icon">
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="17 8 12 3 7 8"></polyline>
                        <line x1="12" y1="3" x2="12" y2="15"></line>
                    </svg>
                </div>
                <div class="upload-zone-text">
                    <strong>{{ app()->getLocale() === 'ar' ? 'اضغط لاختيار الملفات' : 'Click to choose files' }}</strong>
                    {{ app()->getLocale() === 'ar' ? ' أو اسحبها وأفلتها هنا' : ' or drag & drop here' }}
                </div>
                <div class="upload-zone-meta">
                    PDF · PPT · PPTX &nbsp;•&nbsp;
                    <span class="upload-limit-badge">{{ app()->getLocale() === 'ar' ? 'الحد الأقصى 20 ميجابايت لكل ملف' : 'Max 20MB per file' }}</span>
                </div>
            </div>

            <div id="fileList-materials" class="upload-file-list"></div>

            @error('materials.*')
                <div class="error-msg">{{ $message }}</div>
            @enderror
            @if(session('upload_error'))
                <div class="error-msg" style="margin-top:8px;background:rgba(224,85,85,0.08);border:1px solid rgba(224,85,85,0.2);border-radius:8px;padding:10px 12px;">
                    <x-icon name="alert" /> {{ session('upload_error') }}
                </div>
            @endif
        </div>

        <div style="display:flex;gap:10px">
            <button type="submit" class="btn btn-primary">{{ __('app.add_lecture') }} →</button>
            <a href="{{ route('lectures.index') }}" class="btn btn-ghost">{{ __('app.cancel') }}</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.getElementById('uploadZone-materials').dataset.lang = '{{ app()->getLocale() }}';
    setupUploadZone('uploadZone-materials', 'materialsInput', 'fileList-materials', 20);
</script>
@endpush
@endsection