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

@section('title', 'Tambah Sugar Glider')

@section('content')

<x-page-header
    :title="__('text.add_new')"
    :subtitle="__('text.input_data')"
    :backRoute="route('sugarglider.index')"
/>

@if ($errors->any())
    <div class="alert-danger mb-5">
        <i class="bi bi-exclamation-circle-fill text-lg flex-shrink-0"></i>
        <div>@foreach ($errors->all() as $err)<p>{{ $err }}</p>@endforeach</div>
    </div>
@endif

<div class="be-card max-w-2xl">
    <div class="p-6 sm:p-8">
        <form action="{{ route('sugarglider.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">{{ __('text.sugarglider_name') }}</label>
                        <input type="text" name="nama" value="{{ old('nama') }}" class="input-field" required>
                    </div>
                    <div>
                        <label class="form-label">{{ __('text.code') }}</label>
                        <input type="text" name="kode" value="{{ old('kode') }}" class="input-field" required>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">{{ __('text.gender') }}</label>
                        <select name="kelamin" class="input-field">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="1" @selected(old('kelamin') == '1')>{{ __('text.male') }}</option>
                            <option value="0" @selected(old('kelamin') == '0')>{{ __('text.female') }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">{{ __('text.oop_date') }}</label>
                        <input type="date" name="tgl_lahir" value="{{ old('tgl_lahir') }}" class="input-field" required>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">{{ __('text.type') }}</label>
                        <input type="text" name="jenis" value="{{ old('jenis') }}" class="input-field" required>
                    </div>
                    <div>
                        <label class="form-label">{{ __('text.color') }}</label>
                        <input type="text" name="warna" value="{{ old('warna') }}" class="input-field" required>
                    </div>
                </div>
                <div>
                    <label class="form-label">{{ __('text.genetics') }}</label>
                    <input type="text" name="genetika" value="{{ old('genetika') }}" class="input-field">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">{{ __('text.parent_male') }}</label>
                        <select id="ts-indukan-jantan" name="indukan_jantan">
                            <option value="">Tidak diketahui / Tidak diisi</option>
                        </select>
                        <p class="form-hint">Ketik nama untuk mencari • Kosongkan jika tidak diketahui</p>
                    </div>
                    <div>
                        <label class="form-label">{{ __('text.parent_female') }}</label>
                        <select id="ts-indukan-betina" name="indukan_betina">
                            <option value="">Tidak diketahui / Tidak diisi</option>
                        </select>
                        <p class="form-hint">Ketik nama untuk mencari • Kosongkan jika tidak diketahui</p>
                    </div>
                </div>
                <div>
                    <label class="form-label">{{ __('text.fenotype') }}</label>
                    <textarea name="fenotype" rows="3" class="input-field">{{ old('fenotype') }}</textarea>
                </div>
                <div>
                    <label class="form-label">{{ __('text.description') }}</label>
                    <textarea name="keterangan" rows="3" class="input-field">{{ old('keterangan') }}</textarea>
                </div>
                <div>
                    <label class="form-label">{{ __('text.image') }}</label>
                    <input type="file" name="gambar" class="w-full text-sm text-bark-muted file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sage/10 file:text-sage cursor-pointer">
                    <p class="form-hint">Ukuran foto: 500×500px</p>
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit" class="btn-create">
                        <i class="bi bi-check-lg"></i> {{ __('text.submit') }}
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
function makeTomSelect(elId, kelamin) {
    const parentsUrl = "{{ route('sugarglider.parents') }}";
    const optgroups  = [
        { value: 'mine',  label: '— Kandang Saya' },
        { value: 'other', label: '— Kandang Lain' },
    ];
    return new TomSelect('#' + elId, {
        valueField:    'value',
        labelField:    'text',
        searchField:   'text',
        optgroupField: 'group',
        optgroups:     optgroups,
        options:       [],
        placeholder:   'Ketik nama untuk mencari...',
        allowEmptyOption: true,
        shouldLoad: (q) => q.length >= 1,
        load(q, callback) {
            fetch(`${parentsUrl}?q=${encodeURIComponent(q)}&kelamin=${kelamin}`)
                .then(r => r.json())
                .then(callback)
                .catch(() => callback());
        },
        render: {
            no_results: () => '<div class="no-results">Tidak ditemukan</div>',
            loading:    () => '<div class="no-results">Mencari...</div>',
        },
    });
}
makeTomSelect('ts-indukan-jantan', 1);
makeTomSelect('ts-indukan-betina', 0);
</script>
@endpush
