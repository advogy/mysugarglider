@extends('layouts.v_backend')

@section('title', 'Profil')

@section('content')

<x-page-header
    :title="__('text.profile')"
    subtitle="Perbarui data Anda."
/>

<x-alert type="danger" :errors="$errors" />

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
            <button data-tab="tab-bank" onclick="switchTab('tab-bank')"
                    class="tab-btn px-5 py-3.5 text-sm font-bold border-b-2 border-transparent text-bark-muted whitespace-nowrap hover:text-bark transition-colors">
                <i class="bi bi-bank mr-1"></i> Rekening Bank
            </button>
        </div>

        {{-- Tab: Profil --}}
        <div id="tab-profile" class="tab-pane p-6">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="form-label">{{ __('text.address') }}</label>
                        <input type="text" name="alamat" value="{{ $profile?->alamat ?? '' }}"
                               placeholder="{{ __('text.address') }}"
                               class="input-field" required>
                    </div>
                    <div>
                        <label class="form-label">{{ __('text.telp') }} / No. WhatsApp</label>
                        <input type="text" name="telepon" value="{{ $profile?->telepon ?? '' }}"
                               placeholder="{{ __('text.telp') }}"
                               class="input-field" required>
                    </div>
                    <div>
                        <label class="form-label">Kode Profil <span class="text-xs font-normal text-bark-muted">(inisial kode Sugar Glider)</span></label>
                        <input type="text" name="kode_profil" id="kode-profil-input"
                               value="{{ old('kode_profil', $profile?->kode_profil ?? '') }}"
                               placeholder="CTH: ASG"
                               maxlength="3"
                               class="input-field font-mono uppercase tracking-widest w-32"
                               oninput="this.value = this.value.toUpperCase().replace(/[^A-Z]/g,''); updateKodePreview(this.value)"
                               required>
                        <p class="form-hint mt-1">
                            Tepat 3 huruf kapital. Kode SG Anda akan berformat:
                            <span id="kode-preview" class="font-mono font-bold text-sage">{{ $profile?->kode_profil ? $profile->kode_profil . '-0001' : 'ASG-0001' }}</span>
                        </p>
                        @if ($profile?->kode_profil)
                            <p class="text-xs text-amber-600 mt-1">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                Mengubah kode profil tidak akan memperbarui kode Sugar Glider yang sudah ada.
                            </p>
                        @endif
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

        {{-- Tab: Rekening Bank --}}
        <div id="tab-bank" class="tab-pane hidden p-6">
            @if (session('pesan_bank'))
                <div class="alert-success mb-5">
                    <i class="bi bi-check-circle-fill text-lg flex-shrink-0"></i>
                    <p class="font-semibold">{{ session('pesan_bank') }}</p>
                </div>
            @endif

            <div class="mb-5 p-4 rounded-xl bg-amber-50 border border-amber-200 flex gap-3">
                <i class="bi bi-info-circle-fill text-amber-500 flex-shrink-0 mt-0.5"></i>
                <div class="text-sm text-amber-800">
                    <p class="font-bold mb-0.5">Mengapa perlu rekening bank?</p>
                    <p>Setelah proses adopsi selesai dan pemohon mengkonfirmasi penerimaan sugar glider, admin platform akan mencairkan dana ke rekening ini.</p>
                </div>
            </div>

            <form action="{{ route('profile.update.bank') }}" method="POST">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="form-label">Nama Bank</label>
                        <input type="text" name="bank_name"
                               value="{{ old('bank_name', $profile?->bank_name ?? '') }}"
                               placeholder="Contoh: BRI, BCA, Mandiri, BSI"
                               class="input-field">
                    </div>
                    <div>
                        <label class="form-label">Nomor Rekening</label>
                        <input type="text" name="bank_account_number"
                               value="{{ old('bank_account_number', $profile?->bank_account_number ?? '') }}"
                               placeholder="Contoh: 1234567890"
                               class="input-field font-mono tracking-wider">
                    </div>
                    <div>
                        <label class="form-label">Nama Pemilik Rekening</label>
                        <input type="text" name="bank_account_name"
                               value="{{ old('bank_account_name', $profile?->bank_account_name ?? '') }}"
                               placeholder="Sesuai buku tabungan"
                               class="input-field">
                        <p class="form-hint">Pastikan nama sesuai dengan buku tabungan untuk menghindari gagal transfer.</p>
                    </div>

                    @if ($profile?->bank_name && $profile?->bank_account_number)
                        <div class="p-4 rounded-xl bg-sage/10 border border-sage/20 flex items-center gap-3">
                            <i class="bi bi-check-circle-fill text-sage flex-shrink-0"></i>
                            <div class="text-sm">
                                <p class="font-bold text-bark">{{ $profile->bank_name }}</p>
                                <p class="font-mono text-bark-muted">{{ $profile->bank_account_number }} · {{ $profile->bank_account_name }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="flex justify-end">
                        <button type="submit" class="btn-create">
                            <i class="bi bi-check-lg"></i> Simpan Rekening
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
@if (session('pesan_bank'))
document.addEventListener('DOMContentLoaded', () => switchTab('tab-bank'));
@endif
function updateKodePreview(val) {
    const preview = document.getElementById('kode-preview');
    if (preview) {
        preview.textContent = val.length === 3 ? val + '-0001' : (val || 'ASG') + '-0001';
    }
}
</script>
@endpush

@endsection
