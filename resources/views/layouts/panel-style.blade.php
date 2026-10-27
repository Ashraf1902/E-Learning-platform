<style>
    /* ============================================================
       Shared panel design system — admin + student portal
       Operate mode: restrained teal accent, Noto Kufi + IBM Plex, tight rem scale
       ============================================================ */

    :root {
        --radius: 16px;
        --radius-sm: 10px;
        --radius-xs: 8px;
        --font-display: 'Noto Kufi Arabic', system-ui, sans-serif;
        --font-sans: 'IBM Plex Sans Arabic', system-ui, sans-serif;
        --font-mono: 'JetBrains Mono', ui-monospace, monospace;
    }

    [data-theme="dark"] {
        --bg:       #0b1118;
        --surface:  #121c26;
        --surface2: #17222e;
        --surface3: #1d2936;
        --border:   #26323d;
        --border2:  #3a4854;
        --gold:     #5fb3a4;
        --gold2:    #7ac7b9;
        --gold3:    #3f8d80;
        --accent-rgb: 95,179,164;
        --on-gold:  #07120f;
        --red:      #d2715a;
        --green:    #6cc49a;
        --amber:    #e0b25c;
        --blue:     #7aa8e8;
        --text:     #f2ebdd;
        --muted:    #938975;
        --muted2:   #c6bda9;
        --shadow:   0 1px 2px rgba(0,0,0,0.4);
    }

    [data-theme="light"] {
        --bg:       #f7f3e9;
        --surface:  #fffdf7;
        --surface2: #f0e9d9;
        --surface3: #e8dfcc;
        --border:   #ded4bf;
        --border2:  #c7b99d;
        --gold:     #1c6a5d;
        --gold2:    #145247;
        --gold3:    #0f3f37;
        --accent-rgb: 28,106,93;
        --on-gold:  #fbf7ec;
        --red:      #a33b2a;
        --green:    #256a43;
        --amber:    #8a5f0c;
        --blue:     #2b5c8a;
        --text:     #1a1712;
        --muted:    #6b604e;
        --muted2:   #514a3d;
        --shadow:   0 1px 2px rgba(42,33,18,0.07);
    }

    html { overflow-x: clip; -webkit-text-size-adjust: 100%; }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        background: var(--bg);
        color: var(--text);
        font-family: var(--font-sans);
        line-height: 1.55;
        min-height: 100vh;
        display: flex;
        text-rendering: optimizeLegibility;
        -webkit-font-smoothing: antialiased;
        transition: background 0.3s, color 0.3s;
    }

    ::selection { background: rgba(var(--accent-rgb),0.26); color: var(--text); }
    * { scrollbar-width: thin; scrollbar-color: var(--border2) transparent; }
    ::-webkit-scrollbar { width: 8px; height: 8px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: var(--border2); border-radius: 999px; }

    :where(a, button, input, select, textarea, summary):focus-visible {
        outline: 2px solid var(--gold);
        outline-offset: 2px;
    }

    h1, h2, h3 { text-wrap: balance; }
    h1, h2, h3, .card-title, .stat-value, .podium-name, .logo-name { font-family: var(--font-display); }
    p { text-wrap: pretty; }

    /* ===== SIDEBAR ===== */
    .sidebar {
        width: 260px;
        background: var(--surface);
        border-inline-end: 1px solid var(--border);
        display: flex; flex-direction: column;
        position: fixed; inset-block: 0; inset-inline-start: 0;
        z-index: 100; overflow: hidden;
        transition: background 0.3s, border-color 0.3s;
    }
    .sidebar-logo {
        display: flex; align-items: center; gap: 12px;
        padding: 18px 20px 16px;
        border-bottom: 1px solid var(--border);
    }
    .logo-icon {
        width: 40px; height: 40px; flex-shrink: 0;
        border-radius: 11px;
        display: grid; place-items: center;
        background: var(--surface2);
        border: 1px solid var(--border2);
        overflow: hidden;
        transition: border-color 0.2s;
    }
    a.logo-icon:hover, .logo-icon:hover { border-color: var(--gold); }
    .logo-icon img { width: 100%; height: 100%; object-fit: cover; }
    .brand-text { min-width: 0; }
    .logo-name { font-size: 0.92rem; font-weight: 800; line-height: 1.35; color: var(--text); }
    .logo-name span { color: var(--gold); }
    .sidebar-logo small {
        display: block; color: var(--muted);
        font-size: 0.7rem; font-family: var(--font-mono);
        text-transform: uppercase; letter-spacing: 0.14em; margin-top: 1px;
    }
    .sidebar-nav { flex: 1; padding: 8px 10px 14px; overflow-y: auto; overscroll-behavior: contain; }
    .nav-section {
        padding: 16px 12px 6px;
        font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.14em;
        color: var(--muted); font-family: var(--font-mono); font-weight: 500;
    }
    .nav-section:first-child { padding-top: 8px; }
    .nav-link {
        position: relative;
        display: flex; align-items: center; gap: 10px;
        min-height: 40px; padding: 8px 12px; margin: 1px 0;
        border-radius: var(--radius-xs);
        color: var(--muted2); text-decoration: none;
        font-size: 0.875rem; font-weight: 600;
        transition: background 0.15s, color 0.15s;
    }
    .nav-link::before {
        content: '';
        position: absolute; inset-inline-start: 0; top: 50%;
        translate: 0 -50%;
        width: 3px; height: 0; border-radius: 999px;
        background: var(--gold);
        transition: height 0.18s ease;
    }
    .nav-link:hover { color: var(--text); background: var(--surface2); }
    .nav-link.active { color: var(--gold); background: rgba(var(--accent-rgb),0.09); }
    .nav-link.active::before { height: 16px; }
    .nav-link > .icon { width: 18px; height: 18px; display: grid; place-items: center; flex-shrink: 0; opacity: 0.9; }
    .nav-link > .icon svg { width: 18px; height: 18px; }
    .nav-lock { margin-inline-start: auto; display: flex; align-items: center; color: var(--muted); font-size: 0.8rem; }
    .nav-lock .icon { width: 13px; height: 13px; }
    .sidebar-footer {
        padding: 14px 20px 18px;
        border-top: 1px solid var(--border);
        display: flex; flex-direction: column; gap: 2px;
    }
    .user-name {
        color: var(--text); font-weight: 700; font-size: 0.85rem;
        overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }
    .role-badge {
        align-self: flex-start;
        background: transparent; color: var(--muted);
        border: 1px solid var(--border2); border-radius: 999px;
        font-size: 0.7rem; padding: 2px 8px; margin-top: 2px;
    }
    .logout-btn {
        display: inline-flex; align-items: center; gap: 6px;
        margin-top: 10px; padding: 0; background: none; border: 0;
        color: var(--muted); cursor: pointer;
        font-size: 0.78rem; font-family: var(--font-sans); font-weight: 600;
        transition: color 0.15s;
    }
    .logout-btn:hover { color: var(--red); }
    .logout-btn .icon { width: 15px; height: 15px; }

    /* ===== MAIN / TOPBAR ===== */
    .main-content {
        margin-inline-start: 260px;
        flex: 1; display: flex; flex-direction: column;
        min-height: 100vh; min-width: 0;
    }
    .topbar {
        background: var(--surface);
        border-bottom: 1px solid var(--border);
        padding-inline: clamp(16px, 2.4vw, 32px);
        height: 60px;
        display: flex; align-items: center; justify-content: space-between; gap: 12px;
        position: sticky; top: 0; z-index: 50;
        transition: background 0.3s;
    }
    .topbar-title { display: flex; align-items: center; gap: 10px; min-width: 0; flex: 1; }
    .topbar h1 {
        font-size: 1rem; font-weight: 700; letter-spacing: -0.01em;
        overflow: hidden; text-overflow: ellipsis; white-space: nowrap; min-width: 0;
    }
    .topbar-right { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }

    .theme-toggle {
        width: 36px; height: 36px; flex-shrink: 0;
        border-radius: var(--radius-xs);
        background: var(--surface2); border: 1px solid var(--border2);
        color: var(--muted2); cursor: pointer;
        display: grid; place-items: center;
        transition: color 0.15s, border-color 0.15s;
    }
    .theme-toggle:hover { color: var(--text); border-color: var(--muted); }
    .theme-toggle svg { width: 17px; height: 17px; }
    .theme-toggle .icon-sun { display: none; }
    [data-theme="light"] .theme-toggle .icon-sun { display: flex; }
    [data-theme="light"] .theme-toggle .icon-moon { display: none; }

    .lang-switcher {
        display: flex; border: 1px solid var(--border2);
        border-radius: var(--radius-xs); overflow: hidden; background: var(--surface2);
    }
    .lang-btn {
        display: grid; place-items: center;
        min-width: 34px; height: 34px; padding: 0 8px;
        font-size: 0.72rem; font-weight: 700; line-height: 1;
        color: var(--muted2); text-decoration: none;
        transition: background 0.15s, color 0.15s;
    }
    .lang-btn:hover { color: var(--text); }
    .lang-btn.active { background: var(--gold); color: var(--on-gold); }

    .user-chip {
        display: flex; align-items: center; gap: 8px;
        padding-inline-start: 6px; min-width: 0;
        font-size: 0.8rem; color: var(--muted2);
    }
    .user-chip .name {
        max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        font-weight: 600;
    }
    .avatar {
        width: 30px; height: 30px; flex-shrink: 0;
        border-radius: 50%; display: grid; place-items: center;
        font-size: 0.72rem; font-weight: 800; color: #0b1118;
        border: 1px solid rgba(0,0,0,0.12);
    }

    /* ===== NOTIFICATIONS ===== */
    .sr-only {
        position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px;
        overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border: 0;
    }
    .notif-wrap { position: relative; flex-shrink: 0; }
    .notif-btn {
        position: relative;
        width: 36px; height: 36px; flex-shrink: 0;
        border-radius: var(--radius-xs);
        background: var(--surface2); border: 1px solid var(--border2);
        color: var(--muted2); cursor: pointer;
        display: grid; place-items: center;
        transition: color .15s, border-color .15s;
    }
    .notif-btn:hover, .notif-btn[aria-expanded="true"] { color: var(--text); border-color: var(--gold); }
    .notif-btn svg { width: 17px; height: 17px; }
    .notif-badge {
        position: absolute; top: -5px; inset-inline-end: -5px;
        min-width: 18px; height: 18px; padding: 0 4px;
        border-radius: 999px;
        background: var(--red); color: #fff;
        border: 2px solid var(--surface);
        font-family: var(--font-sans);
        font-size: 0.7rem; font-weight: 800; line-height: 1;
        display: grid; place-items: center;
        font-variant-numeric: tabular-nums;
    }
    .notif-badge.is-empty, .notif-badge[hidden] { display: none; }

    .notif-panel {
        position: absolute; z-index: 80;
        inset-block-start: calc(100% + 12px); inset-inline-end: 0;
        width: min(372px, calc(100vw - 32px));
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        box-shadow: 0 22px 55px rgba(42,33,18,0.18);
        overflow: hidden;
        animation: notifIn .16s ease-out;
    }
    [data-theme="dark"] .notif-panel { box-shadow: 0 22px 55px rgba(0,0,0,0.55); }
    .notif-panel[hidden] { display: none; }
    @keyframes notifIn {
        from { opacity: 0; transform: translateY(-4px); }
        to   { opacity: 1; transform: none; }
    }

    .notif-head {
        display: flex; align-items: center; justify-content: space-between; gap: 8px;
        flex-wrap: wrap;
        padding: 12px 14px; border-bottom: 1px solid var(--border);
        background: var(--surface);
    }
    .notif-heading {
        display: flex; align-items: center; gap: 7px;
        font-size: 0.82rem; font-weight: 800; color: var(--text);
    }
    .notif-heading svg { width: 15px; height: 15px; color: var(--gold); }
    .notif-head-actions { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
    .notif-unread {
        font-size: 0.7rem; font-weight: 700; color: var(--gold);
        background: rgba(var(--accent-rgb),0.1);
        border: 1px solid rgba(var(--accent-rgb),0.22);
        border-radius: 999px; padding: 2px 8px; white-space: nowrap;
        font-variant-numeric: tabular-nums;
    }
    .notif-unread[hidden], .notif-mark-all[hidden] { display: none; }
    .notif-mark-all {
        border: 0; background: none; padding: 2px 0;
        color: var(--gold); cursor: pointer;
        font-family: var(--font-sans); font-size: 0.68rem; font-weight: 700;
        white-space: nowrap; text-decoration: underline;
        text-underline-offset: 3px;
    }
    .notif-mark-all:hover { color: var(--text); }
    .notif-mark-all:disabled { opacity: 0.55; cursor: wait; }
    .notif-list { max-height: min(56vh, 430px); overflow-y: auto; overscroll-behavior: contain; }
    .notif-item {
        display: flex; align-items: flex-start; gap: 10px;
        padding: 11px 14px;
        border-bottom: 1px solid var(--border);
        text-decoration: none; color: inherit;
        transition: background .15s;
    }
    .notif-item:last-child { border-bottom: 0; }
    .notif-item:hover { background: var(--surface2); }
    .notif-item.is-new { background: rgba(var(--accent-rgb),0.055); }
    .notif-item.is-new:hover { background: rgba(var(--accent-rgb),0.09); }
    .notif-ico {
        width: 32px; height: 32px; flex-shrink: 0;
        border-radius: 9px;
        display: grid; place-items: center;
        background: var(--surface2); color: var(--muted2);
    }
    .notif-ico svg { width: 16px; height: 16px; }
    .notif-ico.tone-gold { background: rgba(var(--accent-rgb),0.12); color: var(--gold); }
    .notif-ico.tone-green { background: rgba(37,106,67,0.12); color: var(--green); }
    .notif-ico.tone-red { background: rgba(163,59,42,0.12); color: var(--red); }
    .notif-ico.tone-amber { background: rgba(138,95,12,0.12); color: var(--amber); }
    .notif-ico.tone-blue { background: rgba(43,92,138,0.12); color: var(--blue); }
    [data-theme="dark"] .notif-ico.tone-green { background: rgba(108,196,154,0.15); }
    [data-theme="dark"] .notif-ico.tone-red { background: rgba(210,113,90,0.15); }
    [data-theme="dark"] .notif-ico.tone-amber { background: rgba(224,178,92,0.15); }
    [data-theme="dark"] .notif-ico.tone-blue { background: rgba(122,168,232,0.15); }

    .notif-copy { display: flex; flex-direction: column; gap: 2px; min-width: 0; flex: 1; }
    .notif-item-title { font-size: 0.82rem; font-weight: 700; color: var(--text); line-height: 1.45; }
    .notif-item-body {
        font-size: 0.76rem; color: var(--muted2); line-height: 1.55;
        overflow-wrap: anywhere;
    }
    .notif-item-time {
        font-family: var(--font-mono); font-size: 0.72rem; color: var(--muted);
        margin-top: 2px; letter-spacing: 0.01em;
    }
    .notif-dot {
        width: 7px; height: 7px; border-radius: 50%;
        background: var(--gold); flex-shrink: 0; margin-top: 6px;
        box-shadow: 0 0 0 3px rgba(var(--accent-rgb),0.12);
    }
    .notif-empty { padding: 30px 22px; text-align: center; }
    .notif-empty-icon {
        width: 42px; height: 42px; margin: 0 auto 10px;
        border-radius: 50%; display: grid; place-items: center;
        background: var(--surface2); color: var(--muted);
    }
    .notif-empty-icon svg { width: 18px; height: 18px; }
    .notif-empty-title { font-size: 0.85rem; font-weight: 700; color: var(--text); }
    .notif-empty-body { font-size: 0.76rem; color: var(--muted); margin-top: 3px; }
    .notif-foot {
        display: flex; align-items: center; justify-content: space-between; gap: 10px;
        padding: 8px 14px; border-top: 1px solid var(--border);
        font-size: 0.7rem; color: var(--muted);
        background: var(--surface);
    }
    .notif-foot a {
        color: var(--gold); font-weight: 700; text-decoration: none;
        white-space: nowrap;
    }
    .notif-foot a:hover { color: var(--text); text-decoration: underline; text-underline-offset: 3px; }

    .notif-page { width: 100%; max-width: 920px; margin-inline: auto; }
    .notif-page-head {
        display: flex; align-items: flex-start; justify-content: space-between;
        gap: 16px; flex-wrap: wrap; margin-bottom: 16px;
    }
    .notif-page-title {
        display: flex; align-items: center; gap: 10px;
        font-size: 1.03rem; font-weight: 800; color: var(--text);
    }
    .notif-page-icon {
        width: 36px; height: 36px; flex-shrink: 0;
        display: grid; place-items: center; border-radius: 10px;
        background: rgba(var(--accent-rgb),0.11); color: var(--gold);
    }
    .notif-page-icon svg { width: 18px; height: 18px; }
    .notif-page-sub { margin-top: 5px; font-size: 0.79rem; color: var(--muted); line-height: 1.6; }
    .notif-page-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
    .notif-mark-all-page {
        padding: 6px 10px; border: 1px solid rgba(var(--accent-rgb),0.25);
        border-radius: 999px; background: rgba(var(--accent-rgb),0.07);
        text-decoration: none;
    }
    .notif-mark-all-page:hover { background: rgba(var(--accent-rgb),0.12); }
    .notif-tabs { display: flex; align-items: center; gap: 6px; margin-bottom: 12px; }
    .notif-tab {
        padding: 6px 14px; border-radius: 999px;
        border: 1px solid var(--border);
        background: var(--surface); color: var(--muted2);
        font-size: 0.77rem; font-weight: 700; text-decoration: none;
        transition: color .15s, border-color .15s, background .15s;
    }
    .notif-tab:hover { color: var(--text); border-color: var(--gold); }
    .notif-tab.active {
        background: var(--gold); border-color: var(--gold);
        color: var(--surface);
    }
    .notif-page-card {
        background: var(--surface); border: 1px solid var(--border);
        border-radius: var(--radius-sm); overflow: hidden;
        box-shadow: 0 2px 8px rgba(42,33,18,0.04);
    }
    [data-theme="dark"] .notif-page-card { box-shadow: 0 2px 10px rgba(0,0,0,0.22); }
    .notif-list-page { max-height: none; overflow: visible; }
    .notif-list-page .notif-item { padding: 13px 16px; }
    .notif-list-page .notif-item-title { font-size: 0.86rem; }
    .notif-list-page .notif-item-body { font-size: 0.78rem; }
    .notif-empty-page { padding: 52px 22px; }

    @media (max-width: 520px) {
        .notif-panel {
            position: fixed;
            inset-block-start: 62px;
            inset-inline: 12px;
            width: auto;
            max-height: calc(100vh - 76px);
        }
        .notif-list { max-height: calc(100vh - 190px); }
    }

    .page-body {
        width: 100%; max-width: 1400px;
        margin-inline: auto;
        padding: clamp(18px, 2.2vw, 28px) clamp(16px, 2.4vw, 32px) 48px;
        flex: 1; min-width: 0;
    }

    /* ===== ALERTS ===== */
    .alert {
        display: flex; align-items: flex-start; gap: 9px;
        padding: 11px 14px; margin-bottom: 18px;
        border: 1px solid transparent; border-radius: var(--radius-sm);
        font-size: 0.85rem; font-weight: 600; line-height: 1.6;
    }
    .alert .icon { width: 16px; height: 16px; margin-top: 3px; flex-shrink: 0; }
    .alert-success { background: rgba(62,201,122,0.09); border-color: rgba(62,201,122,0.22); color: var(--green); }
    .alert-error   { background: rgba(224,85,85,0.09);  border-color: rgba(224,85,85,0.22);  color: var(--red); }
    .alert-info    { background: rgba(var(--accent-rgb),0.08); border-color: rgba(var(--accent-rgb),0.2);  color: var(--gold2); }
    [data-theme="light"] .alert-info { color: var(--gold); }

    /* ===== CARDS ===== */
    .card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px 22px;
        min-width: 0;
        transition: border-color 0.2s, background 0.3s;
    }
    .card-flush { padding: 0; overflow: hidden; }
    .card-header {
        display: flex; align-items: center; justify-content: space-between;
        gap: 12px; margin-bottom: 16px;
    }
    .card-title { font-size: 0.95rem; font-weight: 800; display: flex; align-items: center; gap: 8px; }
    .card-title .icon { width: 17px; height: 17px; color: var(--muted); flex-shrink: 0; }

    .section-head {
        display: flex; align-items: center; justify-content: space-between;
        gap: 12px; margin-bottom: 12px;
    }
    .section-title { font-size: 0.95rem; font-weight: 800; display: flex; align-items: center; gap: 8px; }
    .section-title .icon { width: 16px; height: 16px; color: var(--muted); flex-shrink: 0; }

    /* ===== METRIC STRIP ===== */
    .stat-strip {
        display: grid;
        grid-template-columns: repeat(var(--stat-cols, 4), minmax(0, 1fr));
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        margin-bottom: 20px;
        overflow: hidden;
    }
    .stat-cell {
        display: flex; align-items: center; gap: 12px;
        padding: 16px 20px; min-width: 0;
    }
    .stat-cell + .stat-cell { border-inline-start: 1px solid var(--border); }
    .stat-cell-icon {
        width: 38px; height: 38px; flex-shrink: 0;
        border-radius: 10px;
        display: grid; place-items: center;
        background: var(--surface2); border: 1px solid var(--border);
        color: var(--gold);
    }
    .stat-cell-icon .icon { width: 17px; height: 17px; }
    .stat-cell-label { font-size: 0.74rem; font-weight: 600; color: var(--muted); line-height: 1.4; }
    .stat-cell-value {
        font-size: 1.45rem; font-weight: 800; line-height: 1.15;
        letter-spacing: -0.02em; font-variant-numeric: tabular-nums;
    }
    .stat-cell-note { font-size: 0.7rem; color: var(--muted); }

    /* ===== TOOLBAR / FILTER CHIPS ===== */
    .toolbar {
        display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
        background: var(--surface); border: 1px solid var(--border);
        border-radius: var(--radius); padding: 10px 14px; margin-bottom: 16px;
    }
    .toolbar-label { font-size: 0.78rem; font-weight: 600; color: var(--muted); margin-inline-end: 2px; }
    .toolbar .spacer { flex: 1; }
    @media (max-width: 560px) { .toolbar .spacer { display: none; } }

    .chip {
        display: inline-flex; align-items: center; gap: 5px;
        min-height: 30px; padding: 5px 12px;
        border-radius: 999px; border: 1px solid var(--border2);
        background: transparent; color: var(--muted2);
        font-size: 0.78rem; font-weight: 700;
        text-decoration: none; white-space: nowrap;
        transition: background 0.15s, border-color 0.15s, color 0.15s;
    }
    .chip:hover { color: var(--text); border-color: var(--muted); background: var(--surface2); }
    .chip.active {
        background: rgba(var(--accent-rgb),0.1);
        border-color: rgba(var(--accent-rgb),0.3);
        color: var(--gold2);
    }
    [data-theme="light"] .chip.active { color: var(--gold); }

    /* ===== DASHBOARD LAYOUT ===== */
    .dash-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.3fr) minmax(0, 1fr);
        gap: 18px; align-items: start;
    }
    @media (max-width: 1000px) { .dash-grid { grid-template-columns: minmax(0, 1fr); } }

    /* ===== DATA LISTS ===== */
    .data-list { display: flex; flex-direction: column; }
    .data-row {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 8px; margin-inline: -8px;
        border-top: 1px solid var(--border);
        border-radius: var(--radius-xs);
        color: inherit; text-decoration: none;
        transition: background 0.12s;
    }
    .data-row:first-child { border-top: none; }
    .data-row:hover { background: var(--surface2); }
    .data-main { min-width: 0; flex: 1; }
    .data-title {
        display: block; font-size: 0.86rem; font-weight: 700; color: var(--text);
        overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }
    a.data-title:hover { color: var(--gold); }
    .data-meta {
        display: block; margin-top: 1px;
        font-size: 0.75rem; color: var(--muted);
        overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }
    .data-side { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
    .data-icon {
        width: 34px; height: 34px; flex-shrink: 0;
        border-radius: 9px; display: grid; place-items: center;
        background: var(--surface2); border: 1px solid var(--border);
        color: var(--muted2);
    }
    .data-icon .icon { width: 16px; height: 16px; }

    /* ===== LEVEL BARS ===== */
    .level-row {
        display: block; padding: 12px 8px; margin-inline: -8px;
        border-top: 1px solid var(--border);
        border-radius: var(--radius-xs);
        color: inherit; text-decoration: none;
        transition: background 0.12s;
    }
    .level-row:first-child { border-top: none; }
    .level-row:hover { background: var(--surface2); }
    .level-row:hover .level-name { color: var(--gold); }
    .level-head {
        display: flex; align-items: baseline; justify-content: space-between;
        gap: 10px; margin-bottom: 7px;
    }
    .level-name { font-size: 0.86rem; font-weight: 700; color: var(--text); transition: color 0.15s; }
    .level-count { font-size: 0.76rem; color: var(--muted); font-variant-numeric: tabular-nums; }
    .level-track { height: 5px; border-radius: 999px; background: var(--surface2); overflow: hidden; }
    .level-fill { height: 100%; min-width: 2px; border-radius: inherit; background: var(--gold); }

    .progress { height: 5px; border-radius: 999px; background: var(--surface2); overflow: hidden; flex: 1; }
    .progress-fill { height: 100%; border-radius: inherit; background: var(--gold); }
    .progress-fill.is-pass { background: var(--green); }
    .progress-fill.is-fail { background: var(--red); }
    .score-text { font-weight: 700; font-variant-numeric: tabular-nums; }
    .score-text.is-pass { color: var(--green); }
    .score-text.is-mid { color: var(--amber); }
    .score-text.is-fail { color: var(--red); }
    tbody tr.is-me { background: rgba(var(--accent-rgb),0.06); }

    /* ===== STATUS CTA PANEL ===== */
    .cta-card {
        display: flex; align-items: center; gap: 14px; flex-wrap: wrap;
        background: var(--surface); border: 1px solid var(--border);
        border-radius: var(--radius); padding: 16px 18px; margin-bottom: 18px;
    }
    .cta-card.is-gold { border-color: rgba(var(--accent-rgb),0.28); background: rgba(var(--accent-rgb),0.045); }
    .cta-card.is-ok { border-color: rgba(62,201,122,0.25); background: rgba(62,201,122,0.035); }
    .cta-icon {
        width: 40px; height: 40px; flex-shrink: 0;
        border-radius: 10px; display: grid; place-items: center;
        background: rgba(var(--accent-rgb),0.1); border: 1px solid rgba(var(--accent-rgb),0.22);
        color: var(--gold);
    }
    .is-ok .cta-icon { background: rgba(62,201,122,0.1); border-color: rgba(62,201,122,0.24); color: var(--green); }
    .cta-icon .icon { width: 18px; height: 18px; }
    .cta-body { flex: 1; min-width: 200px; }
    .cta-title { font-size: 0.92rem; font-weight: 800; color: var(--text); }
    .is-gold .cta-title { color: var(--gold); }
    .is-ok .cta-title { color: var(--green); }
    .cta-text { font-size: 0.8rem; color: var(--muted); margin-top: 2px; line-height: 1.6; }
    .cta-actions { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }

    /* ===== LINK CARD ===== */
    .link-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 14px; }
    .link-card {
        display: flex; align-items: center; gap: 13px;
        background: var(--surface); border: 1px solid var(--border);
        border-radius: var(--radius); padding: 16px 18px;
        color: inherit; text-decoration: none;
        transition: border-color 0.18s, background 0.18s;
    }
    .link-card:hover { border-color: var(--border2); background: var(--surface); }
    .link-card:hover .link-card-arrow { color: var(--gold); }
    .link-card-icon {
        width: 42px; height: 42px; flex-shrink: 0;
        border-radius: 11px; display: grid; place-items: center;
        background: var(--surface2); border: 1px solid var(--border);
        color: var(--gold);
    }
    .link-card-icon .icon { width: 18px; height: 18px; }
    .link-card-body { flex: 1; min-width: 0; }
    .link-card-title {
        font-size: 0.9rem; font-weight: 700; color: var(--text);
        overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }
    .link-card-meta { font-size: 0.76rem; color: var(--muted); margin-top: 2px; }
    .link-card-side { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
    .link-card-arrow { display: flex; color: var(--muted); transition: color 0.18s; }
    .link-card-arrow .icon { width: 17px; height: 17px; }

    /* ===== LECTURE / QUIZ CARDS (portal) ===== */
    .lecture-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px; }
    .lecture-card {
        display: flex; flex-direction: column; gap: 10px;
        background: var(--surface); border: 1px solid var(--border);
        border-radius: var(--radius); padding: 18px;
        color: inherit; text-decoration: none;
        transition: border-color 0.18s;
    }
    .lecture-card:hover { border-color: var(--border2); }
    .lecture-card:hover .lecture-card-cta { color: var(--gold); }
    .lecture-card-top { display: flex; align-items: center; justify-content: space-between; gap: 8px; }
    .lecture-card-icon {
        width: 38px; height: 38px; flex-shrink: 0;
        border-radius: 10px; display: grid; place-items: center;
        background: var(--surface2); border: 1px solid var(--border);
        color: var(--gold);
    }
    .lecture-card-icon .icon { width: 17px; height: 17px; }
    .lecture-card-title { font-size: 0.95rem; font-weight: 800; color: var(--text); line-height: 1.45; }
    .lecture-card-meta {
        display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
        font-size: 0.76rem; color: var(--muted);
    }
    .lecture-card-foot {
        display: flex; align-items: center; justify-content: space-between; gap: 8px;
        margin-top: auto; padding-top: 10px; border-top: 1px solid var(--border);
    }
    .lecture-card-cta {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 0.8rem; font-weight: 700; color: var(--muted2);
        transition: color 0.15s;
    }
    .lecture-card-score { font-size: 0.8rem; font-weight: 700; font-variant-numeric: tabular-nums; }
    .lecture-card-score.is-pass { color: var(--green); }
    .lecture-card-score.is-fail { color: var(--red); }

    /* ===== PLANS ===== */
    .plan-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; }
    .plan-card {
        display: flex; flex-direction: column; gap: 12px;
        background: var(--surface); border: 1px solid var(--border);
        border-radius: var(--radius); padding: 20px;
    }
    .plan-card.is-current { border-color: rgba(62,201,122,0.35); }
    .plan-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 8px; }
    .plan-name { font-size: 0.95rem; font-weight: 800; }
    .plan-desc { font-size: 0.8rem; color: var(--muted); line-height: 1.6; }
    .plan-price { display: flex; align-items: baseline; gap: 6px; flex-wrap: wrap; margin-top: auto; }
    .plan-amount { font-size: 1.8rem; font-weight: 800; line-height: 1; font-variant-numeric: tabular-nums; }
    .plan-currency { font-size: 0.8rem; color: var(--muted); }
    .plan-period { font-size: 0.75rem; color: var(--muted); }

    /* ===== PAYMENT MODAL ===== */
    .modal {
        display: none; position: fixed; inset: 0; z-index: 200;
        background: rgba(0,0,0,0.72);
        align-items: center; justify-content: center;
        padding: 20px;
    }
    .modal.is-open { display: flex; }
    .modal-card {
        background: var(--surface); border: 1px solid var(--border);
        border-radius: var(--radius); padding: 22px;
        width: 100%; max-width: 480px;
        max-height: calc(100vh - 40px); overflow-y: auto;
    }
    .modal-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 16px; }
    .modal-title { font-size: 1rem; font-weight: 800; }
    .modal-close {
        width: 32px; height: 32px; border-radius: var(--radius-xs);
        background: transparent; border: 1px solid transparent;
        color: var(--muted); cursor: pointer;
        display: grid; place-items: center;
    }
    .modal-close:hover { background: var(--surface2); color: var(--text); border-color: var(--border); }
    .modal-close .icon { width: 16px; height: 16px; }
    .pay-box { background: var(--surface2); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 12px 14px; margin-bottom: 14px; }
    .pay-label { font-size: 0.75rem; color: var(--muted); margin-bottom: 2px; }
    .pay-amount { font-size: 1.6rem; font-weight: 800; color: var(--gold); line-height: 1.2; font-variant-numeric: tabular-nums; }
    .pay-steps { font-size: 0.82rem; color: var(--muted2); line-height: 1.8; padding-inline-start: 18px; margin: 0 0 16px; }
    .pay-steps strong { color: var(--text); }
    .pay-number {
        font-family: var(--font-mono); font-weight: 700; color: var(--gold);
        direction: ltr; unicode-bidi: embed; letter-spacing: 0.04em;
    }

    /* ===== PODIUM ===== */
    .podium {
        display: grid; grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px; align-items: end; margin-bottom: 24px;
    }
    .podium-card {
        display: flex; flex-direction: column; align-items: center; gap: 5px;
        background: var(--surface); border: 1px solid var(--border);
        border-radius: var(--radius); padding: 18px 14px; text-align: center;
    }
    .podium-card.place-2 { grid-column: 1; }
    .podium-card.place-1 { grid-column: 2; border-color: rgba(var(--accent-rgb),0.4); padding-block: 22px; }
    .podium-card.place-3 { grid-column: 3; }
    .podium-rank {
        font-family: var(--font-mono); font-size: 0.7rem; font-weight: 700;
        color: var(--muted); letter-spacing: 0.1em;
    }
    .podium-avatar {
        width: 48px; height: 48px; margin: 2px 0 4px;
        border-radius: 50%; display: grid; place-items: center;
        font-size: 1rem; font-weight: 800; color: var(--on-gold);
        background: var(--gold);
    }
    .place-1 .podium-avatar {
        width: 56px; height: 56px; font-size: 1.1rem;
        background: var(--gold); box-shadow: 0 0 0 3px rgba(var(--accent-rgb),0.18);
    }
    .podium-name {
        max-width: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        font-size: 0.86rem; font-weight: 700; color: var(--text);
    }
    .podium-score { font-size: 1.35rem; font-weight: 800; color: var(--gold); font-variant-numeric: tabular-nums; }
    .podium-sub { font-size: 0.72rem; color: var(--muted); }

    /* ===== RANK BADGES ===== */
    .rank-badge {
        display: inline-grid; place-items: center;
        width: 26px; height: 26px; border-radius: 50%;
        background: var(--surface2); border: 1px solid var(--border);
        color: var(--muted2); font-family: var(--font-mono);
        font-size: 0.7rem; font-weight: 700;
    }
    .rank-badge.rank-1 { background: rgba(var(--accent-rgb),0.14); border-color: rgba(var(--accent-rgb),0.35); color: var(--gold2); }
    .rank-badge.rank-2 { background: rgba(192,192,192,0.12); border-color: rgba(192,192,192,0.3); color: var(--muted2); }
    .rank-badge.rank-3 { background: rgba(205,127,50,0.12); border-color: rgba(205,127,50,0.3); color: #d99a5b; }
    [data-theme="light"] .rank-badge.rank-1 { color: var(--gold); }

    /* ===== TABLES ===== */
    .table-wrap { overflow-x: auto; min-width: 0; }
    table { width: 100%; border-collapse: collapse; font-size: 0.865rem; }
    thead th {
        text-align: start; padding: 9px 14px;
        color: var(--muted); background: var(--surface2);
        font-size: 0.7rem; font-weight: 700; font-family: var(--font-mono);
        text-transform: uppercase; letter-spacing: 0.08em; white-space: nowrap;
    }
    thead th:first-child { border-start-start-radius: 8px; border-end-start-radius: 8px; }
    thead th:last-child { border-start-end-radius: 8px; border-end-end-radius: 8px; }
    tbody td { padding: 12px 14px; border-bottom: 1px solid var(--border); vertical-align: middle; }
    tbody tr:last-child td { border-bottom: none; }
    tbody tr { transition: background 0.12s; }
    tbody tr:hover { background: var(--surface2); }
    .num { font-family: var(--font-mono); font-size: 0.76rem; color: var(--muted); font-variant-numeric: tabular-nums; }
    .cell-muted { color: var(--muted); }
    .cell-strong { font-weight: 700; }
    .cell-sub { display: block; font-size: 0.74rem; color: var(--muted); margin-top: 1px; }
    .cell-actions { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }

    /* ===== BADGES ===== */
    .badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 9px; border: 1px solid transparent; border-radius: 999px;
        font-size: 0.71rem; font-weight: 700; font-family: var(--font-mono);
        line-height: 1.5; white-space: nowrap; vertical-align: middle;
    }
    .badge .icon { width: 12px; height: 12px; }
    .badge-gold   { background: rgba(var(--accent-rgb),0.12); color: var(--gold2); border-color: rgba(var(--accent-rgb),0.22); }
    .badge-green  { background: rgba(62,201,122,0.1);  color: var(--green); border-color: rgba(62,201,122,0.2); }
    .badge-red    { background: rgba(224,85,85,0.1);   color: var(--red);   border-color: rgba(224,85,85,0.2); }
    .badge-blue   { background: rgba(91,147,240,0.1);  color: var(--blue);  border-color: rgba(91,147,240,0.2); }
    .badge-yellow { background: rgba(229,185,92,0.1);  color: var(--amber); border-color: rgba(229,185,92,0.22); }
    [data-theme="light"] .badge-gold { background: rgba(138,98,16,0.07); border-color: rgba(138,98,16,0.18); color: var(--gold); }

    /* ===== BUTTONS ===== */
    .btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 7px;
        min-height: 36px; padding: 8px 16px;
        border: 1px solid transparent; border-radius: var(--radius-sm);
        font-family: var(--font-sans); font-size: 0.83rem; font-weight: 700; line-height: 1.4;
        text-decoration: none; white-space: nowrap; cursor: pointer;
        transition: background 0.15s, border-color 0.15s, color 0.15s;
    }
    .btn .icon { width: 15px; height: 15px; }
    .btn:active { transform: translateY(0.5px); }
    .btn:disabled, .btn[disabled] { opacity: 0.55; cursor: not-allowed; }
    .btn-primary { background: var(--gold); color: var(--on-gold); }
    .btn-primary:hover { background: var(--gold2); }
    [data-theme="light"] .btn-primary:hover { background: var(--gold3); }
    .btn-success { background: rgba(62,201,122,0.12); color: var(--green); border-color: rgba(62,201,122,0.3); }
    .btn-success:hover { background: rgba(62,201,122,0.2); }
    .btn-danger { background: rgba(224,85,85,0.08); color: var(--red); border-color: rgba(224,85,85,0.25); }
    .btn-danger:hover { background: rgba(224,85,85,0.16); }
    .btn-ghost { background: transparent; color: var(--muted2); border-color: var(--border2); }
    .btn-ghost:hover { color: var(--text); border-color: var(--muted); background: var(--surface2); }
    .btn-sm { min-height: 28px; padding: 4px 10px; font-size: 0.76rem; border-radius: var(--radius-xs); gap: 5px; }
    .btn-sm .icon { width: 13px; height: 13px; }
    .btn-icon { width: 32px; height: 32px; min-height: 0; padding: 0; border-radius: var(--radius-xs); }
    .btn-icon .icon { width: 15px; height: 15px; }
    .btn-link {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 0; background: none; border: 0; cursor: pointer;
        color: var(--gold); font-family: var(--font-sans); font-size: 0.8rem; font-weight: 700;
        text-decoration: none;
    }
    .btn-link:hover { text-decoration: underline; text-underline-offset: 3px; }

    /* ===== FORMS ===== */
    .form-group { margin-bottom: 18px; }
    label {
        display: block; margin-bottom: 6px;
        font-size: 0.74rem; font-weight: 700; color: var(--muted2);
        font-family: var(--font-mono); text-transform: uppercase; letter-spacing: 0.07em;
    }
    input[type=text], input[type=email], input[type=password], input[type=tel],
    input[type=url], input[type=number], input[type=date], input[type=time],
    select, textarea {
        width: 100%; min-height: 38px; padding: 9px 13px;
        background: var(--surface2); color: var(--text);
        border: 1px solid var(--border2); border-radius: var(--radius-sm);
        font-family: var(--font-sans); font-size: 0.875rem; line-height: 1.5;
        outline: none; transition: border-color 0.15s, box-shadow 0.15s;
    }
    input::placeholder, textarea::placeholder { color: var(--muted); opacity: 0.85; }
    input:hover, select:hover, textarea:hover { border-color: var(--muted); }
    input:focus, select:focus, textarea:focus {
        border-color: var(--gold);
        box-shadow: 0 0 0 3px rgba(var(--accent-rgb),0.12);
    }
    [data-theme="light"] input:focus, [data-theme="light"] select:focus, [data-theme="light"] textarea:focus {
        box-shadow: 0 0 0 3px rgba(138,98,16,0.1);
    }
    textarea { min-height: 110px; resize: vertical; padding-block: 10px; }
    input[type=checkbox], input[type=radio] { accent-color: var(--gold); }
    .form-hint { font-size: 0.75rem; color: var(--muted); margin-top: 6px; line-height: 1.5; }
    .error-msg { font-size: 0.78rem; color: var(--red); margin-top: 5px; }

    /* ===== CODE INPUTS ===== */
    .code-chip {
        display: inline-block; padding: 2px 8px;
        background: var(--surface2); border: 1px solid var(--border); border-radius: 6px;
        font-family: var(--font-mono); font-size: 0.82rem; font-weight: 700;
        letter-spacing: 0.1em; direction: ltr; unicode-bidi: embed; color: var(--text);
    }
    .code-input {
        font-family: var(--font-mono); font-weight: 700;
        letter-spacing: 0.14em; text-transform: uppercase;
        direction: ltr; text-align: center; font-size: 1rem;
    }

    /* ===== QUIZ BUILDER ===== */
    .question-block { background: var(--surface2); border: 1px solid var(--border2); border-radius: var(--radius-sm); padding: 20px; margin-bottom: 16px; }
    .question-block .qnum { font-size: 0.72rem; font-family: var(--font-mono); color: var(--gold); margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.1em; }
    .answer-choice:hover, .answer-choice:focus-within { border-color: var(--gold); }

    /* ===== MISC ===== */
    .page-head {
        display: flex; align-items: flex-start; justify-content: space-between;
        gap: 12px; flex-wrap: wrap; margin-bottom: 20px;
    }
    .page-head h2 { font-size: 1.35rem; font-weight: 800; display: flex; align-items: center; gap: 9px; letter-spacing: -0.01em; }
    .page-head h2 .icon { width: 20px; height: 20px; color: var(--gold); }
    .page-head p { font-size: 0.83rem; color: var(--muted); margin-top: 4px; }
    .grid-2 { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px; }
    .mt-4 { margin-top: 16px; }
    .text-muted { color: var(--muted); }
    .divider { height: 1px; background: var(--border); margin: 18px 0; }
    .nowrap { white-space: nowrap; }

    .empty-state {
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        gap: 4px; padding: 40px 20px; text-align: center; color: var(--muted);
    }
    .empty-state .icon {
        width: 44px; height: 44px; margin-bottom: 8px;
        border-radius: 12px; display: grid; place-items: center;
        background: var(--surface2); border: 1px solid var(--border); color: var(--muted2);
    }
    .empty-state .icon .icon { width: 19px; height: 19px; }
    .empty-title { font-size: 0.92rem; font-weight: 700; color: var(--text); }
    .empty-hint { font-size: 0.8rem; max-width: 44ch; line-height: 1.6; }
    .empty-state .btn { margin-top: 10px; }

    /* ===== PAGINATION ===== */
    .pagination { margin-top: 16px; }
    .pagination-inner { display: flex; align-items: center; flex-wrap: wrap; gap: 4px; }
    .page-btn {
        display: grid; place-items: center;
        min-width: 32px; height: 32px; padding: 0 8px;
        border: 1px solid transparent; border-radius: var(--radius-xs);
        color: var(--muted2); font-size: 0.8rem; font-weight: 700;
        text-decoration: none; font-variant-numeric: tabular-nums;
        transition: background 0.15s, color 0.15s, border-color 0.15s;
    }
    .page-btn:hover { background: var(--surface2); color: var(--text); }
    .page-btn.is-active { background: rgba(var(--accent-rgb),0.12); border-color: rgba(var(--accent-rgb),0.28); color: var(--gold2); }
    [data-theme="light"] .page-btn.is-active { color: var(--gold); }
    .page-btn.is-disabled { opacity: 0.4; pointer-events: none; }
    .page-btn.is-gap { pointer-events: none; color: var(--muted); }
.page-info { font-size: 0.76rem; color: var(--muted); margin-inline-start: 8px; }
.page-btn .icon { width: 15px; height: 15px; }

    /* ===== MOBILE ===== */
    .mobile-overlay {
        display: none; position: fixed; inset: 0; z-index: 98;
        background: rgba(0,0,0,0.75);
        backdrop-filter: blur(2px); -webkit-backdrop-filter: blur(2px);
    }
    .mobile-overlay.active { display: block; }

    .hamburger-btn {
        display: none; width: 36px; height: 36px; flex-shrink: 0;
        border-radius: var(--radius-xs);
        background: var(--surface2); border: 1px solid var(--border2);
        color: var(--text); font-size: 1.2rem; cursor: pointer;
        place-items: center;
    }
    .hamburger-btn .icon { width: 18px; height: 18px; }
    .hamburger-btn:hover { border-color: var(--muted); }

    @media (max-width: 900px) {
        .sidebar {
            width: 280px;
            transform: translateX(100%);
            border-inline-end: none;
            border-inline-start: 1px solid var(--border);
            box-shadow: -8px 0 28px rgba(0,0,0,0.35);
            transition: transform 0.3s ease;
        }
        [dir="ltr"] .sidebar { transform: translateX(-100%); box-shadow: 8px 0 28px rgba(0,0,0,0.35); }
        .sidebar.open { transform: none; }

        .main-content { margin-inline-start: 0 !important; }
        .hamburger-btn { display: grid; }
        .topbar { height: 56px; padding-inline: 16px; gap: 10px; }
        .topbar h1 { font-size: 0.92rem; }
        .page-body { padding: 16px 16px 40px; }

        .page-head h2 { font-size: 1.15rem; }
        .card { padding: 16px; }
        .card-header { margin-bottom: 14px; }

        table { font-size: 0.8rem; }
        thead th, tbody td { padding: 9px 10px; }

        .user-chip .name { display: none; }
        .stat-strip { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .stat-cell:nth-child(odd) { border-inline-start: none; }
        .stat-cell:nth-child(n+3) { border-top: 1px solid var(--border); }
        .stat-cell { padding: 14px 16px; }
        .stat-cell-value { font-size: 1.3rem; }

        .grid-2 { grid-template-columns: minmax(0, 1fr); }
        .podium { grid-template-columns: minmax(0, 1fr); }
        .podium-card.place-1, .podium-card.place-2, .podium-card.place-3 { grid-column: 1; }
        .podium-card.place-1 { order: -1; }
    }

    @media (max-width: 520px) {
        .stat-strip { grid-template-columns: minmax(0, 1fr); }
        .stat-cell { border-inline-start: none !important; }
        .stat-cell + .stat-cell { border-top: 1px solid var(--border); }
        .btn { padding: 7px 13px; font-size: 0.8rem; }
        .lang-switcher { display: none; }

        /* Collapse inline two-column grids on very small screens */
        [style*="grid-template-columns:1fr 1fr"],
        [style*="grid-template-columns: 1fr 1fr"],
        [style*="grid-template-columns:repeat(2,1fr)"] {
            grid-template-columns: 1fr !important;
        }

        [style*="justify-content:center;gap:32px"],
        [style*="justify-content: center; gap: 32px"] {
            flex-wrap: wrap !important;
            gap: 18px !important;
        }
    }

    /* ===== MOBILE CARD LIST ===== */
    .mobile-card-list { display: none; }
    @media (max-width: 700px) {
        .desktop-table { display: none; }
        .mobile-card-list { display: flex; flex-direction: column; gap: 10px; }
    }
    .mobile-row-card {
        background: var(--surface2);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 14px;
        display: flex; flex-direction: column; gap: 8px;
    }
    .mobile-row-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; }
    .mobile-row-title { flex: 1; font-size: 0.92rem; font-weight: 700; color: var(--text); text-decoration: none; }
    .mobile-row-badges { display: flex; gap: 6px; flex-wrap: wrap; }
    .mobile-row-meta { font-size: 0.74rem; color: var(--muted); font-family: var(--font-mono); }
    .mobile-row-actions { display: flex; gap: 6px; flex-wrap: wrap; margin-top: 4px; }
    .mobile-row-actions .btn { flex: 1; justify-content: center; min-width: 0; }

    /* ===== UPLOAD ZONE ===== */
    .upload-zone {
        border: 1.5px dashed var(--border2);
        border-radius: var(--radius);
        padding: 26px 20px; text-align: center; cursor: pointer;
        background: var(--surface2); position: relative;
        transition: border-color 0.2s, background 0.2s;
    }
    .upload-zone:hover { border-color: var(--gold); background: rgba(var(--accent-rgb),0.04); }
    .upload-zone.drag-over { border-color: var(--gold); background: rgba(var(--accent-rgb),0.08); }
    .upload-zone-icon {
        width: 48px; height: 48px; margin: 0 auto 12px;
        border-radius: 50%;
        background: rgba(var(--accent-rgb),0.1); border: 1px solid rgba(var(--accent-rgb),0.25);
        display: grid; place-items: center; color: var(--gold);
        transition: background 0.2s, color 0.2s;
    }
    .upload-zone-icon .icon { width: 20px; height: 20px; }
    .upload-zone:hover .upload-zone-icon { background: var(--gold); color: var(--on-gold); }
    .upload-zone-text { font-size: 0.88rem; color: var(--muted2); margin-bottom: 6px; }
    .upload-zone-text strong { color: var(--gold); font-weight: 800; }
    .upload-zone-meta { font-size: 0.75rem; color: var(--muted); }
    .upload-limit-badge {
        display: inline-block; padding: 2px 10px;
        background: rgba(var(--accent-rgb),0.1); color: var(--gold2);
        border: 1px solid rgba(var(--accent-rgb),0.2); border-radius: 999px;
        font-weight: 700; font-family: var(--font-mono); font-size: 0.7rem;
    }
    .upload-file-list { margin-top: 12px; display: flex; flex-direction: column; gap: 8px; }
    .upload-file-item {
        display: flex; align-items: center; justify-content: space-between; gap: 10px;
        padding: 10px 14px;
        background: var(--surface2); border: 1px solid var(--border);
        border-radius: var(--radius-sm); font-size: 0.85rem;
    }
    .upload-file-item.oversized { border-color: rgba(224,85,85,0.4); background: rgba(224,85,85,0.06); }
    .upload-file-info { display: flex; align-items: center; gap: 10px; flex: 1; min-width: 0; }
    .upload-file-icon { font-size: 1.1rem; flex-shrink: 0; display: flex; }
    .upload-file-name { font-weight: 600; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .upload-file-size { color: var(--muted); font-size: 0.76rem; flex-shrink: 0; font-family: var(--font-mono); }
    .upload-file-item.oversized .upload-file-size { color: var(--red); font-weight: 700; }
    .upload-file-remove {
        width: 24px; height: 24px; flex-shrink: 0;
        display: grid; place-items: center;
        background: none; border: 0; border-radius: 50%;
        color: var(--muted); font-size: 1rem; cursor: pointer;
        transition: background 0.15s, color 0.15s;
    }
    .upload-file-remove:hover { background: rgba(224,85,85,0.15); color: var(--red); }
    .upload-file-warning { font-size: 0.73rem; color: var(--red); margin-top: 2px; font-weight: 600; }

    /* Arabic UI: mono/tracking/uppercase break Arabic joins — fall back to body font */
    [dir="rtl"] .nav-section,
    [dir="rtl"] .role-badge,
    [dir="rtl"] thead th,
    [dir="rtl"] .badge,
    [dir="rtl"] label,
    [dir="rtl"] .question-block .qnum,
    [dir="rtl"] .mobile-row-meta,
    [dir="rtl"] .notif-item-time {
        font-family: var(--font-sans);
        text-transform: none;
        letter-spacing: 0;
    }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
            scroll-behavior: auto !important;
        }
    }
</style>
