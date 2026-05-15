@extends('layouts.v_backend')

@section('title', 'Dashboard')

@section('content')

<div class="mb-8">
    <h2 class="text-2xl font-display font-bold text-bark">Halo, {{ Auth::user()->name }}!</h2>
    <p class="text-bark-muted text-sm mt-1">Berikut ringkasan data sugar glider Anda hari ini.</p>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4 mb-8">
    <x-stat-widget label="Kandang"       :value="$count_shelters"     icon="bi-house-heart-fill"  color="sage"  :url="route('shelter.index')" />
    <x-stat-widget label="Sugar Glider"  :value="$count_sugargliders" icon="bi-heart-fill"         color="sky"   :url="route('sugarglider.index')" />
    <x-stat-widget label="Koleksi"       :value="$count_collections"  icon="bi-collection-fill"   color="honey" :url="route('collection.index')" />
    <x-stat-widget label="Siap Diadopsi" :value="$count_adoptions"    icon="bi-journal-check"     color="sage"  :url="route('adoption.index')" />
    <x-stat-widget label="Dapat Diadopsi":value="$count_adoptable"    icon="bi-heart-arrow"       color="pink"  :url="route('adoption.list')" />
</div>

<div class="grid lg:grid-cols-3 gap-6">

    {{-- Getting started --}}
    <div class="lg:col-span-2 be-card p-6">
        <h3 class="font-bold text-bark text-base mb-5 flex items-center gap-2">
            <i class="bi bi-map text-sage"></i>
            Panduan Penggunaan
        </h3>
        <div class="space-y-3">
            @php
                $steps = [
                    ['num'=>1, 'label'=>'Lengkapi profil Anda',    'route'=>route('profile'),          'icon'=>'bi-person-badge-fill', 'done'=>false],
                    ['num'=>2, 'label'=>'Tambahkan kandang',        'route'=>route('shelter.index'),     'done'=>$count_shelters > 0],
                    ['num'=>3, 'label'=>'Input data Sugar Glider',  'route'=>route('sugarglider.index'), 'done'=>$count_sugargliders > 0],
                    ['num'=>4, 'label'=>'Buat koleksi',             'route'=>route('collection.index'),  'done'=>$count_collections > 0],
                    ['num'=>5, 'label'=>'Kelola adopsi',            'route'=>route('adoption.index'),    'done'=>$count_adoptions > 0],
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

    {{-- Right column --}}
    <div class="space-y-4">
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

        <div class="be-card p-5">
            <h4 class="font-bold text-bark text-sm mb-4 flex items-center gap-2">
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
    </div>
</div>

@endsection
