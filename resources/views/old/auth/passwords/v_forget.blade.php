@extends('layouts.v_auth')

@section('title', 'Lupa Kata Sandi')

@section('content')

<div class="mb-8">
    <h1 class="text-3xl font-display font-bold text-bark mb-2">Lupa Kata Sandi?</h1>
    <p class="text-bark-muted text-sm">Masukkan email Anda dan kami akan kirimkan tautan reset kata sandi.</p>
</div>

@if ($errors->has('email'))
    <div class="alert-danger mb-5">
        <i class="bi bi-exclamation-circle-fill text-lg flex-shrink-0"></i>
        <p class="font-semibold">{{ $errors->first('email') }}</p>
    </div>
@endif

@if (session('pesan'))
    <div class="alert-success mb-5">
        <i class="bi bi-check-circle-fill text-lg flex-shrink-0"></i>
        <p class="font-semibold">{{ session('pesan') }}</p>
    </div>
@endif

<form action="{{ route('password.link') }}" method="POST" class="space-y-4">
    @csrf
    <div>
        <label class="form-label">Alamat Email</label>
        <div class="relative">
            <i class="bi bi-envelope absolute left-4 top-1/2 -translate-y-1/2 text-bark-muted text-sm"></i>
            <input type="email" name="email"
                   value="{{ old('email') }}"
                   placeholder="nama@email.com"
                   class="input-field pl-11"
                   autofocus required>
        </div>
    </div>
    <button type="submit" class="btn-primary w-full justify-center py-3.5">
        Kirim Tautan Reset <i class="bi bi-send"></i>
    </button>
</form>

<div class="text-center mt-8 space-y-2">
    <p class="text-sm text-bark-muted">
        Ingat kata sandi?
        <a href="{{ route('login') }}" class="font-bold text-sage hover:underline">Masuk</a>
    </p>
    <p class="text-sm text-bark-muted">
        atau <a href="{{ route('register') }}" class="font-bold text-sage hover:underline">Buat akun baru</a>
    </p>
</div>

@endsection
