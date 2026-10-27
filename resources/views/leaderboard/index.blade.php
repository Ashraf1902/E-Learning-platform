@extends(auth()->user()->isAdmin() ? 'layouts.app' : 'portal.layout')
@section('title', __('app.leaderboard'))

@section('content')
@php $isAr = app()->getLocale() === 'ar'; @endphp

@if($lectures->count())
<div class="link-grid">
    @foreach($lectures as $lecture)
    @php
        $route = auth()->user()->isAdmin()
            ? route('leaderboard.show', $lecture)
            : route('portal.leaderboard.show', $lecture);
    @endphp
    <a href="{{ $route }}" class="link-card">
        <div class="link-card-icon"><x-icon name="trophy" /></div>
        <div class="link-card-body">
            <div class="link-card-title">{{ $lecture->title }}</div>
            <div class="link-card-meta">{{ $lecture->category }} · {{ $lecture->quizzes_count }} {{ __('app.quizzes') }}</div>
        </div>
        <div class="link-card-side">
            <span class="badge badge-gold">{{ $lecture->level_label }}</span>
            <span class="link-card-arrow"><x-icon :name="$isAr ? 'arrow-left' : 'arrow-right'" /></span>
        </div>
    </a>
    @endforeach
</div>
@else
<div class="card">
    <div class="empty-state">
        <div class="icon"><x-icon name="trophy" /></div>
        <div class="empty-title">{{ $isAr ? 'لا يوجد ترتيب بعد' : 'No rankings yet' }}</div>
        <div class="empty-hint">{{ $isAr ? 'تظهر لوحة المتصدرين للمحاضرات التي تحتوي على اختبارات منشورة.' : 'Leaderboards appear for lectures that have published quizzes.' }}</div>
    </div>
</div>
@endif
@endsection
