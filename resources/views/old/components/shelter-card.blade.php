{{--
    x-shelter-card — Shelter card component
    Props:
      - id        : int
      - nama      : string
      - alamat    : string|null
      - keterangan: string|null
      - gambar    : string|null
--}}
@props([
    'id'         => 0,
    'nama'       => '',
    'alamat'     => null,
    'keterangan' => null,
    'gambar'     => null,
])

<a href="{{ route('shelter.show', $id) }}" class="shelter-card group">
    {{-- Cover Image --}}
    <div class="aspect-[4/3] overflow-hidden bg-cream relative">
        @if ($gambar)
            <img src="{{ asset('/upload/shelters/' . $gambar) }}"
                 alt="{{ $nama }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        @else
            <div class="w-full h-full flex items-center justify-center bg-sage-100">
                <i class="bi bi-house-heart text-5xl text-sage/30"></i>
            </div>
        @endif
        {{-- Gradient overlay --}}
        <div class="absolute inset-0 bg-gradient-to-t from-bark/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
    </div>

    {{-- Body --}}
    <div class="p-5">
        <h4 class="font-bold text-bark text-base mb-1 group-hover:text-sage transition-colors duration-200">
            {{ $nama }}
        </h4>

        @if ($alamat)
            <p class="flex items-center gap-1.5 text-bark-muted text-xs mb-2">
                <i class="bi bi-geo-alt flex-shrink-0"></i>
                <span>{{ $alamat }}</span>
            </p>
        @endif

        @if ($keterangan)
            <p class="text-bark-light text-sm leading-relaxed line-clamp-2">
                {{ $keterangan }}
            </p>
        @endif

        <div class="mt-4 flex items-center gap-1 text-sage text-sm font-bold">
            Lihat Kandang <i class="bi bi-arrow-right text-xs ml-1 group-hover:translate-x-1 transition-transform"></i>
        </div>
    </div>
</a>
