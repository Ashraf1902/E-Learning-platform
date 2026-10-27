@extends(auth()->user()->isAdmin() ? 'layouts.app' : 'portal.layout')
@section('title', __('app.leaderboard') . ' — ' . $lecture->title)

@section('content')
@php
    $backRoute = auth()->user()->isAdmin()
        ? route('leaderboard.index')
        : route('portal.leaderboard.index');
    $currentStudentId = auth()->user()->isStudent() ? auth()->user()->student_id : null;
    $isAr = app()->getLocale() === 'ar';
    $avatarColors = ['#e05555','#3ec97a','#4a8cff','#d4a843','#a064f0','#28c8c8','#f07030','#e05599','#70b040','#5088e0'];
@endphp

<div class="page-head">
    <div>
        <h2><x-icon name="trophy" /> {{ $lecture->title }}</h2>
        <p>{{ __('app.leaderboard') }} · {{ $lecture->quizzes->count() }} {{ __('app.quizzes') }}</p>
    </div>
    <a href="{{ $backRoute }}" class="btn btn-ghost btn-sm"><x-icon :name="$isAr ? 'arrow-right' : 'arrow-left'" /> {{ __('app.back') }}</a>
</div>

@if($leaderboard->count() >= 3)
<div class="podium">
    <div class="podium-card place-1">
        <div class="podium-rank">{{ $isAr ? 'الأول' : '1ST' }}</div>
        <div class="podium-avatar">{{ strtoupper(mb_substr($leaderboard[0]['student']->name, 0, 1)) }}</div>
        <div class="podium-name">{{ $leaderboard[0]['student']->name }}</div>
        <div class="podium-score">{{ $leaderboard[0]['percentage'] }}%</div>
        <div class="podium-sub">{{ $leaderboard[0]['total_score'] }}/{{ $leaderboard[0]['total_points'] }}</div>
    </div>
    <div class="podium-card place-2">
        <div class="podium-rank">{{ $isAr ? 'الثاني' : '2ND' }}</div>
        <div class="podium-avatar" style="background:var(--muted2)">{{ strtoupper(mb_substr($leaderboard[1]['student']->name, 0, 1)) }}</div>
        <div class="podium-name">{{ $leaderboard[1]['student']->name }}</div>
        <div class="podium-score">{{ $leaderboard[1]['percentage'] }}%</div>
        <div class="podium-sub">{{ $leaderboard[1]['total_score'] }}/{{ $leaderboard[1]['total_points'] }}</div>
    </div>
    <div class="podium-card place-3">
        <div class="podium-rank">{{ $isAr ? 'الثالث' : '3RD' }}</div>
        <div class="podium-avatar" style="background:#8a5f2a;color:#fbf7ec">{{ strtoupper(mb_substr($leaderboard[2]['student']->name, 0, 1)) }}</div>
        <div class="podium-name">{{ $leaderboard[2]['student']->name }}</div>
        <div class="podium-score">{{ $leaderboard[2]['percentage'] }}%</div>
        <div class="podium-sub">{{ $leaderboard[2]['total_score'] }}/{{ $leaderboard[2]['total_points'] }}</div>
    </div>
</div>
@endif

<div class="card">
    <div class="section-head">
        <div class="section-title"><x-icon name="clipboard" /> {{ __('app.full_rankings') }}</div>
    </div>

    <div class="table-wrap desktop-table">
        <table>
            <thead>
                <tr>
                    <th>{{ __('app.rank') }}</th>
                    <th>{{ __('app.student') }}</th>
                    <th>{{ __('app.best_score') }}</th>
                    <th>{{ __('app.percentage') }}</th>
                    <th>{{ __('app.quizzes_passed_count') }}</th>
                    <th>{{ __('app.attempts') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leaderboard as $i => $entry)
                @php
                    $isMe = $currentStudentId && $currentStudentId === $entry['student']->id;
                    $pct = $entry['percentage'];
                    $state = $pct >= 70 ? 'is-pass' : ($pct >= 50 ? 'is-mid' : 'is-fail');
                @endphp
                <tr @class(['is-me' => $isMe])>
                    <td>
                        <span class="rank-badge {{ $i < 3 ? 'rank-' . ($i + 1) : '' }}">{{ $i + 1 }}</span>
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;min-width:0">
                            <span class="avatar" style="background:{{ $avatarColors[$entry['student']->id % count($avatarColors)] }}">{{ strtoupper(mb_substr($entry['student']->name, 0, 1)) }}</span>
                            <div style="min-width:0">
                                <div class="cell-strong" style="display:flex;align-items:center;gap:6px">
                                    <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $entry['student']->name }}</span>
                                    @if($isMe) <span class="badge badge-gold">{{ __('app.you') }}</span> @endif
                                </div>
                                <span class="cell-sub">{{ $entry['student']->level_label ?? '' }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="cell-strong">{{ $entry['total_score'] }}/{{ $entry['total_points'] }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;min-width:110px">
                            <div class="progress" style="width:72px;flex:none">
                                <div class="progress-fill {{ $state === 'is-mid' ? '' : $state }}" style="width:{{ $pct }}%"></div>
                            </div>
                            <span class="score-text {{ $state }}">{{ $pct }}%</span>
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $entry['quizzes_passed'] > 0 ? 'badge-green' : 'badge-red' }}">
                            {{ $entry['quizzes_passed'] }} / {{ $lecture->quizzes->count() }}
                        </span>
                    </td>
                    <td class="num">{{ $entry['attempts_count'] }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <div class="icon"><x-icon name="trophy" /></div>
                            <div class="empty-title">{{ __('app.no_attempts') }}</div>
                            <div class="empty-hint">{{ $isAr ? 'سيظهر الترتيب بعد أن يكمل الطلاب اختبارات هذه المحاضرة.' : 'Rankings appear once students complete this lecture\'s quizzes.' }}</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mobile-card-list">
        @forelse($leaderboard as $i => $entry)
        @php
            $isMe = $currentStudentId && $currentStudentId === $entry['student']->id;
            $pct = $entry['percentage'];
            $state = $pct >= 70 ? 'is-pass' : ($pct >= 50 ? 'is-mid' : 'is-fail');
        @endphp
        <div class="mobile-row-card" @if($isMe) style="border-color:rgba(212,168,67,0.4)" @endif>
            <div style="display:flex;align-items:center;gap:10px">
                <span class="rank-badge {{ $i < 3 ? 'rank-' . ($i + 1) : '' }}">{{ $i + 1 }}</span>
                <span class="avatar" style="background:{{ $avatarColors[$entry['student']->id % count($avatarColors)] }}">{{ strtoupper(mb_substr($entry['student']->name, 0, 1)) }}</span>
                <div style="flex:1;min-width:0">
                    <div class="cell-strong" style="display:flex;align-items:center;gap:6px">
                        <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $entry['student']->name }}</span>
                        @if($isMe) <span class="badge badge-gold">{{ __('app.you') }}</span> @endif
                    </div>
                    <div class="cell-sub">{{ $entry['student']->level_label ?? '' }}</div>
                </div>
                <div style="text-align:end;flex-shrink:0">
                    <div class="score-text {{ $state }}" style="font-size:1.05rem">{{ $pct }}%</div>
                    <div class="num">{{ $entry['total_score'] }}/{{ $entry['total_points'] }}</div>
                </div>
            </div>
            <div class="progress" style="margin-top:2px">
                <div class="progress-fill {{ $state === 'is-mid' ? '' : $state }}" style="width:{{ $pct }}%"></div>
            </div>
            <div class="mobile-row-badges">
                <span class="badge {{ $entry['quizzes_passed'] > 0 ? 'badge-green' : 'badge-red' }}">
                    {{ $isAr ? 'ناجح' : 'Passed' }}: {{ $entry['quizzes_passed'] }}/{{ $lecture->quizzes->count() }}
                </span>
                <span class="badge badge-blue">{{ $entry['attempts_count'] }} {{ $isAr ? 'محاولة' : 'attempts' }}</span>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <div class="icon"><x-icon name="trophy" /></div>
            <div class="empty-title">{{ __('app.no_attempts') }}</div>
        </div>
        @endforelse
    </div>
</div>
@endsection
