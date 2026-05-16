@extends('layouts.v_backend')

@section('title', 'Kelola Testimoni')

@section('content')

<x-page-header
    title="Kelola Testimoni"
    subtitle="Review dan setujui testimoni dari pengguna sebelum ditampilkan di halaman publik."
/>

@if (session('pesan'))
    <div class="alert-success mb-6">{{ session('pesan') }}</div>
@endif

{{-- PENDING --}}
<div class="be-card mb-6">
    <div class="p-5 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-bold text-bark flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-amber-400 inline-block"></span>
            Menunggu Review
            @if ($pending->isNotEmpty())
                <span class="ml-1 text-xs font-bold bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">{{ $pending->count() }}</span>
            @endif
        </h3>
    </div>

    @if ($pending->isEmpty())
        <div class="p-8 text-center text-bark-muted text-sm">Tidak ada testimoni yang menunggu review.</div>
    @else
        <div class="divide-y divide-gray-50">
            @foreach ($pending as $t)
            <div class="p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-bark mb-0.5">{{ $t->author }}</p>
                        <p class="text-xs text-bark-muted mb-3">{{ $t->user?->email ?? '—' }} · {{ $t->created_at->diffForHumans() }}</p>
                        <p class="text-sm text-bark-light italic leading-relaxed">"{{ $t->quote }}"</p>
                    </div>
                    <div class="flex flex-col gap-2 flex-shrink-0">
                        <form action="{{ route('testimonial.approve', $t) }}" method="POST" class="flex items-end gap-2">
                            @csrf
                            <input type="text" name="durasi" placeholder="mis. 1 Tahun bersama"
                                   class="text-xs border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:border-sage w-44">
                            <button type="submit" class="flex-shrink-0 text-xs font-bold px-3 py-2 rounded-xl bg-sage text-white hover:opacity-90 transition-opacity">
                                <i class="bi bi-check-lg"></i> Setujui
                            </button>
                        </form>
                        <form action="{{ route('testimonial.reject', $t) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-xs font-bold px-3 py-2 rounded-xl border border-red-200 text-red-500 hover:bg-red-50 transition-colors">
                                <i class="bi bi-x-lg"></i> Tolak
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

{{-- APPROVED --}}
<div class="be-card mb-6">
    <div class="p-5 border-b border-gray-100">
        <h3 class="font-bold text-bark flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-sage inline-block"></span>
            Sudah Tayang
            @if ($approved->isNotEmpty())
                <span class="ml-1 text-xs font-bold bg-sage-100 text-sage-dark px-2 py-0.5 rounded-full">{{ $approved->count() }}</span>
            @endif
        </h3>
    </div>
    @if ($approved->isEmpty())
        <div class="p-8 text-center text-bark-muted text-sm">Belum ada testimoni yang tayang.</div>
    @else
        <div class="divide-y divide-gray-50">
            @foreach ($approved as $t)
            <div class="p-5 flex items-start gap-4">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-bark">{{ $t->author }}
                        @if ($t->durasi)<span class="font-normal text-bark-muted">· {{ $t->durasi }}</span>@endif
                    </p>
                    <p class="text-xs text-bark-muted mb-2">{{ $t->user?->email ?? '(pre-seeded)' }}</p>
                    <p class="text-sm text-bark-light italic leading-relaxed">"{{ $t->quote }}"</p>
                </div>
                <form action="{{ route('testimonial.reject', $t) }}" method="POST" class="flex-shrink-0">
                    @csrf
                    <button type="submit" class="text-xs text-bark-muted hover:text-red-500 transition-colors" title="Cabut">
                        <i class="bi bi-eye-slash"></i>
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    @endif
</div>

{{-- REJECTED --}}
@if ($rejected->isNotEmpty())
<div class="be-card">
    <div class="p-5 border-b border-gray-100">
        <h3 class="font-bold text-bark flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-red-400 inline-block"></span>
            Ditolak
            <span class="ml-1 text-xs font-bold bg-red-50 text-red-400 px-2 py-0.5 rounded-full">{{ $rejected->count() }}</span>
        </h3>
    </div>
    <div class="divide-y divide-gray-50">
        @foreach ($rejected as $t)
        <div class="p-5 flex items-start gap-4">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-bark">{{ $t->author }}</p>
                <p class="text-xs text-bark-muted mb-2">{{ $t->user?->email ?? '—' }}</p>
                <p class="text-sm text-bark-muted italic line-clamp-2">"{{ $t->quote }}"</p>
            </div>
            <form action="{{ route('testimonial.approve', $t) }}" method="POST" class="flex items-end gap-2 flex-shrink-0">
                @csrf
                <input type="text" name="durasi" placeholder="Durasi" class="text-xs border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:border-sage w-36">
                <button type="submit" class="text-xs font-bold px-3 py-2 rounded-xl bg-sage text-white hover:opacity-90 transition-opacity">
                    Setujui Ulang
                </button>
            </form>
        </div>
        @endforeach
    </div>
</div>
@endif

@endsection
