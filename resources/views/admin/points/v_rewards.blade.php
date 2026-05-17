@extends('layouts.v_backend')
@section('title', 'Reward Items')
@section('content')

<x-page-header
    title="Reward Items"
    subtitle="Kelola daftar hadiah yang dapat ditukarkan dengan poin oleh pengguna."
    :createRoute="route('admin.points.rewards.create')"
    createLabel="Tambah Reward"
/>

<div class="be-card overflow-hidden">
    <div class="overflow-x-auto scrollbar-thin">
        <table class="be-table">
            <thead>
                <tr>
                    <th>Reward</th>
                    <th class="text-center hidden sm:table-cell">Kategori</th>
                    <th class="text-right">Poin</th>
                    <th class="text-center hidden md:table-cell">Stok</th>
                    <th class="text-center">Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rewards as $r)
                @php
                    $kategoriLabel = match($r->kategori) {
                        'diskon_adopsi' => ['Diskon Adopsi', 'badge-sky'],
                        'souvenir'      => ['Souvenir',      'badge bg-purple-50 text-purple-600'],
                        'keperluan_sg'  => ['Keperluan SG',  'badge-honey'],
                        default         => [$r->kategori,   'badge-done'],
                    };
                @endphp
                <tr class="{{ $r->aktif ? '' : 'opacity-60' }}">
                    <td>
                        <p class="font-bold text-bark">{{ $r->nama }}</p>
                        <p class="text-xs text-bark-muted font-mono">{{ $r->kode }}</p>
                        @if ($r->deskripsi)
                            <p class="text-xs text-bark-muted mt-0.5 line-clamp-1">{{ $r->deskripsi }}</p>
                        @endif
                        @if ($r->diskon_persen)
                            <p class="text-xs text-blue-500 mt-0.5"><i class="bi bi-percent"></i> {{ $r->diskon_persen }}%</p>
                        @endif
                    </td>
                    <td class="text-center hidden sm:table-cell">
                        <span class="{{ $kategoriLabel[1] }}">{{ $kategoriLabel[0] }}</span>
                    </td>
                    <td class="text-right font-bold text-bark">
                        {{ number_format($r->poin_required) }}
                    </td>
                    <td class="text-center hidden md:table-cell text-bark-muted text-sm">
                        {{ $r->stok !== null ? number_format($r->stok) : '∞' }}
                    </td>
                    <td class="text-center">
                        @if ($r->aktif)
                            <span class="badge-sage">Aktif</span>
                        @else
                            <span class="badge-done">Nonaktif</span>
                        @endif
                    </td>
                    <td class="text-right">
                        <div class="table-actions">
                            <a href="{{ route('admin.points.rewards.edit', $r) }}" class="btn-edit">
                                <i class="bi bi-pencil"></i>
                                <span class="hidden sm:inline">Edit</span>
                            </a>
                            <form action="{{ route('admin.points.rewards.destroy', $r) }}" method="POST"
                                  onsubmit="return confirm('Hapus reward {{ addslashes($r->nama) }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <x-empty-state message="Belum ada reward." colspan="6" />
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
