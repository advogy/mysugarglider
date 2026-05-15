@extends('layouts.v_backend')

@section('title')
    Data Tidak Ditemukan
@endsection

@section('content')
    <section class="section">
        <div class="col-12">
            <div class="alert alert-danger">
                <h4 class="alert-heading">{{ __('text.find_no_adoptable_status') }}</h4>
                <p>Silakan perbaharui data <a href="{{ route('collection.index') }}">Koleksi</a> Anda terlebih dahulu.</p>
            </div>
        </div>
    </section>
@endsection
