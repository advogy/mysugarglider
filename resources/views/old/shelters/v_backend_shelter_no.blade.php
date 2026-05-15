@extends('layouts.v_backend')

@section('title')
    Data Kandang Tidak Ditemukan
@endsection

@section('content')
    <section class="section">
        <div class="col-12">
            <div class="alert alert-danger">
                <h4 class="alert-heading">{{ __('text.find_no_shelter') }}</h4>
                <p>Silakan lengkapi data <a href="{{ route('shelter.index') }}">Kandang</a> Anda terlebih dahulu.</p>
            </div>
        </div>
    </section>
@endsection
