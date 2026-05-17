@extends('layouts.v_backend')

@section('title', 'Edit Kandang')

@section('content')

<x-page-header
    :title="__('text.edit') . ' — ' . $shelter->nama"
    :subtitle="__('text.change_data')"
    :backRoute="route('shelter.index')"
/>

<x-alert type="danger" :errors="$errors" />

<div class="be-card max-w-2xl">
    <div class="p-6 sm:p-8">
        <form action="{{ route('shelter.update', $shelter->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <div class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">{{ __('text.name') }}</label>
                        <input type="text" name="nama" value="{{ $shelter->nama }}" class="input-field" required>
                    </div>
                    <div>
                        <label class="form-label">{{ __('text.code') }}</label>
                        <input type="text" name="kode" value="{{ $shelter->kode }}" class="input-field" required>
                    </div>
                </div>
                <div>
                    <label class="form-label">{{ __('text.address') }}</label>
                    <input type="text" name="alamat" value="{{ $shelter->alamat }}" class="input-field" required>
                </div>
                <div>
                    <label class="form-label">{{ __('text.gmaps') }}</label>
                    <input type="text" id="gmaps-input" name="gmaps" value="{{ $shelter->gmaps }}" class="input-field"
                           placeholder="Tempel URL embed Google Maps di sini...">
                    <p class="form-hint">Salin dari Google Maps → Bagikan → Sematkan peta → salin URL src iframe.</p>
                    <div id="maps-preview" class="{{ $shelter->gmaps ? '' : 'hidden' }} mt-3 rounded-2xl overflow-hidden border border-cream-dark" style="height:220px;">
                        <iframe id="maps-preview-frame" class="w-full h-full border-0"
                                src="{{ $shelter->gmaps ? (str_starts_with($shelter->gmaps, 'http') ? $shelter->gmaps : 'https://www.google.com/maps/embed?pb=' . $shelter->gmaps) : '' }}"
                                allowfullscreen loading="lazy"></iframe>
                    </div>
                </div>
                <div>
                    <label class="form-label">{{ __('text.status') }}</label>
                    <select name="status" class="input-field">
                        <option value="">Pilih Status</option>
                        <option value="1" @selected($shelter->status == '1')>{{ __('text.open') }}</option>
                        <option value="0" @selected($shelter->status == '0')>{{ __('text.close') }}</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">{{ __('text.description') }}</label>
                    <textarea name="keterangan" rows="3" class="input-field">{{ $shelter->keterangan }}</textarea>
                </div>
                <div>
                    <label class="form-label">{{ __('text.logo') }}</label>
                    @if ($shelter->gambar)
                        <div class="mb-3">
                            <img src="{{ asset('/upload/shelters/' . $shelter->gambar) }}"
                                 class="w-24 h-24 rounded-2xl object-cover border border-cream-dark" alt="">
                        </div>
                    @endif
                    <input type="file" name="gambar" class="w-full text-sm text-bark-muted file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sage/10 file:text-sage cursor-pointer">
                    <p class="form-hint">Ukuran logo: 150×150px</p>
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit" class="btn-create">
                        <i class="bi bi-check-lg"></i> {{ __('text.save') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const input   = document.getElementById('gmaps-input');
    const preview = document.getElementById('maps-preview');
    const frame   = document.getElementById('maps-preview-frame');
    let timer;

    function toEmbedUrl(val) {
        val = val.trim();
        if (!val) return '';
        return val.startsWith('http') ? val : 'https://www.google.com/maps/embed?pb=' + val;
    }

    input.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(function () {
            const url = toEmbedUrl(input.value);
            if (!url) {
                preview.classList.add('hidden');
                frame.src = '';
            } else {
                frame.src = url;
                preview.classList.remove('hidden');
            }
        }, 700);
    });
})();
</script>
@endpush

@endsection
