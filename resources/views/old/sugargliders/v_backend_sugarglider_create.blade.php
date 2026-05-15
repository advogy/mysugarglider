@extends('layouts.v_backend')

@section('title', 'Tambah Sugar Glider')

@section('content')

<div class="flex items-center gap-4 mb-6">
    <a href="{{ route('sugarglider.index') }}" class="text-bark-muted hover:text-bark transition-colors">
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
        <form action="{{ route('sugarglider.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-5">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">{{ __('text.sugarglider_name') }}</label>
                        <input type="text" name="nama" value="{{ old('nama') }}"
                               placeholder="{{ __('text.sugarglider_name') }}"
                               class="input-field" required>
                    </div>
                    <div>
                        <label class="form-label">{{ __('text.code') }}</label>
                        <input type="text" name="kode" value="{{ old('kode') }}"
                               placeholder="{{ __('text.code') }}"
                               class="input-field" required>
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
                        <input type="date" name="tgl_lahir" value="{{ old('tgl_lahir') }}"
                               class="input-field" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">{{ __('text.type') }}</label>
                        <input type="text" name="jenis" value="{{ old('jenis') }}"
                               placeholder="{{ __('text.type') }}"
                               class="input-field" required>
                    </div>
                    <div>
                        <label class="form-label">{{ __('text.color') }}</label>
                        <input type="text" name="warna" value="{{ old('warna') }}"
                               placeholder="{{ __('text.color') }}"
                               class="input-field" required>
                    </div>
                </div>

                <div>
                    <label class="form-label">{{ __('text.genetics') }}</label>
                    <input type="text" name="genetika" value="{{ old('genetika') }}"
                           placeholder="{{ __('text.genetics') }}"
                           class="input-field">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">{{ __('text.parent_male') }}</label>
                        <select name="indukan_jantan" class="input-field" required>
                            <option value="">{{ __('text.parent_male') }}</option>
                            <option value="0">{{ __('text.unknown') }}</option>
                            @foreach ($sugargliders as $sg)
                                @if ($sg->kelamin == 1)
                                    <option value="{{ $sg->id }}">{{ $sg->nama }} - {{ $sg->jenis }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">{{ __('text.parent_female') }}</label>
                        <select name="indukan_betina" class="input-field" required>
                            <option value="">{{ __('text.parent_female') }}</option>
                            <option value="0">{{ __('text.unknown') }}</option>
                            @foreach ($sugargliders as $sg)
                                @if ($sg->kelamin == 0)
                                    <option value="{{ $sg->id }}">{{ $sg->nama }} - {{ $sg->jenis }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="form-label">{{ __('text.fenotype') }}</label>
                    <textarea name="fenotype" rows="3" placeholder="{{ __('text.fenotype') }}"
                              class="input-field">{{ old('fenotype') }}</textarea>
                </div>

                <div>
                    <label class="form-label">{{ __('text.description') }}</label>
                    <textarea name="keterangan" rows="3" placeholder="{{ __('text.description') }}"
                              class="input-field">{{ old('keterangan') }}</textarea>
                </div>

                <div>
                    <label class="form-label">{{ __('text.image') }}</label>
                    <input type="file" name="gambar"
                           class="w-full text-sm text-bark-muted file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sage/10 file:text-sage cursor-pointer">
                    <p class="text-xs text-bark-muted mt-1.5">Ukuran foto: 500×500px</p>
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
