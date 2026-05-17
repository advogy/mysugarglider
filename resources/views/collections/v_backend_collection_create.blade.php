@extends('layouts.v_backend')

@section('title', 'Tambah Penempatan')

@section('content')

<x-page-header
    :title="__('text.add_new')"
    :subtitle="__('text.input_data')"
    :backRoute="route('collection.index')"
/>

<x-alert type="danger" :errors="$errors" />

<div class="be-card max-w-2xl">
    <div class="p-6 sm:p-8">
        <form action="{{ route('collection.store') }}" method="POST">
            @csrf
            <div class="space-y-5">

                <div>
                    <label class="form-label">{{ __('text.shelter_name') }}</label>
                    <select name="shelter_id" class="input-field" required>
                        <option value="">Pilih Kandang</option>
                        @foreach ($shelters as $shelter)
                            <option value="{{ $shelter->id }}" @selected(old('shelter_id') == $shelter->id)>
                                {{ $shelter->nama }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-bark-muted mt-1.5">
                        Kandang tidak ditemukan?
                        <a href="{{ route('shelter.index') }}" class="text-sage font-semibold hover:underline">Tambah kandang baru</a>
                    </p>
                </div>

                <div>
                    <label class="form-label">{{ __('text.sugarglider_name') }}</label>
                    <select name="sugarglider_id" class="input-field" required>
                        <option value="">Pilih Sugar Glider</option>
                        @foreach ($sugargliders as $sugarglider)
                            <option value="{{ $sugarglider->id }}" @selected(old('sugarglider_id') == $sugarglider->id)>
                                {{ $sugarglider->nama }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-bark-muted mt-1.5">
                        Sugar Glider tidak ditemukan?
                        <a href="{{ route('sugarglider.index') }}" class="text-sage font-semibold hover:underline">Tambah Sugar Glider baru</a>
                    </p>
                </div>

                <div>
                    <label class="form-label">{{ __('text.status') }}</label>
                    <select name="status" class="input-field" required>
                        <option value="">Pilih Status</option>
                        <option value="1" @selected(old('status') == '1')>Privat — Tidak ditampilkan ke publik</option>
                        <option value="2" @selected(old('status') == '2')>Publik — Ditampilkan ke publik</option>
                        <option value="3" @selected(old('status') == '3')>Adopsi — Terbuka untuk diadopsi</option>
                    </select>
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
