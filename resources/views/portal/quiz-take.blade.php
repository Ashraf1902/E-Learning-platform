@extends('portal.layout')
@section('title', __('app.take_quiz'))

@section('content')
<div class="page-head">
    <h2><x-icon name="clipboard" /> {{ $quiz->title }}</h2>
    <a href="{{ route('portal.quizzes') }}" class="btn btn-ghost">← {{ __('app.back') }}</a>
</div>

<div style="max-width:700px">
    <div class="card" style="margin-bottom:20px">
        <p style="color:var(--muted)">{{ $quiz->description ?? (app()->getLocale() === 'ar' ? 'أجب على جميع الأسئلة أدناه.' : 'Answer all questions below.') }}</p>
        <div style="margin-top:10px;font-size:0.85rem;color:var(--muted)">
            {{ app()->getLocale() === 'ar' ? 'درجة النجاح:' : 'Pass score:' }}
            <strong style="color:var(--gold)">{{ $quiz->pass_score }}%</strong> ·
            {{ app()->getLocale() === 'ar' ? 'عدد الأسئلة:' : 'Questions:' }}
            <strong style="color:var(--gold)">{{ $quiz->questions->count() }}</strong>
            @if($quiz->hasEssayQuestions())
            <br><span style="color:var(--gold)"><x-icon name="clipboard" /> {{ app()->getLocale() === 'ar' ? 'يحتوي هذا الاختبار على أسئلة مقالي تُصحَّح يدوياً.' : 'This quiz contains essay questions graded manually.' }}</span>
            @endif
        </div>
    </div>

    <form method="POST" action="{{ route('portal.quizzes.submit', $quiz) }}">
        @csrf
        @foreach($quiz->questions as $i => $question)
        <div class="card" style="margin-bottom:16px">
            <div style="font-weight:700;margin-bottom:14px">
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
                {{ __('app.submit_quiz') }} →
            </button>
        </div>
    </form>
</div>
@include('quizzes._ordering-script')
@endsection
