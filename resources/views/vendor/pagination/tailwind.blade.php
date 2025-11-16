@if ($paginator->hasPages())
    <nav>
        <ul class="pagination justify-content-center mt-3">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" style="color: #6c757d; padding: 0.25rem 0.5rem; font-size: 0.875rem;">&laquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" style="color: #0d6efd; padding: 0.25rem 0.5rem; font-size: 0.875rem;">&laquo;</a>
                </li>
            @endif

            {{-- Page Numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link" style="color: #6c757d; padding: 0.25rem 0.5rem; font-size: 0.875rem;">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link" style="background-color: #0d6efd; border-color: #0d6efd; color: white; padding: 0.25rem 0.5rem; font-size: 0.875rem;">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}" style="color: #0d6efd; padding: 0.25rem 0.5rem; font-size: 0.875rem;">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" style="color: #0d6efd; padding: 0.25rem 0.5rem; font-size: 0.875rem;">&raquo;</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" style="color: #6c757d; padding: 0.25rem 0.5rem; font-size: 0.875rem;">&raquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif