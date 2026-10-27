@extends('layouts.app')
@section('title', 'Quiz Results')

@section('content')
<div class="page-head">
    <h2><x-icon name="chart" /> {{ app()->getLocale() === 'ar' ? 'نتيجة الطالب' : 'Attempt Results' }}</h2>
    <a href="{{ route('quizzes.show', $quiz) }}" class="btn btn-ghost">← {{ __('app.back') }}</a>
</div>

<div style="max-width:640px;margin:0 auto">
    @php
        $pct         = $attempt->total_points > 0 ? round(($attempt->score / $attempt->total_points) * 100) : 0;
        $essayQs     = $quiz->questions->where('type', 'essay');
        $hasEssay    = $essayQs->isNotEmpty();
        $pending     = $hasEssay && !$attempt->graded_at;
    @endphp

    <div class="card" style="text-align:center;padding:40px;margin-bottom:24px">
            <div style="font-size:4rem;margin-bottom:8px">
                @if($pending)<x-icon name="clock" />@elseif($attempt->passed)<x-icon name="sparkles" />@else<x-icon name="sad" />@endif
            </div>
        <h2 style="font-size:1.5rem;font-weight:700;margin-bottom:6px">
            {{ $pending ? (app()->getLocale() === 'ar' ? 'بانتظار التصحيح' : 'Pending Grading') : ($attempt->passed ? (app()->getLocale() === 'ar' ? 'ناجح' : 'Passed!') : (app()->getLocale() === 'ar' ? 'راسب' : 'Failed')) }}
        </h2>
        <p style="color:var(--muted);margin-bottom:24px">{{ $attempt->student->name }} — {{ $quiz->title }}</p>

        <div style="display:flex;justify-content:center;gap:32px;margin-bottom:24px;flex-wrap:wrap">
            <div>
                <div style="font-size:2.5rem;font-weight:700;color:{{ $pending ? 'var(--gold)' : ($attempt->passed ? 'var(--green)' : 'var(--red)') }}">{{ $pct }}%</div>
                <div style="color:var(--muted);font-size:0.8rem">{{ app()->getLocale() === 'ar' ? 'الدرجة' : 'SCORE' }}</div>
            </div>
            <div>
                <div style="font-size:2.5rem;font-weight:700">{{ $attempt->score }}/{{ $attempt->total_points }}</div>
                <div style="color:var(--muted);font-size:0.8rem">{{ app()->getLocale() === 'ar' ? 'نقاط' : 'POINTS' }}</div>
            </div>
            <div>
                <div style="font-size:2.5rem;font-weight:700;color:var(--gold)">{{ $quiz->pass_score }}%</div>
                <div style="color:var(--muted);font-size:0.8rem">{{ app()->getLocale() === 'ar' ? 'للنجاح' : 'REQUIRED' }}</div>
            </div>
        </div>

        <div style="background:var(--surface2);border-radius:999px;height:10px;overflow:hidden;margin-bottom:8px">
            <div style="height:100%;width:{{ $pct }}%;background:{{ $pending ? 'var(--gold)' : ($attempt->passed ? 'var(--green)' : 'var(--red)') }};border-radius:999px;"></div>
        </div>
        <div style="font-size:0.78rem;color:var(--muted)">{{ app()->getLocale() === 'ar' ? 'درجة النجاح' : 'Pass mark' }} {{ $quiz->pass_score }}%</div>

            <span class="badge {{ $pending ? 'badge-yellow' : ($attempt->passed ? 'badge-green' : 'badge-red') }}" style="font-size:0.85rem;padding:6px 18px;margin-top:16px;display:inline-flex;align-items:center;gap:6px">
                @if($pending)<x-icon name="clock" /> {{ app()->getLocale() === 'ar' ? 'بانتظار تصحيح الأسئلة المقالية' : 'Awaiting essay grading' }}@elseif($attempt->passed)@if(app()->getLocale() === 'ar')<x-icon name="check" /> ناجح @else <x-icon name="check" /> PASSED @endif @else @if(app()->getLocale() === 'ar')<x-icon name="x" /> راسب @else <x-icon name="x" /> FAILED @endif @endif
            </span>
    </div>

    {{-- Essay grading form --}}
    @if($hasEssay)
    <div class="card" style="margin-bottom:24px">
        <div class="card-title" style="margin-bottom:16px"><x-icon name="clipboard" /> {{ app()->getLocale() === 'ar' ? 'تصحيح الأسئلة المقالية' : 'Grade Essay Questions' }}</div>
        @if($attempt->graded_at)
        <div class="alert alert-success" style="margin-bottom:16px">
            <x-icon name="check" /> {{ app()->getLocale() === 'ar' ? 'تم التصحيح بتاريخ' : 'Graded on' }} {{ $attempt->graded_at->format('d M Y، h:i A') }}
        </div>
        @endif
        <form method="POST" action="{{ route('quizzes.attempts.grade', [$quiz, $attempt]) }}">
            @csrf @method('PATCH')
            @foreach($essayQs as $question)
            <div style="padding:14px 0;border-bottom:1px solid var(--border)">
                <div style="font-weight:700;font-size:0.88rem;margin-bottom:8px">
                    {{ $loop->iteration }}. {{ $question->question_text }}
                    <span style="color:var(--muted);font-weight:400">({{ app()->getLocale() === 'ar' ? 'حتى' : 'max' }} {{ $question->points }} {{ __('app.pts') }})</span>
                </div>
                <div style="background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:12px 14px;font-size:0.9rem;white-space:pre-wrap;margin-bottom:10px">{{ $attempt->answers[$question->id] ?? '—' }}</div>
                <div style="display:flex;gap:10px;align-items:center">
                    <label style="font-size:0.82rem;color:var(--muted);font-weight:700">{{ app()->getLocale() === 'ar' ? 'الدرجة' : 'Score' }}:</label>
                    <input type="number" name="scores[{{ $question->id }}]"
                           value="{{ old("scores.{$question->id}", $attempt->manual_scores[$question->id] ?? 0) }}"
                           min="0" max="{{ $question->points }}" required
                           style="width:90px;padding:8px 10px;border-radius:8px;border:1px solid var(--border);background:var(--surface2);color:var(--text);text-align:center">
                </div>
                @error("scores.$question->id") <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            @endforeach
            <div style="margin-top:16px">
                <button type="submit" class="btn btn-primary">{{ app()->getLocale() === 'ar' ? 'حفظ الدرجات واعتماد النتيجة' : 'Save Grades' }} →</button>
            </div>
        </form>
    </div>
    @endif

    {{-- Answer breakdown --}}
    <div class="card">
        <div class="card-title" style="margin-bottom:18px">{{ app()->getLocale() === 'ar' ? 'تفاصيل الإجابات' : 'Answer Breakdown' }}</div>
        @foreach($quiz->questions as $i => $question)
        @php
            $given   = $attempt->answers[$question->id] ?? null;
            $isEssay = $question->type === 'essay';
            $isRight = !$isEssay && \App\Support\AnswerChecker::isCorrect($given, $question);
            $essayPts = $isEssay ? ($attempt->manual_scores[$question->id] ?? null) : null;
        @endphp
        <div style="padding:14px 0;border-bottom:1px solid var(--border)">
            <div style="display:flex;gap:8px;align-items:flex-start;margin-bottom:10px">
                            <span style="font-size:1.1rem">
                                @if($isEssay)<x-icon name="clipboard" />@elseif($isRight)<x-icon name="check-circle" />@else<x-icon name="x-circle" />@endif
                            </span>
                <div style="font-size:0.88rem;font-weight:600">
                    Q{{ $i+1 }}. {{ $question->question_text }}
                    <span class="badge badge-blue" style="font-size:0.65rem;vertical-align:middle">{{ \App\Models\Question::TYPES[$question->type] ?? $question->type }}</span>
                </div>
            </div>
            <div style="margin-left:26px;display:flex;flex-direction:column;gap:6px;font-size:0.85rem">
                @if($question->type === 'mcq')
                    @php
                        $correctLetter = \App\Support\AnswerChecker::canonical($question->correct_answer, $question);
                        $givenLetter   = \App\Support\AnswerChecker::canonical($given, $question);
                        $labels        = \App\Models\Question::OPTION_LABELS;
                    @endphp
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:4px">
                        @foreach(($question->option_list ?? []) as $idx => $text)
                        @php $letter = chr(97 + $idx); @endphp
                        <div style="padding:5px 10px;border-radius:6px;
                            {{ $letter === $correctLetter ? 'background:rgba(62,201,122,0.12);color:var(--green)' : ($letter === $givenLetter && !$isRight ? 'background:rgba(224,85,85,0.12);color:var(--red)' : 'color:var(--muted)') }}">
                            <strong>{{ $labels[$idx] ?? strtoupper($letter) }}</strong>) {{ $text }}
                            @if($letter === $correctLetter) <x-icon name="check" /> @elseif($letter === $givenLetter && !$isRight) <x-icon name="x" /> @endif
                        </div>
                        @endforeach
                    </div>
                @else
                    <div>
                        <span style="color:var(--muted);font-weight:700">{{ app()->getLocale() === 'ar' ? 'إجابة الطالب:' : 'Student answer:' }}</span>
                        <span style="color:{{ $isEssay ? 'var(--text)' : ($isRight ? 'var(--green)' : 'var(--red)') }}">{{ $question->givenLabel($given) }}</span>
                        @if($isEssay && $essayPts !== null)
                        <span style="color:var(--gold)"> — {{ app()->getLocale() === 'ar' ? 'الدرجة:' : 'Score:' }} {{ $essayPts }}/{{ $question->points }}</span>
                        @endif
                    </div>
                    @if(!$isEssay && !$isRight)
                    <div>
                        <span style="color:var(--muted);font-weight:700">{{ app()->getLocale() === 'ar' ? 'الصحيحة:' : 'Correct:' }}</span>
                        <span style="color:var(--green)">{{ $question->correctLabel() }}</span>
                    </div>
                    @endif
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <div style="display:flex;gap:12px;margin-top:20px;justify-content:center">
        <a href="{{ route('quizzes.take', $quiz) }}" class="btn btn-ghost">{{ app()->getLocale() === 'ar' ? 'محاولة أخرى' : 'Try Again' }}</a>
        <a href="{{ route('quizzes.index') }}" class="btn btn-primary">{{ __('app.quizzes') }}</a>
    </div>
</div>
@endsection
