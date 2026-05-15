@extends('layouts.v_backend')

@section('title', 'Profil Tidak Ditemukan')

@section('content')

<div class="flex items-center justify-center min-h-[60vh]">
    <div class="text-center max-w-sm">
        <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-6">
            <i class="bi bi-person-x text-red-400 text-2xl"></i>
        </div>
        <h3 class="text-xl font-bold text-bark mb-2">{{ __('text.find_no_profile') }}</h3>
        <p class="text-bark-muted text-sm mb-6">Silakan lengkapi data profil Anda terlebih dahulu sebelum melanjutkan.</p>
        <a href="{{ route('profile') }}" class="btn-create">
            <i class="bi bi-person-fill"></i> Lengkapi Profil
        </a>
    </div>
</div>

@endsection
