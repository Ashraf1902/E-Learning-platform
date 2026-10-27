@php
    $unread = (int) ($feed['unread'] ?? 0);
    $bellLabel = $unread
        ? __('notifications.aria_unread', ['count' => $unread])
        : __('notifications.title');
@endphp

<div class="notif-wrap" id="notifWrap"
     data-read-url="{{ route('notifications.read') }}"
     data-read-all-url="{{ route('notifications.read-all') }}"
     data-feed-url="{{ route('notifications.feed') }}"
     data-unread="{{ $unread }}"
     data-unread-template="{{ __('notifications.unread_count', ['count' => '__COUNT__']) }}"
     data-aria-template="{{ __('notifications.aria_unread', ['count' => '__COUNT__']) }}"
     data-idle-label="{{ __('notifications.title') }}"
     data-seen-label="{{ __('notifications.seen_status') }}">
    <button type="button" class="notif-btn" id="notifBtn"
            aria-expanded="false" aria-controls="notifPanel"
            aria-label="{{ $bellLabel }}">
        <x-icon name="bell" />
        <span class="notif-badge{{ $unread ? '' : ' is-empty' }}" id="notifBadge" aria-hidden="true">{{ $unread ? ($unread > 9 ? '9+' : $unread) : '' }}</span>
    </button>

    <div class="notif-panel" id="notifPanel" role="region" aria-labelledby="notifHeading" hidden>
        <div id="notifContent">
            @include('components.notification-content', ['feed' => $feed])
        </div>
    </div>

    <span class="sr-only" id="notifStatus" role="status" aria-live="polite"></span>
</div>

<script>
(function () {
    'use strict';

    function init() {
        var wrap = document.getElementById('notifWrap');
        if (!wrap) return;

        var btn = document.getElementById('notifBtn');
        var panel = document.getElementById('notifPanel');
        var content = document.getElementById('notifContent');
        var badge = document.getElementById('notifBadge');
        var status = document.getElementById('notifStatus');
        var page = document.getElementById('notifPage');
        var pageContent = document.getElementById('notifPageContent');
        var csrf = document.querySelector('meta[name="csrf-token"]');

        var feedUrl = wrap.dataset.feedUrl;
        var readUrl = wrap.dataset.readUrl;
        var readAllUrl = wrap.dataset.readAllUrl;
        var idleLabel = wrap.dataset.idleLabel || '';
        var seenLabel = wrap.dataset.seenLabel || '';
        var unreadTemplate = wrap.dataset.unreadTemplate || '__COUNT__';
        var ariaTemplate = wrap.dataset.ariaTemplate || '__COUNT__';

        var open = false;
        var unread = Number(wrap.dataset.unread || 0);
        var stateVersion = 0;

        function fill(template, count) {
            return template.replace('__COUNT__', String(count));
        }

        function setUnread(next) {
            unread = Math.max(0, Number(next) || 0);
            wrap.dataset.unread = String(unread);

            if (badge) {
                badge.textContent = unread ? (unread > 9 ? '9+' : String(unread)) : '';
                badge.hidden = !unread;
                badge.classList.toggle('is-empty', !unread);
            }

            document.querySelectorAll('[data-notif-count]').forEach(function (el) {
                el.textContent = fill(unreadTemplate, unread);
                el.hidden = !unread;
            });

            document.querySelectorAll('[data-notif-mark-all]').forEach(function (el) {
                el.hidden = !unread;
                el.disabled = false;
            });

            btn.setAttribute('aria-label', unread ? fill(ariaTemplate, unread) : idleLabel);
        }

        function setOpen(next) {
            open = next;
            panel.hidden = !next;
            btn.setAttribute('aria-expanded', next ? 'true' : 'false');
            if (!next && page) refreshPage(false);
        }

        function requestJson(url, options) {
            options = options || {};
            options.credentials = 'same-origin';
            options.headers = Object.assign({
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }, options.headers || {});

            return fetch(url, options).then(function (response) {
                return response.ok ? response.json() : null;
            });
        }

        function postJson(url, payload) {
            var headers = {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            };

            if (csrf) {
                headers['X-CSRF-TOKEN'] = csrf.getAttribute('content');
            }

            return requestJson(url, {
                method: 'POST',
                keepalive: true,
                headers: headers,
                body: JSON.stringify(payload || {})
            });
        }

        function refreshPanel(forceReplace) {
            if (!feedUrl) return;

            var version = stateVersion;
            requestJson(feedUrl).then(function (data) {
                if (!data || version !== stateVersion) return;

                if (content && (!open || forceReplace) && typeof data.html === 'string') {
                    content.innerHTML = data.html;
                }

                setUnread(data.unread);
            }).catch(function () { /* keep the server-rendered state */ });
        }

        function refreshPage(forceReplace) {
            if (!page || !pageContent || !feedUrl) return;
            if (document.hidden) return;
            if (!forceReplace && pageContent.contains(document.activeElement)) return;

            var filter = page.dataset.filter === 'unread' ? 'unread' : 'all';
            var separator = feedUrl.indexOf('?') === -1 ? '?' : '&';
            var url = feedUrl + separator + 'mode=page&filter=' + encodeURIComponent(filter);
            var version = stateVersion;

            requestJson(url).then(function (data) {
                if (!data || version !== stateVersion) return;

                if ((!open || forceReplace) && typeof data.html === 'string' && data.html !== pageContent.innerHTML) {
                    pageContent.innerHTML = data.html;
                }

                setUnread(data.unread);
            }).catch(function () { /* page stays usable while polling fails */ });
        }

        function markRead(key) {
            if (!readUrl || !key) return;

            postJson(readUrl, { key: key }).then(function (data) {
                if (data && typeof data.unread !== 'undefined') setUnread(data.unread);
            }).catch(function () { /* the next page load reflects the truth */ });
        }

        btn.addEventListener('click', function () {
            var next = !open;
            setOpen(next);
            if (next) refreshPanel(true);
        });

        document.addEventListener('click', function (event) {
            if (open && !wrap.contains(event.target)) setOpen(false);

            var item = event.target.closest ? event.target.closest('[data-notif-item]') : null;
            if (!item || !item.classList.contains('is-new')) return;

            var key = item.dataset.notifKey;
            stateVersion += 1;
            item.classList.remove('is-new');
            var dot = item.querySelector('.notif-dot');
            if (dot) dot.remove();
            markRead(key);
        });

        document.addEventListener('click', function (event) {
            var markAll = event.target.closest ? event.target.closest('[data-notif-mark-all]') : null;
            if (!markAll || !readAllUrl) return;

            event.preventDefault();
            var restoreFocus = document.activeElement === markAll;
            markAll.disabled = true;

            postJson(readAllUrl).then(function (data) {
                if (!data) {
                    markAll.disabled = false;
                    return;
                }

                stateVersion += 1;
                setUnread(0);
                if (restoreFocus) btn.focus();

                document.querySelectorAll('[data-notif-item].is-new').forEach(function (item) {
                    item.classList.remove('is-new');
                    var dot = item.querySelector('.notif-dot');
                    if (dot) dot.remove();
                });

                if (status) status.textContent = seenLabel;
                if (page) refreshPage(true);
            }).catch(function () {
                markAll.disabled = false;
            });
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && open) {
                setOpen(false);
                btn.focus();
            }
        });

        if (page) {
            setInterval(function () { refreshPage(false); }, 30000);
            document.addEventListener('visibilitychange', function () {
                if (!document.hidden) refreshPage(false);
            });
        } else {
            setInterval(function () { refreshPanel(false); }, 25000);
            document.addEventListener('visibilitychange', function () {
                if (!document.hidden) refreshPanel(false);
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
