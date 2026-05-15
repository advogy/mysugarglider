{{--
    x-section-header
    Props:
      - label   : string  — kecil di atas judul (opsional)
      - title   : string  — judul utama
      - subtitle: string  — deskripsi (opsional)
      - center  : bool    — rata tengah (default true)
--}}
@props([
    'label'    => null,
    'title'    => '',
    'subtitle' => null,
    'center'   => true,
])

<div {{ $attributes->merge(['class' => $center ? 'text-center' : '']) }}>
    @if ($label)
        <span class="inline-block text-sage font-bold text-xs tracking-[0.15em] uppercase mb-3">
            {{ $label }}
        </span>
    @endif

    <h2 class="section-title mb-3">{{ $title }}</h2>

    @if ($subtitle)
        <p class="section-subtitle {{ $center ? 'max-w-xl mx-auto' : 'max-w-lg' }}">
            {{ $subtitle }}
        </p>
    @endif
</div>
