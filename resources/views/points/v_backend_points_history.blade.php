@extends('layouts.v_backend')

@section('title', 'Riwayat Poin')

@section('content')

<x-page-header
    title="Riwayat Poin"
    subtitle="Semua aktivitas poin dan penukaran Anda."
    :backRoute="route('points.index')"
/>

<div class="grid lg:grid-cols-2 gap-6">

    {{-- Riwayat Perolehan Poin --}}
    <div class="be-card p-6">
        <h3 class="font-bold text-bark text-base mb-4">Perolehan & Penggunaan Poin</h3>
        @if ($logs->isEmpty())
            <p class="text-bark-muted text-sm">Belum ada riwayat poin.</p>
        @else
            <div class="divide-y divide-cream-dark">
                @foreach ($logs as $log)
                <div class="py-3 flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-bark">{{ $log->note }}</p>
                        <p class="text-xs text-bark-muted mt-0.5">
                            {{ $log->created_at->format('d M Y, H:i') }}
                            @if ($log->expired_at && $log->points > 0)
                                &middot;
                                <span class="{{ $log->isExpired() ? 'text-red-400' : '' }}">
                                    {{ $log->isExpired() ? 'Kedaluwarsa ' . $log->expired_at->format('d M Y') : 'Berlaku s.d. ' . $log->expired_at->format('d M Y') }}
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
            <div class="mt-4">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

    {{-- Riwayat Penukaran --}}
    <div class="be-card p-6">
        <h3 class="font-bold text-bark text-base mb-4">Riwayat Penukaran</h3>
        @if ($redemptions->isEmpty())
            <p class="text-bark-muted text-sm">Belum ada riwayat penukaran.</p>
        @else
            <div class="divide-y divide-cream-dark">
                @foreach ($redemptions as $r)
                <div class="py-3">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-bark">{{ $r->rewardItem?->nama ?? '(reward dihapus)' }}</p>
                            <p class="text-xs text-bark-muted">{{ $r->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <span class="text-sm font-bold text-red-500 flex-shrink-0">-{{ number_format($r->poin_used) }}</span>
                    </div>
                    @if ($r->kode_klaim)
                        <div class="mt-1.5 flex items-center gap-2">
                            <span class="font-mono text-xs bg-cream-dark px-2 py-0.5 rounded tracking-widest">{{ $r->kode_klaim }}</span>
                            @php
                                $statusBadge = match($r->status) {
                                    'pending'   => ['Menunggu', 'bg-amber-100 text-amber-700'],
                                    'approved'  => $r->isExpired() ? ['Kedaluwarsa', 'bg-red-100 text-red-600'] : ['Aktif', 'bg-sage/20 text-sage-dark'],
                                    'used'      => ['Digunakan', 'bg-gray-100 text-gray-500'],
                                    'expired'   => ['Kedaluwarsa', 'bg-red-100 text-red-600'],
                                    'cancelled' => ['Dibatalkan', 'bg-red-100 text-red-600'],
                                    default     => [$r->status, 'bg-cream-dark text-bark-muted'],
                                };
                            @endphp
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $statusBadge[1] }}">{{ $statusBadge[0] }}</span>
                            @if ($r->expired_at && $r->status === 'approved' && !$r->isExpired())
                                <span class="text-xs text-bark-muted">s.d. {{ $r->expired_at->format('d M Y') }}</span>
                            @endif
                        </div>
                    @else
                        @php
                            $statusBadge = match($r->status) {
                                'pending'   => ['Menunggu diproses', 'bg-amber-100 text-amber-700'],
                                'approved'  => ['Disetujui', 'bg-sage/20 text-sage-dark'],
                                'used'      => ['Selesai', 'bg-gray-100 text-gray-500'],
                                'expired'   => ['Kedaluwarsa', 'bg-red-100 text-red-600'],
                                'cancelled' => ['Dibatalkan', 'bg-red-100 text-red-600'],
                                default     => [$r->status, 'bg-cream-dark text-bark-muted'],
                            };
                        @endphp
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full mt-1 inline-block {{ $statusBadge[1] }}">{{ $statusBadge[0] }}</span>
                    @endif
                </div>
                @endforeach
            </div>
            <div class="mt-4">
                {{ $redemptions->links() }}
            </div>
        @endif
    </div>

</div>

@endsection
