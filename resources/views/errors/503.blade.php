<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>الموقع تحت الصيانة — مستر عبدالرحمن الحبال</title>
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico?v=3">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600&family=Noto+Kufi+Arabic:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        :root{
            --paper:#f7f3e9; --surface:#fffdf7; --ink:#1a1712; --ink-soft:#4c4437;
            --line:#ded4bf; --accent:#1c6a5d; --gold:#c2933a; --gold-ink:#7a5312;
        }
        *{box-sizing:border-box;margin:0;padding:0}
        body{
            min-height:100vh; display:grid; place-items:center; padding:32px 20px;
            background:var(--paper); color:var(--ink);
            font-family:'IBM Plex Sans Arabic',system-ui,sans-serif;
            background-image:
                radial-gradient(circle at 1px 1px, rgba(30,25,18,.055) 1px, transparent 0);
            background-size:22px 22px;
        }
        .card{
            width:100%; max-width:560px; background:var(--surface);
            border:1px solid var(--line); border-radius:18px;
            padding:clamp(28px,6vw,48px); text-align:center;
            box-shadow:0 22px 50px -30px rgba(26,23,18,.35);
            position:relative;
        }
        .card::before{
            content:""; position:absolute; inset:10px; border:1px solid rgba(194,147,58,.35);
            border-radius:12px; pointer-events:none;
        }
        .badge{
            display:inline-flex; align-items:center; gap:7px;
            font-size:.72rem; font-weight:600; letter-spacing:.04em;
            color:var(--gold-ink); text-transform:uppercase;
            border:1px solid rgba(194,147,58,.4); border-radius:999px;
            padding:5px 12px; margin-bottom:22px;
        }
        .badge span{width:6px;height:6px;border-radius:50%;background:var(--gold)}
        h1{
            font-family:'Noto Kufi Arabic',sans-serif; font-weight:700;
            font-size:clamp(1.45rem,4.4vw,2rem); line-height:1.5;
            letter-spacing:-.01em; text-wrap:balance;
        }
        p{
            margin-top:14px; color:var(--ink-soft); font-size:.95rem;
            line-height:1.95; text-wrap:pretty;
        }
        .rule{
            width:64px; height:2px; margin:26px auto;
            background:linear-gradient(90deg,transparent,var(--gold),transparent);
        }
        .en{
            font-size:.8rem; color:#6f6452; line-height:1.8;
            direction:ltr; unicode-bidi:isolate;
        }
        .brand{
            margin-top:26px; font-family:'Noto Kufi Arabic',sans-serif;
            font-size:.78rem; font-weight:600; color:var(--accent);
            letter-spacing:.02em;
        }
        @media (prefers-color-scheme: dark){
            :root{--paper:#0b1118;--surface:#0f1721;--ink:#f2ebdd;--ink-soft:#c6bda9;--line:#26333f;--accent:#7ac7b9;--gold:#e8bc66;--gold-ink:#e8bc66}
            body{background-image:radial-gradient(circle at 1px 1px, rgba(255,255,255,.05) 1px, transparent 0)}
            .en{color:#a49a86}
            .card{box-shadow:0 22px 50px -30px rgba(0,0,0,.8)}
        }
    </style>
</head>
<body>
    <main class="card">
        <div class="badge"><span></span> 503 · الصيانة</div>
        <h1>الموقع تحت الصيانة مؤقتاً</h1>
        <p>
            نعمل حالياً على تحسين المنصة، وسنعود خلال وقت قصير.
            شكراً لصبركم، ويمكنكم المحاولة مرة أخرى بعد قليل.
        </p>
        @isset($retryAfter)
            @if($retryAfter)
                <p class="en">Please try again after {{ $retryAfter }}.</p>
            @endif
        @endisset
        <div class="rule"></div>
        <p class="en">This site is temporarily down for maintenance. We will be back shortly.</p>
        <div class="brand">منصة مستر عبدالرحمن الحبال</div>
    </main>
</body>
</html>
