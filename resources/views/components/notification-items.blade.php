@foreach($items as $item)
    <a href="{{ $item['url'] }}"
       class="notif-item{{ $item['unread'] ? ' is-new' : '' }}"
       data-notif-item
       data-notif-key="{{ $item['key'] }}">
        <span class="notif-ico tone-{{ $item['tone'] }}"><x-icon :name="$item['icon']" /></span>
        <span class="notif-copy">
            <span class="notif-item-title">{{ $item['title'] }}</span>
            <span class="notif-item-body">{{ $item['body'] }}</span>
            <time class="notif-item-time" datetime="{{ $item['time']->toIso8601String() }}" title="{{ $item['time']->format('Y/m/d H:i') }}">{{ $item['time_label'] }}</time>
        </span>
        @if($item['unread'])
            <span class="notif-dot" aria-hidden="true"></span>
        @endif
    </a>
@endforeach
