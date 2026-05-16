@extends('layouts.v_backend')

@section('title', 'Tambah Kandang')

@section('content')

<x-page-header
    :title="__('text.add_new')"
    :subtitle="__('text.input_data')"
    :backRoute="route('shelter.index')"
/>

@if ($errors->any())
    <div class="alert-danger mb-5">
        <i class="bi bi-exclamation-circle-fill text-lg flex-shrink-0"></i>
        <div>@foreach ($errors->all() as $err)<p>{{ $err }}</p>@endforeach</div>
    </div>
@endif

<div class="be-card max-w-2xl">
    <div class="p-6 sm:p-8">
        <form action="{{ route('shelter.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">{{ __('text.name') }}</label>
                        <input type="text" name="nama" value="{{ old('nama') }}" class="input-field" required>
                    </div>
                    <div>
                        <label class="form-label">{{ __('text.code') }}</label>
                        <input type="text" name="kode" value="{{ old('kode') }}" class="input-field" required>
                    </div>
                </div>
                <div>
                    <label class="form-label">{{ __('text.address') }}</label>
                    <input type="text" name="alamat" value="{{ old('alamat') }}" class="input-field" required>
                </div>
                <div>
                    <label class="form-label">{{ __('text.gmaps') }}</label>
                    <input type="text" name="gmaps" value="{{ old('gmaps') }}" class="input-field">
                    <p class="form-hint">Masukkan kode embed Google Maps. Contoh: <span class="font-mono">https://www.google.com/maps/embed?pb=m18!1m12...</span></p>
                </div>
                <div>
                    <label class="form-label">{{ __('text.status') }}</label>
                    <select name="status" class="input-field">
                        <option value="">Pilih Status</option>
                        <option value="1" @selected(old('status') == '1')>{{ __('text.open') }}</option>
                        <option value="0" @selected(old('status') == '0')>{{ __('text.close') }}</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">{{ __('text.description') }}</label>
                    <textarea name="keterangan" rows="3" class="input-field">{{ old('keterangan') }}</textarea>
                </div>
                <div>
                    <label class="form-label">{{ __('text.logo') }}</label>
                    <input type="file" name="gambar" class="w-full text-sm text-bark-muted file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sage/10 file:text-sage cursor-pointer">
                    <p class="form-hint">Ukuran logo: 150×150px</p>
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
