@extends('layouts.v_backend')
@section('title', 'Sistem Konfigurasi')
@section('content')

<x-page-header
    title="Sistem Konfigurasi"
    subtitle="Pengaturan umum situs yang berlaku secara global."
/>

<form action="{{ route('admin.configs.site.update') }}" method="POST">
    @csrf
    <div class="be-card max-w-2xl">
        <div class="p-6 sm:p-8">
            @if ($configs->isEmpty())
                <p class="text-bark-muted text-sm">Belum ada konfigurasi di database. Jalankan migrasi terlebih dahulu.</p>
            @else
                <div class="divide-y divide-cream-dark">
                    @foreach ($configs as $config)
                    <div class="py-4">
                        <label class="form-label">{{ $config->label ?? $config->key }}</label>
                        @if ($config->keterangan)
                            <p class="form-hint -mt-1 mb-2">{{ $config->keterangan }}</p>
                        @endif
                        <input type="{{ $config->type === 'number' ? 'number' : 'text' }}"
                               name="configs[{{ $config->key }}]"
                               value="{{ old('configs.' . $config->key, $config->value) }}"
                               class="input-field">
                    </div>
                    @endforeach
                </div>

                <div class="flex justify-end pt-6 mt-2 border-t border-cream-dark">
                    <button type="submit" class="btn-create">
                        <i class="bi bi-floppy2-fill"></i> Simpan Konfigurasi
                    </button>
                </div>
            @endif
        </div>
    </div>
</form>

@endsection
