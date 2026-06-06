@if($paginator->hasPages())
<nav class="pagination" aria-label="Pagination">
    {{-- Prev --}}
    @if($paginator->onFirstPage())
    <span class="page-link disabled">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
    </span>
    @else
    <a href="{{ $paginator->previousPageUrl() }}" class="page-link">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
    </a>
    @endif

    {{-- Pages --}}
    @foreach($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
    @if($page == $paginator->currentPage())
    <span class="page-link active">{{ $page }}</span>
    @else
    <a href="{{ $url }}" class="page-link">{{ $page }}</a>
    @endif
    @endforeach

    {{-- Next --}}
    @if($paginator->hasMorePages())
    <a href="{{ $paginator->nextPageUrl() }}" class="page-link">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
    </a>
    @else
    <span class="page-link disabled">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
    </span>
    @endif
</nav>
@endif
