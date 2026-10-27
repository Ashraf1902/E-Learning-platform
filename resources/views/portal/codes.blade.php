@extends('portal.layout')
@section('title', app()->getLocale() === 'ar' ? 'أكواد الفتح' : 'Access Codes')

@section('content')
@php $isAr = app()->getLocale() === 'ar'; @endphp

<div style="max-width:700px;display:flex;flex-direction:column;gap:18px">
    <div class="card">
        <div class="section-title" style="margin-bottom:6px"><x-icon name="ticket" /> {{ $isAr ? 'لديك كود فتح؟' : 'Have an unlock code?' }}</div>
        <p class="cell-sub" style="margin-bottom:16px">
            {{ $isAr
                ? 'أدخل الكود لفتح المحاضرات المرتبطة به مباشرة — بدون الحاجة لاشتراك.'
                : 'Enter the code to instantly unlock its lectures — no subscription needed.' }}
        </p>

        <form method="POST" action="{{ route('portal.codes.redeem') }}" style="display:flex;gap:10px;flex-wrap:wrap">
            @csrf
            <input type="text" name="code" value="{{ old('code') }}" required
                   class="code-input"
                   placeholder="{{ $isAr ? 'أدخل الكود هنا' : 'Enter code here' }}"
                   autocomplete="off" spellcheck="false">
            <button type="submit" class="btn btn-primary">{{ $isAr ? 'فتح' : 'Redeem' }}</button>
        </form>

        @error('code')
        <div class="alert alert-error" style="margin-top:12px;margin-bottom:0"><x-icon name="x-circle" /> {{ $message }}</div>
        @enderror
    </div>

    <div class="card">
        <div class="section-head">
            <div class="section-title"><x-icon name="clipboard" /> {{ $isAr ? 'الأكواد التي استخدمتها' : 'Codes you used' }}</div>
            <span class="badge badge-blue">{{ $usages->count() }}</span>
        </div>

        <div class="data-list">
            @forelse($usages as $usage)
            <div class="data-row">
                <div class="data-icon"><x-icon name="ticket" /></div>
                <div class="data-main">
                    <span class="code-chip">{{ $usage->code->code }}</span>
                    <span class="data-meta">
                        @if($usage->code->lectures->count())
                            {{ $usage->code->lectures->pluck('title')->take(3)->implode('، ') }}{{ $usage->code->lectures->count() > 3 ? ' +' . ($usage->code->lectures->count() - 3) : '' }}
                        @else
                            {{ $isAr ? 'بدون محاضرات مرتبطة' : 'No linked lectures' }}
                        @endif
                    </span>
                </div>
                <div class="data-side" style="flex-direction:column;align-items:flex-end;gap:4px">
                    <span class="badge {{ $usage->code->type === 'registration' ? 'badge-blue' : 'badge-gold' }}">
                        {{ $usage->code->type === 'registration'
                            ? ($isAr ? 'كود تسجيل' : 'Registration')
                            : ($isAr ? 'كود فتح' : 'Unlock') }}
                    </span>
                    <span class="cell-sub" style="margin:0">{{ $usage->created_at->format('d M Y') }}</span>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <div class="icon"><x-icon name="ticket" /></div>
                <div class="empty-title">{{ $isAr ? 'لم تستخدم أي كود بعد' : 'No codes used yet' }}</div>
                <div class="empty-hint">{{ $isAr ? 'أدخل كوداً أعلاه وستظهر هنا تفاصيله والمحاضرات التي فتحها.' : 'Redeem a code above and its details and unlocked lectures will appear here.' }}</div>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
