@extends('portal.layout')
@section('title', __('app.my_lectures'))

@section('content')
@php $isAr = app()->getLocale() === 'ar'; @endphp

@if($lectures->count())
<div class="lecture-grid">
    @foreach($lectures as $lecture)
    <a href="{{ route('portal.lectures.show', $lecture) }}" class="lecture-card">
        <div class="lecture-card-top">
            <div class="lecture-card-icon"><x-icon name="play" /></div>
            <span class="badge badge-gold">{{ $lecture->level_label }}</span>
        </div>
        <div class="lecture-card-title">{{ $lecture->title }}</div>
        <div class="lecture-card-meta">
            <span class="badge badge-blue">{{ $lecture->category }}</span>
            @if($lecture->duration_minutes)
            <span><x-icon name="clock" /> {{ $lecture->duration_minutes }} {{ __('app.min') }}</span>
            @endif
        </div>
        <div class="lecture-card-foot">
            <span class="lecture-card-cta">{{ $isAr ? 'مشاهدة المحاضرة' : 'Watch lecture' }} <x-icon :name="$isAr ? 'arrow-left' : 'arrow-right'" /></span>
        </div>
    </a>
    @endforeach
</div>

<div class="pagination" style="margin-top:20px">{{ $lectures->links() }}</div>
@else
<div class="card">
    <div class="empty-state">
        <div class="icon"><x-icon name="video" /></div>
        <div class="empty-title">{{ $isAr ? 'لا توجد محاضرات متاحة' : 'No lectures available' }}</div>
        <div class="empty-hint">
            {{ $isAr ? 'فعّل اشتراكك أو أدخل كود فتح للوصول إلى المحاضرات.' : 'Activate your subscription or redeem an unlock code to access lectures.' }}
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;justify-content:center">
            <a href="{{ route('portal.codes') }}" class="btn btn-ghost btn-sm"><x-icon name="ticket" /> {{ $isAr ? 'إدخال كود' : 'Enter a code' }}</a>
            <a href="{{ route('portal.subscription') }}" class="btn btn-primary btn-sm">{{ $isAr ? 'عرض الخطط' : 'View plans' }}</a>
        </div>
    </div>
</div>
@endif
@endsection
