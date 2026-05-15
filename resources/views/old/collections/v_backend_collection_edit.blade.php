@extends('layouts.v_backend')

@section('title', 'Edit Koleksi')

@section('content')

<div class="flex items-center gap-4 mb-6">
    <a href="{{ route('collection.index') }}" class="text-bark-muted hover:text-bark transition-colors">
        <i class="bi bi-arrow-left text-xl"></i>
    </a>
    <div>
        <h2 class="text-xl font-bold text-bark">{{ __('text.edit') }}</h2>
        <p class="text-bark-muted text-sm mt-0.5">{{ __('text.change_data') }}</p>
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
        <form action="{{ route('collection.update', $collection->id) }}" method="POST" enctype="multipart/form-data">
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
                    <select name="status" class="input-field" required>
                        <option value="">Pilih Status</option>
                        <option value="2" @selected($collection->status == '2')>
                            {{ __('text.live') }} — {{ __('text.not_adopted') }}
                        </option>
                        <option value="3" @selected($collection->status == '3')>
                            {{ __('text.live') }} — {{ __('text.open_adopted') }}
                        </option>
                        <option value="0" @selected($collection->status == '0')>
                            {{ __('text.death') }}
                        </option>
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
