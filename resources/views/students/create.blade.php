@extends('layouts.app')
@section('title', __('app.add_student'))

@section('content')
<div class="page-head">
    <h2><x-icon name="plus" /> {{ __('app.add_student') }}</h2>
    <a href="{{ route('students.index') }}" class="btn btn-ghost">← {{ __('app.back') }}</a>
</div>

<div class="card" style="max-width:720px">
    <form method="POST" action="{{ route('students.store') }}">
        @csrf
        <div class="grid-2">
            <div class="form-group">
                <label>{{ __('app.student_name') }} *</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. أحمد محمد" required>
                @error('name') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>{{ __('app.email') }} *</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="student@example.com" required>
                @error('email') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="grid-2">
            <div class="form-group">
                <label>{{ __('app.phone') }}</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+20 10x xxx xxxx">
            </div>
            <div class="form-group">
                <label>{{ __('app.enrolled_at') }}</label>
                <input type="date" name="enrolled_at" value="{{ old('enrolled_at', date('Y-m-d')) }}">
            </div>
        </div>
        <div class="grid-2">
            <div class="form-group">
                <label>{{ __('app.level') }} *</label>
                <select name="level" required>
                    <option value="first"  {{ old('level') === 'first'  ? 'selected' : '' }}>{{ __('app.first') }}</option>
                    <option value="second" {{ old('level') === 'second' ? 'selected' : '' }}>{{ __('app.second') }}</option>
                    <option value="third"  {{ old('level') === 'third'  ? 'selected' : '' }}>{{ __('app.third') }}</option>
                </select>
            </div>
            <div class="form-group">
                <label>{{ __('app.status') }}</label>
                <select name="status">
                    <option value="active"   {{ old('status') === 'active'   ? 'selected' : '' }}>{{ __('app.active') }}</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>{{ __('app.inactive') }}</option>
                </select>
            </div>
        </div>

        @if($lectures->count())
        <div class="form-group">
            <label>{{ __('app.enroll_lectures') }}</label>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:6px">
                @foreach($lectures as $lecture)
                <label style="display:flex;align-items:center;gap:8px;font-size:0.88rem;color:var(--text);text-transform:none;letter-spacing:0;font-weight:500;cursor:pointer;background:var(--surface2);padding:10px 12px;border-radius:8px;border:1px solid var(--border)">
                    <input type="checkbox" name="lectures[]" value="{{ $lecture->id }}"
                        {{ in_array($lecture->id, old('lectures', [])) ? 'checked' : '' }}
                        style="width:auto;accent-color:var(--gold)">
                    {{ $lecture->title }}
                </label>
                @endforeach
            </div>
        </div>
        @endif

        <div style="background:rgba(212,168,67,0.06);border:1px solid rgba(212,168,67,0.15);border-radius:10px;padding:14px;margin-bottom:20px;font-size:0.85rem;color:var(--muted2)">
            <x-icon name="key" /> {{ app()->getLocale() === 'ar' ? 'سيتم إنشاء حساب تلقائياً للطالب بكلمة مرور: password' : 'A student account will be created automatically with password: password' }}
        </div>

        <div style="display:flex;gap:10px">
            <button type="submit" class="btn btn-primary">{{ __('app.add_student') }} →</button>
            <a href="{{ route('students.index') }}" class="btn btn-ghost">{{ __('app.cancel') }}</a>
        </div>
    </form>
</div>
@endsection
