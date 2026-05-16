@props([
    'title'        => '',
    'subtitle'     => null,
    'backRoute'    => null,
    'createRoute'  => null,
    'createLabel'  => null,
    'createIcon'   => 'bi-plus-lg',
])

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div class="{{ $backRoute ? 'flex items-center gap-4' : '' }}">
        @if ($backRoute)
            <a href="{{ $backRoute }}" class="text-bark-muted hover:text-bark transition-colors flex-shrink-0">
                <i class="bi bi-arrow-left text-xl"></i>
            </a>
        @endif
        <div>
            <h2 class="text-xl font-bold text-bark">{{ $title }}</h2>
            @if ($subtitle)
                <p class="text-bark-muted text-sm mt-0.5">{{ $subtitle }}</p>
            @endif
            {{ $slot }}
        </div>
    </div>
    @if ($createRoute)
        <a href="{{ $createRoute }}" class="btn-create self-start flex-shrink-0">
            <i class="bi {{ $createIcon }}"></i>
            {{ $createLabel ?? __('text.add_new') }}
        </a>
    @endif
</div>
