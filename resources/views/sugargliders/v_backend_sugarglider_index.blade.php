@extends('layouts.v_backend')

@section('title', __('text.sugarglider_data'))

@section('content')

<x-page-header
    :title="__('text.sugarglider_data')"
    subtitle="Kelola data sugar glider Anda."
    :createRoute="route('sugarglider.create')"
/>

@if (session('pesan'))
    <div class="alert-success mb-5">
        <i class="bi bi-check-circle-fill text-lg"></i>
        <p class="font-semibold">{{ session('pesan') }}</p>
    </div>
@endif

<div class="be-card overflow-hidden">
    <div class="overflow-x-auto scrollbar-thin">
        <table class="be-table">
            <thead>
                <tr>
                    <th class="hidden md:table-cell w-12">No</th>
                    <th class="w-16">Foto</th>
                    <th>Nama</th>
                    <th class="hidden sm:table-cell">Kode</th>
                    <th class="hidden md:table-cell">Morph / Jenis</th>
                    <th class="hidden md:table-cell">Kandang</th>
                    <th class="hidden lg:table-cell">Kelamin</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sugargliders as $sg)
                    <tr>
                        <td class="hidden md:table-cell text-bark-muted text-xs">
                            {{ ($sugargliders->currentPage() - 1) * $sugargliders->perPage() + $loop->iteration }}
                        </td>
                        <td>
                            <x-table-photo
                                :src="$sg->gambar ? asset('/upload/sugargliders/' . $sg->gambar) : null"
                                :name="$sg->nama"
                                placeholder-icon="bi-heart"
                            />
                        </td>
                        <td class="font-bold text-bark">
                            <a href="{{ route('sugarglider.backend.show', $sg->id) }}"
                               class="hover:text-sage-dark hover:underline transition-colors">
                                {{ $sg->nama }}
                            </a>
                        </td>
                        <td class="hidden sm:table-cell font-mono text-xs text-bark-muted">{{ $sg->kode }}</td>
                        <td class="hidden md:table-cell">
                            @if ($sg->jenis)
                                <span class="badge-sage">{{ $sg->jenis }}</span>
                            @else
                                <span class="text-bark-muted">—</span>
                            @endif
                        </td>
                        <td class="hidden md:table-cell text-sm">
                            @if ($sg->kandang_nama)
                                <div class="flex flex-col gap-1">
                                    <span class="font-semibold text-bark">{{ $sg->kandang_nama }}</span>
                                    <x-collection-status :status="$sg->cl_status" />
                                </div>
                            @else
                                <span class="text-bark-muted">—</span>
                            @endif
                        </td>
                        <td class="hidden lg:table-cell text-sm font-bold">
                            @if ($sg->kelamin == '0')
                                <span class="text-pink-500">♀ Betina</span>
                            @else
                                <span class="text-blue-500">♂ Jantan</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="table-actions">
                                <a href="{{ route('sugarglider.edit', $sg->id) }}" class="btn-edit">
                                    <i class="bi bi-pencil"></i>
                                    <span class="hidden sm:inline">Edit</span>
                                </a>
                                <button type="button" onclick="confirmDelete('{{ route('sugarglider.destroy', $sg->id) }}', '{{ $sg->nama }}')" class="btn-delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-empty-state
                        message="Belum ada sugar glider."
                        :createRoute="route('sugarglider.create')"
                        createLabel="Tambah Pertama"
                        colspan="8"
                    />
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

<x-delete-modal />

@endsection
