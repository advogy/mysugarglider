@extends('layouts.v_backend')

@section('title', __('text.adoption'))

@section('content')

<div class="flex items-center justify-center min-h-[60vh]">
    <div class="text-center max-w-sm">
        <img src="{{ asset('assets/images/mascot/glider-glide.svg') }}" class="w-24 mx-auto mb-6 opacity-30" alt="">
        <h3 class="text-xl font-bold text-bark mb-2">Belum Ada Adopsi</h3>
        <p class="text-bark-muted text-sm mb-6">Belum ada sugar glider yang dibuka untuk adopsi. Ubah status penempatan ke "Adopsi" untuk memulai.</p>
        <a href="{{ route('collection.index') }}" class="btn-create">
            <i class="bi bi-grid-3x3-gap"></i> Lihat Penempatan
        </a>
    </div>
</div>

@endsection
