@extends('layouts.app')
@section('title', 'Take Quiz')

@section('content')
<div class="page-head">
    <h2><x-icon name="clipboard" /> {{ $quiz->title }}</h2>
    <a href="{{ route('quizzes.show', $quiz) }}" class="btn btn-ghost">← {{ __('app.back') }}</a>
</div>

<div style="max-width:700px">
    @if($quiz->description)
    <div class="card" style="margin-bottom:20px">
        <p style="color:var(--muted)">{{ $quiz->description }}</p>
        <div style="margin-top:10px;font-size:0.85rem;color:var(--muted)">
            {{ app()->getLocale() === 'ar' ? 'درجة النجاح:' : 'Pass score:' }} <strong style="color:var(--gold)">{{ $quiz->pass_score }}%</strong> ·
            {{ app()->getLocale() === 'ar' ? 'عدد الأسئلة:' : 'Questions:' }} <strong style="color:var(--gold)">{{ $quiz->questions->count() }}</strong>
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('quizzes.submit', $quiz) }}">
        @csrf

        <div class="card" style="margin-bottom:20px">
            <div class="form-group" style="margin-bottom:0">
                <label>{{ app()->getLocale() === 'ar' ? 'اختر الطالب' : 'Select Student' }} *</label>
                <select name="student_id" required>
                    <option value="">— {{ app()->getLocale() === 'ar' ? 'اختر طالباً' : 'Choose a student' }} —</option>
                    @foreach($students as $student)
                    <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        @foreach($quiz->questions as $i => $question)
        <div class="card" style="margin-bottom:16px">
            <div style="font-weight:600;margin-bottom:14px">
                <span style="color:var(--gold);font-family:monospace;font-size:0.8rem">{{ $i + 1 }}</span>
                &nbsp; {{ $question->question_text }}
                <span style="color:var(--muted);font-size:0.78rem;font-weight:400"> · {{ $question->points }} {{ __('app.pts') }}</span>
                <span class="badge badge-blue" style="font-size:0.68rem;vertical-align:middle">{{ \App\Models\Question::TYPES[$question->type] ?? $question->type }}</span>
            </div>
            @include('quizzes._answer-widgets', ['question' => $question])
        </div>
        @endforeach

        <div class="card">
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:14px">
                {{ app()->getLocale() === 'ar' ? 'إرسال الاختبار' : 'Submit Quiz' }} →
            </button>
        </div>
    </form>
</div>
@include('quizzes._ordering-script')
@endsection
