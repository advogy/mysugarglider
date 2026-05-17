@extends('layouts.v_backend')
@section('title', 'Konfigurasi Poin')
@section('content')

<x-page-header
    title="Konfigurasi Poin"
    subtitle="Atur nilai poin untuk setiap jenis aktivitas dan aturan sistem poin."
/>

<form action="{{ route('admin.points.configs.update') }}" method="POST">
    @csrf
    <div class="be-card max-w-2xl">
        <div class="p-6 sm:p-8">
            @if ($configs->isEmpty())
                <p class="text-bark-muted text-sm">Belum ada konfigurasi poin di database.</p>
            @else
                <div class="divide-y divide-cream-dark">
                    @foreach ($configs as $config)
                    <div class="py-4 flex items-start gap-4">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-bark font-mono">{{ $config->key }}</p>
                            @if ($config->keterangan)
                                <p class="text-xs text-bark-muted mt-0.5">{{ $config->keterangan }}</p>
                            @endif
                        </div>
                        <div class="flex-shrink-0 w-32">
                            <input type="number" name="configs[{{ $config->key }}]"
                                   value="{{ old('configs.' . $config->key, $config->value) }}"
                                   class="input-field text-right font-bold" min="0">
                        </div>
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
