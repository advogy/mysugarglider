@extends('layouts.v_backend')
@section('title', 'Edit Kandang')
@section('content')

<x-page-header
    :title="'Edit Kandang — ' . $shelter->nama"
    :subtitle="'Pemilik: ' . ($shelter->user?->name ?? '—')"
    :backRoute="route('admin.data.shelters')"
/>

<x-alert type="danger" :errors="$errors" />

<div class="be-card max-w-2xl">
    <div class="p-6 sm:p-8">
        <form action="{{ route('admin.data.shelters.update', $shelter) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Nama Kandang</label>
                        <input type="text" name="nama" value="{{ old('nama', $shelter->nama) }}" class="input-field" required>
                    </div>
                    <div>
                        <label class="form-label">Kode</label>
                        <input type="text" name="kode" value="{{ old('kode', $shelter->kode) }}" class="input-field" required>
                    </div>
                </div>
                <div>
                    <label class="form-label">Alamat</label>
                    <input type="text" name="alamat" value="{{ old('alamat', $shelter->alamat) }}" class="input-field" required>
                </div>
                <div>
                    <label class="form-label">Google Maps Embed</label>
                    <input type="text" name="gmaps" value="{{ old('gmaps', $shelter->gmaps) }}" class="input-field">
                    <p class="form-hint">Kode embed Google Maps.</p>
                </div>
                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="input-field">
                        <option value="1" @selected(old('status', $shelter->status) == '1')>Aktif / Buka</option>
                        <option value="0" @selected(old('status', $shelter->status) == '0')>Nonaktif / Tutup</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" rows="3" class="input-field">{{ old('keterangan', $shelter->keterangan) }}</textarea>
                </div>
                <div>
                    <label class="form-label">Logo / Foto</label>
                    @if ($shelter->gambar)
                        <div class="mb-3">
                            <img src="{{ asset('/upload/shelters/' . $shelter->gambar) }}"
                                 class="w-24 h-24 rounded-2xl object-cover border border-cream-dark" alt="">
                        </div>
                    @endif
                    <input type="file" name="gambar"
                           class="w-full text-sm text-bark-muted file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sage/10 file:text-sage cursor-pointer">
                    <p class="form-hint">Biarkan kosong jika tidak ingin mengubah foto.</p>
                </div>

                <div class="flex justify-end gap-3 pt-2 border-t border-cream-dark">
                    <a href="{{ route('admin.data.shelters') }}" class="btn-ghost">Batal</a>
                    <button type="submit" class="btn-create">
                        <i class="bi bi-check-lg"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
