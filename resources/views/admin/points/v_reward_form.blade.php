@extends('layouts.v_backend')
@section('title', $reward ? 'Edit Reward' : 'Tambah Reward')
@section('content')

<x-page-header
    :title="$reward ? 'Edit Reward' : 'Tambah Reward'"
    :subtitle="$reward ? 'Perbarui detail reward item.' : 'Tambahkan reward baru yang dapat ditukarkan pengguna.'"
    :backRoute="route('admin.points.rewards')"
/>

<x-alert type="danger" :errors="$errors" />

<div class="be-card max-w-2xl">
    <div class="p-6 sm:p-8">
        <form action="{{ $reward ? route('admin.points.rewards.update', $reward) : route('admin.points.rewards.store') }}"
              method="POST">
            @csrf
            @if ($reward)
                @method('PUT')
            @endif

            <div class="space-y-5">

                <div>
                    <label class="form-label">Kode Reward <span class="text-red-400">*</span></label>
                    <input type="text" name="kode" value="{{ old('kode', $reward?->kode) }}"
                           class="input-field font-mono uppercase" placeholder="mis. DISC10, SOUVENIR01"
                           {{ $reward ? 'readonly' : '' }}>
                    <p class="form-hint">Kode unik, tidak dapat diubah setelah dibuat.</p>
                </div>

                <div>
                    <label class="form-label">Nama Reward <span class="text-red-400">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $reward?->nama) }}"
                           class="input-field" placeholder="mis. Diskon Adopsi 10%">
                </div>

                <div>
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" class="input-field" placeholder="Deskripsi singkat reward ini...">{{ old('deskripsi', $reward?->deskripsi) }}</textarea>
                </div>

                <div>
                    <label class="form-label">Kategori <span class="text-red-400">*</span></label>
                    <select name="kategori" class="input-field" id="kategori-select">
                        <option value="">— Pilih Kategori —</option>
                        <option value="diskon_adopsi"  {{ old('kategori', $reward?->kategori) === 'diskon_adopsi'  ? 'selected' : '' }}>Diskon Adopsi (digital)</option>
                        <option value="souvenir"       {{ old('kategori', $reward?->kategori) === 'souvenir'       ? 'selected' : '' }}>Souvenir (fisik)</option>
                        <option value="keperluan_sg"   {{ old('kategori', $reward?->kategori) === 'keperluan_sg'   ? 'selected' : '' }}>Keperluan SG (fisik)</option>
                    </select>
                </div>

                <div id="diskon-group" class="{{ old('kategori', $reward?->kategori) === 'diskon_adopsi' ? '' : 'hidden' }}">
                    <label class="form-label">Persentase Diskon (%)</label>
                    <input type="number" name="diskon_persen" value="{{ old('diskon_persen', $reward?->diskon_persen) }}"
                           class="input-field" placeholder="mis. 10" min="1" max="100">
                    <p class="form-hint">Isi jika reward berupa diskon persentase biaya adopsi.</p>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Poin yang Diperlukan <span class="text-red-400">*</span></label>
                        <input type="number" name="poin_required" value="{{ old('poin_required', $reward?->poin_required) }}"
                               class="input-field" placeholder="mis. 500" min="1">
                    </div>
                    <div>
                        <label class="form-label">Stok</label>
                        <input type="number" name="stok" value="{{ old('stok', $reward?->stok) }}"
                               class="input-field" placeholder="Kosongkan = tidak terbatas" min="0">
                        <p class="form-hint">Kosongkan untuk stok tak terbatas.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <input type="hidden" name="aktif" value="0">
                    <input type="checkbox" name="aktif" id="aktif" value="1"
                           class="w-4 h-4 rounded border-cream-dark text-sage focus:ring-sage"
                           {{ old('aktif', $reward ? ($reward->aktif ? '1' : '0') : '1') === '1' ? 'checked' : '' }}>
                    <label for="aktif" class="text-sm font-medium text-bark cursor-pointer">Aktifkan reward ini</label>
                </div>

            </div>

            <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-cream-dark">
                <a href="{{ route('admin.points.rewards') }}" class="btn-ghost">Batal</a>
                <button type="submit" class="btn-create">
                    <i class="bi bi-check-lg"></i>
                    {{ $reward ? 'Simpan Perubahan' : 'Tambah Reward' }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('kategori-select').addEventListener('change', function () {
    document.getElementById('diskon-group').classList.toggle('hidden', this.value !== 'diskon_adopsi');
});
</script>
@endpush

@endsection
