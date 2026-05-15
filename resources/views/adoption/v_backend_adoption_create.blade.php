@extends('layouts.v_backend')

@section('title', 'Tambah Adopsi')

@section('content')

<div class="flex items-center gap-4 mb-6">
    <a href="{{ route('adoption.index') }}" class="text-bark-muted hover:text-bark transition-colors">
        <i class="bi bi-arrow-left text-xl"></i>
    </a>
    <div>
        <h2 class="text-xl font-bold text-bark">{{ __('text.add_new') }}</h2>
        <p class="text-bark-muted text-sm mt-0.5">{{ __('text.input_data') }}</p>
    </div>
</div>

@if ($errors->any())
    <div class="alert-danger mb-5">
        <i class="bi bi-exclamation-circle-fill text-lg flex-shrink-0"></i>
        <div>@foreach ($errors->all() as $err)<p>{{ $err }}</p>@endforeach</div>
    </div>
@endif

<div class="be-card max-w-2xl">
    <div class="p-6 sm:p-8">
        <form action="{{ route('adoption.store') }}" method="POST">
            @csrf
            <div class="space-y-5">

                <div>
                    <label class="form-label">{{ __('text.sugarglider_name') }}</label>
                    <select name="collection_id" class="input-field" required>
                        <option value="">Pilih Sugar Glider</option>
                        @foreach ($collections as $collection)
                            <option value="{{ $collection->id }}" @selected(old('collection_id') == $collection->id)>
                                {{ $collection->nama }}
                            </option>
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
                    <input type="number" name="harga" value="{{ old('harga') }}"
                           min="0" placeholder="0"
                           class="input-field" required>
                </div>

                <div>
                    <label class="form-label">{{ __('text.description') }}</label>
                    <textarea name="keterangan" rows="3" placeholder="{{ __('text.description') }}"
                              class="input-field">{{ old('keterangan') }}</textarea>
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
