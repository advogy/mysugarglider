@extends('layouts.v_backend')
@section('title', 'Detail Poin — ' . $user->name)
@section('content')

<x-page-header
    :title="$user->name"
    subtitle="Riwayat poin dan penukaran pengguna ini."
    :backRoute="route('admin.points.users')"
/>

{{-- Ringkasan --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="be-stat flex items-center gap-4">
        <div class="w-11 h-11 rounded-2xl bg-amber-100 flex items-center justify-center flex-shrink-0">
            <i class="bi bi-star-fill text-lg text-amber-500"></i>
        </div>
        <div>
            <p class="text-xs text-bark-muted font-bold uppercase tracking-wide">Total Poin</p>
            <p class="text-2xl font-bold text-bark">{{ number_format($user->total_points) }}</p>
        </div>
    </div>
    <div class="be-stat flex items-center gap-4">
        <div class="w-11 h-11 rounded-2xl bg-sage/10 flex items-center justify-center flex-shrink-0">
            <i class="bi bi-coin text-lg text-sage-dark"></i>
        </div>
        <div>
            <p class="text-xs text-bark-muted font-bold uppercase tracking-wide">Poin Tersedia</p>
            <p class="text-2xl font-bold text-bark">{{ number_format($available) }}</p>
        </div>
    </div>
    <div class="be-stat flex items-center gap-4">
        <div class="w-11 h-11 rounded-2xl bg-cream-dark flex items-center justify-center flex-shrink-0">
            <i class="bi bi-patch-check-fill text-lg {{ $level['color'] }}"></i>
        </div>
        <div>
            <p class="text-xs text-bark-muted font-bold uppercase tracking-wide">Level</p>
            <p class="text-2xl font-bold {{ $level['color'] }}">{{ $level['label'] }}</p>
            @if ($level['next'])
                <p class="text-xs text-bark-muted">{{ number_format($user->total_points) }} / {{ number_format($level['next']) }}</p>
            @endif
        </div>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">

    {{-- Riwayat Poin --}}
    <div class="be-card">
        <div class="px-6 py-4 border-b border-cream-dark">
            <h3 class="font-bold text-bark">Riwayat Poin</h3>
        </div>
        @if ($logs->isEmpty())
            <p class="text-bark-muted text-sm p-6">Belum ada riwayat poin.</p>
        @else
            <div class="divide-y divide-cream-dark">
                @foreach ($logs as $log)
                <div class="px-6 py-3.5 flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-bark truncate">{{ $log->note }}</p>
                        <p class="text-xs text-bark-muted">
                            {{ $log->created_at->format('d M Y, H:i') }}
                            @if ($log->expired_at && $log->points > 0)
                                &middot;
                                <span class="{{ $log->isExpired() ? 'text-red-400' : '' }}">
                                    {{ $log->isExpired() ? 'Kedaluwarsa' : 'Berlaku s.d. ' . $log->expired_at->format('d M Y') }}
                                </span>
                            @endif
                        </p>
                    </div>
                    <span class="font-bold text-sm flex-shrink-0 {{ $log->points > 0 ? 'text-sage-dark' : 'text-red-500' }}">
                        {{ $log->points > 0 ? '+' : '' }}{{ number_format($log->points) }}
                    </span>
                </div>
                @endforeach
            </div>
            @if ($logs->hasPages())
                <div class="px-6 py-4 border-t border-cream-dark">
                    {{ $logs->links('pagination::v_pagination') }}
                </div>
            @endif
        @endif
    </div>

    {{-- Riwayat Penukaran --}}
    <div class="be-card">
        <div class="px-6 py-4 border-b border-cream-dark">
            <h3 class="font-bold text-bark">Riwayat Penukaran</h3>
        </div>
        @if ($redemptions->isEmpty())
            <p class="text-bark-muted text-sm p-6">Belum ada penukaran.</p>
        @else
            <div class="divide-y divide-cream-dark">
                @foreach ($redemptions as $r)
                @php
                    $badge = match($r->status) {
                        'pending'   => ['Menunggu',    'bg-amber-100 text-amber-700'],
                        'approved'  => $r->isExpired() ? ['Kedaluwarsa', 'bg-red-50 text-red-500'] : ['Disetujui', 'bg-sage/10 text-sage-dark'],
                        'used'      => ['Digunakan',   'bg-gray-100 text-gray-500'],
                        'expired'   => ['Kedaluwarsa', 'bg-red-50 text-red-500'],
                        'cancelled' => ['Dibatalkan',  'bg-red-50 text-red-500'],
                        default     => [$r->status,    'bg-cream-dark text-bark-muted'],
                    };
                @endphp
                <div class="px-6 py-4">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-bark">{{ $r->rewardItem?->nama ?? '(reward dihapus)' }}</p>
                            <p class="text-xs text-bark-muted">{{ $r->created_at->format('d M Y') }}</p>
                            @if ($r->alamat_pengiriman)
                                <p class="text-xs text-bark-muted mt-0.5 truncate"><i class="bi bi-geo-alt"></i> {{ $r->alamat_pengiriman }}</p>
                            @endif
                        </div>
                        <div class="flex flex-col items-end gap-1 flex-shrink-0">
                            <span class="text-sm font-bold text-red-500">-{{ number_format($r->poin_used) }}</span>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $badge[1] }}">{{ $badge[0] }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @if ($redemptions->hasPages())
                <div class="px-6 py-4 border-t border-cream-dark">
                    {{ $redemptions->links('pagination::v_pagination') }}
                </div>
            @endif
        @endif
    </div>

</div>

@endsection
