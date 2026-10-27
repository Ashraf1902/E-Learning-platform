@php
    $items  = $feed['items'] ?? collect();
    $unread = (int) ($feed['unread'] ?? 0);
@endphp

<div class="notif-head">
    <h2 class="notif-heading" id="notifHeading">
        <x-icon name="bell" />
        {{ __('notifications.title') }}
    </h2>
    <div class="notif-head-actions">
        <span class="notif-unread" data-notif-count @if(!$unread) hidden @endif>{{ __('notifications.unread_count', ['count' => $unread]) }}</span>
        <button type="button" class="notif-mark-all" data-notif-mark-all @if(!$unread) hidden @endif>{{ __('notifications.mark_all') }}</button>
    </div>
</div>

@if($items->isEmpty())
    <div class="notif-empty">
        <span class="notif-empty-icon"><x-icon name="bell" /></span>
        <p class="notif-empty-title">{{ __('notifications.empty_title') }}</p>
        <p class="notif-empty-body">{{ __('notifications.empty_body') }}</p>
    </div>
@else
    <div class="notif-list">
        @include('components.notification-items', ['items' => $items])
    </div>
@endif
<div class="notif-foot">
    <span>{{ __('notifications.window') }}</span>
    <a href="{{ route('notifications.index') }}">{{ __('notifications.view_all') }}</a>
</div>
