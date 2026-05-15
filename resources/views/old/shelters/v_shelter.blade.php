@extends('layouts.v_main')

@push('meta')
    <meta name="description" content="Daftar kandang peternak sugar glider yang bergabung dengan MySugarGlider.id.">
@endpush

@section('title', 'Kandang Peternak')

@section('content')

<x-page-hero
    title="Kandang Peternak"
    :breadcrumbs="[['label'=>'Beranda','url'=>route('home')],['label'=>'Kandang']]"
/>

<section class="py-16 bg-cream">
    <div class="max-w-7xl mx-auto px-6">

        @if ($shelters->isEmpty())
            <div class="text-center py-24">
                <img src="{{ asset('assets/images/mascot/glider-glide.svg') }}"
                     class="w-20 mx-auto mb-5 opacity-30" alt="">
                <h3 class="text-xl font-bold text-bark mb-2">Belum Ada Kandang</h3>
                <p class="text-bark-muted text-sm">Belum ada kandang yang terdaftar.</p>
            </div>
        @else
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach ($shelters as $shelter)
                    <x-shelter-card
                        :id="$shelter->id"
                        :nama="$shelter->nama"
                        :alamat="$shelter->alamat"
                        :keterangan="$shelter->keterangan"
                        :gambar="$shelter->gambar"
                    />
                @endforeach
            </div>

            <div class="flex justify-center">
                {{ $shelters->links('pagination::v_pagination_public') }}
            </div>
        @endif
    </div>
</section>

@endsection
