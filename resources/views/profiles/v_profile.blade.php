@extends('layouts.v_backend')

@section('title', 'Profil')

@section('content')

<x-page-header
    :title="__('text.profile')"
    subtitle="Perbarui data Anda."
/>

@if (session('pesan'))
    <div class="alert-success mb-5">
        <i class="bi bi-check-circle-fill text-lg flex-shrink-0"></i>
        <p class="font-semibold">{{ session('pesan') }}</p>
    </div>
@endif
@if ($errors->any())
    <div class="alert-danger mb-5">
        <i class="bi bi-exclamation-circle-fill text-lg flex-shrink-0"></i>
        <div>@foreach ($errors->all() as $err)<p>{{ $err }}</p>@endforeach</div>
    </div>
@endif

<div class="max-w-2xl">

    {{-- User card header --}}
    <div class="be-card p-6 mb-6 flex items-center gap-4">
        <div class="w-16 h-16 rounded-2xl overflow-hidden bg-sage-100 flex-shrink-0">
            @if (Auth::user()->avatar)
                <img src="{{ asset('/upload/avatars/' . Auth::user()->avatar) }}"
                     class="w-full h-full object-cover" alt="">
            @else
                <div class="w-full h-full flex items-center justify-center">
                    <i class="bi bi-person-fill text-sage text-2xl"></i>
                </div>
            @endif
        </div>
        <div>
            <p class="font-bold text-bark text-lg">{{ Auth::user()->name }}</p>
            <p class="text-bark-muted text-sm">{{ Auth::user()->email }}</p>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="be-card overflow-hidden">

        {{-- Tab buttons --}}
        <div class="flex border-b border-cream-dark overflow-x-auto">
            <button data-tab="tab-profile" onclick="switchTab('tab-profile')"
                    class="tab-btn px-5 py-3.5 text-sm font-bold border-b-2 border-sage text-sage whitespace-nowrap transition-colors">
                <i class="bi bi-person-badge mr-1"></i> Profil
            </button>
            <button data-tab="tab-avatar" onclick="switchTab('tab-avatar')"
                    class="tab-btn px-5 py-3.5 text-sm font-bold border-b-2 border-transparent text-bark-muted whitespace-nowrap hover:text-bark transition-colors">
                <i class="bi bi-person-bounding-box mr-1"></i> Avatar
            </button>
            <button data-tab="tab-account" onclick="switchTab('tab-account')"
                    class="tab-btn px-5 py-3.5 text-sm font-bold border-b-2 border-transparent text-bark-muted whitespace-nowrap hover:text-bark transition-colors">
                <i class="bi bi-gear-fill mr-1"></i> Akun
            </button>
            <button data-tab="tab-password" onclick="switchTab('tab-password')"
                    class="tab-btn px-5 py-3.5 text-sm font-bold border-b-2 border-transparent text-bark-muted whitespace-nowrap hover:text-bark transition-colors">
                <i class="bi bi-shield-lock mr-1"></i> Kata Sandi
            </button>
        </div>

        {{-- Tab: Profil --}}
        <div id="tab-profile" class="tab-pane p-6">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="form-label">{{ __('text.address') }}</label>
                        <input type="text" name="alamat" value="{{ $profile->alamat ?? '' }}"
                               placeholder="{{ __('text.address') }}"
                               class="input-field" required>
                    </div>
                    <div>
                        <label class="form-label">{{ __('text.telp') }} / No. WhatsApp</label>
                        <input type="text" name="telepon" value="{{ $profile->telepon ?? '' }}"
                               placeholder="{{ __('text.telp') }}"
                               class="input-field" required>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="btn-create">
                            <i class="bi bi-check-lg"></i> {{ __('text.save') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Tab: Avatar --}}
        <div id="tab-avatar" class="tab-pane hidden p-6">
            <div class="mb-6">
                <div class="w-24 h-24 rounded-2xl overflow-hidden bg-sage-100">
                    @if (Auth::user()->avatar)
                        <img src="{{ asset('/upload/avatars/' . Auth::user()->avatar) }}"
                             class="w-full h-full object-cover" alt="">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="bi bi-person-fill text-sage text-3xl"></i>
                        </div>
                    @endif
                </div>
            </div>
            <form action="{{ route('profile.update.avatar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="form-label">Ubah Avatar</label>
                        <input type="file" name="avatar"
                               class="w-full text-sm text-bark-muted file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sage/10 file:text-sage cursor-pointer">
                        <p class="text-xs text-bark-muted mt-1.5">Ukuran avatar: 150×150px</p>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="btn-create">
                            <i class="bi bi-check-lg"></i> {{ __('text.save') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Tab: Akun --}}
        <div id="tab-account" class="tab-pane hidden p-6">
            <form action="{{ route('profile.update.user') }}" method="POST">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="form-label">{{ __('text.name') }}</label>
                        <input type="text" name="name" value="{{ $user->name }}"
                               placeholder="{{ __('text.name') }}"
                               class="input-field">
                    </div>
                    <div>
                        <label class="form-label">{{ __('text.email') }}</label>
                        <input type="email" name="email" value="{{ $user->email }}"
                               placeholder="{{ __('text.email') }}"
                               class="input-field">
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="btn-create">
                            <i class="bi bi-check-lg"></i> {{ __('text.save') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Tab: Kata Sandi --}}
        <div id="tab-password" class="tab-pane hidden p-6">
            <form action="{{ route('profile.password.change') }}" method="POST">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="form-label">{{ __('text.password_new') }}</label>
                        <input type="password" name="password_new"
                               placeholder="{{ __('text.password_new') }}"
                               class="input-field" required>
                    </div>
                    <div>
                        <label class="form-label">{{ __('text.password_new_confirmation') }}</label>
                        <input type="password" name="password_new_confirmation"
                               placeholder="{{ __('text.password_new_confirmation') }}"
                               class="input-field" required>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="btn-create">
                            <i class="bi bi-shield-lock"></i> Ubah Kata Sandi
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

@push('scripts')
<script>
function switchTab(id) {
    document.querySelectorAll('.tab-pane').forEach(p => p.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.classList.remove('border-sage', 'text-sage');
        b.classList.add('border-transparent', 'text-bark-muted');
    });
    document.getElementById(id).classList.remove('hidden');
    const btn = document.querySelector(`[data-tab="${id}"]`);
    btn.classList.add('border-sage', 'text-sage');
    btn.classList.remove('border-transparent', 'text-bark-muted');
}
</script>
@endpush

@endsection
