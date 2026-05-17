@extends('layouts.v_backend')
@section('title', 'Manajemen Pengguna')
@section('content')

<x-page-header
    title="Manajemen Pengguna"
    subtitle="Kelola akun pengguna, status akses, dan role."
/>

<div class="be-card px-4 py-3 mb-4">
    <form method="GET" class="flex flex-wrap gap-2">
        <div class="relative flex-1 min-w-[200px] max-w-sm">
            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-bark-muted text-sm"></i>
            <input type="text" name="q" value="{{ $q }}" placeholder="Cari nama atau email..."
                   class="input-field pl-9">
        </div>
        <select name="filter" class="input-field w-auto" onchange="this.form.submit()">
            <option value="all"    {{ $filter === 'all'    ? 'selected' : '' }}>Semua</option>
            <option value="user"   {{ $filter === 'user'   ? 'selected' : '' }}>Member Aktif</option>
            <option value="admin"  {{ $filter === 'admin'  ? 'selected' : '' }}>Admin</option>
            <option value="banned" {{ $filter === 'banned' ? 'selected' : '' }}>Diblokir</option>
        </select>
        <button type="submit" class="btn-create">Cari</button>
        @if ($q || $filter !== 'all')
            <a href="{{ route('admin.users.index') }}" class="btn-ghost">Reset</a>
        @endif
    </form>
</div>

<div class="be-card overflow-hidden">
    <div class="overflow-x-auto scrollbar-thin">
        <table class="be-table">
            <thead>
                <tr>
                    <th class="hidden md:table-cell w-12">No</th>
                    <th>Pengguna</th>
                    <th class="text-center hidden sm:table-cell">Role</th>
                    <th class="text-center">Status</th>
                    <th class="text-right hidden md:table-cell">Bergabung</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $i => $user)
                <tr class="{{ !$user->status ? 'opacity-60' : '' }}">
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
                    <td class="text-center hidden sm:table-cell">
                        @if ($user->role === 'admin')
                            <span class="badge-honey">Admin</span>
                        @else
                            <span class="badge-done">Member</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($user->status)
                            <span class="badge-sage">Aktif</span>
                        @else
                            <span class="badge bg-red-50 text-red-500">Diblokir</span>
                        @endif
                    </td>
                    <td class="text-right hidden md:table-cell text-xs text-bark-muted">
                        {{ $user->created_at?->format('d M Y') ?? '—' }}
                    </td>
                    <td class="text-right">
                        <div class="table-actions">
                            {{-- Toggle Ban --}}
                            <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST"
                                  onsubmit="return confirm('{{ $user->status ? 'Blokir' : 'Aktifkan' }} pengguna {{ addslashes($user->name) }}?')">
                                @csrf
                                <button type="submit"
                                        class="{{ $user->status ? 'btn-delete' : 'btn-ghost' }}"
                                        title="{{ $user->status ? 'Blokir' : 'Aktifkan' }}">
                                    <i class="bi {{ $user->status ? 'bi-slash-circle' : 'bi-check-circle' }}"></i>
                                </button>
                            </form>
                            {{-- Toggle Role --}}
                            <form action="{{ route('admin.users.toggle-role', $user) }}" method="POST"
                                  onsubmit="return confirm('Ubah role {{ addslashes($user->name) }} menjadi {{ $user->role === 'admin' ? 'Member' : 'Admin' }}?')">
                                @csrf
                                <button type="submit" class="btn-edit" title="Ubah Role">
                                    <i class="bi {{ $user->role === 'admin' ? 'bi-person-dash' : 'bi-person-check' }}"></i>
                                </button>
                            </form>
                            {{-- Delete --}}
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                  onsubmit="return confirm('Hapus akun {{ addslashes($user->name) }}? Tindakan ini tidak dapat dibatalkan.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-delete" title="Hapus">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
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
