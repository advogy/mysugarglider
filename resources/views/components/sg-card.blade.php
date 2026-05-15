@props([
    'id'      => 0,
    'nama'    => '',
    'kode'    => '',
    'jenis'   => '',
    'kelamin' => 1,
    'gambar'  => null,
    'status'  => 2,
    'shelter' => null,
    'blob'    => 'sage',
])

@php
    $blobColor = match($blob) {
        'honey' => 'bg-honey-50',
        'sky'   => 'bg-sky',
        default => 'bg-sage-100',
    };
    $genderIcon  = $kelamin == 0 ? '♀' : '♂';
    $genderClass = $kelamin == 0 ? 'text-pink-500' : 'text-blue-500';
@endphp

<a href="{{ route('sugarglider.show', $id) }}" class="sg-card block">
    <div class="sg-card-photo bg-cream">
        <div class="sg-card-blob {{ $blobColor }} animate-blob"></div>
        @if ($gambar)
            <img src="{{ asset('/upload/sugargliders/' . $gambar) }}"
                 alt="{{ $nama }}"
                 class="sg-card-img">
        @else
            <div class="sg-card-img bg-cream-dark flex items-center justify-center">
                <img src="{{ asset('assets/images/mascot/glider-glide.svg') }}"
                     alt="{{ $nama }}"
                     class="w-16 h-16 opacity-40">
            </div>
        @endif
    </div>

    <div class="sg-card-body">
        @if ($kode)
            <p class="text-xs text-bark-muted font-mono mb-1">{{ $kode }}</p>
        @endif
        <h4 class="font-bold text-bark text-base mb-1 leading-snug">{{ $nama }}</h4>

        <div class="flex items-center justify-center gap-2 mb-3">
            @if ($jenis)
                <span class="badge-sage text-[11px]">{{ $jenis }}</span>
            @endif
            <span class="text-sm font-bold {{ $genderClass }}">{{ $genderIcon }}</span>
        </div>

        @if ($shelter)
            <p class="text-xs text-bark-muted mb-3">
                <i class="bi bi-house-heart mr-1"></i>{{ $shelter }}
            </p>
        @endif

        <span class="btn-secondary text-xs px-4 py-2 w-full justify-center">
            Lihat Detail <i class="bi bi-arrow-right"></i>
        </span>
    </div>
</a>
