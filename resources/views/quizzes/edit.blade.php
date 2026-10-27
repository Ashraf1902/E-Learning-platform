@extends('layouts.app')
@section('title', __('app.edit') . ' — ' . $quiz->title)

@section('content')
<div class="page-head">
    <h2><x-icon name="pencil" /> {{ __('app.edit') }}</h2>
    <a href="{{ route('quizzes.index') }}" class="btn btn-ghost">← {{ __('app.back') }}</a>
</div>

<form method="POST" action="{{ route('quizzes.update', $quiz) }}">
    @csrf @method('PUT')

    <div class="card" style="max-width:860px;margin-bottom:20px">
        <div class="card-title" style="margin-bottom:18px">{{ app()->getLocale() === 'ar' ? 'تفاصيل الاختبار' : 'Quiz Details' }}</div>
        <div class="grid-2">
            <div class="form-group">
                <label>{{ __('app.quiz_title') }} *</label>
                <input type="text" name="title" value="{{ old('title', $quiz->title) }}" required>
            </div>
            <div class="form-group">
                <label>{{ __('app.linked_lecture') }}</label>
                <select name="lecture_id">
                    <option value="">— {{ app()->getLocale() === 'ar' ? 'بدون ربط' : 'None' }} —</option>
                    @foreach($lectures as $lecture)
                    <option value="{{ $lecture->id }}" {{ old('lecture_id', $quiz->lecture_id) == $lecture->id ? 'selected' : '' }}>
                        {{ $lecture->title }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="grid-2">
            <div class="form-group">
                <label>{{ __('app.pass_score') }}</label>
                <input type="number" name="pass_score" value="{{ old('pass_score', $quiz->pass_score) }}" min="0" max="100">
            </div>
            <div class="form-group">
                <label>{{ __('app.status') }}</label>
                <select name="status">
                    <option value="draft"     {{ old('status', $quiz->status) === 'draft'     ? 'selected' : '' }}>{{ __('app.draft') }}</option>
                    <option value="published" {{ old('status', $quiz->status) === 'published' ? 'selected' : '' }}>{{ __('app.published') }}</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label>{{ __('app.description') }}</label>
            <textarea name="description">{{ old('description', $quiz->description) }}</textarea>
        </div>
        @error('questions') <div class="error-msg">{{ $message }}</div> @enderror
    </div>

    <div style="max-width:860px">
        @include('quizzes.builder', ['questions' => old('questions') ? collect(old('questions')) : $quiz->questions])

        <div class="card mt-4" style="display:flex;gap:10px">
            <button type="submit" class="btn btn-primary">{{ __('app.save') }} →</button>
            <a href="{{ route('quizzes.show', $quiz) }}" class="btn btn-ghost">{{ __('app.cancel') }}</a>
        </div>
    </div>
</form>
@endsection
