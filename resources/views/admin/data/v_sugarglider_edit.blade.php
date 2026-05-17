@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.min.css">
<style>
.ts-wrapper.full .ts-control { box-shadow: none !important; }
.ts-control { border: 1px solid #e2d9ce !important; border-radius: 0.75rem !important; padding: 0.5rem 0.75rem !important; font-size: 0.875rem !important; background: white !important; min-height: 2.5rem !important; }
.ts-control input { font-size: 0.875rem !important; }
.ts-dropdown { border: 1px solid #e2d9ce !important; border-radius: 0.75rem !important; box-shadow: 0 4px 12px rgba(0,0,0,.08) !important; font-size: 0.875rem !important; margin-top: 4px !important; }
.ts-dropdown .option { padding: 0.5rem 0.75rem !important; }
.ts-dropdown .option.selected, .ts-dropdown .option:hover { background: #f0f5f0 !important; color: #2d5a2d !important; }
.ts-dropdown .optgroup-header { padding: 0.4rem 0.75rem !important; font-size: 0.7rem !important; font-weight: 700 !important; color: #9e8e7e !important; text-transform: uppercase !important; letter-spacing: 0.05em !important; background: #faf8f5 !important; }
.ts-dropdown .no-results { padding: 0.5rem 0.75rem !important; color: #9e8e7e !important; }
.ts-wrapper .ts-control .item { background: #eef4ee !important; border-radius: 0.5rem !important; padding: 2px 8px !important; color: #2d5a2d !important; font-size: 0.8rem !important; }
</style>
@endpush

@extends('layouts.v_backend')
@section('title', 'Edit Sugar Glider')
@section('content')

<x-page-header
    :title="'Edit Sugar Glider — ' . $sugarglider->nama"
    :subtitle="'Pemilik: ' . ($sugarglider->user?->name ?? '—')"
    :backRoute="route('admin.data.sugargliders')"
/>

<x-alert type="danger" :errors="$errors" />

<div class="be-card max-w-2xl">
    <div class="p-6 sm:p-8">
        <form action="{{ route('admin.data.sugargliders.update', $sugarglider) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" value="{{ old('nama', $sugarglider->nama) }}" class="input-field" required>
                    </div>
                    <div>
                        <label class="form-label">Kode</label>
                        <input type="text" name="kode" value="{{ old('kode', $sugarglider->kode) }}" class="input-field" required>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="kelamin" class="input-field">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="1" @selected(old('kelamin', $sugarglider->kelamin) == '1')>Jantan</option>
                            <option value="0" @selected(old('kelamin', $sugarglider->kelamin) == '0')>Betina</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Tanggal Lahir (OOP)</label>
                        <input type="date" name="tgl_lahir" value="{{ old('tgl_lahir', $sugarglider->tgl_lahir) }}" class="input-field" required>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Jenis</label>
                        <input type="text" name="jenis" value="{{ old('jenis', $sugarglider->jenis) }}" class="input-field" required>
                    </div>
                    <div>
                        <label class="form-label">Warna</label>
                        <input type="text" name="warna" value="{{ old('warna', $sugarglider->warna) }}" class="input-field" required>
                    </div>
                </div>
                <div>
                    <label class="form-label">Genetika</label>
                    <input type="text" name="genetika" value="{{ old('genetika', $sugarglider->genetika) }}" class="input-field">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Indukan Jantan</label>
                        <select id="ts-indukan-jantan" name="indukan_jantan">
                            <option value="">Tidak diketahui / Tidak diisi</option>
                            @if ($indukanJantan)
                                <option value="{{ $indukanJantan->id }}" selected>
                                    {{ $indukanJantan->nama }} – {{ $indukanJantan->jenis }}
                                </option>
                            @endif
                        </select>
                        <p class="form-hint">Ketik nama untuk mencari</p>
                    </div>
                    <div>
                        <label class="form-label">Indukan Betina</label>
                        <select id="ts-indukan-betina" name="indukan_betina">
                            <option value="">Tidak diketahui / Tidak diisi</option>
                            @if ($indukanBetina)
                                <option value="{{ $indukanBetina->id }}" selected>
                                    {{ $indukanBetina->nama }} – {{ $indukanBetina->jenis }}
                                </option>
                            @endif
                        </select>
                        <p class="form-hint">Ketik nama untuk mencari</p>
                    </div>
                </div>
                <div>
                    <label class="form-label">Fenotype</label>
                    <textarea name="fenotype" rows="3" class="input-field">{{ old('fenotype', $sugarglider->fenotype) }}</textarea>
                </div>
                <div>
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" rows="3" class="input-field">{{ old('keterangan', $sugarglider->keterangan) }}</textarea>
                </div>
                <div>
                    <label class="form-label">Foto</label>
                    @if ($sugarglider->gambar)
                        <div class="mb-3">
                            <img src="{{ asset('/upload/sugargliders/' . $sugarglider->gambar) }}"
                                 class="w-32 h-32 rounded-2xl object-cover border border-cream-dark" alt="">
                        </div>
                    @endif
                    <input type="file" name="gambar"
                           class="w-full text-sm text-bark-muted file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sage/10 file:text-sage cursor-pointer">
                    <p class="form-hint">Biarkan kosong jika tidak ingin mengubah foto.</p>
                </div>

                <div class="flex justify-end gap-3 pt-2 border-t border-cream-dark">
                    <a href="{{ route('admin.data.sugargliders') }}" class="btn-ghost">Batal</a>
                    <button type="submit" class="btn-create">
                        <i class="bi bi-check-lg"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
function makeTomSelect(elId, kelamin, selectedId, selectedText) {
    const parentsUrl = "{{ route('sugarglider.parents') }}";
    const excludeId  = {{ $sugarglider->id }};

    const opts  = selectedId ? [{ value: selectedId, text: selectedText, group: 'other' }] : [];
    const items = selectedId ? [selectedId] : [];

    return new TomSelect('#' + elId, {
        valueField: 'value',
        labelField: 'text',
        searchField: 'text',
        optgroupField: 'group',
        optgroups: [{ value: 'mine', label: '— Kandang Saya' }, { value: 'other', label: '— Kandang Lain' }],
        options: opts,
        items: items,
        placeholder: 'Ketik nama untuk mencari...',
        allowEmptyOption: true,
        shouldLoad: (q) => q.length >= 1,
        load(q, callback) {
            fetch(`${parentsUrl}?q=${encodeURIComponent(q)}&kelamin=${kelamin}&exclude=${excludeId}`)
                .then(r => r.json()).then(callback).catch(() => callback());
        },
        render: {
            no_results: () => '<div class="no-results">Tidak ditemukan</div>',
            loading:    () => '<div class="no-results">Mencari...</div>',
        },
    });
}

makeTomSelect(
    'ts-indukan-jantan', 1,
    @if($indukanJantan) {{ $indukanJantan->id }}, @json($indukanJantan->nama . ' – ' . $indukanJantan->jenis) @else null, null @endif
);
makeTomSelect(
    'ts-indukan-betina', 0,
    @if($indukanBetina) {{ $indukanBetina->id }}, @json($indukanBetina->nama . ' – ' . $indukanBetina->jenis) @else null, null @endif
);
</script>
@endpush
