@extends('layouts.app')
@section('title', 'تعديل الكود')

@section('content')
<div class="page-head">
    <h2><x-icon name="pencil" /> تعديل الكود</h2>
    <a href="{{ route('access-codes.show', $accessCode) }}" class="btn btn-ghost">← {{ __('app.back') }}</a>
</div>

<form method="POST" action="{{ route('access-codes.update', $accessCode) }}">
    @csrf @method('PUT')
    <div class="card" style="max-width:760px;margin-bottom:20px">
        <div class="card-title" style="margin-bottom:18px">
            <span style="font-family:monospace;direction:ltr">{{ $accessCode->code }}</span>
            <span class="badge {{ $accessCode->type === 'registration' ? 'badge-blue' : 'badge-gold' }}">
                {{ $accessCode->type === 'registration' ? 'كود تسجيل' : 'كود فتح محاضرات' }}
            </span>
            <span class="badge badge-green">مستخدم من {{ $accessCode->usedCount() }} طالب</span>
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label>حد استخدامات الكود (عدد الطلاب) *</label>
                <input type="number" name="max_uses" value="{{ old('max_uses', $accessCode->max_uses) }}" min="1" max="100000" required>
                @error('max_uses') <div class="error-msg">{{ $message }}</div> @enderror
                <div class="form-hint">لا يمكن تخفيضه أقل من {{ $accessCode->usedCount() }} (عدد الطلاب المستخدمين حالياً).</div>
            </div>
            <div class="form-group">
                <label>تاريخ انتهاء الصلاحية</label>
                <input type="date" name="expires_at" value="{{ old('expires_at', $accessCode->expires_at?->format('Y-m-d')) }}">
                @error('expires_at') <div class="error-msg">{{ $message }}</div> @enderror
                <div class="form-hint">اتركه فارغاً لإزالة انتهاء الصلاحية.</div>
            </div>
        </div>

        <div class="form-group">
            <label>ملاحظة داخلية</label>
            <input type="text" name="description" value="{{ old('description', $accessCode->description) }}">
            @error('description') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        @if($accessCode->type === 'lecture')
        <div class="form-group">
            <label>المحاضرات التي يفتحها الكود *</label>
            <div style="border:1px solid var(--border);border-radius:10px;padding:12px;max-height:280px;overflow-y:auto;display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:8px">
                @foreach($lectures as $lecture)
                <label style="display:flex;align-items:center;gap:8px;padding:8px 10px;border:1px solid var(--border);border-radius:8px;cursor:pointer;background:var(--surface2)">
                    <input type="checkbox" name="lectures[]" value="{{ $lecture->id }}"
                           style="accent-color:var(--gold);width:auto"
                           {{ in_array($lecture->id, old('lectures', $accessCode->lectures->pluck('id')->all())) ? 'checked' : '' }}>
                    <span style="font-size:0.82rem">{{ $lecture->title }}</span>
                </label>
                @endforeach
            </div>
            @error('lectures') <div class="error-msg">{{ $message }}</div> @enderror
            <div class="form-hint">أي محاضرة تضيفها هنا تُفتح فوراً لجميع الطلاب الذين استخدموا الكود.</div>
        </div>
        @endif
    </div>

    <div class="card" style="max-width:760px;text-align:right">
        <button type="submit" class="btn btn-primary">حفظ التعديلات →</button>
    </div>
</form>
@endsection
