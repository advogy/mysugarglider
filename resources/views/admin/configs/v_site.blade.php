@extends('layouts.v_backend')
@section('title', 'Sistem Konfigurasi')
@section('content')

<x-page-header
    title="Sistem Konfigurasi"
    subtitle="Pengaturan umum situs yang berlaku secara global."
/>

@php
    $maintenanceOn  = old('configs.maintenance_mode', $maintenance->firstWhere('key', 'maintenance_mode')?->value ?? '0') === '1';
    $maintenanceMsg = old('configs.maintenance_message', $maintenance->firstWhere('key', 'maintenance_message')?->value ?? '');
@endphp

{{-- ── Maintenance Mode Card ── --}}
<form action="{{ route('admin.configs.maintenance.update') }}" method="POST" class="mb-5">
    @csrf
    <div class="be-card overflow-hidden max-w-2xl">
        <div class="px-5 py-4 border-b border-cream-dark flex items-center gap-2 {{ $maintenanceOn ? 'bg-red-50' : 'bg-cream/40' }}">
            <i class="bi bi-cone-striped text-sm flex-shrink-0 {{ $maintenanceOn ? 'text-red-500' : 'text-bark-muted' }}"></i>
            <p class="font-ui font-bold text-bark text-sm">Mode Maintenance</p>
            @if ($maintenanceOn)
                <span class="ml-auto inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-red-100 text-red-600 text-xs font-bold">
                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span> Aktif
                </span>
            @endif
        </div>
        <div class="p-5 space-y-5">

            {{-- Toggle --}}
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold text-bark">Aktifkan Mode Maintenance</p>
                    <p class="text-xs text-bark-muted mt-0.5">Saat aktif, hanya admin yang bisa login. Pengguna biasa akan melihat pesan maintenance.</p>
                </div>
                <label class="flex-shrink-0 relative cursor-pointer">
                    <input type="hidden" name="configs[maintenance_mode]" value="0">
                    <input type="checkbox" name="configs[maintenance_mode]" value="1"
                           id="maintenance-toggle"
                           class="sr-only peer" {{ $maintenanceOn ? 'checked' : '' }}
                           onchange="toggleMaintenanceMsg(this.checked)">
                    <div class="w-11 h-6 bg-gray-200 rounded-full peer
                                peer-checked:bg-red-500
                                after:content-[''] after:absolute after:top-0.5 after:left-0.5
                                after:bg-white after:rounded-full after:h-5 after:w-5
                                after:transition-all peer-checked:after:translate-x-5"></div>
                </label>
            </div>

            {{-- Message — hanya tampil saat toggle aktif --}}
            <div id="maintenance-msg-section"
                 class="border-t border-cream-dark pt-4 {{ $maintenanceOn ? '' : 'hidden' }}">
                <label class="text-sm font-semibold text-bark-light block mb-1.5">Pesan Maintenance</label>
                <p class="text-xs text-bark-muted mb-2">Ditampilkan di halaman login saat maintenance aktif.</p>
                <textarea name="configs[maintenance_message]"
                          rows="3"
                          class="input-field resize-none">{{ $maintenanceMsg }}</textarea>
            </div>

            <div class="flex justify-end border-t border-cream-dark pt-4">
                <button type="submit" class="btn-create">
                    <i class="bi bi-floppy2-fill"></i> Simpan
                </button>
            </div>
        </div>
    </div>
</form>

{{-- ── Site Config Card ── --}}
@php
    $siteConfigs  = $configs->filter(fn($c) => !str_starts_with($c->key, 'admin_'));
    $adminConfigs = $configs->filter(fn($c) =>  str_starts_with($c->key, 'admin_'));
@endphp

<form action="{{ route('admin.configs.site.update') }}" method="POST" class="space-y-5">
    @csrf

    {{-- Info Situs --}}
    <div class="be-card overflow-hidden max-w-2xl">
        <div class="px-5 py-4 border-b border-cream-dark flex items-center gap-2 bg-cream/40">
            <i class="bi bi-globe text-sm text-bark-muted flex-shrink-0"></i>
            <p class="font-ui font-bold text-bark text-sm">Informasi Situs</p>
        </div>
        <div class="p-5">
            @if ($siteConfigs->isEmpty())
                <p class="text-bark-muted text-sm">Belum ada konfigurasi. Jalankan migrasi terlebih dahulu.</p>
            @else
                <div class="divide-y divide-cream-dark">
                    @foreach ($siteConfigs as $config)
                    <div class="py-4">
                        <label class="form-label">{{ $config->label ?? $config->key }}</label>
                        @if ($config->keterangan)
                            <p class="form-hint -mt-1 mb-2">{{ $config->keterangan }}</p>
                        @endif
                        @if ($config->type === 'textarea')
                            <textarea name="configs[{{ $config->key }}]" rows="4"
                                      class="input-field resize-none">{{ old('configs.' . $config->key, $config->value) }}</textarea>
                        @else
                            <input type="{{ $config->type === 'number' ? 'number' : 'text' }}"
                                   name="configs[{{ $config->key }}]"
                                   value="{{ old('configs.' . $config->key, $config->value) }}"
                                   class="input-field">
                        @endif
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Rekening Escrow & Biaya Platform --}}
    @if ($adminConfigs->isNotEmpty())
    <div class="be-card overflow-hidden max-w-2xl">
        <div class="px-5 py-4 border-b border-cream-dark flex items-center gap-2 bg-cream/40">
            <i class="bi bi-bank text-sm text-bark-muted flex-shrink-0"></i>
            <p class="font-ui font-bold text-bark text-sm">Rekening Escrow & Biaya Platform</p>
        </div>
        <div class="p-5">
            <div class="mb-4 p-3 rounded-xl bg-blue-50 border border-blue-200 text-xs text-blue-800 flex gap-2">
                <i class="bi bi-info-circle-fill flex-shrink-0 mt-0.5"></i>
                Dana adopsi berbayar diterima di rekening ini, lalu dicairkan ke pemilik setelah pemohon mengkonfirmasi penerimaan.
            </div>
            <div class="divide-y divide-cream-dark">
                @foreach ($adminConfigs as $config)
                <div class="py-4">
                    <label class="form-label">{{ $config->label ?? $config->key }}</label>
                    @if ($config->keterangan)
                        <p class="form-hint -mt-1 mb-2">{{ $config->keterangan }}</p>
                    @endif
                    <input type="{{ $config->type === 'number' ? 'number' : 'text' }}"
                           name="configs[{{ $config->key }}]"
                           value="{{ old('configs.' . $config->key, $config->value) }}"
                           class="input-field {{ $config->type === 'number' ? 'w-40' : '' }}"
                           {{ $config->type === 'number' ? 'min=0' : '' }}>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <div class="max-w-2xl flex justify-end">
        <button type="submit" class="btn-create">
            <i class="bi bi-floppy2-fill"></i> Simpan Konfigurasi
        </button>
    </div>
</form>

@endsection

@push('scripts')
<script>
function toggleMaintenanceMsg(checked) {
    document.getElementById('maintenance-msg-section').classList.toggle('hidden', !checked);
}
</script>
@endpush
