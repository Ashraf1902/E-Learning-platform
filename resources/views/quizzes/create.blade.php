@extends('layouts.app')
@section('title', __('app.add_quiz'))

@section('content')
<div class="page-head">
    <h2><x-icon name="plus" /> {{ __('app.add_quiz') }}</h2>
    <a href="{{ route('quizzes.index') }}" class="btn btn-ghost">← {{ __('app.back') }}</a>
</div>

<form method="POST" action="{{ route('quizzes.store') }}">
    @csrf
    <div class="card" style="max-width:860px;margin-bottom:20px">
        <div class="card-title" style="margin-bottom:18px">{{ app()->getLocale() === 'ar' ? 'تفاصيل الاختبار' : 'Quiz Details' }}</div>
        <div class="grid-2">
            <div class="form-group">
                <label>{{ __('app.quiz_title') }} *</label>
                <input type="text" name="title" value="{{ old('title') }}" placeholder="{{ app()->getLocale() === 'ar' ? 'مثال: اختبار الفصل الأول' : 'e.g. Chapter 1 Quiz' }}" required>
                @error('title') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>{{ __('app.linked_lecture') }}</label>
                <select name="lecture_id">
                    <option value="">— {{ app()->getLocale() === 'ar' ? 'بدون ربط' : 'None' }} —</option>
                    @foreach($lectures as $lecture)
                    <option value="{{ $lecture->id }}" {{ old('lecture_id') == $lecture->id ? 'selected' : '' }}>
                        {{ $lecture->title }} ({{ $lecture->level_label }})
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="grid-2">
            <div class="form-group">
                <label>{{ __('app.pass_score') }}</label>
                <input type="number" name="pass_score" value="{{ old('pass_score', 60) }}" min="0" max="100" required>
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
            <label>{{ __('app.description') }}</label>
            <textarea name="description" placeholder="{{ app()->getLocale() === 'ar' ? 'ماذا يغطي هذا الاختبار؟' : 'What does this quiz cover?' }}">{{ old('description') }}</textarea>
        </div>
        @error('questions') <div class="error-msg">{{ $message }}</div> @enderror
    </div>

    <div style="max-width:860px">
        @include('quizzes.builder', ['questions' => collect(old('questions', []))])

        <div class="card mt-4" style="text-align:{{ app()->getLocale() === 'ar' ? 'left' : 'right' }}">
            <button type="submit" class="btn btn-primary">{{ app()->getLocale() === 'ar' ? 'إنشاء الاختبار' : 'Create Quiz' }} →</button>
        </div>
    </div>
</form>
@endsection
