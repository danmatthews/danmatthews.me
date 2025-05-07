
@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-end gap-12">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="text-base text-gray-300">
                {!! __('pagination.previous') !!}
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="text-black">
                {!! __('pagination.previous') !!}
            </a>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="text-black">
                {!! __('pagination.next') !!}
            </a>
        @else
            <span class="text-gray-400">
                {!! __('pagination.next') !!}
            </span>
        @endif
    </nav>
@endif
