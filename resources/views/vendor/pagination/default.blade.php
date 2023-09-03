@if ($paginator->hasPages())
    <div class="row tm-row tm-mt-100 tm-mb-75">
        <div class="tm-prev-next-wrapper">
            <a href="{{ $paginator->previousPageUrl() }}" class="mb-2 tm-btn tm-btn-primary tm-prev-next {{ $paginator->onFirstPage() ? 'disabled' : '' }} tm-mr-20">Назад</a>
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="mb-2 tm-btn tm-btn-primary tm-prev-next">Следующая</a>
            @else
                <a href="#" class="mb-2 tm-btn tm-btn-primary tm-prev-next disabled">Следующая</a>
            @endif
        </div>
        <div class="tm-paging-wrapper">
            <span class="d-inline-block mr-3">Страница</span>
            <nav class="tm-paging-nav d-inline-block">
                <ul>
                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <li class="disabled" aria-disabled="true"><span>{{ $element }}</span></li>
                        @endif
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="tm-paging-item active">{{ $page }}</li>
                                @else
                                    <li><a class="mb-2 tm-btn tm-paging-link" href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </ul>
            </nav>
        </div>
    </div>
@endif
