@if ($paginator->hasPages())
<nav class="flex flex-col sm:flex-row items-center justify-between gap-3" aria-label="Navigasi halaman">

    <p class="text-bark-muted text-sm">
        Menampilkan
        <span class="font-semibold text-bark">{{ $paginator->firstItem() }}</span>
        –
        <span class="font-semibold text-bark">{{ $paginator->lastItem() }}</span>
        dari
        <span class="font-semibold text-bark">{{ $paginator->total() }}</span>
        data
    </p>

    <div class="flex items-center gap-1">
        @if ($paginator->onFirstPage())
            <span class="w-9 h-9 flex items-center justify-center rounded-xl text-bark-muted bg-cream cursor-not-allowed">
                <i class="bi bi-chevron-left text-xs"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
               class="w-9 h-9 flex items-center justify-center rounded-xl text-bark-light bg-white border border-cream-dark hover:bg-sage hover:text-white hover:border-sage transition-all duration-200">
                <i class="bi bi-chevron-left text-xs"></i>
            </a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="w-9 h-9 flex items-center justify-center text-bark-muted text-sm">{{ $element }}</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="w-9 h-9 flex items-center justify-center rounded-xl bg-sage text-white text-sm font-semibold">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}"
                           class="w-9 h-9 flex items-center justify-center rounded-xl text-bark-light text-sm bg-white border border-cream-dark hover:bg-sage hover:text-white hover:border-sage transition-all duration-200">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next"
               class="w-9 h-9 flex items-center justify-center rounded-xl text-bark-light bg-white border border-cream-dark hover:bg-sage hover:text-white hover:border-sage transition-all duration-200">
                <i class="bi bi-chevron-right text-xs"></i>
            </a>
        @else
            <span class="w-9 h-9 flex items-center justify-center rounded-xl text-bark-muted bg-cream cursor-not-allowed">
                <i class="bi bi-chevron-right text-xs"></i>
            </span>
        @endif
    </div>
</nav>
@endif
