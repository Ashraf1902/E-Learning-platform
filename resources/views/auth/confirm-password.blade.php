@extends('layouts.auth')

@section('title', 'تأكيد كلمة المرور')
@section('heading', 'تأكيد كلمة المرور.')
@section('sub', 'هذه منطقة محمية. أكّد كلمة المرور للمتابعة.')
@section('side-title', 'خطوة أمان واحدة فقط.')
@section('side-note', 'نطلب التأكيد لحماية بيانات حسابك.')

@section('content')
    @if ($errors->has('password'))
        <div class="notice notice-error" role="alert">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16.5v.01"/></svg>
            <span>{{ $errors->first('password') }}</span>
        </div>
    @endif

    <form class="auth-form" method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="form-group">
            <label for="password">كلمة المرور</label>
            <div class="field">
                <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                <input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="••••••••"
                    autocomplete="current-password"
                    required
                    autofocus
                    aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                    @if ($errors->has('password')) aria-describedby="password-error" @endif
                >
                <button class="field-toggle" type="button" data-toggle-password aria-controls="password" aria-pressed="false" aria-label="إظهار كلمة المرور">
                    <svg class="icon-on" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg class="icon-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><path d="M17.9 17.9A10.4 10.4 0 0 1 12 19c-6.5 0-10-7-10-7a18.6 18.6 0 0 1 5.1-5.9M9.9 4.2A10.5 10.5 0 0 1 12 4c6.5 0 10 7 10 7a18.7 18.7 0 0 1-2.1 3.1M3 3l18 18"/></svg>
                </button>
            </div>
            @if ($errors->has('password'))
                <p class="field-error" id="password-error">{{ $errors->first('password') }}</p>
            @endif
        </div>

        <button class="btn-submit" type="submit">
            تأكيد ومتابعة
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
        </button>
    </form>

    <p class="auth-alt">نسيت كلمة المرور؟ <a href="{{ route('password.request') }}">أعد تعيينها</a></p>
@endsection
