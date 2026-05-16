@extends('layouts.v_backend')

@section('title', 'Dashboard')

@section('content')

<div class="mb-8">
    <h2 class="text-2xl font-display font-bold text-bark">Halo, {{ Auth::user()->name }}!</h2>
    <p class="text-bark-muted text-sm mt-1">Berikut ringkasan data sugar glider Anda hari ini.</p>
</div>

<div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">
    <x-stat-widget label="Kandang"        :value="$count_shelters"     icon="bi-house-heart-fill" color="sage"  :url="route('shelter.index')" />
    <x-stat-widget label="Sugar Glider"   :value="$count_sugargliders" icon="bi-heart-fill"        color="sky"   :url="route('sugarglider.index')" />
    <x-stat-widget label="Koleksi"        :value="$count_collections"  icon="bi-collection-fill"  color="honey" :url="route('collection.index')" />
    <x-stat-widget label="Siap Diadopsi"  :value="$count_adoptions"    icon="bi-journal-check"    color="sage"  :url="route('adoption.index')" />
    <x-stat-widget label="Dapat Diadopsi" :value="$count_adoptable"    icon="bi-heart-arrow"      color="pink"  :url="route('adoption.list')" />
    <x-stat-widget
        label="Total Poin"
        :value="number_format($total_points)"
        icon="bi-award-fill"
        color="amber"
        :url="route('points.index')"
        :sublabel="'Level: ' . $level['label']"
        :sublabelColor="$level['color']"
    />
</div>

<div class="grid lg:grid-cols-3 gap-6">

    {{-- Kolom kiri: Akses Cepat + Kisah Bahagia --}}
    <div class="space-y-4">
        <div class="be-card p-5">
            <h4 class="font-bold text-bark text-base mb-4 flex items-center gap-2">
                <i class="bi bi-lightning-fill text-honey-dark"></i> Akses Cepat
            </h4>
            <div class="space-y-2">
                <a href="{{ route('sugarglider.create') }}" class="flex items-center gap-3 text-sm font-semibold text-bark-light hover:text-sage transition-colors">
                    <i class="bi bi-plus-circle text-sage"></i> Tambah Sugar Glider
                </a>
                <a href="{{ route('collection.create') }}" class="flex items-center gap-3 text-sm font-semibold text-bark-light hover:text-sage transition-colors">
                    <i class="bi bi-plus-circle text-sage"></i> Buat Koleksi Baru
                </a>
                <a href="{{ route('adoption.create') }}" class="flex items-center gap-3 text-sm font-semibold text-bark-light hover:text-sage transition-colors">
                    <i class="bi bi-plus-circle text-sage"></i> Tawarkan Adopsi
                </a>
                <a href="{{ route('adoption.list') }}" class="flex items-center gap-3 text-sm font-semibold text-bark-light hover:text-sage transition-colors">
                    <i class="bi bi-heart-arrow text-honey-dark"></i> Cari Adopsi Baru
                </a>
            </div>
        </div>

        {{-- Testimonial card --}}
        <div class="be-card p-5">
            <h4 class="font-bold text-bark text-base mb-3 flex items-center gap-2">
                <i class="bi bi-chat-quote-fill text-blue-sg"></i> Kisah Bahagia
            </h4>

            @if (!$my_testimonial)
                <p class="text-bark-muted text-xs mb-4 leading-relaxed">Bagikan pengalaman Anda menggunakan MySugarGlider. Dapatkan <span class="font-bold text-amber-500">+50 poin</span> setelah disetujui.</p>
                <button type="button" onclick="document.getElementById('testimonial-modal').classList.remove('hidden'); document.getElementById('testimonial-modal').classList.add('flex')"
                        class="w-full text-center text-sm font-bold py-2.5 px-4 rounded-xl bg-blue-sg text-white hover:opacity-90 transition-opacity">
                    <i class="bi bi-pencil-square mr-1"></i> Tulis Kisah Anda
                </button>

            @elseif ($my_testimonial->isPending())
                <div class="flex items-center gap-2 mb-3">
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-full bg-amber-100 text-amber-700">
                        <i class="bi bi-hourglass-split"></i> Menunggu Persetujuan
                    </span>
                </div>
                <p class="text-bark-muted text-xs italic leading-relaxed line-clamp-3">"{{ $my_testimonial->quote }}"</p>
                <form action="{{ route('testimonial.destroy', $my_testimonial) }}" method="POST" class="mt-3">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-xs text-bark-muted hover:text-red-500 transition-colors">
                        <i class="bi bi-trash3"></i> Hapus &amp; Tulis Ulang
                    </button>
                </form>

            @elseif ($my_testimonial->isApproved())
                <div class="flex items-center gap-2 mb-3">
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-full bg-sage-100 text-sage-dark">
                        <i class="bi bi-check-circle-fill"></i> Sudah Tayang
                    </span>
                </div>
                <p class="text-bark-muted text-xs italic leading-relaxed line-clamp-3">"{{ $my_testimonial->quote }}"</p>
                <p class="text-amber-500 text-xs font-bold mt-3"><i class="bi bi-award-fill mr-1"></i> +50 poin diterima</p>

            @else
                <div class="flex items-center gap-2 mb-3">
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-full bg-red-50 text-red-500">
                        <i class="bi bi-x-circle-fill"></i> Tidak Disetujui
                    </span>
                </div>
                <p class="text-bark-muted text-xs mb-4 leading-relaxed">Testimoni Anda tidak disetujui. Silakan tulis ulang.</p>
                <form action="{{ route('testimonial.destroy', $my_testimonial) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full text-center text-sm font-bold py-2.5 px-4 rounded-xl border-2 border-sage text-sage hover:bg-sage-50 transition-colors">
                        <i class="bi bi-arrow-counterclockwise mr-1"></i> Tulis Ulang
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Kolom tengah: Panduan Penggunaan --}}
    <div class="be-card p-6">
        <h3 class="font-bold text-bark text-base mb-5 flex items-center gap-2">
            <i class="bi bi-map text-sage"></i>
            Panduan Penggunaan
        </h3>
        <div class="space-y-3">
            @php
                $steps = [
                    ['num'=>1, 'label'=>'Lengkapi profil Anda',    'route'=>route('profile'),          'done'=>$profile_done],
                    ['num'=>2, 'label'=>'Tambahkan kandang',        'route'=>route('shelter.index'),    'done'=>$count_shelters > 0],
                    ['num'=>3, 'label'=>'Input data Sugar Glider',  'route'=>route('sugarglider.index'),'done'=>$count_sugargliders > 0],
                    ['num'=>4, 'label'=>'Tambah penempatan',        'route'=>route('collection.index'), 'done'=>$count_collections > 0],
                    ['num'=>5, 'label'=>'Kelola adopsi',            'route'=>route('adoption.index'),   'done'=>$count_adoptions > 0],
                ];
            @endphp
            @foreach ($steps as $step)
                <a href="{{ $step['route'] }}"
                   class="flex items-center gap-4 p-4 rounded-2xl border-2 transition-all duration-200
                          {{ $step['done'] ? 'border-sage/20 bg-sage-50' : 'border-cream-dark hover:border-sage/30 hover:bg-cream' }}">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 text-sm font-bold
                                {{ $step['done'] ? 'bg-sage text-white' : 'bg-cream-dark text-bark-muted' }}">
                        @if ($step['done'])
                            <i class="bi bi-check-lg"></i>
                        @else
                            {{ $step['num'] }}
                        @endif
                    </div>
                    <p class="flex-1 font-semibold text-bark text-sm {{ $step['done'] ? 'line-through text-bark-muted' : '' }}">
                        {{ $step['label'] }}
                    </p>
                    <i class="bi bi-chevron-right text-bark-muted text-xs flex-shrink-0"></i>
                </a>
            @endforeach
        </div>
    </div>

    {{-- Kolom kanan: Kartu profil --}}
    <div>
        <div class="be-card p-6 text-center">
            <div class="w-20 h-20 rounded-2xl overflow-hidden mx-auto mb-4 bg-sage-100">
                @if (Auth::user()->avatar)
                    <img src="{{ asset('/upload/avatars/' . Auth::user()->avatar) }}" class="w-full h-full object-cover" alt="">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <i class="bi bi-person-fill text-3xl text-sage/40"></i>
                    </div>
                @endif
            </div>
            <h3 class="font-bold text-bark text-base">{{ $user->name }}</h3>
            <p class="text-bark-muted text-xs mt-1">{{ $user->email }}</p>
            <a href="{{ route('profile') }}" class="btn-ghost mt-4 text-xs mx-auto">
                <i class="bi bi-pencil"></i> Edit Profil
            </a>
        </div>
    </div>

</div>

{{-- Modal: Submit Testimonial --}}
<div id="testimonial-modal" class="be-modal hidden" onclick="if(event.target===this){this.classList.add('hidden');this.classList.remove('flex')}">
    <div class="relative bg-white rounded-3xl shadow-hover w-full max-w-md mx-4 p-6" onclick="event.stopPropagation()">
        <button type="button" onclick="document.getElementById('testimonial-modal').classList.add('hidden');document.getElementById('testimonial-modal').classList.remove('flex')"
                class="absolute top-4 right-4 w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-bark-muted hover:text-bark transition-colors">
            <i class="bi bi-x text-lg"></i>
        </button>

        <h3 class="font-bold text-bark text-lg mb-1">Bagikan Kisah Anda</h3>
        <p class="text-bark-muted text-sm mb-5">Ceritakan pengalaman Anda bersama MySugarGlider. Setelah disetujui, Anda mendapat <span class="font-bold text-amber-500">+50 poin</span>.</p>

        <form action="{{ route('testimonial.store') }}" method="POST">
            @csrf
            <div class="mb-5">
                <label class="block text-sm font-semibold text-bark mb-1.5">Kisah Anda <span class="text-red-400">*</span></label>
                <textarea name="quote" rows="5" required minlength="20" maxlength="500"
                          placeholder="Ceritakan pengalaman Anda menggunakan MySugarGlider..."
                          class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm text-bark focus:outline-none focus:border-sage focus:ring-2 focus:ring-sage/20 resize-none">{{ old('quote') }}</textarea>
                <p class="text-xs text-bark-muted mt-1">Minimal 20 karakter, maksimal 500 karakter.</p>
            </div>
            <div class="mb-5 p-3 bg-cream rounded-xl">
                <p class="text-xs text-bark-muted">Akan ditampilkan sebagai:</p>
                <p class="text-sm font-bold text-bark mt-1">{{ Auth::user()->name }}</p>
            </div>
            <button type="submit" class="w-full py-3 rounded-2xl bg-sage text-white font-bold text-sm hover:opacity-90 transition-opacity">
                <i class="bi bi-send-fill mr-1"></i> Kirim Testimoni
            </button>
        </form>
    </div>
</div>

@endsection
