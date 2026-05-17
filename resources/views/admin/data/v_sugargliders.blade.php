@extends('layouts.v_backend')
@section('title', 'Data Sugar Glider')
@section('content')

<x-page-header
    title="Data Sugar Glider"
    subtitle="Semua data sugar glider dari seluruh pengguna."
/>

<x-search-bar
    placeholder="Cari nama, kode, atau jenis..."
    :resetRoute="route('admin.data.sugargliders')"
    :q="$q"
/>

<div class="be-card overflow-hidden">
    <div class="overflow-x-auto scrollbar-thin">
        <table class="be-table">
            <thead>
                <tr>
                    <th class="hidden md:table-cell w-12">No</th>
                    <th>Sugar Glider</th>
                    <th class="hidden sm:table-cell">Pemilik</th>
                    <th class="text-center hidden md:table-cell">Jenis</th>
                    <th class="text-center">Kelamin</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sugargliders as $i => $sg)
                <tr>
                    <td class="hidden md:table-cell text-bark-muted text-xs">
                        {{ $sugargliders->firstItem() + $i }}
                    </td>
                    <td>
                        <div class="flex items-center gap-3">
                            <x-table-photo
                                :src="$sg->gambar ? asset('/upload/sugargliders/' . $sg->gambar) : null"
                                :name="$sg->nama"
                                placeholderIcon="bi-heart-fill"
                            />
                            <div class="min-w-0">
                                <p class="font-bold text-bark">{{ $sg->nama }}</p>
                                <p class="text-xs text-bark-muted font-mono">{{ $sg->kode }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="hidden sm:table-cell">
                        <p class="text-sm text-bark">{{ $sg->user?->name ?? '—' }}</p>
                        <p class="text-xs text-bark-muted">{{ $sg->user?->email }}</p>
                    </td>
                    <td class="text-center hidden md:table-cell text-sm text-bark-muted">
                        {{ $sg->jenis ?: '—' }}
                    </td>
                    <td class="text-center">
                        @if ($sg->kelamin == 1)
                            <span class="badge-sky">Jantan</span>
                        @elseif ($sg->kelamin == 0)
                            <span class="badge bg-pink-50 text-pink-500">Betina</span>
                        @else
                            <span class="text-bark-muted text-xs">—</span>
                        @endif
                    </td>
                    <td class="text-right">
                        <div class="table-actions">
                            <a href="{{ route('admin.data.sugargliders.edit', $sg) }}" class="btn-edit">
                                <i class="bi bi-pencil"></i>
                                <span class="hidden sm:inline">Edit</span>
                            </a>
                            <form action="{{ route('admin.data.sugargliders.destroy', $sg) }}" method="POST"
                                  onsubmit="return confirm('Hapus sugar glider {{ addslashes($sg->nama) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-delete"><i class="bi bi-trash3"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <x-empty-state message="Tidak ada sugar glider ditemukan." colspan="6" />
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($sugargliders->hasPages())
        <div class="px-5 py-4 border-t border-cream-dark">
            {{ $sugargliders->links('pagination::v_pagination') }}
        </div>
    @endif
</div>

<x-photo-preview-modal />

@endsection
