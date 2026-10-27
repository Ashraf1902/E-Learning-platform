@extends('portal.layout')
@section('title', __('app.dashboard'))

@section('content')
@php
    $isAr = app()->getLocale() === 'ar';
    $student = auth()->user()->student;
    $activeSub = $student?->activeSubscription();
    $hasUnlocked = !$activeSub && ($student?->hasUnlockedLectures() ?? false);
@endphp

@if($activeSub)
<div class="cta-card is-ok">
    <div class="cta-icon"><x-icon name="check-circle" /></div>
    <div class="cta-body">
        <div class="cta-title">{{ $activeSub->plan->name ?? ($isAr ? 'اشتراكك مفعّل' : 'Subscription active') }}</div>
        <div class="cta-text">
            @if($activeSub->ends_at)
                {{ $isAr ? 'الوصول الكامل متاح حتى' : 'Full access until' }} {{ $activeSub->ends_at->format('d M Y') }}
            @else
                {{ $isAr ? 'لديك وصول كامل لكل المحاضرات والاختبارات.' : 'You have full access to all lectures and quizzes.' }}
            @endif
        </div>
    </div>
    <a href="{{ route('portal.subscription') }}" class="btn btn-ghost btn-sm">{{ $isAr ? 'تفاصيل الاشتراك' : 'Subscription details' }}</a>
</div>
@else
<div class="cta-card is-gold">
    <div class="cta-icon"><x-icon :name="$hasUnlocked ? 'ticket' : 'lock'" /></div>
    <div class="cta-body">
        <div class="cta-title">
            {{ $hasUnlocked
                ? ($isAr ? 'لديك محاضرات مفتوحة بالكود' : 'You have code-unlocked lectures')
                : ($isAr ? 'فعّل اشتراكك للوصول الكامل' : 'Activate your subscription for full access') }}
        </div>
        <div class="cta-text">
            {{ $hasUnlocked
                ? ($isAr ? 'يمكنك مشاهدة المحاضرات المفتوحة الآن — وفعّل الاشتراك للوصول إلى كل المحتوى.' : 'Watch your unlocked lectures now — subscribe for full access.')
                : ($isAr ? 'المحاضرات والاختبارات ولوحة المتصدرين متاحة للمشتركين أو لمن يفعّل كود فتح.' : 'Lectures, quizzes and the leaderboard are available to subscribers or via an unlock code.') }}
        </div>
    </div>
    <div class="cta-actions">
        <a href="{{ route('portal.codes') }}" class="btn btn-ghost btn-sm"><x-icon name="ticket" /> {{ $isAr ? 'أكواد الفتح' : 'Access Codes' }}</a>
        <a href="{{ route('portal.subscription') }}" class="btn btn-primary btn-sm">{{ $isAr ? 'عرض الخطط' : 'View Plans' }}<x-icon :name="$isAr ? 'arrow-left' : 'arrow-right'" /></a>
    </div>
</div>
@endif

<div class="stat-strip" style="--stat-cols:3">
    <div class="stat-cell">
        <div class="stat-cell-icon"><x-icon name="video" /></div>
        <div>
            <div class="stat-cell-label">{{ __('app.my_lectures') }}</div>
            <div class="stat-cell-value">{{ $lectures }}</div>
        </div>
    </div>
    <div class="stat-cell">
        <div class="stat-cell-icon"><x-icon name="clipboard" /></div>
        <div>
            <div class="stat-cell-label">{{ __('app.quizzes_taken') }}</div>
            <div class="stat-cell-value">{{ $attempts }}</div>
        </div>
    </div>
    <div class="stat-cell">
        <div class="stat-cell-icon"><x-icon name="check-circle" /></div>
        <div>
            <div class="stat-cell-label">{{ __('app.quizzes_passed') }}</div>
            <div class="stat-cell-value">{{ $passed }}<span class="stat-cell-note"> / {{ $attempts }}</span></div>
        </div>
    </div>
</div>

<div class="dash-grid">
    <div class="card">
        <div class="section-head">
            <div class="section-title"><x-icon name="video" /> {{ __('app.recent_lectures') }}</div>
            <a href="{{ route('portal.lectures') }}" class="btn-link">{{ __('app.view_all') }}</a>
        </div>
        <div class="data-list">
            @forelse($recentLectures as $lecture)
            <a href="{{ route('portal.lectures.show', $lecture) }}" class="data-row">
                <div class="data-icon"><x-icon name="play" /></div>
                <div class="data-main">
                    <span class="data-title">{{ $lecture->title }}</span>
                    <span class="data-meta">{{ $lecture->category }} · {{ $lecture->duration_minutes ? $lecture->duration_minutes . ' ' . __('app.min') : '—' }}</span>
                </div>
                <div class="data-side"><x-icon :name="$isAr ? 'arrow-left' : 'arrow-right'" /></div>
            </a>
            @empty
            <div class="empty-state">
                <div class="icon"><x-icon name="video" /></div>
                <div class="empty-title">{{ $isAr ? 'لا توجد محاضرات بعد' : 'No lectures yet' }}</div>
                <div class="empty-hint">{{ $isAr ? 'ستظهر المحاضرات المفتوحة أو المشمولة باشتراكك هنا.' : 'Unlocked or subscription lectures will appear here.' }}</div>
            </div>
            @endforelse
        </div>
    </div>

    <div class="card">
        <div class="section-head">
            <div class="section-title"><x-icon name="chart" /> {{ __('app.recent_attempts') }}</div>
            <a href="{{ route('portal.results') }}" class="btn-link">{{ __('app.view_all') }}</a>
        </div>
        <div class="data-list">
            @forelse($recentAttempts as $attempt)
            @php $pct = $attempt->total_points > 0 ? round(($attempt->score / $attempt->total_points) * 100) : 0; @endphp
            <a href="{{ route('portal.results.review', $attempt->id) }}" class="data-row">
                <div class="data-icon"><x-icon name="clipboard" /></div>
                <div class="data-main">
                    <span class="data-title">{{ $attempt->quiz->title }}</span>
                    <span class="data-meta">{{ $attempt->score }}/{{ $attempt->total_points }} · {{ $attempt->created_at->format('d M Y') }}</span>
                </div>
                <div class="data-side">
                    <span class="score-text {{ $attempt->passed ? 'is-pass' : 'is-fail' }}">{{ $pct }}%</span>
                    <span class="badge {{ $attempt->passed ? 'badge-green' : 'badge-red' }}">
                        {{ $attempt->passed ? __('app.passed') : __('app.failed') }}
                    </span>
                </div>
            </a>
            @empty
            <div class="empty-state">
                <div class="icon"><x-icon name="clipboard" /></div>
                <div class="empty-title">{{ $isAr ? 'لم تجرّب أي اختبار بعد' : 'No quiz attempts yet' }}</div>
                <div class="empty-hint">{{ $isAr ? 'بعد إكمال أول اختبار ستظهر نتيجتك هنا.' : 'Your first quiz result will appear here.' }}</div>
                <a href="{{ route('portal.quizzes') }}" class="btn btn-ghost btn-sm"><x-icon name="clipboard" /> {{ __('app.my_quizzes') }}</a>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
