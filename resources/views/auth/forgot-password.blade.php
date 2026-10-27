@extends('layouts.auth')

@section('title', 'استعادة كلمة المرور')
@section('heading', 'استعادة كلمة المرور.')
@section('sub', 'أدخل بريدك الإلكتروني وسنرسل لك رابطاً لتعيين كلمة مرور جديدة.')
@section('side-title', 'الوصول إلى حسابك يبدأ بخطوة واحدة.')
@section('side-note', 'رابط آمن يصلك على بريدك، ويعيدك إلى المنصة في دقيقة.')

@section('content')
    @if (session('status'))
        <div class="notice notice-ok" role="status">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    @if ($errors->has('email'))
        <div class="notice notice-error" role="alert">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16.5v.01"/></svg>
            <span>{{ $errors->first('email') }}</span>
        </div>
    @endif

    <form class="auth-form" method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="form-group">
            <label for="email">البريد الإلكتروني</label>
            <div class="field">
                <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                <input
                    id="email"
                    name="email"
                    type="email"
                    dir="ltr"
                    value="{{ old('email') }}"
                    placeholder="name@example.com"
                    autocomplete="email"
                    inputmode="email"
                    required
                    autofocus
                    aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                    @if ($errors->has('email')) aria-describedby="email-error" @endif
                >
            </div>
            @if ($errors->has('email'))
                <p class="field-error" id="email-error">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <button class="btn-submit" type="submit">
            أرسل رابط الاستعادة
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
        </button>
    </form>

    <p class="auth-alt">تذكّرت كلمة المرور؟ <a href="{{ route('login') }}">سجّل دخولك</a></p>
@endsection
