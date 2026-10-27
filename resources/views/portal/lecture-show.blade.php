@extends('portal.layout')
@section('title', $lecture->title)

@push('styles')
<style>
@media (max-width: 700px) {
    .lecture-show-grid {
        grid-template-columns: 1fr !important;
    }
    /* Description card full width */
    .lecture-show-grid > div:first-child { order: 1; }
    /* Quizzes card below */
    .lecture-show-grid > div:last-child  { order: 2; }
    /* Video controls bar fits on small screen */
    .secure-protected-label { display: none; }
}
</style>
@endpush

@section('content')
<div class="page-head">
    <h2>{{ $lecture->title }}</h2>
    <a href="{{ route('portal.lectures') }}" class="btn btn-ghost">← {{ __('app.back') }}</a>
</div>

{{-- Video always full width on top on mobile --}}
@if($lecture->hasVideo())
<div class="card" style="margin-bottom:20px;padding:16px">
    <x-secure-video-player :lecture="$lecture" :student="$student" />
</div>
@endif

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px" class="lecture-show-grid">
    <div>
        <div class="card">
            <div style="display:flex;gap:10px;margin-bottom:16px;flex-wrap:wrap">
                <span class="badge badge-blue">{{ $lecture->category }}</span>
                <span class="badge badge-gold">{{ $lecture->level_label }}</span>
                @if($lecture->duration_minutes)
                <span class="badge badge-blue">⏱ {{ $lecture->duration_minutes }} {{ __('app.min') }}</span>
                @endif
            </div>
            <p style="color:var(--muted);font-size:0.9rem;line-height:1.8">{{ $lecture->description ?? __('app.no_data') }}</p>
        </div>

        {{-- Materials --}}
        @if($lecture->materials->count())
        <div class="card mt-4">
            <div class="card-title" style="margin-bottom:16px"><x-icon name="paperclip" /> {{ app()->getLocale() === 'ar' ? 'المرفقات' : 'Materials' }} ({{ $lecture->materials->count() }})</div>
            @foreach($lecture->materials as $material)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border)">
                <div style="display:flex;align-items:center;gap:10px">
                    <span style="font-size:1.3rem">{{ $material->file_icon }}</span>
                    <div>
                        <div style="font-size:0.88rem;font-weight:600">{{ $material->title }}</div>
                        <div style="font-size:0.74rem;color:var(--muted)">{{ $material->file_size_label }}</div>
                    </div>
                </div>
                <a href="{{ $material->file_url }}" target="_blank" class="btn btn-primary btn-sm">
                    <x-icon name="download" /> {{ app()->getLocale() === 'ar' ? 'تحميل' : 'Download' }}
                </a>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <div class="card">
        <div class="card-title" style="margin-bottom:16px"><x-icon name="clipboard" /> {{ __('app.quizzes') }} ({{ $lecture->quizzes->count() }})</div>
        @forelse($lecture->quizzes as $quiz)
            @if($quiz->status === 'published')
            @php
                $attempt = $student->quizAttempts->where('quiz_id', $quiz->id)->first();
                $taken   = $attempt !== null;
            @endphp
            <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid var(--border)">
                <div>
                    <div style="font-weight:600;font-size:0.88rem">{{ $quiz->title }}</div>
                    <div style="font-size:0.75rem;color:var(--muted);margin-top:2px">
                        {{ app()->getLocale() === 'ar' ? 'درجة النجاح:' : 'Pass:' }} {{ $quiz->pass_score }}%
                    </div>
                </div>
                @if($taken)
                    <div style="display:flex;align-items:center;gap:8px">
                        <span class="badge {{ $attempt->passed ? 'badge-green' : 'badge-red' }}">
                            {{ $attempt->passed ? __('app.passed') : __('app.failed') }}
                        </span>
                        <a href="{{ route('portal.results.review', $attempt->id) }}" class="btn btn-ghost btn-sm">{{ __('app.review') }}</a>
                    </div>
                @else
                    <a href="{{ route('portal.quizzes.take', $quiz) }}" class="btn btn-primary btn-sm">
                        {{ __('app.take_quiz') }} →
                    </a>
                @endif
            </div>
            @endif
        @empty
        <div class="text-muted" style="padding:12px 0;font-size:0.85rem">{{ __('app.no_data') }}</div>
        @endforelse
    </div>
</div>
@endsection