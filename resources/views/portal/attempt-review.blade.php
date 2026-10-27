@extends('portal.layout')
@section('title', app()->getLocale() === 'ar' ? 'مراجعة الاختبار' : 'Quiz Review')

@section('content')
<div class="page-head">
    <h2><x-icon name="search" /> {{ app()->getLocale() === 'ar' ? 'مراجعة الاختبار' : 'Quiz Review' }}</h2>
    <a href="{{ route('portal.results') }}" class="btn btn-ghost">← {{ __('app.all_results') }}</a>
</div>

@php
    $pct     = $attempt->total_points > 0 ? round(($attempt->score / $attempt->total_points) * 100) : 0;
    $pending = $attempt->isPendingGrading();
@endphp

{{-- Summary --}}
<div class="card" style="margin-bottom:24px">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:20px">
        <div>
            <div style="font-size:1.2rem;font-weight:800;margin-bottom:4px">{{ $quiz->title }}</div>
            <div style="color:var(--muted);font-size:0.85rem">
                {{ app()->getLocale() === 'ar' ? 'تاريخ الاختبار:' : 'Attempted on:' }}
                {{ $attempt->completed_at?->format('d M Y، h:i A') }}
            </div>
            @if($quiz->lecture)
            <div style="color:var(--muted);font-size:0.85rem;margin-top:2px">
                {{ __('app.linked_lecture') }}: <span style="color:var(--text)">{{ $quiz->lecture->title }}</span>
            </div>
            @endif
        </div>
        <div style="display:flex;align-items:center;gap:24px;flex-wrap:wrap">
            @if($pending)
            <div style="text-align:center">
                <div style="font-size:2rem;font-weight:900;color:var(--gold)"><x-icon name="clock" /></div>
                <div style="color:var(--muted);font-size:0.8rem">{{ app()->getLocale() === 'ar' ? 'النتيجة النهائية قيد التصحيح' : 'Final score pending grading' }}</div>
            </div>
            @endif
            <div style="text-align:center">
                <div style="font-size:2.5rem;font-weight:900;color:{{ $pending ? 'var(--muted)' : ($attempt->passed ? 'var(--green)' : 'var(--red)') }}">{{ $pct }}%</div>
                <div style="color:var(--muted);font-size:0.75rem;text-transform:uppercase;letter-spacing:0.1em">{{ __('app.score') }}</div>
            </div>
            <div style="text-align:center">
                <div style="font-size:2.5rem;font-weight:900">{{ $attempt->score }}/{{ $attempt->total_points }}</div>
                <div style="color:var(--muted);font-size:0.75rem">{{ __('app.pts') }}</div>
            </div>
            @php
                $correct = 0;
                foreach($quiz->questions as $q) {
                    if ($q->type === 'essay') continue;
                    if(\App\Support\AnswerChecker::isCorrect($attempt->answers[$q->id] ?? null, $q)) $correct++;
                }
            @endphp
            <div style="text-align:center">
                <div style="font-size:2.5rem;font-weight:900;color:var(--gold2)">{{ $correct }}/{{ $quiz->questions->where('type', '!=', 'essay')->count() }}</div>
                <div style="color:var(--muted);font-size:0.75rem">{{ __('app.correct') }}</div>
            </div>
            <div>
                <span class="badge {{ $pending ? 'badge-yellow' : ($attempt->passed ? 'badge-green' : 'badge-red') }}" style="font-size:0.9rem;padding:8px 20px">
                    @if ($pending)
                        <x-icon name="clock" /> {{ app()->getLocale() === 'ar' ? 'قيد التصحيح' : 'Pending' }}
                    @else
                        {{ $attempt->passed ? __('app.passed') : __('app.failed') }}
                    @endif
                </span>
                <div style="color:var(--muted);font-size:0.75rem;margin-top:6px;text-align:center">
                    {{ app()->getLocale() === 'ar' ? 'للنجاح:' : 'Pass mark:' }} {{ $quiz->pass_score }}%
                </div>
            </div>
        </div>
    </div>
    <div style="margin-top:20px">
        <div style="background:var(--surface2);border-radius:999px;height:10px;overflow:hidden;position:relative">
            <div style="height:100%;width:{{ $pct }}%;background:{{ $pending ? 'var(--gold)' : ($attempt->passed ? 'var(--green)' : 'var(--red)') }};border-radius:999px;"></div>
            <div style="position:absolute;top:0;bottom:0;left:{{ $quiz->pass_score }}%;width:2px;background:var(--gold);opacity:0.7"></div>
        </div>
    </div>
</div>

{{-- Questions --}}
<div style="display:flex;flex-direction:column;gap:16px">
    @foreach($quiz->questions as $i => $question)
    @php
        $given     = $attempt->answers[$question->id] ?? null;
        $isEssay   = $question->type === 'essay';
        $isRight   = !$isEssay && \App\Support\AnswerChecker::isCorrect($given, $question);
        $essayDone = $isEssay && isset($attempt->manual_scores[$question->id]);
        $essayPts  = $essayDone ? (int) $attempt->manual_scores[$question->id] : null;
    @endphp
    <div class="card" style="border-right:4px solid {{ $isEssay ? ($essayDone ? ($essayPts >= $question->points ? 'var(--green)' : 'var(--gold)') : 'var(--gold)') : ($isRight ? 'var(--green)' : 'var(--red)') }}">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:16px">
            <div style="display:flex;align-items:flex-start;gap:12px">
                <div style="width:32px;height:32px;border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:800;background:{{ $isEssay ? 'rgba(212,168,67,0.15)' : ($isRight ? 'rgba(62,201,122,0.15)' : 'rgba(224,85,85,0.15)') }};color:{{ $isEssay ? 'var(--gold)' : ($isRight ? 'var(--green)' : 'var(--red)') }}">
                    {{ $i + 1 }}
                </div>
                <div style="font-weight:700;font-size:0.95rem;line-height:1.5">
                    {{ $question->question_text }}
                    <span class="badge badge-blue" style="font-size:0.65rem;vertical-align:middle">{{ \App\Models\Question::TYPES[$question->type] ?? $question->type }}</span>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:8px;flex-shrink:0">
                <span style="font-size:1.3rem">
                    @if ($isEssay)
                        <x-icon name="clipboard" />
                        @if ($essayDone)<x-icon name="check" />@endif
                    @elseif ($isRight)
                        <x-icon name="check-circle" />
                    @else
                        <x-icon name="x-circle" />
                    @endif
                </span>
                <span style="font-size:0.78rem;color:var(--muted)">{{ $question->points }} {{ __('app.pts') }}</span>
            </div>
        </div>

        @if($question->type === 'mcq')
            @php
                $labels      = \App\Models\Question::OPTION_LABELS;
                $correctLetter = \App\Support\AnswerChecker::canonical($question->correct_answer, $question);
                $givenLetter   = \App\Support\AnswerChecker::canonical($given, $question);
            @endphp
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}:44px">
                @foreach(($question->option_list ?? []) as $idx => $text)
                @php
                    $letter       = chr(97 + $idx);
                    $isCorrectOpt = $letter === $correctLetter;
                    $isGivenOpt   = $letter === $givenLetter;
                    $isWrongPick  = $isGivenOpt && !$isCorrectOpt;
                @endphp
                <div style="padding:10px 14px;border-radius:10px;font-size:0.88rem;
                    border:1px solid {{ $isCorrectOpt ? 'rgba(62,201,122,0.4)' : ($isWrongPick ? 'rgba(224,85,85,0.4)' : 'var(--border)') }};
                    background:{{ $isCorrectOpt ? 'rgba(62,201,122,0.08)' : ($isWrongPick ? 'rgba(224,85,85,0.08)' : 'transparent') }};
                    color:{{ $isCorrectOpt ? 'var(--green)' : ($isWrongPick ? 'var(--red)' : 'var(--muted2)') }};
                    display:flex;align-items:center;gap:10px;">
                    <span style="font-weight:800;font-size:0.8rem;width:18px;flex-shrink:0">{{ $labels[$idx] ?? strtoupper($letter) }}</span>
                    <span style="flex:1">{{ $text }}</span>
                    @if($isCorrectOpt)
                        <span style="font-size:0.72rem;font-weight:800;flex-shrink:0"><x-icon name="check" /> {{ app()->getLocale() === 'ar' ? 'صحيح' : 'Correct' }}</span>
                    @elseif($isWrongPick)
                        <span style="font-size:0.72rem;font-weight:800;flex-shrink:0"><x-icon name="x" /> {{ app()->getLocale() === 'ar' ? 'إجابتك' : 'Your Answer' }}</span>
                    @endif
                </div>
                @endforeach
            </div>
        @elseif($isEssay)
            <div style="margin-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}:44px">
                <div style="font-size:0.8rem;color:var(--muted);font-weight:700;margin-bottom:6px">{{ app()->getLocale() === 'ar' ? 'إجابتك:' : 'Your answer:' }}</div>
                <div style="background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:12px 14px;font-size:0.9rem;white-space:pre-wrap">{{ $given ?: '—' }}</div>
                @if($essayDone)
                <div style="margin-top:8px;font-size:0.85rem;color:var(--green)"><x-icon name="clipboard" /> {{ app()->getLocale() === 'ar' ? 'درجة المقال:' : 'Essay score:' }} <strong>{{ $essayPts }}/{{ $question->points }}</strong></div>
                @elseif($attempt->graded_at)
                <div style="margin-top:8px;font-size:0.85rem;color:var(--muted)"><x-icon name="clipboard" /> {{ app()->getLocale() === 'ar' ? 'لم يُمنح لهذه الإجابة درجة.' : 'No score awarded for this answer.' }}</div>
                @else
                <div style="margin-top:8px;font-size:0.85rem;color:var(--gold)"><x-icon name="clock" /> {{ app()->getLocale() === 'ar' ? 'بانتظار تصحيح المدرس...' : 'Awaiting teacher grading...' }}</div>
                @endif
            </div>
        @else
            <div style="margin-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}:44px;display:flex;flex-direction:column;gap:8px">
                <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                    <span style="font-size:0.78rem;font-weight:700;color:var(--muted);min-width:90px">{{ app()->getLocale() === 'ar' ? 'إجابتك:' : 'Your answer:' }}</span>
                    <span style="padding:8px 14px;border-radius:8px;font-size:0.88rem;background:{{ $isRight ? 'rgba(62,201,122,0.08)' : 'rgba(224,85,85,0.08)' }};color:{{ $isRight ? 'var(--green)' : 'var(--red)' }};border:1px solid {{ $isRight ? 'rgba(62,201,122,0.3)' : 'rgba(224,85,85,0.3)' }}">
                        {{ $question->givenLabel($given) }}
                    </span>
                </div>
                @if(!$isRight)
                <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                    <span style="font-size:0.78rem;font-weight:700;color:var(--muted);min-width:90px">{{ app()->getLocale() === 'ar' ? 'الصحيحة:' : 'Correct:' }}</span>
                    <span style="padding:8px 14px;border-radius:8px;font-size:0.88rem;background:rgba(62,201,122,0.08);color:var(--green);border:1px solid rgba(62,201,122,0.3)">
                        {{ $question->correctLabel() }}
                    </span>
                </div>
                @endif
            </div>
        @endif

        @if(!$given && !$isEssay)
        <div style="margin-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}:44px;margin-top:10px;font-size:0.82rem;color:var(--muted);background:var(--surface2);padding:8px 12px;border-radius:8px;">
            <x-icon name="alert" /> {{ app()->getLocale() === 'ar' ? 'لم تجب على هذا السؤال' : 'You did not answer this question' }}
        </div>
        @endif
    </div>
    @endforeach
</div>

<div style="display:flex;gap:12px;margin-top:24px;justify-content:center">
    <a href="{{ route('portal.results') }}" class="btn btn-primary">{{ __('app.all_results') }}</a>
    <a href="{{ route('portal.quizzes') }}" class="btn btn-ghost"><x-icon name="clipboard" /> {{ __('app.my_quizzes') }}</a>
</div>
@endsection
