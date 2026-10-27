<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول — منصة مستر عبدالرحمن الحبال</title>
    <meta name="robots" content="noindex">
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico?v=3">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600&family=Noto+Kufi+Arabic:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script>
        (function () {
            try {
                var saved = localStorage.getItem('theme');
                if (saved === 'light' || saved === 'dark') {
                    document.documentElement.setAttribute('data-theme', saved);
                }
            } catch (e) {}
        })();
    </script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        [data-theme="dark"] {
            --bg:#0b1118; --surface:#121c26; --surface2:#17222e;
            --border:#26323d; --border2:#3a4854;
            --gold:#5fb3a4; --gold2:#7ac7b9;
            --text:#f2ebdd; --muted:#a49a86; --muted2:#c6bda9;
            --danger:#ff9a9a;
        }
        [data-theme="light"] {
            --bg:#f7f3e9; --surface:#fffdf7; --surface2:#f0e9d9;
            --border:#ded4bf; --border2:#c7b99d;
            --gold:#1c6a5d; --gold2:#145247;
            --text:#111118; --muted:#5c5c74; --muted2:#4a4a60;
            --danger:#a11f1f;
        }
        [data-theme="dark"] .btn-submit, [data-theme="dark"] .skip-link, [data-theme="dark"] ::selection { color:#07120f; }

        html, body { min-height: 100%; }
        body {
            background: var(--bg); color: var(--text);
            font-family: 'IBM Plex Sans Arabic', sans-serif; line-height: 1.75;
            -webkit-font-smoothing: antialiased;
        }
        img { display: block; max-width: 100%; }
        h1, .visual-copy p { font-family: 'Noto Kufi Arabic', sans-serif; }
        a { color: inherit; }
        button, input { font: inherit; color: inherit; }
        ::selection { background: var(--gold); color: #fbf7ec; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg); }
        ::-webkit-scrollbar-thumb { background: var(--border2); border-radius: 999px; }
        :focus-visible { outline: 2px solid var(--gold2); outline-offset: 3px; border-radius: 4px; }

        .skip-link {
            position: absolute; top: -60px; inset-inline-start: 16px; z-index: 100;
            background: var(--gold); color: #fbf7ec; padding: 10px 18px;
            border-radius: 10px; font-weight: 800; text-decoration: none;
            transition: top .2s ease;
        }
        .skip-link:focus { top: 16px; }

        .auth {
            min-height: 100vh; min-height: 100svh;
            display: grid; grid-template-columns: minmax(0, 1fr);
        }
        @media (min-width: 980px) {
            .auth { grid-template-columns: minmax(0, 1.08fr) minmax(0, .92fr); }
        }

        /* ── Form panel ─────────────────────────────────── */
        .auth-panel {
            display: flex; flex-direction: column; justify-content: center;
            padding: 40px 24px;
            position: relative;
        }
        @media (min-width: 980px) { .auth-panel { padding: 56px 64px; } }

        .panel-top {
            position: absolute; top: 20px; inset-inline-end: 20px;
        }
        @media (min-width: 980px) { .panel-top { top: 28px; inset-inline-end: 32px; } }

        .theme-btn {
            width: 40px; height: 40px; border-radius: 10px;
            background: var(--surface2); border: 1px solid var(--border);
            display: grid; place-items: center; cursor: pointer;
            color: var(--muted2); transition: color .2s ease, border-color .2s ease;
        }
        .theme-btn:hover { color: var(--gold2); border-color: var(--border2); }
        .theme-btn svg { width: 18px; height: 18px; }
        .theme-btn .icon-moon { display: none; }
        [data-theme="light"] .theme-btn .icon-moon { display: block; }
        [data-theme="light"] .theme-btn .icon-sun { display: none; }

        .mobile-brand {
            display: flex; align-items: center; gap: 10px; text-decoration: none;
            margin-bottom: 34px;
        }
        @media (min-width: 980px) { .mobile-brand { display: none; } }
        .mobile-brand img { width: 40px; height: 40px; border-radius: 10px; object-fit: cover; }
        .mobile-brand span { font-weight: 800; font-size: .98rem; }
        .mobile-brand span span { color: var(--gold); }

        .auth-card { width: 100%; max-width: 420px; margin-inline: auto; }

        .auth-card h1 {
            font-size: clamp(1.85rem, 3.4vw, 2.35rem); font-weight: 800;
            line-height: 1.3; letter-spacing: 0;
        }
        .auth-sub {
            color: var(--muted2); font-size: .95rem; margin-top: 10px;
            max-width: 46ch; text-wrap: balance;
        }

        .notice {
            display: flex; gap: 10px; align-items: flex-start;
            margin-top: 22px; padding: 12px 14px; border-radius: 10px;
            font-size: .86rem; line-height: 1.7;
            background: var(--surface2); border: 1px solid var(--border);
        }
        .notice svg { width: 17px; height: 17px; flex-shrink: 0; margin-top: 3px; }
        .notice-error { color: var(--danger); }
        .notice-ok { color: var(--gold2); }

        .auth-form { margin-top: 30px; display: grid; gap: 20px; }
        .form-group { display: grid; gap: 7px; }
        .form-group label { font-size: .84rem; font-weight: 700; color: var(--muted2); }

        .field { position: relative; }
        .field-icon {
            position: absolute; top: 50%; inset-inline-start: 15px;
            transform: translateY(-50%); width: 17px; height: 17px;
            color: var(--muted); pointer-events: none;
        }
        .field input {
            width: 100%; padding: 13px 46px;
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 11px; font-size: .95rem; color: var(--text);
            caret-color: var(--gold);
            transition: border-color .2s ease, box-shadow .2s ease;
        }
        .field input::placeholder { color: var(--muted); opacity: 1; font-size: .9rem; }
        .field input:hover { border-color: var(--border2); }
        .field input:focus {
            outline: none; border-color: var(--gold);
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--gold) 18%, transparent);
        }
        .field input[aria-invalid="true"] { border-color: var(--danger); }
        .field input[dir="ltr"] { text-align: left; }
        .field input:-webkit-autofill,
        .field input:-webkit-autofill:hover,
        .field input:-webkit-autofill:focus {
            -webkit-text-fill-color: var(--text);
            -webkit-box-shadow: 0 0 0 1000px var(--surface) inset;
            caret-color: var(--text);
        }

        .field-toggle {
            position: absolute; top: 50%; inset-inline-end: 8px;
            transform: translateY(-50%);
            width: 34px; height: 34px; border: 0; border-radius: 8px;
            background: transparent; color: var(--muted); cursor: pointer;
            display: grid; place-items: center;
        }
        .field-toggle:hover { color: var(--text); background: var(--surface2); }
        .field-toggle svg { width: 18px; height: 18px; }
        .field-toggle .icon-off { display: none; }
        .field-toggle[aria-pressed="true"] .icon-off { display: block; }
        .field-toggle[aria-pressed="true"] .icon-on { display: none; }

        .field-error { color: var(--danger); font-size: .8rem; font-weight: 600; }

        .form-row {
            display: flex; align-items: center; justify-content: space-between;
            gap: 12px; flex-wrap: wrap;
        }
        .remember {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: .86rem; color: var(--muted2); cursor: pointer;
            min-height: 24px;
        }
        .remember input {
            width: 16px; height: 16px; accent-color: var(--gold);
            cursor: pointer;
        }
        .forgot {
            font-size: .86rem; font-weight: 700; color: var(--gold2);
            text-decoration: none; border-bottom: 1px solid transparent;
        }
        .forgot:hover { border-bottom-color: currentColor; }

        .btn-submit {
            width: 100%; display: inline-flex; align-items: center; justify-content: center;
            gap: 9px; padding: 14px 20px; border: 0; border-radius: 11px;
            background: var(--gold); color: #fbf7ec;
            font-size: .98rem; font-weight: 800; cursor: pointer;
            box-shadow: 0 10px 28px rgba(0, 0, 0, .26);
            transition: transform .18s ease, box-shadow .18s ease, background .18s ease;
        }
        .btn-submit:hover { background: var(--gold2); transform: translateY(-2px); box-shadow: 0 16px 36px rgba(0, 0, 0, .32); }
        .btn-submit:active { transform: translateY(0); }
        .btn-submit svg { width: 17px; height: 17px; }
        .btn-submit[aria-busy="true"] { opacity: .82; cursor: progress; transform: none; }

        .auth-alt {
            margin-top: 26px; padding-top: 22px; border-top: 1px solid var(--border);
            text-align: center; font-size: .88rem; color: var(--muted2);
        }
        .auth-alt a { color: var(--gold2); font-weight: 800; text-decoration: none; margin-inline-start: 5px; }
        .auth-alt a:hover { text-decoration: underline; text-underline-offset: 3px; }

        .panel-foot {
            width: 100%; max-width: 420px; margin: 40px auto 0;
            font-size: .76rem; color: var(--muted); text-align: center;
        }

        /* ── Visual panel ───────────────────────────────── */
        .auth-visual {
            display: none; position: relative; overflow: hidden;
            background: #06060b;
        }
        @media (min-width: 980px) { .auth-visual { display: flex; } }
        .auth-visual img {
            position: absolute; inset: 0; width: 100%; height: 100%;
            object-fit: cover; object-position: 50% 22%;
        }
        .visual-scrim {
            position: absolute; inset: 0;
            background:
                linear-gradient(to top, rgba(5,5,10,.94) 0%, rgba(5,5,10,.55) 46%, rgba(5,5,10,.28) 100%),
                linear-gradient(to left, rgba(5,5,10,.55), transparent 60%);
        }
        .visual-inner {
            position: relative; z-index: 2; width: 100%;
            display: flex; flex-direction: column; justify-content: space-between;
            padding: 38px 44px; color: #fff;
        }
        .visual-brand { display: inline-flex; align-items: center; gap: 11px; text-decoration: none; width: fit-content; }
        .visual-brand img { position: static; width: 42px; height: 42px; border-radius: 11px; object-fit: cover; border: 1px solid rgba(255,255,255,.18); }
        .visual-brand span { font-weight: 800; font-size: 1rem; }
        .visual-brand span span { color: #e3c27a; }
        .visual-brand small {
            display: block; font-size: .7rem; font-weight: 500; color: #b6b6c9;
            letter-spacing: .14em; text-transform: uppercase; line-height: 1.5;
        }
        .visual-copy { max-width: 21ch; }
        .visual-rule { display: block; width: 42px; height: 2px; background: #c9a25a; margin-bottom: 20px; }
        .visual-copy p { font-size: clamp(1.5rem, 2.4vw, 2rem); font-weight: 800; line-height: 1.4; text-wrap: balance; }
        .visual-copy span { display: block; margin-top: 14px; font-size: .9rem; color: #c3c3d6; font-weight: 500; }
        .visual-back {
            display: inline-flex; align-items: center; gap: 8px; width: fit-content;
            color: #d7d7e4; text-decoration: none; font-size: .86rem; font-weight: 700;
            padding: 8px 0;
        }
        .visual-back:hover { color: #fff; }
        .visual-back svg { width: 15px; height: 15px; transition: transform .2s ease; }
        .visual-back:hover svg { transform: translateX(-4px); }

        @media (max-width: 480px) {
            .field-toggle { width: 40px; height: 40px; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation: none !important; transition: none !important; }
        }
    </style>
</head>
<body>
<a class="skip-link" href="#main">تجاوز إلى النموذج</a>

<main class="auth" id="main">
    <section class="auth-panel" aria-labelledby="login-title">
        <div class="panel-top">
            <button class="theme-btn" id="themeBtn" type="button" aria-label="التبديل إلى الوضع الفاتح">
                <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
                <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8Z"/></svg>
            </button>
        </div>

        <a class="mobile-brand" href="{{ route('landing') }}">
            <img src="{{ asset('images/logo.png') }}" alt="">
            <span>مستر عبدالرحمن <span>الحبال</span></span>
        </a>

        <div class="auth-card">
            <h1 id="login-title">مرحباً بعودتك.</h1>
            <p class="auth-sub">سجّل دخولك للوصول إلى محاضراتك ونتائج اختباراتك ومتابعة تقدّمك.</p>

            @if (session('status'))
                <div class="notice notice-ok" role="status">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="m8 12.5 2.5 2.5L16 9.5"/></svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <form class="auth-form" method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">البريد الإلكتروني</label>
                    <div class="field">
                        <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3.5 7 8.5 6 8.5-6"/></svg>
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                               placeholder="name@example.com" required autofocus
                               autocomplete="username" inputmode="email" dir="ltr"
                               @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                    </div>
                    @error('email')
                        <p class="field-error" id="email-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">كلمة المرور</label>
                    <div class="field">
                        <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                        <input id="password" type="password" name="password" placeholder="••••••••"
                               required autocomplete="current-password"
                               @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                        <button class="field-toggle" type="button" data-toggle-password
                                aria-label="إظهار كلمة المرور" aria-pressed="false" aria-controls="password">
                            <svg class="icon-on" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg class="icon-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 3l18 18M10.6 6.1A9.7 9.7 0 0 1 12 6c6 0 9.5 6 9.5 6a16.6 16.6 0 0 1-2.2 2.8M6.3 6.5C4 8.3 2.5 12 2.5 12S6 18 12 18a9.6 9.6 0 0 0 4-.9"/><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="field-error" id="password-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-row">
                    <label class="remember"><input type="checkbox" name="remember"> تذكرني</label>
                    @if (Route::has('password.request'))
                        <a class="forgot" href="{{ route('password.request') }}">نسيت كلمة المرور؟</a>
                    @endif
                </div>

                <button class="btn-submit" type="submit">
                    <span data-label>تسجيل الدخول</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>
                </button>
            </form>

            <p class="auth-alt">
                ليس لديك حساب؟
                <a href="{{ route('register') }}">أنشئ حساباً مجاناً</a>
            </p>
        </div>

        <p class="panel-foot">© {{ date('Y') }} منصة مستر عبدالرحمن الحبال. جميع الحقوق محفوظة.</p>
    </section>

    <aside class="auth-visual" aria-hidden="true">
        <img src="{{ asset('images/hero-2.webp') }}" alt="" fetchpriority="high">
        <div class="visual-scrim"></div>
        <div class="visual-inner">
            <a class="visual-brand" href="{{ route('landing') }}" tabindex="-1">
                <span class="brand-mark"><img src="{{ asset('images/logo.png') }}" alt=""></span>
                <span>مستر عبدالرحمن <span>الحبال</span><small>El-Molhem Platform</small></span>
            </a>

            <div class="visual-copy">
                <span class="visual-rule"></span>
                <p>محاضراتك واختباراتك وتقدّمك في مكان واحد.</p>
                <span>المنهج كامل، والمراجعة تبدأ من هنا.</span>
            </div>

            <a class="visual-back" href="{{ route('landing') }}" tabindex="-1">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M9 6l6 6-6 6"/></svg>
                العودة إلى الصفحة الرئيسية
            </a>
        </div>
    </aside>
</main>

<script>
    (function () {
        var root = document.documentElement;
        var btn = document.getElementById('themeBtn');

        function syncThemeLabel() {
            var dark = root.getAttribute('data-theme') === 'dark';
            btn.setAttribute('aria-label', dark ? 'التبديل إلى الوضع الفاتح' : 'التبديل إلى الوضع الداكن');
        }

        btn.addEventListener('click', function () {
            var next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            root.setAttribute('data-theme', next);
            try { localStorage.setItem('theme', next); } catch (e) {}
            syncThemeLabel();
        });
        syncThemeLabel();

        var toggle = document.querySelector('[data-toggle-password]');
        var input = document.getElementById('password');
        if (toggle && input) {
            toggle.addEventListener('click', function () {
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                toggle.setAttribute('aria-pressed', show ? 'true' : 'false');
                toggle.setAttribute('aria-label', show ? 'إخفاء كلمة المرور' : 'إظهار كلمة المرور');
                input.focus({ preventScroll: true });
            });
        }

        var form = document.querySelector('.auth-form');
        if (form) {
            form.addEventListener('submit', function () {
                if (!form.checkValidity()) return;
                var submit = form.querySelector('.btn-submit');
                if (!submit || submit.getAttribute('aria-busy') === 'true') return;
                var label = submit.querySelector('[data-label]');
                submit.setAttribute('aria-busy', 'true');
                submit.disabled = true;
                if (label) label.textContent = 'جارٍ الدخول…';
            });
        }
    })();
</script>
</body>
</html>
