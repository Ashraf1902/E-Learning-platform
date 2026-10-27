<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مستر عبدالرحمن الحبال — منصة التعلم الإلكتروني</title>
    <meta name="description" content="منصة مستر عبدالرحمن الحبال: محاضرات مسجّلة، اختبارات فورية، ولوحة متصدرين لكل محاضرة. تعلّم البكالوريا والصف الثالث الإعدادي في منصة واحدة.">
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico?v=3">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600&family=Noto+Kufi+Arabic:wght@400;500;600;700&family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;1,9..144,400&family=JetBrains+Mono:wght@400;500&display=swap" rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600&family=Noto+Kufi+Arabic:wght@400;500;600;700&family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;1,9..144,400&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet"></noscript>
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

        :root {
            --radius: 16px;
            --radius-sm: 10px;
            --radius-lg: 22px;
            --font-display: 'Noto Kufi Arabic', sans-serif;
            --font-body: 'IBM Plex Sans Arabic', sans-serif;
            --font-serif: 'Fraunces', Georgia, serif;
            --font-mono: 'JetBrains Mono', ui-monospace, monospace;
        }

        [data-theme="light"] {
            --paper: #f7f3e9;
            --paper-2: #fcf9f1;
            --surface: #fffdf7;
            --surface-2: #f0e9d9;
            --ink: #1a1712;
            --ink-soft: #4c4437;
            --ink-faint: #6f6452;
            --line: #ded4bf;
            --line-strong: #c7b99d;
            --lapis: #13253f;
            --lapis-2: #1b3355;
            --accent: #1c6a5d;
            --accent-strong: #145247;
            --accent-soft: rgba(28, 106, 93, .10);
            --on-accent: #fbf7ec;
            --gold: #7a5312;
            --gold-bright: #c2933a;
            --on-gold: #fbf7ec;
            --gold-soft: rgba(168, 117, 36, .16);
            --terra: #a4472c;
            --on-dark: #f7f0e1;
            --on-dark-soft: #c9c0ab;
            --on-dark-faint: #9b927f;
            --nav: rgba(247, 243, 233, .86);
            --shadow: 0 30px 70px rgba(42, 33, 18, .13);
            --shadow-sm: 0 10px 30px rgba(42, 33, 18, .08);
        }

        [data-theme="dark"] {
            --paper: #0b1118;
            --paper-2: #0f1721;
            --surface: #121c26;
            --surface-2: #17222e;
            --ink: #f2ebdd;
            --ink-soft: #c6bda9;
            --ink-faint: #938975;
            --line: #26323d;
            --line-strong: #3a4854;
            --lapis: #0a1420;
            --lapis-2: #101d2c;
            --accent: #5fb3a4;
            --accent-strong: #7ac7b9;
            --accent-soft: rgba(95, 179, 164, .12);
            --on-accent: #07120f;
            --gold: #d9a441;
            --gold-bright: #e8bc66;
            --on-gold: #171208;
            --gold-soft: rgba(217, 164, 65, .16);
            --terra: #d2715a;
            --on-dark: #f2ebdd;
            --on-dark-soft: #c6bda9;
            --on-dark-faint: #938975;
            --nav: rgba(11, 17, 24, .82);
            --shadow: 0 30px 70px rgba(0, 0, 0, .45);
            --shadow-sm: 0 10px 30px rgba(0, 0, 0, .3);
        }

        [data-theme="dark"] .step-num { color: #556878; }

        html { scroll-behavior: smooth; scroll-padding-top: 96px; }
        body {
            background: var(--paper);
            color: var(--ink);
            font-family: var(--font-body);
            font-size: 16px;
            line-height: 1.85;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
            transition: background .35s ease, color .35s ease;
        }

        body::after {
            content: "";
            position: fixed;
            inset: 0;
            z-index: 9997;
            pointer-events: none;
            opacity: .035;
            mix-blend-mode: multiply;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='160' height='160' filter='url(%23n)' opacity='0.55'/%3E%3C/svg%3E");
        }
        [data-theme="dark"] body::after { mix-blend-mode: screen; opacity: .04; }

        ::selection { background: var(--accent); color: var(--on-accent); }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--paper); }
        ::-webkit-scrollbar-thumb { background: var(--line-strong); border-radius: 999px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--accent); }

        a { color: inherit; }
        img { max-width: 100%; display: block; }
        button { font-family: inherit; }
        h1, h2, h3, h4 { font-family: var(--font-display); font-weight: 600; line-height: 1.4; text-wrap: balance; }
        p { text-wrap: pretty; }

        :focus-visible {
            outline: 2px solid var(--accent);
            outline-offset: 3px;
            border-radius: 4px;
        }

        .skip-link {
            position: absolute; inset-inline-start: 16px; top: -90px; z-index: 10000;
            background: var(--accent); color: var(--on-accent); padding: 10px 20px;
            border-radius: var(--radius-sm); font-weight: 600; font-size: .85rem; text-decoration: none;
            transition: top .2s ease;
        }
        .skip-link:focus { top: 16px; }
        .sr-only {
            position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px;
            overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border: 0;
        }

        .wrap { width: min(1240px, 100% - 48px); margin-inline: auto; }
        @media (max-width: 640px) { .wrap { width: calc(100% - 36px); } }

        .section { padding: clamp(72px, 9vw, 128px) 0; position: relative; }
        .section--tight { padding: clamp(56px, 7vw, 96px) 0; }
        .section--paper { background: var(--paper-2); border-block: 1px solid var(--line); }

        .eyebrow {
            display: inline-flex; align-items: center; gap: 12px;
            font-family: var(--font-mono); font-size: .72rem; font-weight: 500;
            letter-spacing: .12em; color: var(--gold); text-transform: uppercase;
        }
        .eyebrow::before {
            content: ""; width: 34px; height: 1px; background: var(--gold); display: inline-block;
        }
        .eyebrow--plain::before { display: none; }

        .h2 {
            font-size: clamp(1.75rem, 4.2vw, 2.7rem);
            line-height: 1.55;
            margin-top: 16px;
        }
        .lead {
            color: var(--ink-soft);
            font-size: 1.02rem;
            line-height: 2;
            max-width: 62ch;
            margin-top: 16px;
        }
        .head-block { max-width: 860px; }
        .head-block.center { margin-inline: auto; text-align: center; }
        .head-block.center .lead { margin-inline: auto; }
        .head-block.center .eyebrow { justify-content: center; }

        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 9px;
            padding: 13px 26px; border-radius: var(--radius-sm);
            font-family: var(--font-body); font-size: .94rem; font-weight: 600;
            text-decoration: none; border: 1px solid transparent; cursor: pointer;
            transition: transform .22s cubic-bezier(.22,1,.36,1), box-shadow .22s ease,
                        background .22s ease, border-color .22s ease, color .22s ease;
        }
        .btn svg { width: 18px; height: 18px; flex-shrink: 0; }
        .btn-primary {
            background: var(--accent); color: var(--on-accent);
            box-shadow: 0 12px 28px rgba(19, 37, 63, .16);
        }
        .btn-primary:hover { background: var(--accent-strong); transform: translateY(-2px); box-shadow: 0 18px 36px rgba(19, 37, 63, .22); }
        .btn-outline {
            background: transparent; color: var(--ink); border-color: var(--line-strong);
        }
        .btn-outline:hover { border-color: var(--accent); color: var(--accent); transform: translateY(-2px); }
        .btn-light {
            background: transparent; color: var(--on-dark); border-color: rgba(247, 240, 225, .28);
        }
        .btn-light:hover { border-color: var(--gold-bright); color: var(--gold-bright); transform: translateY(-2px); }

        .frame { position: relative; padding: 10px; background: var(--surface); border: 1px solid var(--line-strong); }
        .frame::before {
            content: ""; position: absolute; inset: 4px; border: 1px solid var(--line);
            pointer-events: none; z-index: 2;
        }

        /* ── Navigation ─────────────────────────────────── */
        .nav {
            position: fixed; inset-inline: 0; top: 0; z-index: 900;
            height: 78px; display: flex; align-items: center;
            background: var(--nav); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid transparent;
            transition: border-color .3s ease, background .3s ease, height .3s ease;
        }
        .nav.solid { border-bottom-color: var(--line); height: 70px; }
        .nav-inner { display: flex; align-items: center; justify-content: space-between; gap: 20px; }
        .brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .brand-mark {
            width: 42px; height: 42px; border-radius: 10px; overflow: hidden; flex-shrink: 0;
            border: 1px solid var(--line-strong); background: var(--surface);
            padding: 3px;
            transition: transform .25s cubic-bezier(.22,1,.36,1), border-color .25s ease;
        }
        .brand:hover .brand-mark { transform: translateY(-2px); border-color: var(--gold); }
        .brand-mark img { width: 100%; height: 100%; object-fit: cover; border-radius: 6px; }
        .brand-text { font-family: var(--font-display); font-size: .98rem; font-weight: 600; line-height: 1.5; }
        .brand-text span { color: var(--gold); }
        .brand-text small {
            display: block; font-family: var(--font-serif); font-style: italic; font-size: .6875rem;
            letter-spacing: .08em; color: var(--ink-faint); font-weight: 400; line-height: 1.6;
        }
        .nav-links { display: flex; align-items: center; gap: 2px; }
        .nav-links a {
            position: relative; text-decoration: none; color: var(--ink-soft);
            font-size: .86rem; font-weight: 500; padding: 9px 13px; border-radius: 6px;
            transition: color .2s ease, background .2s ease;
        }
        .nav-links a:hover { color: var(--ink); background: var(--accent-soft); }
        .nav-actions { display: flex; align-items: center; gap: 10px; }
        .icon-btn {
            width: 40px; height: 40px; border-radius: var(--radius-sm); display: grid; place-items: center;
            background: transparent; border: 1px solid var(--line-strong); color: var(--ink-soft);
            cursor: pointer; transition: color .2s ease, border-color .2s ease;
        }
        .icon-btn:hover { color: var(--accent); border-color: var(--accent); }
        .icon-btn svg { width: 18px; height: 18px; }
        .nav-login {
            text-decoration: none; font-size: .86rem; font-weight: 600; color: var(--ink-soft);
            padding: 9px 4px; transition: color .2s ease;
        }
        .nav-login:hover { color: var(--accent); }
        .nav-cta {
            padding: 10px 20px; border-radius: var(--radius-sm);
            font-size: .85rem; font-weight: 600; text-decoration: none;
            background: var(--accent); color: var(--on-accent);
            transition: transform .2s ease, background .2s ease;
        }
        .nav-cta:hover { transform: translateY(-2px); background: var(--accent-strong); }
        .nav-burger { display: none; }
        @media (max-width: 1040px) {
            .nav-links { display: none; }
            .nav-burger { display: grid; }
            .nav-login { display: none; }
            .nav-cta { display: none; }
        }
        .mobile-menu {
            position: fixed; inset-inline: 0; top: 70px; z-index: 899;
            background: var(--paper-2); border-bottom: 1px solid var(--line);
            padding: 14px 22px 24px; display: none; flex-direction: column; gap: 2px;
            box-shadow: var(--shadow-sm);
        }
        .mobile-menu.open { display: flex; }
        .mobile-menu a {
            text-decoration: none; color: var(--ink-soft); font-weight: 500; font-size: .95rem;
            padding: 12px 10px; border-radius: 6px;
        }
        .mobile-menu a:hover { background: var(--accent-soft); color: var(--ink); }
        .mobile-menu .btn { margin-top: 10px; }

        /* ── Hero ───────────────────────────────────────── */
        .hero {
            position: relative; overflow: hidden;
            padding: 148px 0 clamp(72px, 8vw, 104px);
            background:
                linear-gradient(var(--line) 1px, transparent 1px),
                linear-gradient(90deg, var(--line) 1px, transparent 1px);
            background-size: 68px 68px;
            background-position: center;
        }
        .hero::before {
            content: ""; position: absolute; inset: 0; pointer-events: none;
            background:
                radial-gradient(ellipse 70% 70% at 78% 30%, rgba(28,106,93,.10), transparent 70%),
                radial-gradient(ellipse 60% 60% at 20% 80%, rgba(168,117,36,.08), transparent 70%),
                linear-gradient(var(--paper), rgba(247,243,233,.35) 40%, var(--paper));
        }
        [data-theme="dark"] .hero::before {
            background:
                radial-gradient(ellipse 70% 70% at 78% 30%, rgba(95,179,164,.10), transparent 70%),
                radial-gradient(ellipse 60% 60% at 20% 80%, rgba(217,164,65,.07), transparent 70%),
                linear-gradient(var(--paper), rgba(11,17,24,.35) 40%, var(--paper));
        }
        .hero > .wrap { position: relative; z-index: 1; }
        .hero-grid {
            display: grid; grid-template-columns: minmax(0, 1.05fr) minmax(0, .95fr);
            gap: clamp(40px, 6vw, 88px); align-items: center;
        }
        .hero-copy { padding-block: 12px; }
        .hero h1 {
            font-size: clamp(2.3rem, 5.6vw, 4.4rem);
            line-height: 1.35; font-weight: 600;
            margin-top: 24px;
        }
        .hero h1 em {
            font-style: normal; color: var(--accent); position: relative; white-space: nowrap;
            isolation: isolate;
        }
        .hero h1 em::after {
            content: ""; position: absolute; inset-inline: 0; bottom: .06em; height: .32em;
            background: var(--gold-soft); z-index: -1; border-radius: 2px;
        }
        .hero-lead {
            color: var(--ink-soft); font-size: clamp(.98rem, 1.3vw, 1.08rem);
            line-height: 2.05; max-width: 52ch; margin-top: 24px;
        }
        .hero-actions { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 34px; }
        .hero-facts {
            display: flex; flex-wrap: wrap; gap: 10px 22px; align-items: center;
            margin-top: 34px; padding-top: 22px; border-top: 1px solid var(--line);
            color: var(--ink-faint); font-size: .82rem;
        }
        .hero-facts span { display: inline-flex; align-items: center; gap: 8px; }
        .hero-facts svg { width: 15px; height: 15px; color: var(--accent); flex-shrink: 0; }

        .artifact-wrap { position: relative; }
        .artifact {
            position: relative; margin: 0;
            background: var(--surface); border: 1px solid var(--line-strong);
            padding: 12px; box-shadow: var(--shadow);
        }
        .artifact::before {
            content: ""; position: absolute; inset: 5px; border: 1px solid var(--line); pointer-events: none; z-index: 3;
        }
        .artifact-media { position: relative; overflow: hidden; aspect-ratio: 4 / 5; background: var(--surface-2); }
        .artifact-media img {
            width: 100%; height: 100%; object-fit: cover; object-position: 50% 28%;
            filter: saturate(.6) contrast(1.04);
            transform: scale(1.02);
            transition: transform 1.2s cubic-bezier(.22,1,.36,1), filter .6s ease;
        }
        .artifact:hover .artifact-media img { transform: scale(1.05); filter: saturate(.85) contrast(1.02); }
        .artifact-media::after {
            content: ""; position: absolute; inset: 0;
            background: linear-gradient(165deg, rgba(19,37,63,.02) 30%, rgba(19,37,63,.32));
            mix-blend-mode: multiply;
        }
        .artifact-label {
            position: absolute; inset-inline-start: 18px; top: 18px; z-index: 4;
            display: inline-flex; align-items: center; gap: 8px;
            padding: 6px 11px; background: rgba(247,243,233,.9); color: #1a1712;
            font-family: var(--font-mono); font-size: .6875rem; letter-spacing: .08em;
            border: 1px solid rgba(26,23,18,.14);
        }
        .live-dot {
            width: 6px; height: 6px; border-radius: 50%; background: #1c6a5d;
            box-shadow: 0 0 0 3px rgba(28,106,93,.16);
        }
        .artifact-caption {
            position: absolute; inset-inline: -20px; bottom: 28px; z-index: 5;
            background: var(--paper-2); border: 1px solid var(--line-strong);
            padding: 16px 18px 15px; box-shadow: var(--shadow-sm);
        }

        .player-head {
            display: flex; align-items: center; justify-content: space-between; gap: 12px;
            font-family: var(--font-mono); font-size: .6875rem; letter-spacing: .06em;
            color: var(--ink-faint);
        }
        .player-kicker { display: inline-flex; align-items: center; gap: 8px; }
        .player-kicker::before { content: ""; width: 16px; height: 1px; background: var(--gold); }
        .player-duration { color: var(--gold); white-space: nowrap; }

        .player-body {
            display: flex; align-items: center; gap: 13px; margin-top: 12px;
            text-decoration: none; color: inherit;
        }
        .player-play {
            flex: 0 0 auto; width: 46px; height: 46px; border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            background: var(--ink); color: var(--paper);
            transition: background .25s ease, color .25s ease, transform .25s ease;
        }
        .player-play svg { width: 19px; height: 19px; margin-inline-start: 2px; }
        .player-body:hover .player-play { background: var(--gold); color: var(--on-gold); transform: scale(1.05); }
        .player-body:focus-visible { outline: 2px solid var(--gold); outline-offset: 5px; }
        .player-text { min-width: 0; display: flex; flex-direction: column; gap: 3px; }
        .player-title {
            font-family: var(--font-display); font-size: 1rem; font-weight: 600; line-height: 1.35;
            color: var(--ink); overflow: hidden; display: -webkit-box;
            -webkit-line-clamp: 2; -webkit-box-orient: vertical;
        }
        .player-sub { display: flex; flex-wrap: wrap; gap: 4px 7px; font-size: .78rem; color: var(--ink-faint); }
        .player-sub .sep { color: var(--gold); }

        .player-track { position: relative; height: 3px; margin-top: 14px; background: var(--line); }
        .player-track span {
            position: absolute; inset-block: 0; inset-inline-start: 0; width: 34%;
            background: var(--gold);
        }
        .player-track span::after {
            content: ""; position: absolute; inset-inline-end: -3px; top: 50%;
            width: 7px; height: 7px; border-radius: 50%; background: var(--gold);
            transform: translateY(-50%);
        }

        .player-foot { display: flex; flex-wrap: wrap; align-items: center; gap: 6px; margin-top: 12px; }
        .player-tag {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 4px 9px; border: 1px solid var(--line);
            font-family: var(--font-mono); font-size: .6875rem; color: var(--ink-soft);
        }
        .player-tag svg { width: 13px; height: 13px; color: var(--accent); flex-shrink: 0; }
        .player-empty { margin-top: 10px; font-size: .85rem; color: var(--ink-soft); }
        .artifact-side {
            position: absolute; inset-inline-end: -34px; top: 50%; transform: translateY(-50%) rotate(90deg);
            transform-origin: center; font-family: var(--font-mono); font-size: .6875rem;
            letter-spacing: .24em; color: var(--ink-faint); white-space: nowrap;
        }
        .scroll-cue {
            display: flex; align-items: center; gap: 14px; margin-top: clamp(48px, 6vw, 76px);
            color: var(--ink-faint); font-family: var(--font-mono); font-size: .6875rem;
            letter-spacing: .16em; text-transform: uppercase;
        }
        .scroll-cue .line { width: 64px; height: 1px; background: var(--line-strong); position: relative; overflow: hidden; }
        .scroll-cue .line::after {
            content: ""; position: absolute; inset-block: 0; inset-inline-start: 0; width: 40%;
            background: var(--gold); animation: cue 2.4s ease-in-out infinite;
        }
        @keyframes cue { 0%,100% { transform: translateX(0); } 50% { transform: translateX(140%); } }
        @media (max-width: 980px) {
            .hero { padding-top: 124px; }
            .hero-grid { grid-template-columns: 1fr; gap: 48px; }
            .artifact-wrap { max-width: 480px; }
            .artifact-side { display: none; }
        }
        @media (max-width: 560px) {
            .artifact-caption { inset-inline: 12px; bottom: 18px; padding: 13px 14px 12px; }
            .artifact-media { aspect-ratio: 4 / 4.5; }
            .player-play { width: 42px; height: 42px; }
            .player-title { font-size: .94rem; }
            .player-track { margin-top: 12px; }
            .player-foot { margin-top: 10px; }
            .hero-facts { gap: 8px 16px; }
        }

        /* ── Story (pinned chapters) ────────────────────── */
        .story { position: relative; background: var(--lapis); color: var(--on-dark); }
        /* Static stacked chapters by default. JS adds .is-live to enable the
           pinned sequence only when motion is allowed and the viewport is wide,
           so reduced-motion / no-JS / resized windows never get a dead pin. */
        .story-track { height: auto; }
        .story-sticky { position: static; height: auto; overflow: visible; }
        .story-panels { position: static; }
        .story-panel {
            position: static; opacity: 1; visibility: visible;
            transition: opacity .55s ease, visibility .55s ease;
            display: flex; align-items: center;
            padding: 64px 0;
            border-bottom: 1px solid rgba(247,240,225,.12);
        }
        .story-panel:last-child { border-bottom: 0; }
        .story-inner {
            display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, .82fr);
            gap: clamp(36px, 6vw, 84px); align-items: center;
            width: min(1240px, 100% - 48px); margin-inline: auto;
        }
        .story-copy { position: relative; }
        .story-chapter {
            font-family: var(--font-serif); font-style: italic; font-size: .95rem;
            color: var(--gold-bright); letter-spacing: .06em;
        }
        .story-verb {
            display: inline-flex; align-items: center; gap: 10px; margin-top: 18px;
            font-family: var(--font-mono); font-size: .6875rem; letter-spacing: .2em;
            color: var(--on-dark-faint); text-transform: uppercase;
        }
        .story-verb::before { content: ""; width: 28px; height: 1px; background: var(--gold); }
        .story-copy h3 {
            font-size: clamp(1.7rem, 3.6vw, 2.75rem); line-height: 1.58;
            margin-top: 18px; color: var(--on-dark);
        }
        .story-copy p {
            margin-top: 20px; color: var(--on-dark-soft); max-width: 52ch;
            font-size: .98rem; line-height: 2.05;
        }
        .story-media {
            position: relative; padding: 10px; border: 1px solid rgba(217,164,65,.32);
            background: rgba(247,240,225,.03);
        }
        .story-media::before {
            content: ""; position: absolute; inset: 4px; border: 1px solid rgba(247,240,225,.1); z-index: 2; pointer-events: none;
        }
        .story-media img {
            width: 100%; aspect-ratio: 4 / 3; object-fit: cover; object-position: 50% 28%;
            filter: saturate(.5) contrast(1.05) brightness(.92);
        }
        .story-media::after {
            content: ""; position: absolute; inset: 10px;
            background: linear-gradient(160deg, rgba(19,37,63,.06), rgba(19,37,63,.55));
            mix-blend-mode: multiply; pointer-events: none;
        }
        .story-chrome {
            position: absolute; inset-inline: 0; bottom: 28px; z-index: 5;
            display: none; flex-direction: column; align-items: center; gap: 14px;
            pointer-events: none;
        }
        .story-dots { display: flex; gap: 10px; pointer-events: auto; }
        .story-dot {
            width: 34px; height: 3px; border: 0; background: rgba(247,240,225,.2); cursor: pointer;
            transition: background .25s ease, width .25s ease; padding: 0;
        }
        .story-dot.active { background: var(--gold-bright); width: 52px; }
        .story-progress { width: min(420px, 60vw); height: 1px; background: rgba(247,240,225,.14); }
        .story-progress span { display: block; height: 100%; width: 0; background: var(--gold-bright); transition: width .12s linear; }
        .story-count {
            position: absolute; inset-inline-end: 28px; bottom: 26px; z-index: 5;
            display: none;
            font-family: var(--font-mono); font-size: .6875rem; letter-spacing: .18em; color: var(--on-dark-faint);
        }
        @media (min-width: 981px) {
            .story.is-live .story-track { height: 320vh; }
            .story.is-live .story-sticky { position: sticky; top: 0; height: 100svh; overflow: hidden; }
            .story.is-live .story-panels { position: absolute; inset: 0; }
            .story.is-live .story-panel {
                position: absolute; inset: 0; opacity: 0; visibility: hidden;
                padding: 112px 0 96px; border-bottom: 0;
            }
            .story.is-live .story-panel.is-active { opacity: 1; visibility: visible; }
            .story.is-live .story-chrome { display: flex; }
            .story.is-live .story-count { display: block; }
        }
        @media (max-width: 980px) {
            .story-inner { grid-template-columns: 1fr; gap: 28px; }
            .story-media { order: -1; }
            .story-media img { aspect-ratio: 16 / 10; }
        }

        /* ── Features ───────────────────────────────────── */
        .features-head { display: flex; justify-content: space-between; align-items: flex-end; gap: 32px; flex-wrap: wrap; }
        .features-list { margin-top: 56px; border-top: 1px solid var(--line); }
        .feature {
            display: grid; grid-template-columns: 64px minmax(0, 1fr) minmax(0, 240px);
            gap: 28px; align-items: start; padding: 30px 4px;
            border-bottom: 1px solid var(--line);
            transition: background .25s ease, padding .25s ease;
        }
        .feature:hover { background: var(--paper-2); padding-inline: 16px; }
        .feature-num {
            font-family: var(--font-mono); font-size: .72rem; color: var(--gold);
            letter-spacing: .12em; padding-top: 4px;
        }
        .feature-title {
            display: flex; align-items: center; gap: 12px;
            font-size: 1.08rem; font-weight: 600; line-height: 1.7;
        }
        .feature-title svg { width: 21px; height: 21px; color: var(--accent); flex-shrink: 0; }
        .feature-text { color: var(--ink-soft); font-size: .91rem; line-height: 1.95; margin-top: 8px; max-width: 64ch; }
        .feature-tags { display: flex; flex-wrap: wrap; gap: 6px; justify-content: flex-start; padding-top: 6px; }
        .tag {
            display: inline-flex; align-items: center; padding: 4px 9px;
            border: 1px solid var(--line-strong); border-radius: 4px;
            font-size: .7rem; color: var(--ink-faint); white-space: nowrap; line-height: 1.6;
        }
        @media (max-width: 860px) {
            .feature { grid-template-columns: 40px minmax(0, 1fr); gap: 16px; }
            .feature-tags { grid-column: 2; padding-top: 0; }
        }

        /* ── Steps ──────────────────────────────────────── */
        .steps {
            margin-top: 56px; display: grid; grid-template-columns: repeat(4, 1fr);
            border-top: 1px solid var(--line-strong);
        }
        .step {
            position: relative; padding: 30px 26px 34px;
            border-inline-start: 1px solid var(--line);
        }
        .step:first-child { border-inline-start: 0; }
        .step::before {
            content: ""; position: absolute; top: -4px; inset-inline-start: 26px;
            width: 7px; height: 7px; background: var(--gold); transform: rotate(45deg);
        }
        .step-num {
            font-family: var(--font-serif); font-size: 2.6rem; font-style: italic;
            color: #9c8c74; line-height: 1; font-weight: 400;
        }
        .step h3 { font-size: 1rem; font-weight: 600; margin-top: 20px; }
        .step p { color: var(--ink-faint); font-size: .85rem; line-height: 1.9; margin-top: 8px; }
        @media (max-width: 860px) {
            .steps { grid-template-columns: repeat(2, 1fr); }
            .step:nth-child(3) { border-inline-start: 0; }
            .step:nth-child(1), .step:nth-child(2) { border-bottom: 1px solid var(--line); }
        }
        @media (max-width: 520px) {
            .steps { grid-template-columns: 1fr; }
            .step { border-inline-start: 0; border-bottom: 1px solid var(--line); }
            .step:last-child { border-bottom: 0; }
        }

        /* ── Lectures ───────────────────────────────────── */
        .lecture-list { margin-top: 48px; border-top: 1px solid var(--line-strong); }
        .lecture-head {
            display: grid; grid-template-columns: 54px minmax(0, 1fr) 220px 40px;
            gap: 20px; padding: 10px 4px; border-bottom: 1px solid var(--line);
            font-family: var(--font-mono); font-size: .6875rem; letter-spacing: .14em;
            color: var(--ink-faint); text-transform: uppercase;
        }
        .lecture-row {
            display: grid; grid-template-columns: 54px minmax(0, 1fr) 220px 40px;
            gap: 20px; align-items: center; padding: 22px 4px;
            border-bottom: 1px solid var(--line); text-decoration: none;
            transition: background .22s ease, padding .22s ease;
        }
        .lecture-row:hover { background: var(--paper-2); padding-inline: 16px; }
        .lecture-index { font-family: var(--font-mono); font-size: .74rem; color: var(--gold); }
        .lecture-title { display: block; font-family: var(--font-display); font-size: 1.02rem; font-weight: 600; line-height: 1.6; }
        .lecture-desc { display: block; color: var(--ink-faint); font-size: .84rem; line-height: 1.85; margin-top: 4px; }
        .lecture-meta { display: flex; flex-wrap: wrap; gap: 6px; }
        .lecture-arrow {
            width: 34px; height: 34px; border: 1px solid var(--line-strong); border-radius: 50%;
            display: grid; place-items: center; color: var(--ink-faint);
            transition: color .2s ease, border-color .2s ease, transform .2s ease;
        }
        .lecture-row:hover .lecture-arrow { color: var(--accent); border-color: var(--accent); transform: translateX(-4px); }
        .lecture-arrow svg { width: 15px; height: 15px; }
        .lecture-cta {
            display: flex; flex-wrap: wrap; align-items: center; gap: 16px; margin-top: 34px;
        }
        .lecture-cta .note { color: var(--ink-faint); font-size: .83rem; }
        .lecture-cta .note a { color: var(--accent); text-decoration: none; font-weight: 600; }
        .lecture-cta .note a:hover { text-decoration: underline; }
        @media (max-width: 900px) {
            .lecture-head { display: none; }
            .lecture-row { grid-template-columns: 34px minmax(0, 1fr) 34px; gap: 14px; }
            .lecture-meta { grid-column: 2; }
            .lecture-arrow { grid-column: 3; grid-row: 1; }
        }

        /* ── About ──────────────────────────────────────── */
        .about { background: var(--lapis); color: var(--on-dark); overflow: hidden; }
        .about::before {
            content: ""; position: absolute; inset: 0; pointer-events: none;
            background: radial-gradient(ellipse 55% 60% at 80% 20%, rgba(217,164,65,.12), transparent 65%);
        }
        .about .wrap { position: relative; z-index: 1; }
        .about-grid {
            display: grid; grid-template-columns: minmax(0, .8fr) minmax(0, 1.2fr);
            gap: clamp(40px, 7vw, 96px); align-items: center;
        }
        .about-media { position: relative; }
        .about-media .frame { background: rgba(247,240,225,.04); border-color: rgba(217,164,65,.32); }
        .about-media .frame::before { border-color: rgba(247,240,225,.1); }
        .about-media img {
            width: 100%; aspect-ratio: 4 / 5; object-fit: cover; object-position: 50% 25%;
            filter: saturate(.55) contrast(1.05) brightness(.9);
        }
        .about-media figcaption {
            position: absolute; inset-inline-start: 24px; bottom: 24px; z-index: 4;
            background: var(--paper); color: var(--ink); padding: 10px 16px;
            font-family: var(--font-display); font-size: .82rem; font-weight: 600;
        }
        .about .eyebrow { color: var(--gold-bright); }
        .about .eyebrow::before { background: var(--gold-bright); }
        .about .h2 { color: var(--on-dark); }
        .about-quote {
            font-family: var(--font-serif); font-style: italic;
            font-size: clamp(1.3rem, 2.4vw, 1.8rem); line-height: 1.7;
            color: var(--gold-bright); margin-top: 22px;
        }
        .about p { color: var(--on-dark-soft); font-size: .96rem; line-height: 2.05; margin-top: 18px; max-width: 58ch; }
        .about-list { margin-top: 30px; display: grid; gap: 0; border-top: 1px solid rgba(247,240,225,.14); }
        .about-item {
            display: grid; grid-template-columns: 34px minmax(0, 1fr); gap: 14px;
            padding: 16px 0; border-bottom: 1px solid rgba(247,240,225,.14);
        }
        .about-item span {
            font-family: var(--font-mono); font-size: .6875rem; color: var(--gold-bright);
            padding-top: 4px; letter-spacing: .08em;
        }
        .about-item b { display: block; font-family: var(--font-display); font-size: .92rem; font-weight: 500; color: var(--on-dark); }
        .about-item small { display: block; color: var(--on-dark-faint); font-size: .82rem; line-height: 1.9; margin-top: 2px; }
        @media (max-width: 860px) {
            .about-grid { grid-template-columns: 1fr; gap: 36px; }
            .about-media { max-width: 420px; }
            .about-media img { aspect-ratio: 4 / 4.2; }
        }

        /* ── FAQ ────────────────────────────────────────── */
        .faq-wrap { margin-top: 48px; border-top: 1px solid var(--line-strong); }
        .faq-item { border-bottom: 1px solid var(--line); }
        .faq-item summary {
            list-style: none; cursor: pointer; display: flex; align-items: center; justify-content: space-between; gap: 20px;
            padding: 22px 4px; font-family: var(--font-display); font-size: .98rem; font-weight: 500;
            transition: color .2s ease;
        }
        .faq-item summary::-webkit-details-marker { display: none; }
        .faq-item summary:hover { color: var(--accent); }
        .faq-item summary .plus {
            width: 26px; height: 26px; border: 1px solid var(--line-strong); border-radius: 50%;
            display: grid; place-items: center; flex-shrink: 0; color: var(--ink-faint);
            transition: transform .25s ease, border-color .25s ease, color .25s ease;
        }
        .faq-item summary .plus svg { width: 12px; height: 12px; }
        .faq-item[open] summary { color: var(--accent); }
        .faq-item[open] summary .plus { transform: rotate(45deg); border-color: var(--accent); color: var(--accent); }
        .faq-answer {
            padding: 0 4px 26px; color: var(--ink-soft); font-size: .9rem;
            line-height: 2; max-width: 78ch;
        }

        /* ── CTA ────────────────────────────────────────── */
        .cta { background: var(--paper); padding: clamp(48px, 7vw, 96px) 0; }
        .cta-inner {
            position: relative; background: var(--lapis); color: var(--on-dark);
            padding: clamp(56px, 8vw, 96px) clamp(24px, 6vw, 88px); overflow: hidden;
            border: 1px solid var(--lapis-2);
        }
        .cta-inner::before {
            content: ""; position: absolute; inset: 14px; border: 1px solid rgba(217,164,65,.28); pointer-events: none;
        }
        .cta-inner::after {
            content: ""; position: absolute; width: 420px; height: 420px; border-radius: 50%;
            inset-inline-end: -160px; top: -190px; border: 1px solid rgba(217,164,65,.16);
            box-shadow: 0 0 0 40px rgba(217,164,65,.04), 0 0 0 90px rgba(217,164,65,.03);
            pointer-events: none;
        }
        .cta-content { position: relative; z-index: 2; max-width: 720px; }
        .cta .eyebrow { color: var(--gold-bright); }
        .cta .eyebrow::before { background: var(--gold-bright); }
        .cta h2 { font-size: clamp(1.9rem, 4.4vw, 3rem); line-height: 1.45; color: var(--on-dark); margin-top: 16px; }
        .cta p { color: var(--on-dark-soft); font-size: .98rem; line-height: 2; margin-top: 18px; max-width: 54ch; }
        .cta-actions { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 32px; }
        .cta .btn-primary { background: var(--gold); color: var(--on-gold); box-shadow: none; }
        .cta .btn-primary:hover { background: var(--gold); filter: brightness(1.06); transform: translateY(-2px); }
        .cta-mark {
            position: absolute; inset-inline-end: clamp(24px, 6vw, 84px); bottom: clamp(24px, 5vw, 64px);
            font-family: var(--font-serif); font-style: italic; font-size: .8rem; color: rgba(247,240,225,.32);
            z-index: 2;
        }

        /* ── Footer ─────────────────────────────────────── */
        .footer { background: var(--lapis); color: var(--on-dark); border-top: 1px solid rgba(247,240,225,.12); padding: 64px 0 30px; }
        .footer-top {
            display: grid; grid-template-columns: minmax(0, 1.4fr) repeat(3, minmax(0, .7fr));
            gap: 40px; padding-bottom: 44px; border-bottom: 1px solid rgba(247,240,225,.12);
        }
        .footer .brand-text { color: var(--on-dark); }
        .footer .brand-text span { color: var(--gold-bright); }
        .footer .brand-text small { color: var(--on-dark-faint); }
        .footer-about p { color: var(--on-dark-faint); font-size: .85rem; line-height: 1.95; margin-top: 18px; max-width: 42ch; }
        .footer-col-title {
            font-family: var(--font-mono); font-size: .6875rem; letter-spacing: .14em;
            color: var(--gold-bright); text-transform: uppercase; margin-bottom: 16px;
        }
        .footer-col { display: flex; flex-direction: column; gap: 9px; }
        .footer-col a {
            text-decoration: none; color: var(--on-dark-soft); font-size: .85rem;
            transition: color .2s ease;
        }
        .footer-col a:hover { color: var(--gold-bright); }
        .social-row { display: flex; gap: 8px; }
        .social-row a {
            width: 36px; height: 36px; border: 1px solid rgba(247,240,225,.16);
            display: grid; place-items: center; color: var(--on-dark-soft);
            transition: color .2s ease, border-color .2s ease;
        }
        .social-row a:hover { color: var(--gold-bright); border-color: var(--gold); }
        .social-row svg { width: 17px; height: 17px; }
        .footer-bottom {
            display: flex; flex-wrap: wrap; justify-content: space-between; gap: 12px;
            padding-top: 22px; color: var(--on-dark-faint); font-size: .74rem;
        }
        .footer-bottom .mono { font-family: var(--font-mono); font-size: .6875rem; letter-spacing: .1em; }
        @media (max-width: 900px) {
            .footer-top { grid-template-columns: repeat(2, 1fr); gap: 32px; }
        }
        @media (max-width: 560px) {
            .footer-top { grid-template-columns: 1fr; }
        }

        /* ── Map Intro ─────────────────────────────────── */
        .map-intro {
            position: fixed; inset: 0; z-index: 10001;
            overflow: hidden; pointer-events: none;
            background-color: #efe4cb;
            --seam-ink: rgba(40, 30, 14, .34);
        }
        [data-theme="dark"] .map-intro { background-color: #0d151f; --seam-ink: rgba(0, 0, 0, .85); }
        .map-intro.is-active { pointer-events: all; }

        .map-intro__panel {
            position: absolute; top: 0; bottom: 0; width: 50%;
            background-color: #efe4cb;
            background-image:
                url('{{ asset('images/map-ink.svg') }}'),
                radial-gradient(ellipse 70% 55% at 28% 18%, rgba(255, 252, 244, .85), rgba(255, 252, 244, 0) 62%),
                radial-gradient(ellipse 62% 52% at 78% 82%, rgba(150, 115, 56, .22), rgba(150, 115, 56, 0) 66%),
                radial-gradient(ellipse 46% 42% at 62% 38%, rgba(122, 90, 38, .14), rgba(122, 90, 38, 0) 70%);
            background-repeat: no-repeat;
            background-size: 200% 100%, auto, auto, auto;
            background-position: 0% 50%, center, center, center;
            transform: translateX(0);
            transition: transform .95s cubic-bezier(.66, 0, .34, 1);
            will-change: transform;
            box-shadow: 0 0 60px rgba(40, 30, 14, .25);
        }
        .map-intro__panel--a { inset-inline-start: 0; }
        .map-intro__panel--b { inset-inline-end: 0; background-position: 100% 50%, center, center, center; }
        [dir="rtl"] .map-intro__panel--a { background-position: 100% 50%, center, center, center; }
        [dir="rtl"] .map-intro__panel--b { background-position: 0% 50%, center, center, center; }
        .map-intro__panel::after {
            content: ""; position: absolute; top: 0; bottom: 0; width: 110px;
            background: linear-gradient(to var(--seam-dir, right), var(--seam-ink), transparent);
            pointer-events: none;
        }
        .map-intro__panel--a::after { inset-inline-end: 0; }
        .map-intro__panel--b::after { inset-inline-start: 0; }
        /* dark edge always sits on the seam side */
        [dir="rtl"] .map-intro__panel--a::after { --seam-dir: right; }
        [dir="rtl"] .map-intro__panel--b::after { --seam-dir: left; }
        [dir="ltr"] .map-intro__panel--a::after { --seam-dir: left; }
        [dir="ltr"] .map-intro__panel--b::after { --seam-dir: right; }

        .map-intro::before {
            content: ""; position: absolute; inset: 0; z-index: 1; pointer-events: none;
            background: radial-gradient(ellipse at center, rgba(58, 44, 20, 0) 42%, rgba(58, 44, 20, .26) 100%);
        }
        [data-theme="dark"] .map-intro::before {
            background: radial-gradient(ellipse at center, rgba(0, 0, 0, 0) 38%, rgba(0, 0, 0, .6) 100%);
        }

        [data-theme="dark"] .map-intro__panel {
            background-color: #0d151f;
            background-image:
                url('{{ asset('images/map-ink-dark.svg') }}'),
                radial-gradient(ellipse 70% 55% at 28% 18%, rgba(70, 92, 118, .3), rgba(70, 92, 118, 0) 62%),
                radial-gradient(ellipse 62% 52% at 78% 82%, rgba(0, 0, 0, .55), rgba(0, 0, 0, 0) 66%),
                radial-gradient(ellipse 46% 42% at 62% 38%, rgba(232, 188, 102, .1), rgba(232, 188, 102, 0) 70%);
            opacity: .95;
        }

        .map-intro__seam {
            position: absolute; top: 0; bottom: 0; left: 50%; width: 1px;
            transform: translateX(-50%);
            background: linear-gradient(to bottom, transparent, rgba(122, 83, 18, .75) 18%, rgba(194, 147, 58, .9) 50%, rgba(122, 83, 18, .75) 82%, transparent);
            z-index: 2;
        }

        .map-intro__seal {
            position: absolute; inset: 0; z-index: 3;
            display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 18px;
            transition: opacity .45s ease, transform .55s cubic-bezier(.22,1,.36,1);
        }
        .map-intro__seal::before {
            content: ""; position: absolute; left: 50%; top: 50%;
            width: min(620px, 88vw); aspect-ratio: 1;
            transform: translate(-50%, -50%);
            border-radius: 50%; pointer-events: none;
            background: radial-gradient(circle,
                rgba(247, 243, 233, .97) 0%,
                rgba(247, 243, 233, .9) 34%,
                rgba(247, 243, 233, .5) 56%,
                rgba(247, 243, 233, 0) 72%);
        }
        [data-theme="dark"] .map-intro__seal::before {
            background: radial-gradient(circle,
                rgba(9, 14, 20, .96) 0%,
                rgba(9, 14, 20, .86) 34%,
                rgba(9, 14, 20, .45) 56%,
                rgba(9, 14, 20, 0) 72%);
        }
        .map-intro__seal > * { position: relative; z-index: 1; }
        .map-intro__compass {
            width: 76px; height: 76px; color: #7a5312;
            opacity: .9;
            animation: mapCompass 9s linear infinite;
        }
        [data-theme="dark"] .map-intro__compass { color: #e8bc66; }
        .map-intro__brand {
            font-family: var(--font-display); font-size: .9rem; font-weight: 600;
            letter-spacing: normal; color: #4c3a17;
            padding: 9px 20px;
            border-block: 1px solid rgba(122, 83, 18, .35);
            white-space: nowrap;
        }
        [data-theme="dark"] .map-intro__brand { color: #f2ebdd; border-color: rgba(232,188,102,.35); }
        .map-intro__hint {
            font-family: var(--font-mono); font-size: .75rem; letter-spacing: normal;
            color: rgba(60, 46, 18, .85);
        }
        [data-theme="dark"] .map-intro__hint { color: rgba(242, 235, 221, .85); }
        .map-intro__bar {
            width: 132px; height: 2px; background: rgba(76, 58, 23, .18); overflow: hidden;
        }
        .map-intro__bar span {
            display: block; height: 100%; width: 0%;
            background: #7a5312;
        }
        [data-theme="dark"] .map-intro__bar { background: rgba(242,235,221,.16); }
        [data-theme="dark"] .map-intro__bar span { background: #e8bc66; }

        .map-intro.is-loading .map-intro__bar span { width: 100%; transition: width .95s cubic-bezier(.4,0,.2,1); }
        .map-intro.is-opening .map-intro__seal { opacity: 0; transform: scale(.94); pointer-events: none; }
        .map-intro.is-opening .map-intro__seam { opacity: 0; transition: opacity .5s ease .2s; }

        [dir="rtl"] .map-intro.is-opening .map-intro__panel--a { transform: translateX(102%); }
        [dir="rtl"] .map-intro.is-opening .map-intro__panel--b { transform: translateX(-102%); }
        [dir="ltr"] .map-intro.is-opening .map-intro__panel--a { transform: translateX(-102%); }
        [dir="ltr"] .map-intro.is-opening .map-intro__panel--b { transform: translateX(102%); }

        .map-intro.is-done { display: none; }

        /* Page reveal behind the map */
        #main.intro-pending { opacity: 0; transform: scale(.988); filter: blur(3px); }
        #main.intro-revealing {
            opacity: 1; transform: none; filter: none;
            transition: opacity .95s ease .12s, transform 1.25s cubic-bezier(.22,1,.36,1) .12s, filter .9s ease .12s;
        }

        @keyframes mapCompass { to { transform: rotate(360deg); } }

        @media (max-width: 420px) {
            .map-intro__seal { gap: 13px; }
            .map-intro__compass { width: 62px; height: 62px; }
            .map-intro__brand { font-size: .8rem; padding: 8px 13px; max-width: 88vw; white-space: normal; text-align: center; }
            .map-intro__hint { font-size: .7rem; }
            .map-intro__bar { width: 104px; }
        }

        /* ── Motion ─────────────────────────────────────── */
        .reveal {
            opacity: 0; transform: translateY(12px); filter: blur(2px);
            transition: opacity .7s cubic-bezier(.22,1,.36,1),
                        transform .7s cubic-bezier(.22,1,.36,1),
                        filter .7s cubic-bezier(.22,1,.36,1);
        }
        .reveal.in { opacity: 1; transform: none; filter: none; }
        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            .reveal { opacity: 1; transform: none; filter: none; transition: none; }
            .scroll-cue .line::after { animation: none; }
            .story-panel { transition: none; }
            .btn, .nav-cta, .artifact-media img, .lecture-row, .feature { transition: none; }
            .map-intro { display: none !important; }
        }
    </style>
</head>
<body>

@php
    $lqip = [];
    foreach (['hero-1', 'hero-2', 'hero-3'] as $name) {
        $file = base_path('images/' . $name . '-lqip.txt');
        $lqip[$name] = is_file($file)
            ? 'data:image/jpeg;base64,' . trim((string) file_get_contents($file))
            : '';
    }
@endphp

<div id="mapIntro" class="map-intro" aria-hidden="true">
    <div class="map-intro__panel map-intro__panel--a"></div>
    <div class="map-intro__panel map-intro__panel--b"></div>
    <div class="map-intro__seam"></div>
    <div class="map-intro__seal">
        <svg class="map-intro__compass" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="12" r="9.4"/><circle cx="12" cy="12" r="6.4" opacity=".55"/>
            <path d="M12 2.6v2.2M12 19.2v2.2M2.6 12h2.2M19.2 12h2.2"/>
            <path d="m12 6.6 1.7 4.1 4.1 1.7-4.1 1.7-1.7 4.1-1.7-4.1-4.1-1.7 4.1-1.7Z"/>
            <circle cx="12" cy="12" r="1" fill="currentColor" stroke="none"/>
        </svg>
        <div class="map-intro__brand">مستر عبدالرحمن الحبال</div>
        <div class="map-intro__hint">جارٍ فتح الخريطة</div>
        <div class="map-intro__bar"><span></span></div>
    </div>
</div>

@include('components.page-loader')

<a class="skip-link" href="#main">تجاوز إلى المحتوى</a>

<header class="nav" id="siteNav">
    <div class="wrap nav-inner">
        <a href="{{ route('landing') }}" class="brand" aria-label="الصفحة الرئيسية — منصة مستر عبدالرحمن الحبال">
            <span class="brand-mark"><img src="{{ asset('images/logo.png') }}" alt=""></span>
            <span class="brand-text">
                مستر عبدالرحمن <span>الحبال</span>
                <small>El-Molhem Platform</small>
            </span>
        </a>

        <nav class="nav-links" aria-label="روابط الصفحة">
            <a href="#story">الرحلة</a>
            <a href="#features">المميزات</a>
            <a href="#how">كيف تبدأ</a>
            <a href="#lectures">المحاضرات</a>
            <a href="#about">عن المنصة</a>
            <a href="#faq">الأسئلة</a>
        </nav>

        <div class="nav-actions">
            <button class="icon-btn" id="themeBtn" type="button" aria-label="تبديل المظهر">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8Z"/></svg>
            </button>
            <a href="{{ route('login') }}" class="nav-login">تسجيل الدخول</a>
            <a href="{{ route('register') }}" class="nav-cta">ابدأ الآن</a>
            <button class="icon-btn nav-burger" id="burgerBtn" type="button" aria-label="القائمة" aria-expanded="false" aria-controls="mobileMenu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
            </button>
        </div>
    </div>
</header>

<div class="mobile-menu" id="mobileMenu">
    <a href="#story">الرحلة</a>
    <a href="#features">المميزات</a>
    <a href="#how">كيف تبدأ</a>
    <a href="#lectures">المحاضرات</a>
    <a href="#about">عن المنصة</a>
    <a href="#faq">الأسئلة الشائعة</a>
    <a href="{{ route('login') }}">تسجيل الدخول</a>
    <a href="{{ route('register') }}" class="btn btn-primary">أنشئ حسابك</a>
</div>

<main id="main">

    <section class="hero">
        <div class="wrap">
            <div class="hero-grid">
                <div class="hero-copy">
                    <span class="eyebrow">منصة تعليمية · البكالوريا والإعدادية</span>
                    <h1>تعلّم المنهج <em>بالفهم</em>، لا بالحفظ.</h1>
                    <p class="hero-lead">
                        محاضرات مسجّلة، اختبارات تُصحَّح في اللحظة، ولوحة متصدرين لكل محاضرة.
                        كل ما تحتاجه للمراجعة والتفوّق في مكان واحد، مرتّب خطوة بخطوة.
                    </p>
                    <div class="hero-actions">
                        <a href="{{ route('register') }}" class="btn btn-primary">
                            ابدأ الآن
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline">لديّ حساب</a>
                    </div>
                    <div class="hero-facts">
                        <span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-3.5 8-10V5l-8-3-8 3v7c0 6.5 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/></svg>
                            التسجيل مجاني
                        </span>
                        <span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                            تصحيح فوري
                        </span>
                        <span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 21h8M12 17v4"/><path d="M7 4h10v5a5 5 0 0 1-10 0V4Z"/><path d="M17 5h3v2a3 3 0 0 1-3 3M7 5H4v2a3 3 0 0 0 3 3"/></svg>
                            لوحة متصدرين
                        </span>
                    </div>
                </div>

                <div class="artifact-wrap reveal">
                    <figure class="artifact">
                        <span class="artifact-label"><span class="live-dot"></span>داخل المنصة · متاح الآن</span>
                        <div class="artifact-media" @if($lqip['hero-1']) style="background-image:url('{{ $lqip['hero-1'] }}');background-size:cover;background-position:50% 28%;" @endif>
                            <img src="{{ asset('images/hero-1.webp') }}"
                                 srcset="{{ asset('images/hero-1-sm.webp') }} 640w, {{ asset('images/hero-1.webp') }} 1402w"
                                 sizes="(max-width: 980px) 90vw, 44vw"
                                 alt="مستر عبدالرحمن الحبال" fetchpriority="high">
                        </div>
                        <figcaption class="artifact-caption">
                            @if($heroLecture)
                                <div class="player-head">
                                    <span class="player-kicker">أحدث محاضرة</span>
                                    @if($heroLecture->duration_minutes)
                                        <span class="player-duration">{{ $heroLecture->duration_minutes }} دقيقة</span>
                                    @endif
                                </div>
                                <a class="player-body" href="{{ route('login') }}"
                                   aria-label="تسجيل الدخول لمشاهدة {{ $heroLecture->title }}">
                                    <span class="player-play" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5.6v12.8c0 .9 1 1.4 1.7.9l9.6-6.4c.6-.4.6-1.3 0-1.7L9.7 4.7C9 4.2 8 4.7 8 5.6Z"/></svg>
                                    </span>
                                    <span class="player-text">
                                        <span class="player-title">{{ $heroLecture->title }}</span>
                                        <span class="player-sub">
                                            <span>{{ $heroLecture->level_label }}</span>
                                            @if($heroLecture->category)
                                                <span class="sep">·</span>
                                                <span>{{ $heroLecture->category }}</span>
                                            @endif
                                        </span>
                                    </span>
                                </a>
                                <div class="player-track" aria-hidden="true"><span></span></div>
                                <div class="player-foot">
                                    <span class="player-tag">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="13" height="14" rx="2"/><path d="m16 10 5-3v10l-5-3"/></svg>
                                        فيديو
                                    </span>
                                    <span class="player-tag">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="m8.5 12 2.5 2.5 4.5-5"/></svg>
                                        تصحيح فوري
                                    </span>
                                </div>
                            @else
                                <div class="player-head">
                                    <span class="player-kicker">داخل المنصة</span>
                                </div>
                                <p class="player-empty">ستظهر أحدث محاضرة هنا فور نشرها.</p>
                            @endif
                        </figcaption>
                    </figure>
                    <span class="artifact-side">EL-MOLHEM PLATFORM</span>
                </div>
            </div>

            <div class="scroll-cue" aria-hidden="true">
                <span>انزل لتتعرّف</span>
                <span class="line"></span>
            </div>
        </div>
    </section>

    <section class="story" id="story" aria-label="رحلة داخل المنصة">
        <div class="story-track" id="storyTrack">
            <div class="story-sticky">
                <div class="story-panels" id="storyPanels">

                    <article class="story-panel is-active" data-panel="0" aria-hidden="false">
                        <div class="story-inner">
                            <div class="story-copy">
                                <div class="story-chapter">الفصل الأول</div>
                                <span class="story-verb">شاهد</span>
                                <h3>محاضرات مسجّلة، بجودة تسمح لك بالتركيز.</h3>
                                <p>شرح مرتّب حسب المنهج والمستوى. تشغّل المحاضرة وقتما تشاء، وتعيد الجزء الذي لم تفهمه، من دون أن تفوّت شيئاً.</p>
                            </div>
                            <figure class="story-media">
                                <img src="{{ asset('images/hero-1.webp') }}" alt="مستر عبدالرحمن الحبال — محاضرة" loading="lazy">
                            </figure>
                        </div>
                    </article>

                    <article class="story-panel" data-panel="1" aria-hidden="true">
                        <div class="story-inner">
                            <div class="story-copy">
                                <div class="story-chapter">الفصل الثاني</div>
                                <span class="story-verb">اختبر</span>
                                <h3>اختبارات تُصحَّح في اللحظة.</h3>
                                <p>ستة أنواع من الأسئلة: اختيار من متعدد، صح أو خطأ، إكمال، مطابقة، ترتيب، وسؤال مقالي. تعرف نتيجتك فوراً، والمقالي يراجعه المدرس.</p>
                            </div>
                            <figure class="story-media">
                                <img src="{{ asset('images/hero-2.webp') }}" alt="مستر عبدالرحمن الحبال — شرح" loading="lazy">
                            </figure>
                        </div>
                    </article>

                    <article class="story-panel" data-panel="2" aria-hidden="true">
                        <div class="story-inner">
                            <div class="story-copy">
                                <div class="story-chapter">الفصل الثالث</div>
                                <span class="story-verb">تابع</span>
                                <h3>تقدّمك واضح، وترتيبك بين زملائك كذلك.</h3>
                                <p>تابع درجاتك ونسبة إنجازك في كل محاضرة، وشاهد موقعك على لوحة المتصدرين الخاصة بمحاضرتك.</p>
                            </div>
                            <figure class="story-media">
                                <img src="{{ asset('images/hero-3.webp') }}" alt="مستر عبدالرحمن الحبال — متابعة" loading="lazy">
                            </figure>
                        </div>
                    </article>

                </div>

                <div class="story-chrome">
                    <div class="story-progress"><span id="storyBar"></span></div>
                    <div class="story-dots" aria-label="فصول الرحلة">
                        <button class="story-dot active" type="button" data-goto="0" aria-label="الفصل الأول: شاهد"></button>
                        <button class="story-dot" type="button" data-goto="1" aria-label="الفصل الثاني: اختبر"></button>
                        <button class="story-dot" type="button" data-goto="2" aria-label="الفصل الثالث: تابع"></button>
                    </div>
                </div>
                <div class="story-count" aria-hidden="true">01 / 03</div>
            </div>
        </div>
    </section>

    <section class="section" id="features">
        <div class="wrap">
            <div class="features-head">
                <div class="head-block reveal">
                    <span class="eyebrow">المميزات</span>
                    <h2 class="h2">منصة مبنية للدراسة، لا للتصفّح فقط.</h2>
                    <p class="lead">كل جزء هنا له وظيفة واضحة: تشاهد، تختبر، تراجع، وتتابع. لا أدوات زائدة، ولا خطوات لا لزوم لها.</p>
                </div>
            </div>

            <div class="features-list">
                <article class="feature reveal">
                    <div class="feature-num">01</div>
                    <div>
                        <h3 class="feature-title">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="4" width="20" height="14" rx="2"/><path d="m10 9 5 3-5 3V9Z"/><path d="M8 21h8"/></svg>
                            محاضرات فيديو محمية
                        </h3>
                        <p class="feature-text">مشغّل مدمج يعمل داخل المنصة، مع علامة مائية للمنصة، وإيقاف تلقائي عند مغادرة التبويب، حتى تبقى مشاهدة المحاضرة للتعلّم.</p>
                    </div>
                    <div class="feature-tags"><span class="tag">مشغّل خاص</span><span class="tag">بدون تحميل</span></div>
                </article>

                <article class="feature reveal">
                    <div class="feature-num">02</div>
                    <div>
                        <h3 class="feature-title">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 11h.01M15 11h.01M12 3a9 9 0 1 0 9 9c0-1.6-.4-3.1-1.2-4.4L21 3l-4.6 1.2A8.96 8.96 0 0 0 12 3Z"/></svg>
                            ستة أنواع من الأسئلة
                        </h3>
                        <p class="feature-text">اختيار من متعدد، صح أو خطأ، إكمال، مطابقة، ترتيب، وسؤال مقالي؛ تنوّع يقيس الفهم لا الحفظ فقط.</p>
                    </div>
                    <div class="feature-tags"><span class="tag">6 أنواع</span><span class="tag">بنك أسئلة</span></div>
                </article>

                <article class="feature reveal">
                    <div class="feature-num">03</div>
                    <div>
                        <h3 class="feature-title">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 12 2 2 4-4"/><path d="M21 12a9 9 0 1 1-9-9"/><path d="M21 3v6h-6"/></svg>
                            تصحيح فوري، ومراجعة للمقالي
                        </h3>
                        <p class="feature-text">الأسئلة الموضوعية تُصحَّح لحظة الإرسال، والأسئلة المقالية تصل للمدرس ليعطيها درجتها مع تعليقه.</p>
                    </div>
                    <div class="feature-tags"><span class="tag">نتيجة فورية</span><span class="tag">درجات يدوية</span></div>
                </article>

                <article class="feature reveal">
                    <div class="feature-num">04</div>
                    <div>
                        <h3 class="feature-title">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/><path d="M6 15h4"/></svg>
                            اشتراك يُراجَع قبل التفعيل
                        </h3>
                        <p class="feature-text">أرسل إثبات الدفع عبر فوري أو فودافون كاش، وتُراجع الإدارة الطلب وتفعّل الاشتراك. ويمكن للإدارة فتح محاضرات محددة بأكواد وصول.</p>
                    </div>
                    <div class="feature-tags"><span class="tag">فوري</span><span class="tag">فودافون كاش</span><span class="tag">أكواد وصول</span></div>
                </article>
            </div>
        </div>
    </section>

    <section class="section section--paper" id="how">
        <div class="wrap">
            <div class="head-block center reveal">
                <span class="eyebrow">كيف تبدأ</span>
                <h2 class="h2">أربع خطوات، وأنت داخل المحاضرة.</h2>
            </div>

            <div class="steps">
                <div class="step reveal">
                    <div class="step-num">01</div>
                    <h3>أنشئ حسابك</h3>
                    <p>سجّل ببياناتك الأساسية واختر صفّك الدراسي.</p>
                </div>
                <div class="step reveal">
                    <div class="step-num">02</div>
                    <h3>فعّل اشتراكك</h3>
                    <p>اختر الخطة المتاحة وأرسل إثبات الدفع للمراجعة.</p>
                </div>
                <div class="step reveal">
                    <div class="step-num">03</div>
                    <h3>ادخل محاضراتك</h3>
                    <p>تُضاف تلقائياً إلى محاضرات صفّك المنشورة.</p>
                </div>
                <div class="step reveal">
                    <div class="step-num">04</div>
                    <h3>اختبر وتابع</h3>
                    <p>اختبر نفسك، شاهد نتيجتك، وتابع ترتيبك.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="lectures">
        <div class="wrap">
            <div class="head-block reveal">
                <span class="eyebrow">المحاضرات</span>
                <h2 class="h2">أحدث ما نُشر على المنصة.</h2>
                <p class="lead">قائمة حقيقية من المحاضرات المنشورة. سجّل الدخول لتشاهد محاضرات صفّك.</p>
            </div>

            @if($lectures->isEmpty())
                <div class="lecture-list">
                    <div class="lecture-row" style="grid-template-columns:1fr;text-decoration:none;">
                        <span class="lecture-desc" style="font-size:.95rem;">لم تُنشر محاضرات بعد؛ تابعنا قريباً، أو أنشئ حسابك لتُضاف تلقائياً عند نشر محاضرات صفّك.</span>
                    </div>
                </div>
            @else
                <div class="lecture-list">
                    <div class="lecture-head" aria-hidden="true">
                        <span>رقم</span>
                        <span>المحاضرة</span>
                        <span>التصنيف</span>
                        <span></span>
                    </div>
                    @foreach($lectures as $i => $lecture)
                        <a class="lecture-row reveal" href="{{ route('login') }}"
                           aria-label="تسجيل الدخول لمشاهدة {{ $lecture->title }}">
                            <span class="lecture-index">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <span>
                                <span class="lecture-title">{{ $lecture->title }}</span>
                                @if($lecture->description)
                                    <span class="lecture-desc">{{ \Illuminate\Support\Str::limit($lecture->description, 120) }}</span>
                                @endif
                            </span>
                            <span class="lecture-meta">
                                <span class="tag">{{ $lecture->level_label }}</span>
                                @if($lecture->category)
                                    <span class="tag">{{ $lecture->category }}</span>
                                @endif
                                @if($lecture->duration_minutes)
                                    <span class="tag">{{ $lecture->duration_minutes }} دقيقة</span>
                                @endif
                            </span>
                            <span class="lecture-arrow" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                            </span>
                        </a>
                    @endforeach
                </div>
            @endif

            <div class="lecture-cta reveal">
                <a href="{{ route('login') }}" class="btn btn-primary">
                    سجّل الدخول للمشاهدة
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                </a>
                <span class="note">ليس لديك حساب؟ <a href="{{ route('register') }}">أنشئ حسابك مجاناً</a></span>
            </div>
        </div>
    </section>

    <section class="section about" id="about">
        <div class="wrap">
            <div class="about-grid">
                <figure class="about-media reveal">
                    <div class="frame">
                        <img src="{{ asset('images/hero-2.webp') }}"
                             srcset="{{ asset('images/hero-2-sm.webp') }} 640w, {{ asset('images/hero-2.webp') }} 1536w"
                             sizes="(max-width: 860px) 90vw, 38vw"
                             alt="مستر عبدالرحمن الحبال" loading="lazy">
                    </div>
                    <figcaption>مستر عبدالرحمن الحبال</figcaption>
                </figure>

                <div class="reveal">
                    <span class="eyebrow">عن المنصة</span>
                    <h2 class="h2">المنصة ليست مكتبة فيديو.</h2>
                    <div class="about-quote">«الدرس يُفهم مرة، ويُثبَّت بالاختبار، ويظهر أثره في متابعتك.»</div>
                    <p>بُنيت المنصة حول ثلاث خطوات واضحة: مشاهدة مرتّبة، اختبار يقيس الفهم، ثم متابعة تُظهر تقدّمك. لا شيء هنا للزينة، وكل شاشة تخدم خطوة من هذه الخطوات.</p>

                    <div class="about-list">
                        <div class="about-item">
                            <span>01</span>
                            <div>
                                <b>المحتوى يصل لصفّه الصحيح</b>
                                <small>تُضاف المحاضرات المنشورة تلقائياً لطلاب الصف المطابق، والمحاضرات العامة لجميع المستويات.</small>
                            </div>
                        </div>
                        <div class="about-item">
                            <span>02</span>
                            <div>
                                <b>الاختبار جزء من الدرس</b>
                                <small>أسئلة موضوعية تُصحَّح فوراً، ومقالية يراجعها المدرس، مع نتيجة واضحة لكل محاولة.</small>
                            </div>
                        </div>
                        <div class="about-item">
                            <span>03</span>
                            <div>
                                <b>الوصول واضح ومنظّم</b>
                                <small>اشتراك يُراجَع قبل التفعيل، أو كود وصول لمحاضرة محددة تمنحه الإدارة.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="faq">
        <div class="wrap">
            <div class="head-block reveal">
                <span class="eyebrow">الأسئلة الشائعة</span>
                <h2 class="h2">أسئلة يطرحها الطلاب قبل البدء.</h2>
            </div>

            <div class="faq-wrap">
                <details class="faq-item reveal">
                    <summary>
                        <span>كيف أسجّل في المنصة؟</span>
                        <span class="plus" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></span>
                    </summary>
                    <div class="faq-answer">املأ نموذج التسجيل ببياناتك الأساسية: الاسم، البريد الإلكتروني، رقم الهاتف، العنوان، رقم ولي الأمر، والصف الدراسي. يمكنك إضافة كود دعوة إن كان لديك واحد.</div>
                </details>

                <details class="faq-item reveal">
                    <summary>
                        <span>ماذا يحدث بعد إنشاء الحساب؟</span>
                        <span class="plus" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></span>
                    </summary>
                    <div class="faq-answer">يُضاف حسابك تلقائياً إلى كل المحاضرات المنشورة المطابقة لصفّك الدراسي، وكذلك المحاضرات المتاحة لجميع المستويات.</div>
                </details>

                <details class="faq-item reveal">
                    <summary>
                        <span>هل الوصول للمحاضرات مجاني؟</span>
                        <span class="plus" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></span>
                    </summary>
                    <div class="faq-answer">التسجيل مجاني، لكن مشاهدة المحاضرات تتطلب اشتراكاً نشطاً أو كود وصول تمنحه الإدارة. اختر الخطة المتاحة وأرسل إثبات الدفع، وتُراجع الإدارة الطلب وتفعّله.</div>
                </details>

                <details class="faq-item reveal">
                    <summary>
                        <span>كيف يتم تصحيح الاختبارات؟</span>
                        <span class="plus" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></span>
                    </summary>
                    <div class="faq-answer">تُصحَّح الأسئلة الموضوعية تلقائياً لحظة الإرسال، وترى نتيجتك فوراً. أما الأسئلة المقالية فتُراجع يدوياً من المدرس، وتظهر نتيجتها بعد إضافة الدرجة.</div>
                </details>

                <details class="faq-item reveal">
                    <summary>
                        <span>هل توجد لوحة متصدرين؟</span>
                        <span class="plus" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></span>
                    </summary>
                    <div class="faq-answer">نعم. كل محاضرة لها لوحة متصدرين مستقلة تُرتّب الطلاب حسب نتائجهم، ويمكن للمدرس التحكم في إظهارها وعدد المراكز المعروضة.</div>
                </details>

                <details class="faq-item reveal">
                    <summary>
                        <span>كيف أفتح محاضرة إضافية؟</span>
                        <span class="plus" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></span>
                    </summary>
                    <div class="faq-answer">من صفحة «أكواد الدخول» داخل حسابك، أدخل الكود الذي حصلت عليه من الإدارة لفتح المحاضرة المخصصة له.</div>
                </details>
            </div>
        </div>
    </section>

    <section class="cta" aria-label="ابدأ الآن">
        <div class="wrap">
            <div class="cta-inner">
                <div class="cta-content">
                    <span class="eyebrow">ابدأ الآن</span>
                    <h2>ابدأ رحلتك مع مستر عبدالرحمن الحبال اليوم.</h2>
                    <p>أنشئ حسابك، فعّل اشتراكك، وستجد محاضرات صفّك بانتظارك، مع اختبارات ومتابعة لكل خطوة.</p>
                    <div class="cta-actions">
                        <a href="{{ route('register') }}" class="btn btn-primary">
                            أنشئ حسابك مجاناً
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-light">تسجيل الدخول</a>
                    </div>
                </div>
                <span class="cta-mark">El-Molhem Platform</span>
            </div>
        </div>
    </section>

</main>

<footer class="footer">
    <div class="wrap">
        <div class="footer-top">
            <div class="footer-about">
                <a href="{{ route('landing') }}" class="brand" aria-label="منصة مستر عبدالرحمن الحبال">
                    <span class="brand-mark"><img src="{{ asset('images/logo.png') }}" alt=""></span>
                    <span class="brand-text">
                        مستر عبدالرحمن <span>الحبال</span>
                        <small>El-Molhem Platform</small>
                    </span>
                </a>
                <p>منصة تعليمية تجمع المحاضرات المسجّلة، الاختبارات التفاعلية، ولوحة المتصدرين، لطلاّب البكالوريا والصف الثالث الإعدادي.</p>
            </div>

            <div class="footer-col">
                <div class="footer-col-title">المنصة</div>
                <a href="#story">الرحلة</a>
                <a href="#features">المميزات</a>
                <a href="#how">كيف تبدأ</a>
                <a href="#lectures">المحاضرات</a>
                <a href="#faq">الأسئلة الشائعة</a>
            </div>

            <div class="footer-col">
                <div class="footer-col-title">الحساب</div>
                <a href="{{ route('login') }}">تسجيل الدخول</a>
                <a href="{{ route('register') }}">إنشاء حساب</a>
            </div>

            <div class="footer-col">
                <div class="footer-col-title">تواصل</div>
                <div class="social-row">
                    <a href="https://whatsapp.com/channel/0029Vaxzz7A4IBhIVIkpIc2z" target="_blank" rel="noopener" aria-label="قناة واتساب">
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.84.5 3.56 1.36 5.03L2 22l5.25-1.38c1.41.77 3.02 1.21 4.79 1.21h.01c5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2zm5.81 14.06c-.24.68-1.42 1.31-1.94 1.39-.5.08-1.12.11-1.81-.12-.42-.13-.96-.31-1.65-.6-2.91-1.26-4.8-4.18-4.94-4.37-.14-.19-1.18-1.57-1.18-3 0-1.42.74-2.12 1.01-2.41.27-.29.59-.36.78-.36.2 0 .39.002.56.01.18.01.42-.07.65.5.24.58.81 2 .88 2.14.07.15.12.32.02.51-.09.19-.15.31-.29.48-.15.17-.31.38-.44.51-.15.15-.3.31-.13.6.17.3.78 1.28 1.67 2.07 1.15 1.03 2.12 1.36 2.43 1.51.31.15.49.13.67-.06.18-.19.78-.91.99-1.22.21-.31.42-.26.7-.16.28.1 1.79.85 2.1 1 .31.15.51.23.59.36.08.13.08.74-.16 1.42z"/></svg>
                    </a>
                    <a href="https://t.me/abdelrahmanalsayed" target="_blank" rel="noopener" aria-label="قناة تليجرام">
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.4-1.13 7.16-.14.75-.42 1-.68 1.02-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.7.01-.02.01-.13-.05-.18-.06-.05-.16-.03-.23-.02-.1.02-1.66 1.06-4.7 3.11-.45.31-.85.46-1.21.45-.4-.01-1.16-.22-1.73-.41-.7-.23-1.25-.36-1.2-.75.02-.21.32-.42.88-.64 3.49-1.52 5.81-2.52 6.97-3 3.32-1.38 4-1.62 4.45-1.62.1 0 .32.02.42.13.08.1.1.23.1.32-.01.07.01.27-.02.43z"/></svg>
                    </a>
                    <a href="https://www.facebook.com/mrabdelrahmanalsayed/" target="_blank" rel="noopener" aria-label="صفحة فيسبوك">
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06c0 5 3.66 9.16 8.44 9.94v-7.03H7.9v-2.91h2.54V9.85c0-2.5 1.49-3.89 3.78-3.89 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.87h2.78l-.44 2.91h-2.34V22c4.78-.78 8.44-4.94 8.44-9.94z"/></svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div>© {{ date('Y') }} مستر عبدالرحمن الحبال. جميع الحقوق محفوظة.</div>
            <div class="mono">DESIGNED FOR FOCUS</div>
        </div>
    </div>
</footer>

<script>
(function () {
    'use strict';

    var themeBtn = document.getElementById('themeBtn');
    var ICON_MOON = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8Z"/></svg>';
    var ICON_SUN = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>';

    function applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        try { localStorage.setItem('theme', theme); } catch (e) {}
        if (themeBtn) {
            themeBtn.innerHTML = theme === 'dark' ? ICON_SUN : ICON_MOON;
            themeBtn.setAttribute('aria-label', theme === 'dark' ? 'التبديل إلى الوضع النهاري' : 'التبديل إلى الوضع الليلي');
        }
    }

    var savedTheme = 'light';
    try { savedTheme = localStorage.getItem('theme') || 'light'; } catch (e) {}
    applyTheme(savedTheme);

    if (themeBtn) {
        themeBtn.addEventListener('click', function () {
            applyTheme(document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark');
        });
    }

    var nav = document.getElementById('siteNav');
    function syncNav() {
        if (nav) nav.classList.toggle('solid', window.scrollY > 24);
    }
    window.addEventListener('scroll', syncNav, { passive: true });
    syncNav();

    var burger = document.getElementById('burgerBtn');
    var menu = document.getElementById('mobileMenu');
    if (burger && menu) {
        burger.addEventListener('click', function () {
            var open = menu.classList.toggle('open');
            burger.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
        menu.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                menu.classList.remove('open');
                burger.setAttribute('aria-expanded', 'false');
            });
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && menu.classList.contains('open')) {
                menu.classList.remove('open');
                burger.setAttribute('aria-expanded', 'false');
                burger.focus();
            }
        });
    }

    var track = document.getElementById('storyTrack');
    var panels = Array.prototype.slice.call(document.querySelectorAll('.story-panel'));
    var dots = Array.prototype.slice.call(document.querySelectorAll('[data-goto]'));
    var bar = document.getElementById('storyBar');
    var count = document.querySelector('.story-count');
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (track && panels.length) {
        var story = track.closest('.story');
        var total = panels.length;
        var current = -1;
        var live = false;
        var ticking = false;

        function setActive(index) {
            if (index === current) return;
            current = index;
            panels.forEach(function (panel, i) {
                panel.classList.toggle('is-active', i === index);
                panel.classList.toggle('is-past', i < index);
                panel.setAttribute('aria-hidden', i === index ? 'false' : 'true');
            });
            dots.forEach(function (dot, i) {
                dot.classList.toggle('active', i === index);
            });
            if (count) count.textContent = '0' + (index + 1) + ' / 0' + total;
        }

        function paint() {
            var rect = track.getBoundingClientRect();
            var span = track.offsetHeight - window.innerHeight;
            if (span <= 0) return;
            var progress = Math.min(1, Math.max(0, -rect.top / span));
            setActive(Math.min(total - 1, Math.floor(progress * total + 0.0001)));
            if (bar) bar.style.width = (progress * 100).toFixed(2) + '%';
        }

        function staticMode() {
            panels.forEach(function (panel) { panel.setAttribute('aria-hidden', 'false'); });
            dots.forEach(function (dot, i) { dot.classList.toggle('active', i === 0); });
            if (bar) bar.style.width = '0%';
        }

        /* Only pin when the scroll tracker can actually drive the panels. */
        function syncMode() {
            var wide = !reduceMotion && window.matchMedia('(min-width: 981px)').matches;
            if (wide) {
                if (!live) {
                    live = true;
                    story.classList.add('is-live');
                    current = -1;
                }
                paint();
                return;
            }
            if (live) {
                live = false;
                story.classList.remove('is-live');
                current = -1;
            }
            staticMode();
        }

        window.addEventListener('scroll', function () {
            if (!live || ticking) return;
            ticking = true;
            requestAnimationFrame(function () { ticking = false; paint(); });
        }, { passive: true });
        window.addEventListener('resize', syncMode);
        syncMode();

        dots.forEach(function (dot) {
            dot.addEventListener('click', function () {
                var i = parseInt(dot.getAttribute('data-goto'), 10) || 0;
                if (!live) {
                    if (panels[i]) panels[i].scrollIntoView({ behavior: 'auto', block: 'start' });
                    return;
                }
                var span = track.offsetHeight - window.innerHeight;
                var top = track.getBoundingClientRect().top + window.pageYOffset;
                window.scrollTo({ top: top + ((i + 0.5) / total) * span, behavior: 'smooth' });
            });
        });
    }

    var reveals = Array.prototype.slice.call(document.querySelectorAll('.reveal'));
    if (!('IntersectionObserver' in window) || reduceMotion) {
        reveals.forEach(function (el) { el.classList.add('in'); });
    } else {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
        reveals.forEach(function (el) { io.observe(el); });
    }

    var mapIntro = document.getElementById('mapIntro');
    var mainEl = document.getElementById('main');

    if (mapIntro && !reduceMotion) {
        var introOpened = false;

        var stopScroll = function (e) { e.preventDefault(); };
        var stopScrollKeys = function (e) {
            var k = e.key;
            if (k === 'ArrowDown' || k === 'ArrowUp' || k === 'ArrowLeft' || k === 'ArrowRight' ||
                k === 'PageDown' || k === 'PageUp' || k === 'Home' || k === 'End' || k === ' ') {
                e.preventDefault();
            }
        };
        window.addEventListener('wheel', stopScroll, { passive: false });
        window.addEventListener('touchmove', stopScroll, { passive: false });
        window.addEventListener('keydown', stopScrollKeys, { passive: false });

        var releaseScroll = function () {
            window.removeEventListener('wheel', stopScroll, { passive: false });
            window.removeEventListener('touchmove', stopScroll, { passive: false });
            window.removeEventListener('keydown', stopScrollKeys, { passive: false });
        };

        mapIntro.classList.add('is-active');
        if (mainEl) mainEl.classList.add('intro-pending');

        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                mapIntro.classList.add('is-loading');
            });
        });

        var openIntro = function () {
            if (introOpened) return;
            introOpened = true;
            mapIntro.classList.add('is-opening');
            if (mainEl) mainEl.classList.add('intro-revealing');

            window.setTimeout(function () {
                mapIntro.classList.add('is-done');
                if (mainEl) mainEl.classList.remove('intro-pending', 'intro-revealing');
                releaseScroll();
            }, 1050);
        };

        window.setTimeout(openIntro, 950);
        mapIntro.addEventListener('click', openIntro, { once: true });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' || e.key === ' ' || e.key === 'Enter') {
                openIntro();
            }
        }, { once: true });
    } else if (mapIntro) {
        mapIntro.classList.add('is-done');
    }
})();
</script>

</body>
</html>
