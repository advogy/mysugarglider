@extends('layouts.v_backend')
@section('title', 'Pengguna & Poin')
@section('content')

<x-page-header
    title="Pengguna & Poin"
    subtitle="Daftar semua member beserta akumulasi poin mereka."
/>

{{-- Search --}}
<form method="GET" class="mb-4 flex gap-2">
    <div class="relative flex-1 max-w-sm">
        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-bark-muted text-sm"></i>
        <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama atau email..."
               class="input-field pl-9">
    </div>
    <button type="submit" class="btn-create">Cari</button>
    @if ($search)
        <a href="{{ route('admin.points.users') }}" class="btn-ghost">Reset</a>
    @endif
</form>

<div class="be-card overflow-hidden">
    <div class="overflow-x-auto scrollbar-thin">
        <table class="be-table">
            <thead>
                <tr>
                    <th class="hidden md:table-cell w-12">No</th>
                    <th>Pengguna</th>
                    <th class="text-center">Level</th>
                    <th class="text-right">Total Poin</th>
                    <th class="hidden sm:table-cell text-center">Penukaran</th>
                    <th class="text-right"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $i => $user)
                @php $level = $user->level(); @endphp
                <tr>
                    <td class="hidden md:table-cell text-bark-muted text-xs">
                        {{ $users->firstItem() + $i }}
                    </td>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-sage/10 flex items-center justify-center flex-shrink-0 overflow-hidden">
                                @if ($user->avatar)
                                    <img src="{{ asset('/upload/avatars/' . $user->avatar) }}" class="w-full h-full object-cover" alt="">
                                @else
                                    <i class="bi bi-person-fill text-sage text-sm"></i>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-bark truncate">{{ $user->name }}</p>
                                <p class="text-xs text-bark-muted truncate">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="text-xs font-bold {{ $level['color'] }}">{{ $level['label'] }}</span>
                    </td>
                    <td class="text-right font-bold text-bark">
                        {{ number_format($user->total_points) }}
                    </td>
                    <td class="hidden sm:table-cell text-center text-bark-muted text-xs">
                        {{ ($c = $user->redemptions()->count()) > 0 ? $c . 'x' : '—' }}
                    </td>
                    <td class="text-right">
                        <div class="table-actions">
                            <a href="{{ route('admin.points.user.detail', $user) }}" class="btn-edit">
                                <i class="bi bi-eye"></i>
                                <span class="hidden sm:inline">Detail</span>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <x-empty-state message="Tidak ada pengguna ditemukan." colspan="6" />
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($users->hasPages())
        <div class="px-5 py-4 border-t border-cream-dark">
            {{ $users->links('pagination::v_pagination') }}
        </div>
    @endif
</div>

@endsection
