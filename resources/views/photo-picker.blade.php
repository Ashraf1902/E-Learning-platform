<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>اختر صورة المعلم</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root{--bg:#0b0b0f;--card:#15151a;--gold:#d4a843;--text:#f5f5f7;--muted:#a1a1aa}
        *{margin:0;padding:0;box-sizing:border-box;font-family:Cairo,system-ui}
        body{background:radial-gradient(1200px 800px at 50% 0%,#1f1f25,#0b0b0f);color:var(--text);line-height:1.8}
        header{padding:40px 20px;text-align:center}
        header h1{font-size:clamp(24px,4vw,42px);font-weight:800;margin-bottom:10px}
        header p{color:var(--muted)}
        main{max-width:1100px;margin:0 auto;padding:0 20px 60px;display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:24px}
        .card{background:linear-gradient(180deg,rgba(212,168,67,.18) 0%,transparent 60%),var(--card);border:1px solid rgba(212,168,67,.4);border-radius:18px;overflow:hidden;box-shadow:0 60px 120px -80px rgba(212,168,67,.4);position:relative;isolation:isolate}
        .card::after{content:"";position:absolute;inset:0;background:radial-gradient(400px 200px at 50% 0%,rgba(212,168,67,.25),transparent);pointer-events:none}
        .img-wrap{position:relative;height:520px}
        .img-wrap img{width:100%;height:100%;object-fit:cover;object-position:top;filter:contrast(1.02)}
        .info{position:relative;padding:18px 22px;display:flex;align-items:center;justify-content:space-between}
        .info h2{font-size:18px;font-weight:700}
        .info span{color:var(--muted);font-size:14px}
        .badge{position:absolute;top:16px;right:16px;background:rgba(11,11,15,.7);border:1px solid rgba(212,168,67,.8);color:var(--gold);padding:6px 14px;border-radius:999px;font-size:13px;backdrop-filter:blur(10px)}
    </style>
</head>
<body>
    <header>
        <h1>اختر صورة الأستاذ / صاحب المنصة</h1>
        <p>انظر الصور أدناه وأخبرني برقم الصورة التي تريد استخدامها في صفحة الهبوط</p>
    </header>
    <main>
        <article class="card">
            <div class="badge">صورة 1</div>
            <div class="img-wrap">
                <img src="/images/image1.png" alt="صورة 1">
            </div>
            <div class="info">
                <h2>image1.png</h2>
                <span>2116 KB</span>
            </div>
        </article>
        <article class="card">
            <div class="badge">صورة 2</div>
            <div class="img-wrap">
                <img src="/images/image2.png" alt="صورة 2">
            </div>
            <div class="info">
                <h2>image2.png</h2>
                <span>2450 KB</span>
            </div>
        </article>
        <article class="card">
            <div class="badge">صورة 3</div>
            <div class="img-wrap">
                <img src="/images/image3.png" alt="صورة 3">
            </div>
            <div class="info">
                <h2>image3.png</h2>
                <span>2018 KB</span>
            </div>
        </article>
    </main>
</body>
</html>
