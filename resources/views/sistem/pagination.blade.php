@if ($datatabel->hasPages())
<nav>
    <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($datatabel->onFirstPage())
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link">@lang('pagination.previous')</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $datatabel->previousPageUrl() }}" rel="prev">@lang('pagination.previous')</a>
            </li>
        @endif

        {{-- Next Page Link --}}
        @if ($datatabel->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $datatabel->nextPageUrl() }}" rel="next">@lang('pagination.next')</a>
            </li>
        @else
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link">@lang('pagination.next')</span>
            </li>
        @endif
    </ul>
</nav>
@endif