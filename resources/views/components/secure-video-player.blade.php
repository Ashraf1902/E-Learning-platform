{{--
    Secure Video Player Component
    Usage: <x-secure-video-player :lecture="$lecture" :student="$student" />

    What this DOES protect against:
    - Viewing/copying the raw YouTube/Vimeo source link (never printed to the page)
    - Right-click "Save video as" / "Copy video URL" / "Open in new tab"
    - Native YouTube/Vimeo player chrome (related videos, channel links, share button)
    - Easy casual screen-recording attribution (visible watermark ties the leak to a person)

    What this CANNOT protect against (and no platform can):
    - A second camera/phone recording the screen
    - OS-level or third-party screen recording software
    - YouTube showing its own "Watch on YouTube" fallback card if the ORIGINAL
      uploader disabled embedding for that specific video — this is enforced by
      YouTube server-side and no embed parameter can override it. If this happens,
      use a different video (one with embedding allowed) or switch to Vimeo.
--}}
@props(['lecture', 'student' => null])

@php
    $watermarkText = $student ? ($student->name . ' — ' . $student->email) : 'مستر عبدالرحمن الحبال';
@endphp

<div
    class="secure-video-wrap"
    id="secure-video-wrap-{{ $lecture->id }}"
    oncontextmenu="return false"
    style="position:relative;width:100%;border-radius:16px;overflow:hidden;background:#000;aspect-ratio:16/9;user-select:none;"
>
    @if($lecture->hasVideo())
        @if($lecture->isSelfHosted())
            <video
                id="secure-player-{{ $lecture->id }}"
                src="{{ route('lectures.video', $lecture) }}"
                playsinline
                preload="metadata"
                style="width:100%;height:100%;display:block;background:#000;object-fit:contain;opacity:0;transition:opacity 0.35s ease;"
            ></video>
        @else
            <div id="secure-player-{{ $lecture->id }}" style="width:100%;height:100%;opacity:0;transition:opacity 0.35s ease;"></div>
        @endif

        {{-- Custom poster: shown until playback starts, hides the source
             player's branded thumbnail / play button / title bar. --}}
        <div
            id="poster-{{ $lecture->id }}"
            style="position:absolute;inset:0;z-index:4;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:16px;background:radial-gradient(ellipse at 50% 40%,rgba(212,168,67,0.10) 0%,rgba(0,0,0,0) 60%),linear-gradient(150deg,#101014 0%,#000 100%);transition:opacity 0.35s ease;pointer-events:none;padding:24px;text-align:center;"
        >
            <span style="width:72px;height:72px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:var(--gold,#1c6a5d);color:var(--on-gold,#fbf7ec);box-shadow:0 10px 40px rgba(0,0,0,0.45);">
                <x-icon name="play" style="width:26px;height:26px" />
            </span>
            <span style="color:#fff;font-weight:700;font-size:1rem;max-width:80%;line-height:1.6">{{ $lecture->title }}</span>
            <span style="color:rgba(255,255,255,0.5);font-size:0.78rem">
                {{ app()->getLocale() === 'ar' ? 'اضغط لتشغيل المحاضرة' : 'Click to play the lecture' }}
            </span>
        </div>

        {{-- Invisible click-shield: blocks right-click menu & drag-to-save on the iframe itself --}}
        <div
            class="video-shield"
            oncontextmenu="return false"
            onclick="toggleSecurePlay({{ $lecture->id }})"
            style="position:absolute;inset:0;z-index:5;background:transparent;cursor:pointer;"
            ondblclick="return false"
        ></div>

        @unless($lecture->isSelfHosted())
        {{-- Branding masks: YouTube draws its title bar (top-left), logo and
             "Watch on YouTube" pill (bottom corners) whenever playback starts or
             resumes. Full-width gradient strips cover those areas while still
             letting the video show through. --}}
        <div style="position:absolute;top:0;left:0;right:0;height:96px;background:linear-gradient(to bottom,#000 0%,rgba(0,0,0,0.95) 38%,rgba(0,0,0,0.55) 70%,transparent 100%);pointer-events:none;z-index:6;"></div>
        <div style="position:absolute;bottom:0;left:0;right:0;height:112px;background:linear-gradient(to top,#000 0%,rgba(0,0,0,0.96) 36%,rgba(0,0,0,0.55) 70%,transparent 100%);pointer-events:none;z-index:6;"></div>
        @endunless

        {{-- Watermark overlay: traceable, semi-transparent, repositions periodically --}}
        <div
            id="watermark-{{ $lecture->id }}"
            style="position:absolute;z-index:7;color:rgba(255,255,255,0.28);font-size:11px;font-weight:700;font-family:'JetBrains Mono',monospace;pointer-events:none;text-shadow:0 1px 4px rgba(0,0,0,0.8);white-space:nowrap;transition:all 1.4s ease;top:12px;left:12px;letter-spacing:0.5px;"
        >
            {{ $watermarkText }}
        </div>

        {{-- Tiled faint watermark for extra deterrence --}}
        <div
            id="watermark-tile-{{ $lecture->id }}"
            style="position:absolute;inset:0;z-index:6;pointer-events:none;opacity:0.12;background-image:repeating-linear-gradient(135deg, transparent 0, transparent 80px, rgba(255,255,255,0.4) 80px, rgba(255,255,255,0.4) 81px), repeating-linear-gradient(45deg, transparent 0, transparent 120px, rgba(255,255,255,0.25) 120px, rgba(255,255,255,0.25) 121px);mix-blend-mode:overlay;"
        ></div>

        {{-- Embed-blocked warning (shown only if YouTube reports the video can't be embedded) --}}
        <div
            id="embed-warning-{{ $lecture->id }}"
            style="display:none;position:absolute;inset:0;z-index:8;background:rgba(10,10,15,0.96);flex-direction:column;align-items:center;justify-content:center;gap:10px;text-align:center;padding:24px;"
        >
            <span style="font-size:2.2rem"><x-icon name="alert" /></span>
            <span style="color:#fff;font-weight:700;font-size:0.95rem">
                {{ app()->getLocale() === 'ar' ? 'هذا الفيديو لا يدعم التشغيل داخل الموقع' : 'This video can\'t be embedded' }}
            </span>
            <span style="color:rgba(255,255,255,0.6);font-size:0.8rem;max-width:340px;line-height:1.6">
                {{ app()->getLocale() === 'ar'
                    ? 'صاحب الفيديو الأصلي عطّل خيار التضمين. يرجى استخدام فيديو آخر أو رفعه على Vimeo.'
                    : 'The original uploader disabled embedding for this video. Please use a different video or upload to Vimeo instead.' }}
            </span>
        </div>

        {{-- Custom minimal controls bar (keeps native player UI hidden) --}}
        <div class="secure-controls" style="position:absolute;bottom:0;left:0;right:0;z-index:7;background:linear-gradient(transparent,rgba(0,0,0,0.7));padding:14px 16px 10px;display:flex;align-items:center;gap:12px;">
            <button onclick="toggleSecurePlay({{ $lecture->id }})" id="play-btn-{{ $lecture->id }}"
                style="background:rgba(255,255,255,0.15);border:none;color:white;width:32px;height:32px;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:0.9rem;flex-shrink:0;">
                <x-icon name="play" />
            </button>
            <div style="flex:1;height:4px;background:rgba(255,255,255,0.2);border-radius:999px;overflow:hidden;cursor:pointer;" id="seekbar-{{ $lecture->id }}" onclick="seekSecureVideo(event, {{ $lecture->id }})">
                <div id="progress-{{ $lecture->id }}" style="height:100%;width:0%;background:var(--gold,#d4a843);border-radius:999px;transition:width 0.3s linear;"></div>
            </div>
            <span class="secure-protected-label" style="color:rgba(255,255,255,0.6);font-size:0.72rem;flex-shrink:0;"><x-icon name="lock" /> {{ app()->getLocale() === 'ar' ? 'محتوى محمي' : 'Protected' }}</span>
            <button onclick="toggleSecureFullscreen({{ $lecture->id }})" id="fs-btn-{{ $lecture->id }}"
                style="background:rgba(255,255,255,0.15);border:none;color:white;width:30px;height:30px;border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:0.95rem;flex-shrink:0;">
                <x-icon name="maximize" />
            </button>
        </div>
    @else
        <div style="display:flex;align-items:center;justify-content:center;height:100%;color:#666;flex-direction:column;gap:10px;">
            <span style="font-size:2.5rem;opacity:0.3"><x-icon name="video" /></span>
            <span style="font-size:0.85rem">{{ app()->getLocale() === 'ar' ? 'لا يوجد فيديو لهذه المحاضرة' : 'No video for this lecture' }}</span>
        </div>
    @endif
</div>

@once
@push('styles')
<style>
    @media (max-width: 480px) {
        .secure-protected-label { display: none; }
    }
</style>
@endpush
@push('scripts')
<script>
document.addEventListener('keydown', function(e) {
    const isVideoFocused = document.querySelector('.secure-video-wrap:hover, .secure-video-wrap:focus-within');
    if (!isVideoFocused) return;
    if ((e.ctrlKey && e.key === 's') || (e.ctrlKey && e.shiftKey && e.key === 'I') || e.key === 'F12') {
        e.preventDefault();
    }
});
document.querySelectorAll('.video-shield').forEach(el => {
    el.addEventListener('dragstart', e => e.preventDefault());
});

function setSecurePoster(lectureId, playing) {
    const poster = document.getElementById('poster-' + lectureId);
    const player = document.getElementById('secure-player-' + lectureId);
    if (poster) poster.style.opacity = playing ? '0' : '1';
    if (player) player.style.opacity = playing ? '1' : '0';
    window['secPlaying' + lectureId] = !!playing;
}

function secureIsPlaying(lectureId) {
    const p = window['secPlayer' + lectureId];
    if (p && typeof p.getPlayerState === 'function') {
        const state = p.getPlayerState();
        if (state === 1 || state === 3) return true;
        if (state === 0 || state === 2) return false;
    }
    return !!window['secPlaying' + lectureId];
}

function toggleSecureFullscreen(lectureId) {
    const wrap = document.getElementById('secure-video-wrap-' + lectureId);
    if (!wrap) return;

    if (!document.fullscreenElement) {
        if (wrap.requestFullscreen) wrap.requestFullscreen();
        else if (wrap.webkitRequestFullscreen) wrap.webkitRequestFullscreen();
        else if (wrap.msRequestFullscreen) wrap.msRequestFullscreen();
    } else {
        if (document.exitFullscreen) document.exitFullscreen();
        else if (document.webkitExitFullscreen) document.webkitExitFullscreen();
    }
}

function seekSecureVideo(event, lectureId) {
    const p = window['secPlayer' + lectureId];
    if (!p || typeof p.getDuration !== 'function') return;
    const bar = event.currentTarget;
    const rect = bar.getBoundingClientRect();
    const isRtl = document.documentElement.dir === 'rtl';
    let ratio = (event.clientX - rect.left) / rect.width;
    if (isRtl) ratio = 1 - ratio;
    const duration = p.getDuration();
    p.seekTo(duration * ratio, true);
}
</script>
@endpush
@endonce

<script>
(function() {
    const lectureId   = {{ $lecture->id }};
    const provider     = @json($lecture->video_provider);
    const videoId      = @json($lecture->video_id);
    const containerId  = 'secure-player-' + lectureId;

    const ICON_PLAY = '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M7 4.5 19 12 7 19.5Z"/></svg>';
    const ICON_PAUSE = '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M9 5v14M15 5v14" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>';

    if (!videoId) return;

    function showEmbedWarning() {
        const warn = document.getElementById('embed-warning-' + lectureId);
        if (warn) warn.style.display = 'flex';
    }

    function loadYouTube() {
        if (window.__ytApiLoading) {
            window.__ytApiQueue = window.__ytApiQueue || [];
            window.__ytApiQueue.push(initYouTube);
            return;
        }
        if (window.YT && window.YT.Player) {
            initYouTube();
            return;
        }
        window.__ytApiLoading = true;
        window.__ytApiQueue = [initYouTube];
        window.onYouTubeIframeAPIReady = function() {
            (window.__ytApiQueue || []).forEach(fn => fn());
        };
        // Must come from youtube.com — youtube-nocookie.com/iframe_api is
        // rejected by Chrome's Opaque Response Blocking (ERR_BLOCKED_BY_ORB).
        const tag = document.createElement('script');
        tag.src = 'https://www.youtube.com/iframe_api';
        tag.onerror = fallbackEmbed;
        document.head.appendChild(tag);
        setTimeout(function() {
            if (!(window.YT && window.YT.Player)) fallbackEmbed();
        }, 8000);
    }

    function fallbackEmbed() {
        if (document.querySelector('#secure-player-' + lectureId + ' iframe')) return;
        setSecurePoster(lectureId, true);
        const shield = document.querySelector('#secure-video-wrap-' + lectureId + ' .video-shield');
        if (shield) shield.style.display = 'none';
        const bar = document.querySelector('#secure-video-wrap-' + lectureId + ' .secure-controls');
        if (bar) bar.style.display = 'none';
        const iframe = document.createElement('iframe');
        iframe.src = 'https://www.youtube-nocookie.com/embed/' + encodeURIComponent(videoId) + '?rel=0&modestbranding=1&playsinline=1';
        iframe.style.width = '100%';
        iframe.style.height = '100%';
        iframe.style.border = 'none';
        iframe.allow = 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture; fullscreen';
        iframe.allowFullscreen = true;
        iframe.referrerPolicy = 'no-referrer';
        document.getElementById(containerId).appendChild(iframe);
    }

    function initYouTube() {
        const player = new YT.Player(containerId, {
            videoId: videoId,
            host: 'https://www.youtube-nocookie.com',
            playerVars: {
                controls: 0,
                rel: 0,
                modestbranding: 1,
                fs: 0,
                disablekb: 1,
                iv_load_policy: 3,
                playsinline: 1,
                origin: window.location.origin,
                showinfo: 0,
                cc_load_policy: 0
            },
            events: {
                onReady: function(e) {
                    window['secPlayer' + lectureId] = e.target;
                    if (window['secWantPlay' + lectureId]) {
                        e.target.playVideo();
                    }
                },
                onStateChange: function(e) {
                    const btn = document.getElementById('play-btn-' + lectureId);
                    if (btn) btn.innerHTML = (e.data === YT.PlayerState.PLAYING) ? ICON_PAUSE : ICON_PLAY;
                    setSecurePoster(lectureId, e.data === YT.PlayerState.PLAYING || e.data === YT.PlayerState.BUFFERING);
                    if (e.data === YT.PlayerState.PLAYING) {
                        startProgressLoop(lectureId);
                    }
                },
                // error code 101/150 = embedding disabled by uploader
                onError: function(e) {
                    if (e.data === 101 || e.data === 150) {
                        showEmbedWarning();
                    }
                }
            }
        });
    }

    function startProgressLoop(id) {
        const interval = setInterval(() => {
            const p = window['secPlayer' + id];
            if (!p || typeof p.getDuration !== 'function') { clearInterval(interval); return; }
            const dur = p.getDuration();
            const cur = p.getCurrentTime();
            const bar = document.getElementById('progress-' + id);
            if (bar && dur > 0) bar.style.width = ((cur / dur) * 100) + '%';
            if (p.getPlayerState && p.getPlayerState() !== 1) clearInterval(interval);
        }, 500);
    }

    function initNative() {
        const v = document.getElementById(containerId);
        if (!v) return;

        const btn = document.getElementById('play-btn-' + lectureId);

        function syncIcon() {
            if (btn) btn.innerHTML = v.paused ? ICON_PLAY : ICON_PAUSE;
        }

        v.addEventListener('play', function() {
            setSecurePoster(lectureId, true);
            syncIcon();
        });
        v.addEventListener('pause', function() {
            setSecurePoster(lectureId, false);
            syncIcon();
        });
        v.addEventListener('ended', function() {
            setSecurePoster(lectureId, false);
            syncIcon();
        });
        v.addEventListener('timeupdate', function() {
            const bar = document.getElementById('progress-' + lectureId);
            if (bar && v.duration > 0) bar.style.width = ((v.currentTime / v.duration) * 100) + '%';
        });
        v.addEventListener('loadedmetadata', function() {
            const bar = document.getElementById('progress-' + lectureId);
            if (bar && v.duration > 0) bar.style.width = ((v.currentTime / v.duration) * 100) + '%';
        });

        // Adapter so the shared controls (play/pause/seek/fullscreen/visibility)
        // work exactly like they do for YouTube/Vimeo.
        window['secPlayer' + lectureId] = {
            getDuration:    () => v.duration || 0,
            getCurrentTime: () => v.currentTime || 0,
            getPlayerState: () => v.paused ? 2 : 1,
            playVideo:      () => v.play(),
            pauseVideo:     () => v.pause(),
            seekTo:         (t) => { v.currentTime = t; }
        };

        syncIcon();
    }

    if (provider === 'youtube') {
        loadYouTube();
    } else if (provider === 'vimeo') {
        const iframe = document.createElement('iframe');
        iframe.src = `https://player.vimeo.com/video/${videoId}?title=0&byline=0&portrait=0&controls=0&pip=0`;
        iframe.style.width = '100%';
        iframe.style.height = '100%';
        iframe.style.border = 'none';
        iframe.allow = 'autoplay; fullscreen';
        iframe.referrerPolicy = 'no-referrer';
        document.getElementById(containerId).appendChild(iframe);

        // Vimeo player JS API for the custom progress bar / seek
        const vimeoScript = document.createElement('script');
        vimeoScript.src = 'https://player.vimeo.com/api/player.js';
        vimeoScript.referrerPolicy = 'no-referrer';
        vimeoScript.onload = function() {
            const vp = new Vimeo.Player(iframe);
            window['secPlayer' + lectureId] = {
                getDuration: () => vp.getDuration(),
                getCurrentTime: () => vp.getCurrentTime(),
                getPlayerState: () => window['secPlaying' + lectureId] ? 1 : 2,
                playVideo: () => vp.play(),
                pauseVideo: () => vp.pause(),
                seekTo: (t) => vp.setCurrentTime(t)
            };
            vp.on('timeupdate', function(data) {
                const bar = document.getElementById('progress-' + lectureId);
                if (bar) bar.style.width = (data.percent * 100) + '%';
            });
            vp.on('play', function() { setSecurePoster(lectureId, true); });
            vp.on('pause', function() { setSecurePoster(lectureId, false); });
            vp.on('ended', function() { setSecurePoster(lectureId, false); });
        };
        document.head.appendChild(vimeoScript);
    } else if (provider === 'upload' || provider === 'url') {
        initNative();
    }

    const positions = [
        {top:'12px', left:'12px'}, {top:'12px', right:'12px', left:'auto'},
        {bottom:'56px', left:'12px', top:'auto'}, {bottom:'56px', right:'12px', left:'auto', top:'auto'},
        {top:'40%', left:'30%'}, {top:'55%', right:'20%', left:'auto'}, {top:'25%', right:'40%', left:'auto'}
    ];
    let posIndex = 0;
    const wm = document.getElementById('watermark-' + lectureId);
    if (wm) {
        setInterval(() => {
            posIndex = (posIndex + 1) % positions.length;
            Object.assign(wm.style, { top:'', left:'', right:'', bottom:'' }, positions[posIndex]);
        }, 6000);
    }

    // Auto-pause on tab/visibility change (deterrent)
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            const p = window['secPlayer' + lectureId];
            if (p && typeof p.pauseVideo === 'function') {
                try { p.pauseVideo(); } catch(e) {}
            }
        }
    });
})();

function toggleSecurePlay(lectureId) {
    const p = window['secPlayer' + lectureId];
    if (!p) {
        window['secWantPlay' + lectureId] = true;
        return;
    }
    if (secureIsPlaying(lectureId)) {
        p.pauseVideo();
        setSecurePoster(lectureId, false);
    } else {
        p.playVideo();
    }
}
</script>