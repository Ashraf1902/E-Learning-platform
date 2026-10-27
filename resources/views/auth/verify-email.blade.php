@extends('layouts.auth')

@section('title', 'تأكيد البريد الإلكتروني')
@section('heading', 'أكّد بريدك الإلكتروني.')
@section('sub', 'افتح الرسالة التي أرسلناها إلى بريدك واضغط على رابط التأكيد. إن لم تصلك، اطلب رسالة جديدة.')
@section('side-title', 'خطوة أخيرة قبل أن تبدأ.')
@section('side-note', 'التأكيد يحمي حسابك ويضمن وصول الإشعارات إليك.')

@section('content')
    @if (session('status') === 'verification-link-sent')
        <div class="notice notice-ok" role="status">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
            <span>أُرسلت رسالة تأكيد جديدة إلى بريدك الإلكتروني.</span>
        </div>
    @endif

    <div class="auth-form">
        <div class="form-actions">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button class="btn-submit" type="submit">
                    إعادة إرسال رسالة التأكيد
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12a9 9 0 1 1-3-6.7M21 3v6h-6"/></svg>
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn-ghost" type="submit">تسجيل الخروج</button>
            </form>
        </div>
    </div>

    <p class="auth-alt">تحتاج مساعدة؟ <a href="{{ route('landing') }}">تواصل مع المدرس</a></p>
@endsection
