@extends('layouts.v_backend')

@section('title', 'Edit Adopsi')

@section('content')

<x-page-header
    :title="__('text.edit') . ' — ' . $adoption->nama"
    :subtitle="__('text.change_data')"
    :backRoute="route('adoption.index')"
/>

@if ($errors->any())
    <div class="alert-danger mb-5">
        <i class="bi bi-exclamation-circle-fill text-lg flex-shrink-0"></i>
        <div>@foreach ($errors->all() as $err)<p>{{ $err }}</p>@endforeach</div>
    </div>
@endif

<div class="be-card max-w-2xl">
    <div class="p-6 sm:p-8">
        <form action="{{ route('adoption.update', $adoption->id) }}" method="POST">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <div class="space-y-5">

                <div>
                    <label class="form-label">{{ __('text.sugarglider_name') }}</label>
                    <select name="collection_id" class="input-field" required>
                        <option value="{{ $adoption->collection_id }}" selected>{{ $adoption->nama }}</option>
                        <option value="">— Ganti Sugar Glider —</option>
                        @foreach ($collections as $collection)
                            <option value="{{ $collection->id }}">{{ $collection->nama }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-bark-muted mt-1.5">
                        Sugar Glider tidak ditemukan? Tambahkan di halaman
                        <a href="{{ route('sugarglider.index') }}" class="text-sage font-semibold hover:underline">Sugar Glider</a>
                        lalu masukkan ke
                        <a href="{{ route('collection.index') }}" class="text-sage font-semibold hover:underline">Koleksi</a>.
                    </p>
                </div>

                <div>
                    <label class="form-label">{{ __('text.adoption_price') }}</label>
                    <input type="number" name="harga" value="{{ $adoption->harga }}"
                           min="0" placeholder="0"
                           class="input-field" required>
                </div>

                <div>
                    <label class="form-label">{{ __('text.description') }}</label>
                    <textarea name="keterangan" rows="3" placeholder="{{ __('text.description') }}"
                              class="input-field">{{ $adoption->keterangan }}</textarea>
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
