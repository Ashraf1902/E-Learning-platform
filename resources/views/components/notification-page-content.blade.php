@php
    $items  = $feed['items'] ?? collect();
    $isUnread = ($filter ?? 'all') === 'unread';
@endphp

@if($items->isEmpty())
    <div class="notif-empty notif-empty-page">
        <span class="notif-empty-icon"><x-icon name="bell" /></span>
        <p class="notif-empty-title">{{ __($isUnread ? 'notifications.empty_unread_title' : 'notifications.empty_title') }}</p>
        <p class="notif-empty-body">{{ __($isUnread ? 'notifications.empty_unread_body' : 'notifications.empty_body') }}</p>
    </div>
@else
    <div class="notif-list notif-list-page">
        @include('components.notification-items', ['items' => $items])
    </div>
    <div class="notif-foot">
        <span>{{ __('notifications.window') }}</span>
        <span>{{ __('notifications.showing', ['count' => $items->count()]) }}</span>
    </div>
@endif
