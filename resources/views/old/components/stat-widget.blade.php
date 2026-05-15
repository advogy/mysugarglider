{{--
    x-stat-widget — Backend dashboard stat card
    Props:
      - label  : string
      - value  : int|string
      - icon   : string — Bootstrap icon class (e.g. 'bi-house-heart')
      - color  : string — 'sage' | 'honey' | 'sky' | 'pink' (default sage)
      - url    : string|null
--}}
@props([
    'label' => '',
    'value' => 0,
    'icon'  => 'bi-star',
    'color' => 'sage',
    'url'   => null,
])

@php
    $iconBg = match($color) {
        'honey' => 'bg-honey-50 text-honey-dark',
        'sky'   => 'bg-sky text-blue-600',
        'pink'  => 'bg-pink-50 text-pink-500',
        default => 'bg-sage-100 text-sage',
    };
@endphp

<div class="be-stat">
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 {{ $iconBg }} rounded-2xl flex items-center justify-center flex-shrink-0">
            <i class="bi {{ $icon }} text-xl"></i>
        </div>
        <div class="min-w-0">
            <p class="text-2xl font-display font-bold text-bark leading-none">{{ $value }}</p>
            <p class="text-bark-muted text-xs font-semibold mt-1 truncate">{{ $label }}</p>
        </div>
    </div>
    @if ($url)
        <a href="{{ $url }}" class="block mt-4 text-xs font-bold text-sage hover:text-sage-dark transition-colors">
            Lihat semua <i class="bi bi-arrow-right"></i>
        </a>
    @endif
</div>
