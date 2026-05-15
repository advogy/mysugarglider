@props([
    'title'       => '',
    'subtitle'    => '',
    'breadcrumbs' => [],
])

<div class="page-hero">
    {{-- Blob decorations --}}
    <div class="absolute top-10 right-10 w-48 h-48 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
    <div class="absolute -bottom-5 -left-5 w-64 h-64 bg-honey/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">
        {{-- Breadcrumb --}}
        @if (count($breadcrumbs) > 0)
            <nav class="flex items-center gap-2 text-white/60 text-sm mb-6">
                @foreach ($breadcrumbs as $i => $crumb)
                    @if ($i > 0)
                        <i class="bi bi-chevron-right text-xs"></i>
                    @endif
                    @if (isset($crumb['url']) && $loop->last === false)
                        <a href="{{ $crumb['url'] }}" class="hover:text-white transition-colors">{{ $crumb['label'] }}</a>
                    @else
                        <span class="{{ $loop->last ? 'text-white font-semibold' : '' }}">{{ $crumb['label'] }}</span>
                    @endif
                @endforeach
            </nav>
        @endif

        <h1 class="text-3xl sm:text-4xl font-display text-white leading-tight mb-3">{{ $title }}</h1>

        @if ($subtitle)
            <p class="text-white/70 text-base max-w-xl">{{ $subtitle }}</p>
        @endif

        {{ $slot ?? '' }}
    </div>
</div>
