@extends('layouts.v_backend')
@section('title', 'Kelola Penukaran Poin')
@section('content')

<x-page-header
    title="Kelola Penukaran Poin"
    subtitle="Tinjau dan proses semua permintaan penukaran poin dari pengguna."
/>

{{-- Tab Filter --}}
<div class="flex gap-1 mb-6 bg-cream rounded-2xl p-1 w-fit">
    @foreach ([
        ['pending',   'Menunggu',   $counts['pending']],
        ['approved',  'Disetujui',  $counts['approved']],
        ['used',      'Digunakan',  $counts['used']],
        ['cancelled', 'Dibatalkan', $counts['cancelled']],
    ] as [$val, $label, $count])
    <a href="{{ route('admin.points.redemptions', ['status' => $val]) }}"
       class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-semibold transition-all
              {{ $status === $val ? 'bg-white shadow text-bark' : 'text-bark-muted hover:text-bark' }}">
        {{ $label }}
        @if ($count > 0)
        <span class="text-xs font-bold px-1.5 py-0.5 rounded-full
                     {{ $status === $val
                         ? ($val === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-sage/10 text-sage-dark')
                         : 'bg-cream-dark text-bark-muted' }}">{{ $count }}</span>
        @endif
    </a>
    @endforeach
</div>

<div class="be-card overflow-hidden">
    @if ($redemptions->isEmpty())
        <div class="p-10 text-center text-bark-muted text-sm">Tidak ada data untuk ditampilkan.</div>
    @else
        <div class="divide-y divide-cream-dark">
            @foreach ($redemptions as $r)
            @php $isPhysical = !in_array($r->kategori, ['diskon_adopsi']); @endphp
            <div class="p-5">
                <div class="flex items-start justify-between gap-4 flex-wrap">
                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap mb-1">
                            <p class="font-bold text-bark">{{ $r->user?->name ?? '—' }}</p>
                            <span class="text-bark-muted text-xs">·</span>
                            <p class="text-xs text-bark-muted">{{ $r->user?->email }}</p>
                        </div>
                        <p class="text-sm text-bark-light mb-1">
                            <i class="bi bi-gift text-sage mr-1"></i>
                            {{ $r->rewardItem?->nama ?? '(reward dihapus)' }}
                            <span class="text-bark-muted">·</span>
                            <span class="font-bold text-red-400">{{ number_format($r->poin_used) }} poin</span>
                        </p>
                        <p class="text-xs text-bark-muted">{{ $r->created_at->format('d M Y, H:i') }}</p>
                        @if ($r->alamat_pengiriman)
                            <p class="text-xs text-bark-muted mt-1">
                                <i class="bi bi-geo-alt text-honey-dark"></i> {{ $r->alamat_pengiriman }}
                            </p>
                        @endif
                        @if ($r->kode_klaim)
                            <p class="text-xs mt-1">
                                Kode: <span class="font-mono font-bold bg-cream-dark px-2 py-0.5 rounded tracking-widest">{{ $r->kode_klaim }}</span>
                            </p>
                        @endif
                        @if ($r->catatan)
                            <p class="text-xs text-bark-muted mt-1 italic">Catatan: {{ $r->catatan }}</p>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col gap-2 flex-shrink-0">
                        @if ($status === 'pending' && $isPhysical)
                            <form action="{{ route('admin.points.redemptions.approve', $r) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                <input type="text" name="catatan" placeholder="Catatan (opsional)"
                                       class="text-xs border border-cream-dark rounded-2xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sage bg-white w-44">
                                <button type="submit" class="btn-create text-xs px-3 py-2 flex-shrink-0">
                                    <i class="bi bi-check-lg"></i> Setujui
                                </button>
                            </form>
                        @endif
                        @if (in_array($status, ['pending', 'approved']))
                            <form action="{{ route('admin.points.redemptions.cancel', $r) }}" method="POST"
                                  onsubmit="return confirm('Batalkan penukaran dan kembalikan poin?')">
                                @csrf
                                <button type="submit" class="btn-delete w-full justify-center">
                                    <i class="bi bi-x-lg"></i> Batalkan & Refund
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @if ($redemptions->hasPages())
            <div class="px-5 py-4 border-t border-cream-dark">
                {{ $redemptions->links('pagination::v_pagination') }}
            </div>
        @endif
    @endif
</div>

@endsection
