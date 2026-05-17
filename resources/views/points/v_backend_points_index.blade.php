@extends('layouts.v_backend')

@section('title', 'Poin Saya')

@section('content')

<x-page-header
    title="Poin Saya"
    subtitle="Kumpulkan poin dari setiap aktivitas dan tukarkan dengan hadiah menarik."
/>

{{-- Ringkasan Poin --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">

    {{-- Poin Tersedia --}}
    <div class="be-card p-6 flex items-center gap-4">
        <div class="w-14 h-14 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
            <i class="bi bi-star-fill text-2xl text-amber-500"></i>
        </div>
        <div>
            <p class="text-bark-muted text-xs font-semibold uppercase tracking-wide">Poin Tersedia</p>
            <p class="text-3xl font-bold text-bark">{{ number_format($available) }}</p>
        </div>
    </div>

    {{-- Total Poin (lifetime) --}}
    <div class="be-card p-6 flex items-center gap-4">
        <div class="w-14 h-14 rounded-full bg-sage/20 flex items-center justify-center flex-shrink-0">
            <i class="bi bi-trophy-fill text-2xl text-sage-dark"></i>
        </div>
        <div>
            <p class="text-bark-muted text-xs font-semibold uppercase tracking-wide">Total Poin Sepanjang Masa</p>
            <p class="text-3xl font-bold text-bark">{{ number_format($user->total_points) }}</p>
        </div>
    </div>

    {{-- Level --}}
    <div class="be-card p-6 flex items-center gap-4">
        <div class="w-14 h-14 rounded-full bg-cream-dark flex items-center justify-center flex-shrink-0">
            <i class="bi bi-patch-check-fill text-2xl {{ $level['color'] }}"></i>
        </div>
        <div class="flex-1">
            <p class="text-bark-muted text-xs font-semibold uppercase tracking-wide">Level</p>
            <p class="text-2xl font-bold {{ $level['color'] }}">{{ $level['label'] }}</p>
            @if ($level['next'])
                @php $progress = $level['min'] > 0 ? min(100, round(($user->total_points - $level['min']) / ($level['next'] - $level['min']) * 100)) : min(100, round($user->total_points / $level['next'] * 100)); @endphp
                <div class="mt-1 w-full bg-cream-dark rounded-full h-1.5">
                    <div class="h-1.5 rounded-full bg-sage-dark" style="width: {{ $progress }}%"></div>
                </div>
                <p class="text-xs text-bark-muted mt-0.5">{{ number_format($user->total_points) }} / {{ number_format($level['next']) }} poin</p>
            @else
                <p class="text-xs text-bark-muted mt-0.5">Level tertinggi</p>
            @endif
        </div>
    </div>

</div>

<div class="grid lg:grid-cols-2 gap-6 mb-8">

    {{-- Riwayat Poin Terkini --}}
    <div class="be-card p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-bark text-base">Riwayat Poin Terkini</h3>
            <a href="{{ route('points.history') }}" class="text-sage-dark text-xs font-semibold hover:underline">Lihat semua</a>
        </div>
        @if ($recent_logs->isEmpty())
            <p class="text-bark-muted text-sm">Belum ada riwayat poin.</p>
        @else
            <div class="divide-y divide-cream-dark">
                @foreach ($recent_logs as $log)
                <div class="py-2.5 flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-bark truncate">{{ $log->note }}</p>
                        <p class="text-xs text-bark-muted">{{ $log->created_at->format('d M Y') }}
                            @if ($log->expired_at)
                                &middot; <span class="{{ $log->isExpired() ? 'text-red-400' : 'text-bark-muted' }}">
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
        @endif
    </div>

    {{-- Cara Mendapatkan Poin --}}
    <div class="be-card p-6">
        <h3 class="font-bold text-bark text-base mb-4">Cara Mendapatkan Poin</h3>
        <div class="space-y-2">
            @foreach ([
                ['Lengkapi profil (telepon + alamat)', 50, 'bi-person-check-fill'],
                ['Tambah kandang (maks. 5x)', 25, 'bi-house-heart-fill'],
                ['Input Sugar Glider baru', 30, 'bi-heart-fill'],
                ['SG dilengkapi foto', 10, 'bi-image-fill'],
                ['Indukan SG diisi', 15, 'bi-diagram-3-fill'],
                ['Tambah penempatan', 10, 'bi-collection-fill'],
                ['Buka adopsi SG', 20, 'bi-journal-check'],
                ['SG berhasil diadopsi (pemilik)', 100, 'bi-bag-check-fill'],
                ['Berhasil mengadopsi SG', 75, 'bi-heart-arrow'],
                ['Menulis testimoni (disetujui)', 50, 'bi-chat-quote-fill'],
            ] as [$label, $pts, $icon])
            <div class="flex items-center gap-3">
                <i class="bi {{ $icon }} text-sage flex-shrink-0"></i>
                <p class="text-sm text-bark flex-1">{{ $label }}</p>
                <span class="text-sm font-bold text-amber-500 flex-shrink-0">+{{ $pts }}</span>
            </div>
            @endforeach
        </div>
        <p class="text-xs text-bark-muted mt-4">* Poin berlaku 1 tahun sejak diperoleh.</p>
    </div>

</div>

{{-- Katalog Reward --}}
<div class="be-card p-6 mb-8">
    <h3 class="font-bold text-bark text-base mb-4">Tukar Poin</h3>

    @if ($rewards->isEmpty())
        <p class="text-bark-muted text-sm">Belum ada reward yang tersedia saat ini.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($rewards as $reward)
            <div class="border border-cream-dark rounded-xl p-4 flex flex-col gap-3">
                <div>
                    <span class="inline-block text-xs font-semibold px-2 py-0.5 rounded-full mb-1
                        {{ $reward->kategori === 'diskon_adopsi' ? 'bg-amber-100 text-amber-700' : ($reward->kategori === 'souvenir' ? 'bg-sage/20 text-sage-dark' : 'bg-sky-100 text-sky-700') }}">
                        {{ match($reward->kategori) {
                            'diskon_adopsi'  => 'Diskon Adopsi',
                            'souvenir'       => 'Souvenir',
                            'keperluan_sg'   => 'Keperluan SG',
                            default          => $reward->kategori,
                        } }}
                    </span>
                    <p class="font-semibold text-bark">{{ $reward->nama }}</p>
                    @if ($reward->deskripsi)
                        <p class="text-xs text-bark-muted mt-0.5">{{ $reward->deskripsi }}</p>
                    @endif
                    @if ($reward->diskon_persen)
                        <p class="text-xs text-sage-dark font-semibold mt-1">Diskon {{ $reward->diskon_persen }}%</p>
                    @endif
                    @if ($reward->stok !== null)
                        <p class="text-xs text-bark-muted">Stok: {{ $reward->stok }}</p>
                    @endif
                </div>
                <div class="mt-auto flex items-center justify-between gap-2">
                    <span class="font-bold text-amber-500">{{ number_format($reward->poin_required) }} poin</span>
                    @if ($available >= $reward->poin_required && $reward->isAvailable())
                        <form method="POST" action="{{ route('points.redeem') }}" onsubmit="return confirm('Tukar {{ $reward->poin_required }} poin untuk {{ $reward->nama }}?')">
                            @csrf
                            <input type="hidden" name="reward_item_id" value="{{ $reward->id }}">
                            <button type="submit" class="btn-primary text-xs py-1.5 px-3">Tukar</button>
                        </form>
                    @else
                        <button disabled class="btn-secondary text-xs py-1.5 px-3 opacity-50 cursor-not-allowed">
                            {{ !$reward->isAvailable() ? 'Habis' : 'Poin kurang' }}
                        </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Riwayat Penukaran --}}
@if ($redemptions->isNotEmpty())
<div class="be-card p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-bold text-bark text-base">Riwayat Penukaran</h3>
        <a href="{{ route('points.history') }}" class="text-sage-dark text-xs font-semibold hover:underline">Lihat semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-cream-dark text-left">
                    <th class="pb-2 text-bark-muted font-semibold">Reward</th>
                    <th class="pb-2 text-bark-muted font-semibold">Poin</th>
                    <th class="pb-2 text-bark-muted font-semibold">Kode</th>
                    <th class="pb-2 text-bark-muted font-semibold">Status</th>
                    <th class="pb-2 text-bark-muted font-semibold">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-cream-dark">
                @foreach ($redemptions as $r)
                <tr>
                    <td class="py-2.5 font-medium text-bark">{{ $r->rewardItem?->nama ?? '-' }}</td>
                    <td class="py-2.5 text-red-500 font-semibold">-{{ number_format($r->poin_used) }}</td>
                    <td class="py-2.5">
                        @if ($r->kode_klaim)
                            <span class="font-mono text-xs bg-cream-dark px-2 py-0.5 rounded tracking-widest">{{ $r->kode_klaim }}</span>
                        @else
                            <span class="text-bark-muted">-</span>
                        @endif
                    </td>
                    <td class="py-2.5">
                        @php
                            $statusLabel = match($r->status) {
                                'pending'  => ['Menunggu', 'bg-amber-100 text-amber-700'],
                                'approved' => $r->isExpired() ? ['Kedaluwarsa', 'bg-red-100 text-red-600'] : ['Aktif', 'bg-sage/20 text-sage-dark'],
                                'used'     => ['Digunakan', 'bg-gray-100 text-gray-500'],
                                'expired'  => ['Kedaluwarsa', 'bg-red-100 text-red-600'],
                                'cancelled'=> ['Dibatalkan', 'bg-red-100 text-red-600'],
                                default    => [$r->status, 'bg-cream-dark text-bark-muted'],
                            };
                        @endphp
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $statusLabel[1] }}">{{ $statusLabel[0] }}</span>
                    </td>
                    <td class="py-2.5 text-bark-muted">{{ $r->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection
