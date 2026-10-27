@extends('portal.layout')
@section('title', __('app.my_quizzes'))

@section('content')
@php $isAr = app()->getLocale() === 'ar'; @endphp

@if($quizzes->count())
<div class="lecture-grid">
    @foreach($quizzes as $quiz)
    @php
        $attempt = $student->quizAttempts->where('quiz_id', $quiz->id)->first();
        $taken   = $attempt !== null;
        $pct     = $taken && $attempt->total_points > 0 ? round(($attempt->score / $attempt->total_points) * 100) : 0;
        $state   = $pct >= 70 ? 'is-pass' : ($pct >= 50 ? '' : 'is-fail');
    @endphp
    <div class="lecture-card">
        <div class="lecture-card-top">
            <div class="lecture-card-icon"><x-icon name="clipboard" /></div>
            @if($taken)
                <span class="badge {{ $attempt->passed ? 'badge-green' : 'badge-red' }}">
                    {{ $attempt->passed ? __('app.passed') : __('app.failed') }}
                </span>
            @else
                <span class="badge badge-yellow">{{ __('app.not_taken') }}</span>
            @endif
        </div>
        <div class="lecture-card-title">{{ $quiz->title }}</div>
        <div class="lecture-card-meta">
            <span class="badge badge-blue">{{ $quiz->questions_count }} {{ __('app.questions') }}</span>
            <span>{{ __('app.pass_score') }} {{ $quiz->pass_score }}%</span>
        </div>
        <div class="cell-sub" style="margin:0">{{ $quiz->lecture->title ?? '—' }}</div>

        @if($taken)
        <div style="display:flex;align-items:center;gap:8px">
            <div class="progress" style="flex:1">
                <div class="progress-fill {{ $state === 'is-pass' ? 'is-pass' : ($state === 'is-fail' ? 'is-fail' : '') }}" style="width:{{ $pct }}%"></div>
            </div>
            <span class="score-text {{ $state === 'is-pass' ? 'is-pass' : ($state === 'is-fail' ? 'is-fail' : 'is-mid') }}">{{ $pct }}%</span>
        </div>
        @endif

        <div class="lecture-card-foot">
            @if($taken)
                <a href="{{ route('portal.results.review', $attempt->id) }}" class="btn btn-ghost btn-sm"><x-icon name="search" /> {{ __('app.review') }}</a>
            @else
                <a href="{{ route('portal.quizzes.take', $quiz) }}" class="btn btn-primary btn-sm"><x-icon name="play" /> {{ __('app.take_quiz') }}</a>
            @endif
        </div>
    </div>
    @endforeach
</div>

<div class="pagination" style="margin-top:20px">{{ $quizzes->links() }}</div>
@else
<div class="card">
    <div class="empty-state">
        <div class="icon"><x-icon name="clipboard" /></div>
        <div class="empty-title">{{ $isAr ? 'لا توجد اختبارات متاحة' : 'No quizzes available' }}</div>
        <div class="empty-hint">{{ $isAr ? 'ستظهر الاختبارات المرتبطة بمحاضراتك هنا بعد تفعيل الوصول.' : 'Quizzes linked to your lectures will appear here once access is active.' }}</div>
        <a href="{{ route('portal.lectures') }}" class="btn btn-ghost btn-sm"><x-icon name="video" /> {{ __('app.my_lectures') }}</a>
    </div>
</div>
@endif
@endsection
