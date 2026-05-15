@props([
    'label'    => '',
    'title'    => '',
    'subtitle' => '',
    'center'   => true,
])

<div @class(['text-center' => $center])>
    @if ($label)
        <span class="inline-block text-xs font-bold uppercase tracking-widest text-sage bg-sage-50 px-4 py-1.5 rounded-full mb-4">
            {{ $label }}
        </span>
    @endif

    <h2 class="section-title mb-3">{{ $title }}</h2>

    @if ($subtitle)
        <p class="section-subtitle {{ $center ? 'max-w-xl mx-auto' : 'max-w-xl' }}">{{ $subtitle }}</p>
    @endif
</div>
