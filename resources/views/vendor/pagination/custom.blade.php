@if ($paginator->hasPages())
@php $rtl = app()->getLocale() === 'ar'; @endphp
<nav class="pagination" role="navigation" aria-label="{{ $rtl ? 'ترقيم الصفحات' : 'Pagination' }}">
    <div class="pagination-inner">
        @if ($paginator->onFirstPage())
            <span class="page-btn is-disabled" aria-disabled="true">
                <x-icon :name="$rtl ? 'arrow-right' : 'arrow-left'" />
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="page-btn" rel="prev" aria-label="{{ $rtl ? 'السابق' : 'Previous' }}">
                <x-icon :name="$rtl ? 'arrow-right' : 'arrow-left'" />
            </a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="page-btn is-gap" aria-hidden="true">{{ $element }}</span>
            @elseif (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="page-btn is-active" aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="page-btn" rel="next" aria-label="{{ $rtl ? 'التالي' : 'Next' }}">
                <x-icon :name="$rtl ? 'arrow-left' : 'arrow-right'" />
            </a>
        @else
            <span class="page-btn is-disabled" aria-disabled="true">
                <x-icon :name="$rtl ? 'arrow-left' : 'arrow-right'" />
            </span>
        @endif

        <span class="page-info">
            {{ $rtl ? 'عرض' : 'Showing' }} {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}
            {{ $rtl ? 'من' : 'of' }} {{ $paginator->total() }}
        </span>
    </div>
</nav>
@endif
