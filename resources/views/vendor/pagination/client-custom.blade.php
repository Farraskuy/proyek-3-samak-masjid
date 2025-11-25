@if ($paginator->hasPages())
    <nav class="d-flex justify-content-center mt-4">
        <ul class="pagination align-items-center gap-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link border-0 bg-transparent text-muted px-1">
                        <i class="fas fa-chevron-left small me-1"></i> Previous
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link border-0 bg-transparent text-dark px-1 fw-medium"
                        href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        <i class="fas fa-chevron-left small me-1"></i> Previous
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span
                            class="page-link border-0 bg-transparent">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link rounded-3 border-0 fw-bold shadow-sm"
                                    style="background-color: #175C9E; color: white; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link rounded-3 border fw-medium text-dark" href="{{ $url }}"
                                    style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link border-0 bg-transparent text-dark px-1 fw-medium"
                        href="{{ $paginator->nextPageUrl() }}" rel="next">
                        Next <i class="fas fa-chevron-right small ms-1"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link border-0 bg-transparent text-muted px-1">
                        Next <i class="fas fa-chevron-right small ms-1"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
