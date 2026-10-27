@extends('layouts.app')
@section('title', __('app.edit_student'))

@section('content')
<div class="page-head">
    <h2><x-icon name="pencil" /> {{ __('app.edit_student') }}</h2>
    <a href="{{ route('students.index') }}" class="btn btn-ghost">← {{ __('app.back') }}</a>
</div>

<div class="card" style="max-width:720px">
    <form method="POST" action="{{ route('students.update', $student) }}">
        @csrf @method('PUT')
        <div class="grid-2">
            <div class="form-group">
                <label>{{ __('app.student_name') }} *</label>
                <input type="text" name="name" value="{{ old('name', $student->name) }}" required>
                @error('name') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>{{ __('app.email') }} *</label>
                <input type="email" name="email" value="{{ old('email', $student->email) }}" required>
                @error('email') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="grid-2">
            <div class="form-group">
                <label>{{ __('app.phone') }}</label>
                <input type="text" name="phone" value="{{ old('phone', $student->phone) }}">
            </div>
            <div class="form-group">
                <label>{{ __('app.enrolled_at') }}</label>
                <input type="date" name="enrolled_at" value="{{ old('enrolled_at', $student->enrolled_at) }}">
            </div>
        </div>
        <div class="grid-2">
            <div class="form-group">
                <label>{{ __('app.level') }} *</label>
                <select name="level" required>
                    <option value="first"  {{ old('level', $student->level) === 'first'  ? 'selected' : '' }}>{{ __('app.first') }}</option>
                    <option value="second" {{ old('level', $student->level) === 'second' ? 'selected' : '' }}>{{ __('app.second') }}</option>
                    <option value="third"  {{ old('level', $student->level) === 'third'  ? 'selected' : '' }}>{{ __('app.third') }}</option>
                </select>
            </div>
            <div class="form-group">
                <label>{{ __('app.status') }}</label>
                <select name="status">
                    <option value="active"   {{ old('status', $student->status) === 'active'   ? 'selected' : '' }}>{{ __('app.active') }}</option>
                    <option value="inactive" {{ old('status', $student->status) === 'inactive' ? 'selected' : '' }}>{{ __('app.inactive') }}</option>
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
                        {{ in_array($lecture->id, old('lectures', $enrolledIds)) ? 'checked' : '' }}
                        style="width:auto;accent-color:var(--gold)">
                    {{ $lecture->title }}
                </label>
                @endforeach
            </div>
        </div>
        @endif

        <div style="display:flex;gap:10px">
            <button type="submit" class="btn btn-primary">{{ __('app.save') }} →</button>
            <a href="{{ route('students.show', $student) }}" class="btn btn-ghost">{{ __('app.cancel') }}</a>
        </div>
    </form>
</div>
@endsection
