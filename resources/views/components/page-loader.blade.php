{{--
    Navigation feedback: slim top progress bar during page loads and a
    spinner state for submit buttons. No full-screen splash — panels should
    feel instant.
--}}

<div id="nav-progress" class="nav-progress" aria-hidden="true"></div>

<style>
    .nav-progress {
        position: fixed; inset-block-start: 0; inset-inline: 0;
        height: 2px; z-index: 9998;
        background: var(--gold, #d4a843);
        width: 0%;
        opacity: 0;
        transition: width 0.3s ease, opacity 0.2s ease;
        pointer-events: none;
    }
    .nav-progress.active { opacity: 1; }

    /* Button loading state — spinner replaces content, button keeps its size */
    .btn.is-loading {
        position: relative;
        color: transparent !important;
        pointer-events: none;
    }
    .btn.is-loading::after {
        content: '';
        position: absolute; top: 50%; left: 50%;
        width: 16px; height: 16px;
        margin: -8px 0 0 -8px;
        border: 2px solid rgba(0,0,0,0.25);
        border-top-color: var(--on-gold, #141007);
        border-radius: 50%;
        animation: btnSpin 0.6s linear infinite;
    }
    .btn.is-loading.btn-ghost::after,
    .btn.is-loading.btn-danger::after,
    .btn.is-loading.btn-success::after {
        border-color: rgba(128,128,128,0.25);
        border-top-color: var(--text, #f2f2f8);
    }
    @keyframes btnSpin { to { transform: rotate(360deg); } }

    @media (prefers-reduced-motion: reduce) {
        .nav-progress { transition: none; }
        .btn.is-loading::after { animation-duration: 1.5s; }
    }
</style>

<script>
(function () {
    var bar = document.getElementById('nav-progress');

    function startProgress() {
        if (!bar) return;
        bar.style.width = '0%';
        bar.classList.add('active');
        requestAnimationFrame(function () {
            bar.style.width = '70%';
        });
    }

    document.addEventListener('click', function (e) {
        var link = e.target.closest('a');
        if (!link) return;
        if (link.target === '_blank' || link.hasAttribute('download')) return;
        if (e.ctrlKey || e.metaKey || e.shiftKey) return;
        var href = link.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;
        if (link.origin && link.origin !== window.location.origin) return;
        startProgress();
    });

    document.addEventListener('submit', function (e) {
        startProgress();
        var form = e.target;
        var submitBtn = form.querySelector('button[type="submit"], button:not([type])');
        if (submitBtn && !submitBtn.classList.contains('is-loading')) {
            submitBtn.classList.add('is-loading');
        }
    });

    window.addEventListener('pageshow', function () {
        if (bar) { bar.classList.remove('active'); bar.style.width = '0%'; }
        document.querySelectorAll('.btn.is-loading').forEach(function (btn) {
            btn.classList.remove('is-loading');
        });
    });
})();
</script>
