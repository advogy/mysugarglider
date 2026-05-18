@extends('layouts.v_backend')

@section('title', __('text.adoption'))

@section('content')

<div class="flex items-center justify-center min-h-[60vh]">
    <div class="text-center max-w-sm">
        <img src="{{ asset('assets/images/mascot/glider-glide.svg') }}" class="w-24 mx-auto mb-6 opacity-30" alt="">
        <h3 class="text-xl font-bold text-bark mb-2">Belum Ada Sugar Glider</h3>
        <p class="text-bark-muted text-sm mb-6">Tambahkan sugar glider ke koleksi Anda terlebih dahulu sebelum membuka adopsi.</p>
        <a href="{{ route('collection.create') }}" class="btn-create">
            <i class="bi bi-plus-lg"></i> Tambah Penempatan
        </a>
    </div>
</div>

@endsection
