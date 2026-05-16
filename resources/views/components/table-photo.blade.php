@props([
    'src'             => null,
    'name'            => '',
    'placeholderIcon' => 'bi-image',
    'size'            => 'md',
])

@php
$dim = $size === 'sm' ? 'w-8 h-8 rounded-lg' : 'w-11 h-11 rounded-xl';
@endphp

@if ($src)
    <button type="button" onclick="previewPhoto('{{ $src }}', '{{ addslashes($name) }}')"
            class="{{ $dim }} overflow-hidden bg-sage-100 block focus:outline-none flex-shrink-0">
        <img src="{{ $src }}" class="w-full h-full object-cover hover:opacity-80 transition-opacity cursor-zoom-in" alt="">
    </button>
@else
    <div class="{{ $dim }} overflow-hidden bg-sage-100 flex items-center justify-center flex-shrink-0">
        <i class="bi {{ $placeholderIcon }} text-sage/40 {{ $size === 'sm' ? 'text-xs' : '' }}"></i>
    </div>
@endif
