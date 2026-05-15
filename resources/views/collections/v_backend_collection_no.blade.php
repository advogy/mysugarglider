@extends('layouts.v_backend')

@section('title', __('text.collection_data'))

@section('content')

<div class="flex items-center justify-center min-h-[60vh]">
    <div class="text-center max-w-sm">
        <img src="{{ asset('assets/images/mascot/glider-glide.svg') }}" class="w-24 mx-auto mb-6 opacity-30" alt="">
        <h3 class="text-xl font-bold text-bark mb-2">Belum Ada Koleksi</h3>
        <p class="text-bark-muted text-sm mb-6">Tambahkan koleksi pertama Anda untuk mulai menggunakan fitur ini.</p>
        <a href="{{ route('collection.create') }}" class="btn-create">
            <i class="bi bi-plus-lg"></i> Buat Koleksi
        </a>
    </div>
</div>

@endsection
