@extends('layouts.v_backend')
@section('title', 'Manajemen Halaman Publik')
@section('content')

<x-page-header
    title="Manajemen Halaman Publik"
    subtitle="Kelola konten yang ditampilkan di halaman publik situs."
/>

<form action="{{ route('admin.configs.halaman.update') }}" method="POST">
    @csrf
    <div class="be-card max-w-2xl">
        <div class="p-6 sm:p-8">
            @if ($configs->isEmpty())
                <p class="text-bark-muted text-sm">Belum ada konfigurasi halaman di database.</p>
            @else
                <div class="divide-y divide-cream-dark">
                    @foreach ($configs as $config)
                    <div class="py-4">
                        <label class="form-label">{{ $config->label ?? $config->key }}</label>
                        @if ($config->keterangan)
                            <p class="form-hint -mt-1 mb-2">{{ $config->keterangan }}</p>
                        @endif
                        @if ($config->type === 'textarea')
                            <textarea name="configs[{{ $config->key }}]" rows="5"
                                      class="input-field">{{ old('configs.' . $config->key, $config->value) }}</textarea>
                        @else
                            <input type="text" name="configs[{{ $config->key }}]"
                                   value="{{ old('configs.' . $config->key, $config->value) }}"
                                   class="input-field">
                        @endif
                    </div>
                    @endforeach
                </div>

                <div class="flex justify-end pt-6 mt-2 border-t border-cream-dark">
                    <button type="submit" class="btn-create">
                        <i class="bi bi-floppy2-fill"></i> Simpan Konten
                    </button>
                </div>
            @endif
        </div>
    </div>
</form>

@endsection
