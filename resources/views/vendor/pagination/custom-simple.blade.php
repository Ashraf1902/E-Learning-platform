@if ($paginator->hasMorePages() || ! $paginator->onFirstPage())
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

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="page-btn" rel="next" aria-label="{{ $rtl ? 'التالي' : 'Next' }}">
                <x-icon :name="$rtl ? 'arrow-left' : 'arrow-right'" />
            </a>
        @else
            <span class="page-btn is-disabled" aria-disabled="true">
                <x-icon :name="$rtl ? 'arrow-left' : 'arrow-right'" />
            </span>
        @endif
    </div>
</nav>
@endif
