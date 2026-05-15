@extends('layouts.v_auth')

@section('title', 'Masuk')

@section('form')

<div class="mb-8">
    <h1 class="text-3xl font-display font-bold text-bark mb-2">Selamat Datang!</h1>
    <p class="text-bark-muted text-sm">Masuk ke akun MySugarGlider Anda.</p>
</div>

@if (session('error'))
    <div class="alert-danger mb-5">
        <i class="bi bi-exclamation-circle-fill text-lg flex-shrink-0"></i>
        <div>
            <p class="font-bold">Akun Ditangguhkan</p>
            <p class="text-xs mt-0.5">{{ session('error') }}</p>
        </div>
    </div>
@endif

@if ($errors->has('email'))
    <div class="alert-danger mb-5">
        <i class="bi bi-exclamation-circle-fill text-lg flex-shrink-0"></i>
        <div>
            <p class="font-bold">Login Gagal</p>
            <p class="text-xs mt-0.5">{{ $errors->first('email') }}</p>
        </div>
    </div>
@endif

@if (session('pesan'))
    <div class="alert-success mb-5">
        <i class="bi bi-check-circle-fill text-lg flex-shrink-0"></i>
        <p class="font-semibold">{{ session('pesan') }}</p>
    </div>
@endif

<form action="{{ route('login.authenticate') }}" method="POST" class="space-y-4">
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

    <div>
        <label class="form-label">Kata Sandi</label>
        <div class="relative">
            <i class="bi bi-lock absolute left-4 top-1/2 -translate-y-1/2 text-bark-muted text-sm"></i>
            <input type="password" name="password"
                   placeholder="••••••••"
                   class="input-field pl-11 pr-eye"
                   required>
            <button type="button" class="auth-eye-btn" onclick="togglePassword(this)" tabindex="-1">
                <i class="bi bi-eye"></i>
            </button>
        </div>
    </div>

    <div class="flex items-center justify-between">
        <label class="flex items-center gap-2 cursor-pointer select-none">
            <input type="checkbox" name="rememberme" value="1"
                   class="w-4 h-4 rounded border-cream-dark text-sage focus:ring-sage">
            <span class="text-sm text-bark-muted font-semibold">Ingat saya</span>
        </label>
        <a href="{{ route('password.forget') }}" class="text-sm font-bold text-sage hover:underline">
            Lupa kata sandi?
        </a>
    </div>

    <button type="submit" class="auth-btn">
        Masuk <i class="bi bi-arrow-right"></i>
    </button>
</form>

<p class="text-center text-sm text-bark-muted mt-8">
    Belum punya akun?
    <a href="{{ route('register') }}" class="font-bold text-sage hover:underline">Daftar gratis</a>
</p>

@endsection
