@extends('layouts.v_backend')

@section('title', 'Edit Penempatan')

@section('content')

<x-page-header
    :title="__('text.edit')"
    :subtitle="__('text.change_data')"
    :backRoute="route('collection.index')"
/>

<x-alert type="danger" :errors="$errors" />

<div class="be-card max-w-2xl">
    <div class="p-6 sm:p-8">
        <form action="{{ route('collection.update', $collection->id) }}" method="POST">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <div class="space-y-5">

                <div>
                    <label class="form-label">{{ __('text.shelter_name') }}</label>
                    <select name="shelter_id" class="input-field" required>
                        <option value="">Pilih Kandang</option>
                        @foreach ($shelters as $shelter)
                            <option value="{{ $shelter->id }}" @selected($collection->shelter_id == $shelter->id)>
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
                        <option value="{{ $collection->sugarglider_id }}" selected>
                            {{ $collection->sugarglider->nama }}
                        </option>
                        @foreach ($sugargliders as $sugarglider)
                            <option value="{{ $sugarglider->id }}">{{ $sugarglider->nama }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-bark-muted mt-1.5">
                        Sugar Glider tidak ditemukan?
                        <a href="{{ route('sugarglider.index') }}" class="text-sage font-semibold hover:underline">Tambah Sugar Glider baru</a>
                    </p>
                </div>

                <div>
                    <label class="form-label">{{ __('text.status') }}</label>
                    @if ($collection->status == '5')
                        <input type="hidden" name="status" value="5">
                        <div class="input-field bg-gray-50 text-bark-muted cursor-not-allowed flex items-center gap-2">
                            <span class="badge-done">Selesai</span>
                            <span class="text-xs">Status ini tidak dapat diubah.</span>
                        </div>
                    @else
                        <select name="status" class="input-field" required>
                            <option value="1" @selected($collection->status == '1')>Privat — Tidak ditampilkan ke publik</option>
                            <option value="2" @selected($collection->status == '2')>Publik — Ditampilkan ke publik</option>
                            @if ($collection->status != '4')
                                <option value="3" @selected($collection->status == '3')>Adopsi — Terbuka untuk diadopsi</option>
                            @endif
                            <option value="4" @selected($collection->status == '4')>Mati — Data tersimpan untuk history</option>
                        </select>
                        @if ($collection->status == '4')
                            <p class="form-hint text-amber-600">SG dengan status Mati tidak dapat dibuka untuk adopsi.</p>
                        @endif
                    @endif
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

@endsection
