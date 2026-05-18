@extends('layouts.v_backend')

@section('title', __('text.adoption_data'))

@section('content')

<x-page-header
    :title="__('text.adoption_data')"
    subtitle="Kelola data adopsi sugar glider Anda."
    :createRoute="route('adoption.create')"
/>

@if ($errors->any())
    <div class="alert-danger mb-5">
        <i class="bi bi-exclamation-circle-fill text-lg"></i>
        <div>@foreach ($errors->all() as $err)<p>{{ $err }}</p>@endforeach</div>
    </div>
@endif

@if (session('pesan'))
    <div class="alert-success mb-5">
        <i class="bi bi-check-circle-fill text-lg flex-shrink-0"></i>
        <p class="font-semibold">{{ session('pesan') }}</p>
    </div>
@endif
@if (session('error'))
    <div class="alert-danger mb-5">
        <i class="bi bi-exclamation-circle-fill text-lg flex-shrink-0"></i>
        <p class="font-semibold">{{ session('error') }}</p>
    </div>
@endif

<div class="be-card overflow-hidden">
    <div class="overflow-x-auto scrollbar-thin">
        <table class="be-table">
            <thead>
                <tr>
                    <th class="hidden md:table-cell w-12">No</th>
                    <th>Sugar Glider</th>
                    <th class="hidden sm:table-cell">Morph</th>
                    <th class="hidden md:table-cell">Harga</th>
                    <th>Permohonan</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($adoptions as $adoption)
                    <tr>
                        <td class="hidden md:table-cell text-bark-muted text-xs">
                            {{ ($adoptions->currentPage() - 1) * $adoptions->perPage() + $loop->iteration }}
                        </td>
                        <td class="font-bold text-bark">{{ $adoption->nama }}</td>
                        <td class="hidden sm:table-cell">
                            @if ($adoption->jenis)
                                <span class="badge-sage">{{ $adoption->jenis }}</span>
                            @else <span class="text-bark-muted">—</span> @endif
                        </td>
                        <td class="hidden md:table-cell font-semibold text-bark-light text-sm">
                            Rp {{ number_format($adoption->harga, 0, ',', '.') }}
                        </td>
                        <td>
                            <a href="{{ route('adoption.request', $adoption->id) }}"
                               class="inline-flex items-center gap-2 text-xs font-bold text-honey-dark
                                      bg-honey-50 border border-honey/30 px-3 py-1.5 rounded-full
                                      hover:bg-honey/20 transition-colors">
                                <i class="bi bi-inbox"></i>
                                {{ $adoption->total_permohonan ?? 0 }} Permohonan
                            </a>
                        </td>
                        <td class="text-right">
                            <div class="table-actions flex-wrap">
                                @if (in_array($adoption->id, $lockedEditIds))
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-xl bg-gray-50 text-gray-400 border border-gray-200 cursor-not-allowed"
                                          title="Tidak dapat diedit — ada permohonan yang sedang diproses">
                                        <i class="bi bi-pencil"></i>
                                    </span>
                                @else
                                    <a href="{{ route('adoption.edit', $adoption->id) }}" class="btn-edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endif
                                @if (in_array($adoption->id, $lockedAdoptionIds))
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-xl bg-blue-50 text-blue-500 border border-blue-200 cursor-not-allowed"
                                          title="Tidak dapat ditutup — ada proses transfer/pengiriman yang sedang berjalan">
                                        <i class="bi bi-lock-fill"></i>
                                    </span>
                                @else
                                    <button type="button"
                                            onclick="confirmDelete('{{ route('adoption.destroy', $adoption->id) }}', '{{ $adoption->nama }}')"
                                            class="btn-delete">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-empty-state
                        message="Belum ada data adopsi."
                        :createRoute="route('adoption.create')"
                        createLabel="Buat Adopsi"
                        colspan="6"
                    />
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($adoptions->hasPages())
        <div class="px-5 py-4 border-t border-cream-dark">
            {{ $adoptions->links('pagination::v_pagination') }}
        </div>
    @endif
</div>

<x-delete-modal title="Tutup Listing Adopsi?" subtitle="Listing akan dinonaktifkan dan SG kembali ke status Privat. Aksi ini tidak dapat dibatalkan jika ada permohonan yang sudah diproses." />

@endsection
