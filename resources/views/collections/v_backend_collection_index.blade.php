@extends('layouts.v_backend')

@section('title', __('text.collection_data'))

@section('content')

<x-page-header
    :title="__('text.collection_data')"
    subtitle="Kelola penempatan sugar glider ke kandang."
    :createRoute="route('collection.create')"
/>

<x-alert type="danger" :errors="$errors" />

<x-search-bar
    placeholder="Cari nama sugar glider atau kandang..."
    :resetRoute="route('collection.index')"
    :q="$q"
/>

<div class="be-card overflow-hidden">
    <div class="overflow-x-auto scrollbar-thin">
        <table class="be-table">
            <thead>
                <tr>
                    <th class="hidden md:table-cell w-12">No</th>
                    <th>Nama Sugar Glider</th>
                    <th class="hidden sm:table-cell">Kandang</th>
                    <th>Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($collections as $collection)
                    <tr>
                        <td class="hidden md:table-cell text-bark-muted text-xs">
                            {{ ($collections->currentPage() - 1) * $collections->perPage() + $loop->iteration }}
                        </td>
                        <td>
                            <div class="flex items-center gap-2.5">
                                <x-table-photo
                                    :src="$collection->sgGambar ? asset('upload/sugargliders/' . $collection->sgGambar) : null"
                                    :name="$collection->sgNama"
                                    placeholder-icon="bi-heart"
                                    size="sm"
                                />
                                <span class="font-bold text-bark">{{ $collection->sgNama }}</span>
                            </div>
                        </td>
                        <td class="hidden sm:table-cell">
                            <div class="flex items-center gap-2.5">
                                <x-table-photo
                                    :src="$collection->stGambar ? asset('upload/shelters/' . $collection->stGambar) : null"
                                    :name="$collection->stNama"
                                    placeholder-icon="bi-house-heart"
                                    size="sm"
                                />
                                <span class="text-bark-light text-sm">{{ $collection->stNama }}</span>
                            </div>
                        </td>
                        <td>
                            <x-collection-status :status="$collection->status" />
                        </td>
                        <td class="text-right">
                            <div class="table-actions">
                                @if ($collection->status != 5)
                                    <a href="{{ route('collection.edit', $collection->id) }}" class="btn-edit">
                                        <i class="bi bi-pencil"></i>
                                        <span class="hidden sm:inline">Edit</span>
                                    </a>
                                    <button type="button"
                                            onclick="confirmDelete('{{ route('collection.destroy', $collection->id) }}', '{{ $collection->sgNama }}')"
                                            class="btn-delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-empty-state
                        message="Belum ada penempatan."
                        :createRoute="route('collection.create')"
                        createLabel="Tambah Penempatan"
                        colspan="5"
                    />
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
<x-delete-modal />

@endsection
