@extends('portal.layout')
@section('title', __('app.my_results'))

@section('content')
@php $isAr = app()->getLocale() === 'ar'; @endphp

<div class="card">
    <div class="table-wrap desktop-table">
        <table>
            <thead>
                <tr>
                    <th>{{ __('app.quiz_title') }}</th>
                    <th>{{ __('app.score') }}</th>
                    <th>{{ __('app.percentage') }}</th>
                    <th>{{ __('app.status') }}</th>
                    <th>{{ __('app.date') }}</th>
                    <th>{{ __('app.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attempts as $attempt)
                @php
                    $pct = $attempt->total_points > 0 ? round(($attempt->score / $attempt->total_points) * 100) : 0;
                    $state = $pct >= 70 ? 'is-pass' : ($pct >= 50 ? 'is-mid' : 'is-fail');
                @endphp
                <tr>
                    <td class="cell-strong">{{ $attempt->quiz->title }}</td>
                    <td class="cell-muted">{{ $attempt->score }}/{{ $attempt->total_points }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;min-width:110px">
                            <div class="progress" style="width:72px;flex:none">
                                <div class="progress-fill {{ $state === 'is-pass' ? 'is-pass' : ($state === 'is-fail' ? 'is-fail' : '') }}" style="width:{{ $pct }}%"></div>
                            </div>
                            <span class="score-text {{ $state }}">{{ $pct }}%</span>
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $attempt->passed ? 'badge-green' : 'badge-red' }}">
                            {{ $attempt->passed ? __('app.passed') : __('app.failed') }}
                        </span>
                    </td>
                    <td class="cell-muted" style="font-size:0.8rem">{{ $attempt->completed_at?->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('portal.results.review', $attempt) }}" class="btn btn-ghost btn-sm"><x-icon name="search" /> {{ __('app.review') }}</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <div class="icon"><x-icon name="chart" /></div>
                            <div class="empty-title">{{ $isAr ? 'لا توجد نتائج بعد' : 'No results yet' }}</div>
                            <div class="empty-hint">{{ $isAr ? 'أكمل اختباراً لتظهر نتيجتك هنا مع مراجعة الإجابات.' : 'Complete a quiz to see your result and review answers here.' }}</div>
                            <a href="{{ route('portal.quizzes') }}" class="btn btn-ghost btn-sm"><x-icon name="clipboard" /> {{ __('app.my_quizzes') }}</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mobile-card-list">
        @forelse($attempts as $attempt)
        @php
            $pct = $attempt->total_points > 0 ? round(($attempt->score / $attempt->total_points) * 100) : 0;
            $state = $pct >= 70 ? 'is-pass' : ($pct >= 50 ? 'is-mid' : 'is-fail');
        @endphp
        <div class="mobile-row-card">
            <div class="mobile-row-top">
                <span class="mobile-row-title">{{ $attempt->quiz->title }}</span>
                <span class="badge {{ $attempt->passed ? 'badge-green' : 'badge-red' }}">
                    {{ $attempt->passed ? __('app.passed') : __('app.failed') }}
                </span>
            </div>
            <div class="progress">
                <div class="progress-fill {{ $state === 'is-pass' ? 'is-pass' : ($state === 'is-fail' ? 'is-fail' : '') }}" style="width:{{ $pct }}%"></div>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center">
                <span class="cell-muted">{{ $attempt->score }}/{{ $attempt->total_points }}</span>
                <span class="score-text {{ $state }}">{{ $pct }}%</span>
            </div>
            <div class="mobile-row-meta">{{ $attempt->completed_at?->format('d M Y') }}</div>
            <div class="mobile-row-actions">
                <a href="{{ route('portal.results.review', $attempt) }}" class="btn btn-ghost btn-sm"><x-icon name="search" /> {{ __('app.review') }}</a>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <div class="icon"><x-icon name="chart" /></div>
            <div class="empty-title">{{ $isAr ? 'لا توجد نتائج بعد' : 'No results yet' }}</div>
        </div>
        @endforelse
    </div>

    <div class="pagination">{{ $attempts->links() }}</div>
</div>
@endsection
