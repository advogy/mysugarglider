@extends('layouts.v_backend')
@section('title', 'Data Kandang')
@section('content')

<x-page-header
    title="Data Kandang"
    subtitle="Semua data kandang dari seluruh pengguna."
/>

<x-search-bar
    placeholder="Cari nama atau kode kandang..."
    :resetRoute="route('admin.data.shelters')"
    :q="$q"
/>

<div class="be-card overflow-hidden">
    <div class="overflow-x-auto scrollbar-thin">
        <table class="be-table">
            <thead>
                <tr>
                    <th class="hidden md:table-cell w-12">No</th>
                    <th>Kandang</th>
                    <th class="hidden sm:table-cell">Pemilik</th>
                    <th class="text-center hidden md:table-cell">Sugar Glider</th>
                    <th class="text-center">Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($shelters as $i => $shelter)
                <tr>
                    <td class="hidden md:table-cell text-bark-muted text-xs">
                        {{ $shelters->firstItem() + $i }}
                    </td>
                    <td>
                        <div class="flex items-center gap-3">
                            <x-table-photo
                                :src="$shelter->gambar ? asset('/upload/shelters/' . $shelter->gambar) : null"
                                :name="$shelter->nama"
                                placeholderIcon="bi-house-heart-fill"
                            />
                            <div class="min-w-0">
                                <p class="font-bold text-bark">{{ $shelter->nama }}</p>
                                <p class="text-xs text-bark-muted font-mono">{{ $shelter->kode }}</p>
                                @if ($shelter->alamat)
                                    <p class="text-xs text-bark-muted truncate max-w-[180px]">{{ $shelter->alamat }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="hidden sm:table-cell">
                        <p class="text-sm text-bark">{{ $shelter->user?->name ?? '—' }}</p>
                        <p class="text-xs text-bark-muted">{{ $shelter->user?->email }}</p>
                    </td>
                    <td class="text-center hidden md:table-cell text-bark-muted text-sm">
                        {{ $shelter->sugargliders_count }}
                    </td>
                    <td class="text-center">
                        @if ($shelter->status)
                            <span class="badge-sage">Aktif</span>
                        @else
                            <span class="badge-done">Nonaktif</span>
                        @endif
                    </td>
                    <td class="text-right">
                        <div class="table-actions">
                            <a href="{{ route('admin.data.shelters.edit', $shelter) }}" class="btn-edit">
                                <i class="bi bi-pencil"></i>
                                <span class="hidden sm:inline">Edit</span>
                            </a>
                            <form action="{{ route('admin.data.shelters.destroy', $shelter) }}" method="POST"
                                  onsubmit="return confirm('Hapus kandang {{ addslashes($shelter->nama) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-delete"><i class="bi bi-trash3"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <x-empty-state message="Tidak ada kandang ditemukan." colspan="6" />
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($shelters->hasPages())
        <div class="px-5 py-4 border-t border-cream-dark">
            {{ $shelters->links('pagination::v_pagination') }}
        </div>
    @endif
</div>

<x-photo-preview-modal />

@endsection
