@extends('layouts.v_auth')

@section('title', 'Verifikasi Email')

@section('form')

<div class="mb-8">
    <h1 class="text-3xl font-display font-bold text-bark mb-2">
        Halo, {{ Auth::user()->name }}!
    </h1>
    <p class="text-bark-muted text-sm">{{ __('text.verification_cek') }}</p>
</div>

@if (session('resent'))
    <div class="alert-success mb-5">
        <i class="bi bi-check-circle-fill text-lg flex-shrink-0"></i>
        <p class="font-semibold">{{ __('text.verification_sent') }}</p>
    </div>
@endif

<p class="text-bark-muted text-sm mb-6">{{ __('text.verification_not_receive') }}</p>

<form action="{{ route('verification.resend') }}" method="POST">
    @csrf
    <button type="submit" class="btn-primary w-full justify-center py-3.5">
        <i class="bi bi-envelope"></i> {{ __('text.verification_request') }}
    </button>
</form>

<div class="text-center mt-8">
    <form action="{{ route('logout') }}" method="POST" class="inline">
        @csrf
        <button type="submit" class="text-sm text-bark-muted hover:text-bark font-semibold underline underline-offset-2 transition-colors">
            {{ __('text.logout') }}
        </button>
    </form>
</div>

@endsection
