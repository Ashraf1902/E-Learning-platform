@extends($layout)

@section('title', __('notifications.title'))

@section('content')
    <div class="notif-page" id="notifPage" data-filter="{{ $filter }}">
        <div class="notif-page-head">
            <div>
                <h2 class="notif-page-title">
                    <span class="notif-page-icon"><x-icon name="bell" /></span>
                    {{ __('notifications.title') }}
                </h2>
                <p class="notif-page-sub">{{ __('notifications.page_subtitle') }}</p>
            </div>
            <div class="notif-page-actions">
                <span class="notif-unread" data-notif-count @if(!$feed['unread']) hidden @endif>{{ __('notifications.unread_count', ['count' => $feed['unread']]) }}</span>
                <button type="button" class="notif-mark-all notif-mark-all-page" data-notif-mark-all @if(!$feed['unread']) hidden @endif>{{ __('notifications.mark_all') }}</button>
            </div>
        </div>

        <nav class="notif-tabs" aria-label="{{ __('notifications.filters') }}">
            <a href="{{ route('notifications.index') }}"
               class="notif-tab{{ $filter === 'all' ? ' active' : '' }}"
               @if($filter === 'all') aria-current="page" @endif>{{ __('notifications.filter_all') }}</a>
            <a href="{{ route('notifications.index', ['filter' => 'unread']) }}"
               class="notif-tab{{ $filter === 'unread' ? ' active' : '' }}"
               @if($filter === 'unread') aria-current="page" @endif>{{ __('notifications.filter_unread') }}</a>
        </nav>

        <div class="notif-page-card">
            <div id="notifPageContent">
                @include('components.notification-page-content', ['feed' => $feed, 'filter' => $filter])
            </div>
        </div>
    </div>
@endsection
