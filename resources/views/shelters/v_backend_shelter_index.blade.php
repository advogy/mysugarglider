@extends('layouts.v_backend')

@section('title', __('text.shelter_data'))

@section('content')

<x-page-header
    :title="__('text.shelter_data')"
    subtitle="Kelola data kandang Anda."
    :createRoute="route('shelter.create')"
/>

<x-search-bar
    placeholder="Cari nama, kode, atau alamat kandang..."
    :resetRoute="route('shelter.index')"
    :q="$q"
/>

<div class="be-card overflow-hidden">
    <div class="overflow-x-auto scrollbar-thin">
        <table class="be-table">
            <thead>
                <tr>
                    <th class="hidden md:table-cell w-12">No</th>
                    <th class="w-16">Foto</th>
                    <th>Nama</th>
                    <th class="hidden sm:table-cell">Kode</th>
                    <th class="hidden md:table-cell">Alamat</th>
                    <th class="hidden md:table-cell">Sugar Glider</th>
                    <th class="hidden lg:table-cell">Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($shelters as $shelter)
                    <tr>
                        <td class="hidden md:table-cell text-bark-muted text-xs">
                            {{ ($shelters->currentPage() - 1) * $shelters->perPage() + $loop->iteration }}
                        </td>
                        <td>
                            <x-table-photo
                                :src="$shelter->gambar ? asset('/upload/shelters/' . $shelter->gambar) : null"
                                :name="$shelter->nama"
                                placeholder-icon="bi-house-heart"
                            />
                        </td>
                        <td class="font-bold text-bark">{{ $shelter->nama }}</td>
                        <td class="hidden sm:table-cell font-mono text-xs text-bark-muted">{{ $shelter->kode }}</td>
                        <td class="hidden md:table-cell text-bark-light text-sm">{{ $shelter->alamat ?? '—' }}</td>
                        <td class="hidden md:table-cell">
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1 rounded-full bg-sage-100 text-sage-dark">
                                <i class="bi bi-heart-fill text-xs"></i>
                                {{ $shelter->sg_count }}
                            </span>
                        </td>
                        <td class="hidden lg:table-cell">
                            @if ($shelter->status == '1')
                                <span class="badge-sage">{{ __('text.open') }}</span>
                            @else
                                <span class="badge-done">{{ __('text.close') }}</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="table-actions">
                                <a href="{{ route('shelter.edit', $shelter->id) }}" class="btn-edit">
                                    <i class="bi bi-pencil"></i>
                                    <span class="hidden sm:inline">Edit</span>
                                </a>
                                <button type="button" onclick="confirmDelete('{{ route('shelter.destroy', $shelter->id) }}', '{{ $shelter->nama }}')" class="btn-delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-empty-state
                        message="Belum ada kandang."
                        :createRoute="route('shelter.create')"
                        createLabel="Tambah Kandang"
                        colspan="8"
                    />
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
<x-delete-modal />

@endsection
