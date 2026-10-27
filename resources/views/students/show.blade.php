@extends('layouts.app')
@section('title', $student->name)

@section('content')
<div class="page-head">
    <h2>{{ $student->name }}</h2>
    <div style="display:flex;gap:8px">
        <a href="{{ route('students.edit', $student) }}" class="btn btn-ghost">{{ __('app.edit') }}</a>
        <a href="{{ route('students.index') }}" class="btn btn-ghost">← {{ __('app.back') }}</a>
    </div>
</div>

<div class="grid-2" style="gap:20px">
    {{-- Student Info --}}
    <div>
        <div class="card">
            <div style="display:flex;gap:10px;margin-bottom:16px;flex-wrap:wrap">
                <span class="badge {{ $student->status === 'active' ? 'badge-green' : 'badge-red' }}">
                    {{ __('app.' . $student->status) }}
                </span>
                <span class="badge badge-gold">{{ $student->level_label }}</span>
            </div>
            <table style="font-size:0.88rem;width:100%">
                <tr><td style="color:var(--muted);padding:6px 0;width:130px">{{ __('app.email') }}</td><td>{{ $student->email }}</td></tr>
                <tr><td style="color:var(--muted);padding:6px 0">{{ __('app.phone') }}</td><td>{{ $student->phone ?? '—' }}</td></tr>
                <tr><td style="color:var(--muted);padding:6px 0">{{ __('app.level') }}</td><td>{{ $student->level_label }}</td></tr>
                <tr><td style="color:var(--muted);padding:6px 0">{{ __('app.enrolled_at') }}</td><td>{{ $student->enrolled_at ? \Carbon\Carbon::parse($student->enrolled_at)->format('d M Y') : '—' }}</td></tr>
            </table>
        </div>

        <div class="card mt-4">
            <div class="card-title" style="margin-bottom:16px"><x-icon name="video" /> {{ __('app.lectures') }} ({{ $student->lectures->count() }})</div>
            @forelse($student->lectures as $lecture)
            <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid var(--border)">
                <div>
                    <div style="font-weight:600;font-size:0.88rem">{{ $lecture->title }}</div>
                    <div style="color:var(--muted);font-size:0.78rem">{{ $lecture->category }}</div>
                </div>
                    <span class="badge {{ $lecture->pivot->completed ? 'badge-green' : 'badge-yellow' }}">
                        @if($lecture->pivot->completed)<x-icon name="check" />@else ... @endif
                    </span>
            </div>
            @empty
            <div class="text-muted" style="padding:12px 0;font-size:0.85rem">{{ __('app.no_data') }}</div>
            @endforelse
        </div>
    </div>

    {{-- Quiz Attempts with Reset --}}
    <div class="card">
        <div class="card-title" style="margin-bottom:16px"><x-icon name="clipboard" /> {{ __('app.quizzes') }} ({{ $student->quizAttempts->count() }})</div>

        @forelse($student->quizAttempts as $attempt)
        <div style="padding:12px 0;border-bottom:1px solid var(--border)">
            <div style="display:flex;justify-content:space-between;align-items:center">
                <div>
                    <div style="font-weight:600;font-size:0.88rem">{{ $attempt->quiz->title }}</div>
                    <div style="color:var(--muted);font-size:0.78rem;margin-top:2px">
                        {{ $attempt->score }}/{{ $attempt->total_points }} pts
                        ({{ $attempt->total_points > 0 ? round(($attempt->score/$attempt->total_points)*100) : 0 }}%)
                        · {{ $attempt->completed_at?->format('d M Y') }}
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:8px">
                    <span class="badge {{ $attempt->passed ? 'badge-green' : 'badge-red' }}">
                        {{ $attempt->passed ? __('app.passed') : __('app.failed') }}
                    </span>
                    {{-- Reset Button --}}
                    <form method="POST"
                          action="{{ route('students.attempts.reset', [$student, $attempt->quiz_id]) }}"
                          onsubmit="return confirm('{{ app()->getLocale() === 'ar' ? 'هل تريد إعادة تعيين محاولة هذا الطالب؟' : 'Reset this student attempt?' }}')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" title="{{ __('app.reset_attempt') }}">
                            <x-icon name="refresh" /> {{ __('app.reset_attempt') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="text-muted" style="padding:12px 0;font-size:0.85rem">{{ __('app.no_data') }}</div>
        @endforelse
    </div>
</div>
@endsection