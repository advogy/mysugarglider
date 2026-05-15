@extends('layouts.v_auth')

@section('title', 'Atur Ulang Kata Sandi')

@section('form')

<div class="mb-8">
    <h1 class="text-3xl font-display font-bold text-bark mb-2">Atur Ulang Kata Sandi</h1>
    <p class="text-bark-muted text-sm">Buat kata sandi baru yang kuat untuk akun Anda.</p>
</div>

@if ($errors->any())
    <div class="alert-danger mb-5">
        <i class="bi bi-exclamation-circle-fill text-lg flex-shrink-0"></i>
        <div>
            @foreach ($errors->all() as $err)<p class="font-semibold">{{ $err }}</p>@endforeach
        </div>
    </div>
@endif

@if (session('pesan'))
    <div class="alert-success mb-5">
        <i class="bi bi-check-circle-fill text-lg flex-shrink-0"></i>
        <p class="font-semibold">{{ session('pesan') }}</p>
    </div>
@endif

<form action="{{ route('password.reset.action') }}" method="POST" class="space-y-4">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <div>
        <label class="form-label">Alamat Email</label>
        <div class="relative">
            <i class="bi bi-envelope absolute left-4 top-1/2 -translate-y-1/2 text-bark-muted text-sm"></i>
            <input type="email" name="email"
                   value="{{ $email }}"
                   class="input-field pl-11 bg-cream-dark"
                   readonly>
        </div>
    </div>

    <div>
        <label class="form-label">Kata Sandi Baru</label>
        <div class="relative">
            <i class="bi bi-lock absolute left-4 top-1/2 -translate-y-1/2 text-bark-muted text-sm"></i>
            <input type="password" name="password"
                   placeholder="Min. 8 karakter"
                   class="input-field pl-11"
                   autofocus required>
        </div>
    </div>

    <div>
        <label class="form-label">Konfirmasi Kata Sandi Baru</label>
        <div class="relative">
            <i class="bi bi-lock-fill absolute left-4 top-1/2 -translate-y-1/2 text-bark-muted text-sm"></i>
            <input type="password" name="password_confirmation"
                   placeholder="Ulangi kata sandi baru"
                   class="input-field pl-11"
                   required>
        </div>
    </div>

    <button type="submit" class="btn-primary w-full justify-center py-3.5">
        Atur Ulang Kata Sandi <i class="bi bi-arrow-right"></i>
    </button>
</form>

<p class="text-center text-sm text-bark-muted mt-8">
    <a href="{{ route('login') }}" class="font-bold text-sage hover:underline">Kembali ke halaman masuk</a>
</p>

@endsection
