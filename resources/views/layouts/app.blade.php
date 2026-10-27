<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('app.dashboard')) — مستر عبدالرحمن الحبال</title>
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
        <a href="{{ route('dashboard') }}" class="logo-icon" aria-label="مستر عبدالرحمن الحبال">
            <img src="{{ asset('images/logo.png') }}" alt="">
        </a>
        <div class="brand-text">
            <div class="logo-name">منصة <span>مستر عبدالرحمن</span></div>
            <small>{{ __('app.e_learning') }}</small>
        </div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section">{{ app()->getLocale() === 'ar' ? 'الرئيسية' : 'Main' }}</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" @if(request()->routeIs('dashboard')) aria-current="page" @endif>
            <span class="icon"><x-icon name="bolt" /></span> {{ __('app.dashboard') }}
        </a>
        <div class="nav-section">{{ app()->getLocale() === 'ar' ? 'المحتوى' : 'Content' }}</div>
        <a href="{{ route('lectures.index') }}" class="nav-link {{ request()->routeIs('lectures*') ? 'active' : '' }}" @if(request()->routeIs('lectures*')) aria-current="page" @endif>
            <span class="icon"><x-icon name="video" /></span> {{ __('app.lectures') }}
        </a>
        <a href="{{ route('quizzes.index') }}" class="nav-link {{ request()->routeIs('quizzes*') ? 'active' : '' }}" @if(request()->routeIs('quizzes*')) aria-current="page" @endif>
            <span class="icon"><x-icon name="clipboard" /></span> {{ __('app.quizzes') }}
        </a>
        <div class="nav-section">{{ app()->getLocale() === 'ar' ? 'الأشخاص' : 'People' }}</div>
        <a href="{{ route('leaderboard.index') }}" class="nav-link {{ request()->routeIs('leaderboard*') ? 'active' : '' }}" @if(request()->routeIs('leaderboard*')) aria-current="page" @endif>
            <span class="icon"><x-icon name="trophy" /></span> {{ __('app.leaderboard') }}
        </a>
        <a href="{{ route('students.index') }}" class="nav-link {{ request()->routeIs('students*') ? 'active' : '' }}" @if(request()->routeIs('students*')) aria-current="page" @endif>
            <span class="icon"><x-icon name="users" /></span> {{ __('app.students') }}
        </a>
        <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments*') ? 'active' : '' }}" @if(request()->routeIs('admin.payments*')) aria-current="page" @endif>
            <span class="icon"><x-icon name="card" /></span> {{ app()->getLocale() === 'ar' ? 'المدفوعات' : 'Payments' }}
        </a>
        <a href="{{ route('access-codes.index') }}" class="nav-link {{ request()->routeIs('access-codes*') ? 'active' : '' }}" @if(request()->routeIs('access-codes*')) aria-current="page" @endif>
            <span class="icon"><x-icon name="key" /></span> {{ app()->getLocale() === 'ar' ? 'أكواد الدخول' : 'Access Codes' }}
        </a>
        <div class="nav-section">{{ app()->getLocale() === 'ar' ? 'النظام' : 'System' }}</div>
        <a href="{{ route('admin.maintenance.index') }}" class="nav-link {{ request()->routeIs('admin.maintenance*') ? 'active' : '' }}" @if(request()->routeIs('admin.maintenance*')) aria-current="page" @endif>
            <span class="icon"><x-icon name="wrench" /></span> {{ app()->getLocale() === 'ar' ? 'وضع الصيانة' : 'Maintenance' }}
        </a>
    </nav>
    <div class="sidebar-footer">
        <div class="user-name">{{ auth()->user()->name }}</div>
        <div class="role-badge">{{ app()->getLocale() === 'ar' ? 'مدير' : 'Admin' }}</div>
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
            <div class="user-chip">
                <div class="avatar" style="background:{{ $__avatarColor }}">{{ $__avatarLetter }}</div>
                <span class="name">{{ auth()->user()->name }}</span>
            </div>
        </div>
    </div>
    <div class="page-body">
        @if(session('success'))
            <div class="alert alert-success"><x-icon name="check-circle" /> <div>{{ session('success') }}</div></div>
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

<script>
function setupUploadZone(zoneId, inputId, listId, maxMb) {
    const zone  = document.getElementById(zoneId);
    const input = document.getElementById(inputId);
    const list  = document.getElementById(listId);
    if (!zone || !input || !list) return;

    let selectedFiles = [];

    function formatSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    }

    function iconSvg(paths) {
        return '<svg viewBox="0 0 24 24" width="1em" height="1em" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="vertical-align:-.125em">' + paths + '</svg>';
    }

    const ICON_FILE = iconSvg('<path d="M14 2.5H6.5a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2V8Z"/><path d="M14 2.5V8h5.5M9 13h6M9 17h6"/>');
    const ICON_CHART = iconSvg('<path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/>');
    const ICON_FOLDER = iconSvg('<path d="M3 7.5A2.5 2.5 0 0 1 5.5 5h3l2 2.5h8A2.5 2.5 0 0 1 21 10v7.5A2.5 2.5 0 0 1 18.5 20h-13A2.5 2.5 0 0 1 3 17.5Z"/>');

    function fileIcon(name) {
        const ext = name.split('.').pop().toLowerCase();
        if (ext === 'pdf') return ICON_FILE;
        if (ext === 'ppt' || ext === 'pptx') return ICON_CHART;
        return ICON_FOLDER;
    }

    function render() {
        list.innerHTML = '';
        selectedFiles.forEach((file, idx) => {
            const isOversized = file.size > maxMb * 1024 * 1024;
            const item = document.createElement('div');
            item.className = 'upload-file-item' + (isOversized ? ' oversized' : '');
            item.innerHTML = `
                <div class="upload-file-info">
                    <span class="upload-file-icon">${fileIcon(file.name)}</span>
                    <div style="min-width:0;flex:1">
                        <div class="upload-file-name">${file.name}</div>
                        ${isOversized ? `<div class="upload-file-warning">${zone.dataset.lang === 'ar' ? 'الملف أكبر من الحد المسموح (' + maxMb + ' ميجابايت) ولن يتم رفعه' : 'File exceeds the ' + maxMb + 'MB limit and will not upload'}</div>` : ''}
                    </div>
                </div>
                <span class="upload-file-size">${formatSize(file.size)}</span>
                <button type="button" class="upload-file-remove" aria-label="إزالة الملف" onclick="removeUploadFile_${zoneId.replace(/-/g,'_')}(${idx})">${iconSvg('<path d="M18 6 6 18M6 6l12 12"/>')}</button>
            `;
            list.appendChild(item);
        });
        syncInputFiles();
    }

    function syncInputFiles() {
        const dt = new DataTransfer();
        selectedFiles.forEach(file => {
            if (file.size <= maxMb * 1024 * 1024) dt.items.add(file);
        });
        input.files = dt.files;
    }

    window['removeUploadFile_' + zoneId.replace(/-/g,'_')] = function(idx) {
        selectedFiles.splice(idx, 1);
        render();
    };

    function addFiles(fileArray) {
        selectedFiles = selectedFiles.concat(Array.from(fileArray));
        render();
    }

    input.addEventListener('change', (e) => addFiles(e.target.files));

    ['dragenter', 'dragover'].forEach(evt => {
        zone.addEventListener(evt, (e) => {
            e.preventDefault();
            zone.classList.add('drag-over');
        });
    });
    ['dragleave', 'drop'].forEach(evt => {
        zone.addEventListener(evt, (e) => {
            e.preventDefault();
            zone.classList.remove('drag-over');
        });
    });
    zone.addEventListener('drop', (e) => {
        if (e.dataTransfer.files.length) addFiles(e.dataTransfer.files);
    });
}
</script>
@stack('scripts')
</body>
</html>
