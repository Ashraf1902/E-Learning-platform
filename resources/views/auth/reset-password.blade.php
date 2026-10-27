@extends('layouts.auth')

@section('title', 'تعيين كلمة مرور جديدة')
@section('heading', 'كلمة مرور جديدة.')
@section('sub', 'اختر كلمة مرور قوية لحسابك. الطول الموصى به 8 أحرف على الأقل.')
@section('side-title', 'بداية جديدة لحسابك.')
@section('side-note', 'اختر كلمة مرور لا تستخدمها في مكان آخر.')

@section('content')
    @if ($errors->any())
        <div class="notice notice-error" role="alert">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16.5v.01"/></svg>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <form class="auth-form" method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="form-group">
            <label for="email">البريد الإلكتروني</label>
            <div class="field">
                <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                <input
                    id="email"
                    name="email"
                    type="email"
                    dir="ltr"
                    value="{{ old('email', $request->email) }}"
                    placeholder="name@example.com"
                    autocomplete="email"
                    inputmode="email"
                    required
                    aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                >
            </div>
            @if ($errors->has('email'))
                <p class="field-error">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <div class="form-group">
            <label for="password">كلمة المرور الجديدة</label>
            <div class="field">
                <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                <input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="••••••••"
                    autocomplete="new-password"
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

        <div class="form-group">
            <label for="password_confirmation">تأكيد كلمة المرور</label>
            <div class="field">
                <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                <input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    placeholder="••••••••"
                    autocomplete="new-password"
                    required
                >
                <button class="field-toggle" type="button" data-toggle-password aria-controls="password_confirmation" aria-pressed="false" aria-label="إظهار كلمة المرور">
                    <svg class="icon-on" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg class="icon-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><path d="M17.9 17.9A10.4 10.4 0 0 1 12 19c-6.5 0-10-7-10-7a18.6 18.6 0 0 1 5.1-5.9M9.9 4.2A10.5 10.5 0 0 1 12 4c6.5 0 10 7 10 7a18.7 18.7 0 0 1-2.1 3.1M3 3l18 18"/></svg>
                </button>
            </div>
        </div>

        <button class="btn-submit" type="submit">
            تعيين كلمة المرور
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
        </button>
    </form>

    <p class="auth-alt">تذكّرت كلمة المرور؟ <a href="{{ route('login') }}">سجّل دخولك</a></p>
@endsection
