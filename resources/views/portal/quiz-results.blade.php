@extends('portal.layout')
@section('title', app()->getLocale() === 'ar' ? 'نتيجة الاختبار' : 'Quiz Results')

@section('content')
<div class="page-head">
    <h2><x-icon name="chart" /> {{ app()->getLocale() === 'ar' ? 'نتيجة الاختبار' : 'Quiz Results' }}</h2>
    <a href="{{ route('portal.quizzes') }}" class="btn btn-ghost">← {{ __('app.back') }}</a>
</div>

<div style="max-width:640px;margin:0 auto">
    @php
        $pct     = $attempt->total_points > 0 ? round(($attempt->score / $attempt->total_points) * 100) : 0;
        $pending = $attempt->isPendingGrading();
    @endphp

    <div class="card" style="text-align:center;padding:40px;margin-bottom:24px">
        <div style="font-size:4rem;margin-bottom:8px">
            @if ($pending)
                <x-icon name="clock" />
            @elseif ($attempt->passed)
                <x-icon name="sparkles" />
            @else
                <x-icon name="sad" />
            @endif
        </div>
        <h2 style="font-size:1.5rem;font-weight:900;margin-bottom:6px">
            @if ($pending)
                {{ app()->getLocale() === 'ar' ? 'تم إرسال إجاباتك!' : 'Answers submitted!' }}
            @elseif ($attempt->passed)
                {{ app()->getLocale() === 'ar' ? 'أحسنت! لقد نجحت' : 'Congratulations!' }}
                <x-icon name="star" />
            @else
                {{ app()->getLocale() === 'ar' ? 'حاول مرة أخرى' : 'Better Luck Next Time' }}
            @endif
        </h2>
        <p style="color:var(--muted);margin-bottom:24px">{{ $quiz->title }}</p>

        @if($pending)
        <div class="alert alert-info" style="text-align:right;max-width:440px;margin:0 auto 20px">
            <x-icon name="clipboard" /> {{ app()->getLocale() === 'ar'
                ? 'يحتوي هذا الاختبار على أسئلة مقالي تُصحَّح يدوياً من المدرس. ستظهر النتيجة النهائية بعد التصحيح، ويمكنك مراجعتها من صفحة "كل النتائج".'
                : 'This quiz has essay questions graded manually by your teacher. The final result will appear after grading under "All Results".' }}
        </div>
        @endif

        <div style="display:flex;justify-content:center;gap:32px;margin-bottom:24px;flex-wrap:wrap">
            <div>
                <div style="font-size:2.5rem;font-weight:900;color:{{ $pending ? 'var(--gold)' : ($attempt->passed ? 'var(--green)' : 'var(--red)') }}">{{ $pct }}%</div>
                <div style="color:var(--muted);font-size:0.8rem">{{ $pending ? (app()->getLocale() === 'ar' ? 'مؤقت' : 'Provisional') : __('app.score') }}</div>
            </div>
            <div>
                <div style="font-size:2.5rem;font-weight:900">{{ $attempt->score }}/{{ $attempt->total_points }}</div>
                <div style="color:var(--muted);font-size:0.8rem">{{ __('app.pts') }}</div>
            </div>
            <div>
                <div style="font-size:2.5rem;font-weight:900;color:var(--gold)">{{ $quiz->pass_score }}%</div>
                <div style="color:var(--muted);font-size:0.8rem">{{ app()->getLocale() === 'ar' ? 'للنجاح' : 'Required' }}</div>
            </div>
        </div>

        <div style="background:var(--surface2);border-radius:999px;height:10px;overflow:hidden;margin-bottom:8px">
            <div style="height:100%;width:{{ $pct }}%;background:{{ $pending ? 'var(--gold)' : ($attempt->passed ? 'var(--green)' : 'var(--red)') }};border-radius:999px;"></div>
        </div>

        <span class="badge {{ $pending ? 'badge-yellow' : ($attempt->passed ? 'badge-green' : 'badge-red') }}" style="font-size:0.85rem;padding:6px 20px;margin-top:16px;display:inline-block">
            @if ($pending)
                <x-icon name="clock" /> {{ app()->getLocale() === 'ar' ? 'قيد التصحيح' : 'Pending grading' }}
            @else
                {{ $attempt->passed ? __('app.passed') : __('app.failed') }}
            @endif
        </span>
    </div>

    <div style="display:flex;gap:12px;justify-content:center">
        <a href="{{ route('portal.results.review', $attempt->id) }}" class="btn btn-primary">
            <x-icon name="search" /> {{ __('app.review') }}
        </a>
        <a href="{{ route('portal.results') }}" class="btn btn-ghost">{{ __('app.all_results') }}</a>
    </div>
</div>
@endsection
