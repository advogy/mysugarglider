{{--
    x-page-hero — Standard page hero for inner pages
    Props:
      - title      : string
      - breadcrumbs: array  [['label'=>'Home','url'=>'/'], ['label'=>'Current']]
--}}
@props([
    'title'       => '',
    'breadcrumbs' => [],
])

<div class="page-hero">
    {{-- Decorative blobs --}}
    <div class="absolute top-0 right-0 w-72 h-72 bg-white/5 rounded-full -translate-y-1/3 translate-x-1/3 animate-blob"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 bg-honey/10 rounded-full translate-y-1/3 -translate-x-1/4"></div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <h1 class="text-3xl md:text-4xl font-display text-white mb-3 animate-fade-in">
            {{ $title }}
        </h1>

        @if (count($breadcrumbs))
            <ol class="flex items-center gap-2 text-white/60 text-sm">
                @foreach ($breadcrumbs as $crumb)
                    @if (!$loop->last)
                        <li>
                            <a href="{{ $crumb['url'] }}"
                               class="hover:text-white transition-colors">{{ $crumb['label'] }}</a>
                        </li>
                        <li><i class="bi bi-chevron-right text-[10px]"></i></li>
                    @else
                        <li class="text-white font-semibold">{{ $crumb['label'] }}</li>
                    @endif
                @endforeach
            </ol>
        @endif

        {{ $slot }}
    </div>
</div>
