<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('app.my_lectures')) — مستر عبدالرحمن الحبال</title>
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico?v=3">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600&family=Noto+Kufi+Arabic:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    @include('layouts.panel-style')
    @stack('styles')
</head>
<body>

@include('components.page-loader')

<div class="mobile-overlay" id="mobileOverlay" aria-hidden="true" onclick="toggleSidebar()"></div>
<aside class="sidebar" id="sidebar" aria-label="{{ app()->getLocale() === 'ar' ? 'القائمة الرئيسية' : 'Main navigation' }}">
    <div class="sidebar-logo">
        <a href="{{ route('portal.dashboard') }}" class="logo-icon" aria-label="مستر عبدالرحمن الحبال">
            <img src="{{ asset('images/logo.png') }}" alt="">
        </a>
        <div class="brand-text">
            <div class="logo-name">مستر <span>عبدالرحمن</span></div>
            <small>{{ __('app.student_portal') }}</small>
        </div>
    </div>
    <nav class="sidebar-nav">
        @php
            $navHasAccess = (auth()->user()->student?->hasPortalAccess()) ?? false;
            $navLeaderboardVisible = \App\Models\Lecture::where('status', 'published')
                ->where('show_leaderboard', true)
                ->exists();
        @endphp
        <div class="nav-section">{{ __('app.my_lectures') }}</div>
        <a href="{{ route('portal.dashboard') }}" class="nav-link {{ request()->routeIs('portal.dashboard') ? 'active' : '' }}" @if(request()->routeIs('portal.dashboard')) aria-current="page" @endif>
            <span class="icon"><x-icon name="bolt" /></span> {{ __('app.dashboard') }}
        </a>
        <a href="{{ route('portal.lectures') }}" class="nav-link {{ request()->routeIs('portal.lectures*') ? 'active' : '' }}" @if(request()->routeIs('portal.lectures*')) aria-current="page" @endif>
            <span class="icon"><x-icon name="video" /></span> {{ __('app.my_lectures') }}
            @unless($navHasAccess)<span class="nav-lock"><x-icon name="lock" /></span>@endunless
        </a>
        <a href="{{ route('portal.quizzes') }}" class="nav-link {{ request()->routeIs('portal.quizzes*') ? 'active' : '' }}" @if(request()->routeIs('portal.quizzes*')) aria-current="page" @endif>
            <span class="icon"><x-icon name="clipboard" /></span> {{ __('app.my_quizzes') }}
            @unless($navHasAccess)<span class="nav-lock"><x-icon name="lock" /></span>@endunless
        </a>
        <a href="{{ route('portal.results') }}" class="nav-link {{ request()->routeIs('portal.results*') ? 'active' : '' }}" @if(request()->routeIs('portal.results*')) aria-current="page" @endif>
            <span class="icon"><x-icon name="chart" /></span> {{ __('app.my_results') }}
            @unless($navHasAccess)<span class="nav-lock"><x-icon name="lock" /></span>@endunless
        </a>
        @if($navLeaderboardVisible)
        <a href="{{ route('portal.leaderboard.index') }}" class="nav-link {{ request()->routeIs('portal.leaderboard*') ? 'active' : '' }}" @if(request()->routeIs('portal.leaderboard*')) aria-current="page" @endif>
            <span class="icon"><x-icon name="trophy" /></span> {{ __('app.leaderboard') }}
            @unless($navHasAccess)<span class="nav-lock"><x-icon name="lock" /></span>@endunless
        </a>
        @endif
        <div class="nav-section">{{ app()->getLocale() === 'ar' ? 'الحساب' : 'Account' }}</div>
        <a href="{{ route('portal.codes') }}" class="nav-link {{ request()->routeIs('portal.codes*') ? 'active' : '' }}" @if(request()->routeIs('portal.codes*')) aria-current="page" @endif>
            <span class="icon"><x-icon name="ticket" /></span> {{ app()->getLocale() === 'ar' ? 'أكواد الفتح' : 'Access Codes' }}
        </a>
        <a href="{{ route('portal.subscription') }}" class="nav-link {{ request()->routeIs('portal.subscription*') ? 'active' : '' }}" @if(request()->routeIs('portal.subscription*')) aria-current="page" @endif>
            <span class="icon"><x-icon name="card" /></span> {{ app()->getLocale() === 'ar' ? 'الاشتراك' : 'Subscription' }}
        </a>
        <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" @if(request()->routeIs('profile.*')) aria-current="page" @endif>
            <span class="icon"><x-icon name="user" /></span> {{ app()->getLocale() === 'ar' ? 'الملف الشخصي' : 'My Profile' }}
        </a>
    </nav>
    <div class="sidebar-footer">
        <div class="user-name">{{ auth()->user()->name }}</div>
        <div class="role-badge">{{ app()->getLocale() === 'ar' ? 'طالب' : 'Student' }}</div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn"><x-icon name="logout" /> {{ __('app.sign_out') }}</button>
        </form>
    </div>
</aside>

<div class="main-content">
    <div class="topbar">
        <div class="topbar-title">
            <button class="hamburger-btn" type="button" onclick="toggleSidebar()" aria-label="{{ app()->getLocale() === 'ar' ? 'القائمة' : 'Menu' }}" aria-expanded="false" aria-controls="sidebar"><x-icon name="menu" /></button>
            <h1>@yield('title', __('app.dashboard'))</h1>
        </div>
        <div class="topbar-right">
            <div class="lang-switcher" role="group" aria-label="{{ app()->getLocale() === 'ar' ? 'اللغة' : 'Language' }}">
                <a href="{{ route('lang.switch', 'ar') }}" class="lang-btn {{ app()->getLocale() === 'ar' ? 'active' : '' }}" hreflang="ar">ع</a>
                <a href="{{ route('lang.switch', 'en') }}" class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}" hreflang="en">EN</a>
            </div>
            <button class="theme-toggle" onclick="toggleTheme()" title="{{ app()->getLocale() === 'ar' ? 'تبديل المظهر' : 'Toggle theme' }}" aria-label="{{ app()->getLocale() === 'ar' ? 'تبديل المظهر' : 'Toggle theme' }}" id="themeBtn">
                <span class="icon-moon"><x-icon name="moon" /></span>
                <span class="icon-sun"><x-icon name="sun" /></span>
            </button>
            @include('components.notifications', ['feed' => app(\App\Support\NotificationFeed::class)->for(auth()->user())])
            @php
                $__avatarColors = ['#e05555','#3ec97a','#4a8cff','#d4a843','#a064f0','#28c8c8','#f07030','#e05599','#70b040','#5088e0'];
                $__avatarColor = $__avatarColors[auth()->id() % count($__avatarColors)];
                $__avatarLetter = strtoupper(mb_substr(auth()->user()->name, 0, 1));
            @endphp
            <a href="{{ route('profile.edit') }}" class="user-chip" style="text-decoration:none" aria-label="{{ app()->getLocale() === 'ar' ? 'الملف الشخصي' : 'My Profile' }}">
                <div class="avatar" style="background:{{ $__avatarColor }}">{{ $__avatarLetter }}</div>
                <span class="name">{{ auth()->user()->name }}</span>
            </a>
        </div>
    </div>
    <div class="page-body">
        @if(session('success'))
            <div class="alert alert-success"><x-icon name="check-circle" /> <div>{{ session('success') }}</div></div>
        @endif
        @if(session('info'))
            <div class="alert alert-info"><x-icon name="info" /> <div>{{ session('info') }}</div></div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                <x-icon name="alert" />
                <div>
                    @foreach($errors->all() as $error) {{ $error }}<br> @endforeach
                </div>
            </div>
        @endif
        @yield('content')
    </div>
</div>

<script>
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);

    function toggleTheme() {
        const html = document.documentElement;
        const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-theme', next);
        localStorage.setItem('theme', next);
    }
</script>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobileOverlay');
        const button = document.querySelector('.hamburger-btn');
        const open = sidebar.classList.toggle('open');
        overlay.classList.toggle('active', open);
        document.body.style.overflow = open ? 'hidden' : '';
        if (button) button.setAttribute('aria-expanded', open ? 'true' : 'false');
    }

    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 900 && document.getElementById('sidebar').classList.contains('open')) {
                toggleSidebar();
            }
        });
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && document.getElementById('sidebar').classList.contains('open')) {
            toggleSidebar();
        }
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 900) {
            document.body.style.overflow = '';
        }
    });
</script>
@stack('scripts')
</body>
</html>
