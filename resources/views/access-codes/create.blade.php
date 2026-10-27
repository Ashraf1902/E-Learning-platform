@extends('layouts.app')
@section('title', 'إنشاء كود جديد')

@section('content')
<div class="page-head">
    <h2><x-icon name="key" /> إنشاء كود جديد</h2>
    <a href="{{ route('access-codes.index') }}" class="btn btn-ghost">← {{ __('app.back') }}</a>
</div>

<form method="POST" action="{{ route('access-codes.store') }}">
    @csrf
    <div class="card" style="max-width:760px;margin-bottom:20px">
        <div class="card-title" style="margin-bottom:18px">بيانات الكود</div>

        <div class="grid-2">
            <div class="form-group">
                <label>نوع الكود *</label>
                <select name="type" id="codeType" required onchange="toggleTypeFields()">
                    <option value="registration" {{ old('type') === 'registration' ? 'selected' : '' }}>
                        كود تسجيل — للطلاب الجدد عند إنشاء الحساب
                    </option>
                    <option value="lecture" {{ old('type') === 'lecture' ? 'selected' : '' }}>
                        كود فتح محاضرات — يفتح محاضرات محددة بدون اشتراك
                    </option>
                </select>
                @error('type') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>حد استخدامات الكود (عدد الطلاب) *</label>
                <input type="number" name="max_uses" value="{{ old('max_uses', 1) }}" min="1" max="100000" required>
                @error('max_uses') <div class="error-msg">{{ $message }}</div> @enderror
                <div class="form-hint">عدد الطلاب الكلي الذين يمكنهم استخدام هذا الكود.</div>
            </div>
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label>نص الكود نفسه (اختياري)</label>
                <input type="text" name="code" value="{{ old('code') }}" placeholder="اتركه فارغاً لتوليد كود تلقائي"
                       style="text-transform:uppercase;direction:ltr;font-family:monospace" maxlength="32">
                @error('code') <div class="error-msg">{{ $message }}</div> @enderror
                <div class="form-hint">حروف وأرقام فقط. إن تُرك فارغاً سيُولَّد كود عشوائي قوي.</div>
            </div>
            <div class="form-group">
                <label>تاريخ انتهاء الصلاحية (اختياري)</label>
                <input type="date" name="expires_at" value="{{ old('expires_at') }}">
                @error('expires_at') <div class="error-msg">{{ $message }}</div> @enderror
                <div class="form-hint">اتركه فارغاً للكود بدون انتهاء.</div>
            </div>
        </div>

        <div class="form-group">
            <label>ملاحظة داخلية (اختياري)</label>
            <input type="text" name="description" value="{{ old('description') }}" placeholder="مثال: لطلاب الفوج المسائي">
            @error('description') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        {{-- Lectures — only for lecture codes --}}
        <div class="form-group" id="lecturesField" style="display:{{ old('type', 'registration') === 'lecture' ? 'block' : 'none' }}">
            <label>المحاضرات التي يفتحها الكود *</label>
            <div style="border:1px solid var(--border);border-radius:10px;padding:12px;max-height:280px;overflow-y:auto;display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:8px">
                @foreach($lectures as $lecture)
                <label style="display:flex;align-items:center;gap:8px;padding:8px 10px;border:1px solid var(--border);border-radius:8px;cursor:pointer;background:var(--surface2)">
                    <input type="checkbox" name="lectures[]" value="{{ $lecture->id }}"
                           style="accent-color:var(--gold);width:auto"
                           {{ in_array($lecture->id, old('lectures', [])) ? 'checked' : '' }}>
                    <span style="font-size:0.82rem">{{ $lecture->title }}</span>
                </label>
                @endforeach
            </div>
            @error('lectures') <div class="error-msg">{{ $message }}</div> @enderror
            <div class="form-hint">حدد محاضرة واحدة أو أكثر — يمكن للطالب فتحها بإدخال الكود بدون اشتراك.</div>
        </div>
    </div>

    <div class="card" style="max-width:760px;text-align:right">
        <button type="submit" class="btn btn-primary">إنشاء الكود →</button>
    </div>
</form>

@push('scripts')
<script>
function toggleTypeFields() {
    const isLecture = document.getElementById('codeType').value === 'lecture';
    document.getElementById('lecturesField').style.display = isLecture ? 'block' : 'none';
}
toggleTypeFields();
</script>
@endpush
@endsection
