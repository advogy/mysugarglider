@extends('layouts.v_auth')

@section('title', 'Daftar Akun')

@section('form')

<div class="mb-8">
    <h1 class="text-3xl font-number font-bold text-bark mb-2">Buat Akun Baru</h1>
    <p class="text-bark-muted text-sm">Bergabung dan mulai catat data sugar glider Anda.</p>
</div>

@if ($errors->any())
    <div class="alert-danger mb-5">
        <i class="bi bi-exclamation-circle-fill text-lg flex-shrink-0"></i>
        <div>
            <p class="font-bold mb-1">Periksa kembali data Anda</p>
            @foreach ($errors->all() as $err)
                <p class="text-xs">{{ $err }}</p>
            @endforeach
        </div>
    </div>
@endif

<form action="{{ route('register') }}" method="POST" class="space-y-4">
    @csrf

    <div>
        <label class="form-label">Nama Lengkap</label>
        <div class="relative">
            <i class="bi bi-person absolute left-4 top-1/2 -translate-y-1/2 text-bark-muted text-sm"></i>
            <input type="text" name="nama"
                   value="{{ old('nama') }}"
                   placeholder="Nama Anda"
                   class="input-field pl-11"
                   autofocus required>
        </div>
    </div>

    <div>
        <label class="form-label">Alamat Email</label>
        <div class="relative">
            <i class="bi bi-envelope absolute left-4 top-1/2 -translate-y-1/2 text-bark-muted text-sm"></i>
            <input type="email" name="email"
                   value="{{ old('email') }}"
                   placeholder="nama@email.com"
                   class="input-field pl-11"
                   required>
        </div>
    </div>

    <div>
        <label class="form-label">Kata Sandi</label>
        <div class="relative">
            <i class="bi bi-lock absolute left-4 top-1/2 -translate-y-1/2 text-bark-muted text-sm"></i>
            <input type="password" name="password"
                   placeholder="Min. 8 karakter"
                   class="input-field pl-11 pr-eye"
                   required>
            <button type="button" class="auth-eye-btn" onclick="togglePassword(this)" tabindex="-1">
                <i class="bi bi-eye"></i>
            </button>
        </div>
    </div>

    <div>
        <label class="form-label">Konfirmasi Kata Sandi</label>
        <div class="relative">
            <i class="bi bi-lock-fill absolute left-4 top-1/2 -translate-y-1/2 text-bark-muted text-sm"></i>
            <input type="password" name="password_konfirmasi"
                   placeholder="Ulangi kata sandi"
                   class="input-field pl-11 pr-eye"
                   required>
            <button type="button" class="auth-eye-btn" onclick="togglePassword(this)" tabindex="-1">
                <i class="bi bi-eye"></i>
            </button>
        </div>
    </div>

    <p class="text-xs text-bark-muted leading-relaxed">
        Dengan mendaftar, Anda menyetujui
        <span class="font-semibold text-bark">Syarat & Ketentuan</span> dan
        <span class="font-semibold text-bark">Kebijakan Privasi</span> MySugarGlider.id.
    </p>

    <button type="submit" class="auth-btn">
        Buat Akun <i class="bi bi-arrow-right"></i>
    </button>
</form>

<p class="text-center text-sm text-bark-muted mt-8">
    Sudah punya akun?
    <a href="{{ route('login') }}" class="font-bold text-sage hover:underline">Masuk di sini</a>
</p>

@endsection
