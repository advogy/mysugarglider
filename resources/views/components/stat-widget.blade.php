@props([
    'label'         => '',
    'value'         => 0,
    'icon'          => 'bi-circle',
    'color'         => 'sage',
    'url'           => '#',
    'sublabel'      => null,
    'sublabelColor' => 'text-bark-muted',
])

@php
    $colors = [
        'sage'  => 'bg-sage-100 text-sage',
        'honey' => 'bg-honey-50 text-honey-dark',
        'sky'   => 'bg-sky text-blue-600',
        'pink'  => 'bg-pink-50 text-pink-500',
        'amber' => 'bg-amber-50 text-amber-500',
    ];
    $bg = $colors[$color] ?? $colors['sage'];
@endphp

<a href="{{ $url }}" class="be-stat group flex flex-col h-full">
    <div class="flex items-start justify-between mb-4">
        <div class="w-11 h-11 {{ $bg }} rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
            <i class="bi {{ $icon }} text-lg"></i>
        </div>
        <i class="bi bi-arrow-up-right text-bark-muted text-xs group-hover:text-sage transition-colors"></i>
    </div>
    <div class="text-3xl font-number font-bold text-bark mt-auto">{{ $value }}</div>
    <p class="font-ui text-bark-muted text-xs font-semibold mt-1">{{ $label }}</p>
    @if ($sublabel)
        <p class="font-ui text-xs font-bold mt-0.5 {{ $sublabelColor }}">{{ $sublabel }}</p>
    @endif
</a>
