@extends('layouts.v_backend')
@section('title', 'Data Penempatan')
@section('content')

<x-page-header
    title="Data Penempatan"
    subtitle="Semua data penempatan sugar glider di kandang dari seluruh pengguna."
/>

<x-search-bar
    placeholder="Cari nama sugar glider atau kandang..."
    :resetRoute="route('admin.data.collections')"
    :q="$q"
/>

<div class="be-card overflow-hidden">
    <div class="overflow-x-auto scrollbar-thin">
        <table class="be-table">
            <thead>
                <tr>
                    <th class="hidden md:table-cell w-12">No</th>
                    <th>Sugar Glider</th>
                    <th>Kandang</th>
                    <th class="hidden sm:table-cell">Pemilik</th>
                    <th class="text-center">Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($collections as $i => $col)
                @php
                    $statusOptions = [1 => 'Privat', 2 => 'Publik', 3 => 'Adopsi', 4 => 'Mati', 5 => 'Selesai'];
                    $statusBadge = match((int) $col->status) {
                        1 => 'badge-done',
                        2 => 'badge-sage',
                        3 => 'badge-honey',
                        4 => 'badge bg-red-50 text-red-500',
                        5 => 'badge-sky',
                        default => 'badge-done',
                    };
                @endphp
                <tr>
                    <td class="hidden md:table-cell text-bark-muted text-xs">
                        {{ $collections->firstItem() + $i }}
                    </td>
                    <td>
                        <div class="flex items-center gap-3">
                            <x-table-photo
                                :src="$col->sugarglider?->gambar ? asset('/upload/sugargliders/' . $col->sugarglider->gambar) : null"
                                :name="$col->sugarglider?->nama ?? ''"
                                placeholderIcon="bi-heart-fill"
                                size="sm"
                            />
                            <div class="min-w-0">
                                <p class="font-bold text-bark">{{ $col->sugarglider?->nama ?? '—' }}</p>
                                <p class="text-xs text-bark-muted font-mono">{{ $col->sugarglider?->kode }}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="flex items-center gap-3">
                            <x-table-photo
                                :src="$col->shelter?->gambar ? asset('/upload/shelters/' . $col->shelter->gambar) : null"
                                :name="$col->shelter?->nama ?? ''"
                                placeholderIcon="bi-house-heart-fill"
                                size="sm"
                            />
                            <div class="min-w-0">
                                <p class="text-sm text-bark">{{ $col->shelter?->nama ?? '—' }}</p>
                                <p class="text-xs text-bark-muted font-mono">{{ $col->shelter?->kode }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="hidden sm:table-cell">
                        <p class="text-sm text-bark">{{ $col->shelter?->user?->name ?? '—' }}</p>
                    </td>
                    <td class="text-center">
                        {{-- Inline status change --}}
                        <form action="{{ route('admin.data.collections.status', $col) }}" method="POST">
                            @csrf @method('PATCH')
                            <select name="status" onchange="this.form.submit()"
                                    class="text-xs border border-cream-dark rounded-xl px-2 py-1 bg-white text-bark focus:outline-none focus:ring-1 focus:ring-sage cursor-pointer">
                                @foreach ($statusOptions as $val => $label)
                                    <option value="{{ $val }}" @selected((int) $col->status === $val)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td class="text-right">
                        <div class="table-actions">
                            <form action="{{ route('admin.data.collections.destroy', $col) }}" method="POST"
                                  onsubmit="return confirm('Hapus data penempatan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-delete"><i class="bi bi-trash3"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <x-empty-state message="Tidak ada data penempatan ditemukan." colspan="6" />
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($collections->hasPages())
        <div class="px-5 py-4 border-t border-cream-dark">
            {{ $collections->links('pagination::v_pagination') }}
        </div>
    @endif
</div>

<x-photo-preview-modal />

@endsection
