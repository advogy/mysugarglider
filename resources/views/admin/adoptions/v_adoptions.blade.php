@extends('layouts.v_backend')
@section('title', 'Manajemen Adopsi')
@section('content')

<x-page-header
    title="Manajemen Adopsi"
    subtitle="Konfirmasi pembayaran masuk dan proses pencairan dana ke pemilik."
/>

{{-- ── Tab Nav ── --}}
<div class="flex gap-1 mb-5 border-b border-cream-dark max-w-4xl">
    <button data-tab="tab-payment" onclick="switchTab('tab-payment')"
            class="tab-btn px-5 py-3 text-sm font-bold border-b-2 border-sage text-sage transition-colors">
        <i class="bi bi-credit-card mr-1"></i> Konfirmasi Pembayaran
        @if ($pendingPayment->isNotEmpty())
            <span class="ml-1 inline-flex items-center justify-center w-5 h-5 rounded-full bg-red-500 text-white text-xs font-bold">{{ $pendingPayment->count() }}</span>
        @endif
    </button>
    <button data-tab="tab-disburse" onclick="switchTab('tab-disburse')"
            class="tab-btn px-5 py-3 text-sm font-bold border-b-2 border-transparent text-bark-muted hover:text-bark transition-colors">
        <i class="bi bi-send-check mr-1"></i> Pencairan Dana
        @if ($pendingDisbursement->isNotEmpty())
            <span class="ml-1 inline-flex items-center justify-center w-5 h-5 rounded-full bg-amber-500 text-white text-xs font-bold">{{ $pendingDisbursement->count() }}</span>
        @endif
    </button>
    <button data-tab="tab-all" onclick="switchTab('tab-all')"
            class="tab-btn px-5 py-3 text-sm font-bold border-b-2 border-transparent text-bark-muted hover:text-bark transition-colors">
        <i class="bi bi-list-ul mr-1"></i> Semua Proses
    </button>
</div>

{{-- ══════════════════════════════════════
     TAB: Konfirmasi Pembayaran
══════════════════════════════════════ --}}
<div id="tab-payment" class="tab-pane max-w-4xl space-y-4">
    @forelse ($pendingPayment as $ar)
    @php
        $adoption = $ar->adoption;
        $owner    = $adoption?->owner;
        $sg       = $adoption?->collection?->sugarglider;
        $total    = ($ar->harga ?? 0) + ($ar->platform_fee ?? 0);
    @endphp
    <div class="be-card overflow-hidden">
        <div class="px-5 py-3 border-b border-cream-dark bg-cream/40 flex items-center justify-between gap-3 flex-wrap">
            <div class="flex items-center gap-2">
                <i class="bi bi-clock-history text-amber-500 text-sm"></i>
                <p class="font-bold text-bark text-sm">{{ $sg->nama ?? 'Sugar Glider' }}</p>
                <span class="text-bark-muted text-xs">·</span>
                <span class="text-xs text-bark-muted">Dibayar {{ $ar->paid_at?->diffForHumans() }}</span>
            </div>
            <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 text-xs font-bold">Menunggu Konfirmasi</span>
        </div>
        <div class="p-5 grid grid-cols-1 sm:grid-cols-3 gap-5">

            {{-- Info Transaksi --}}
            <div class="space-y-2">
                <p class="text-xs font-bold text-bark-muted uppercase tracking-wide mb-3">Info Transaksi</p>
                <div class="text-sm space-y-1">
                    <div class="flex justify-between">
                        <span class="text-bark-muted">Harga terpilih</span>
                        <span class="font-semibold text-bark">Rp {{ number_format($ar->harga ?? 0, 0, ',', '.') }}</span>
                    </div>
                    @if ($ar->platform_fee > 0)
                    <div class="flex justify-between">
                        <span class="text-bark-muted">Biaya platform</span>
                        <span class="font-semibold text-bark">Rp {{ number_format($ar->platform_fee, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between border-t border-cream-dark pt-1 mt-1">
                        <span class="font-bold text-bark">Total transfer</span>
                        <span class="font-bold text-sage">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="pt-2 text-xs space-y-0.5">
                    <p class="text-bark-muted">Pemilik: <span class="text-bark font-semibold">{{ $owner?->name ?? '-' }}</span></p>
                    <p class="text-bark-muted">Pemohon: <span class="text-bark font-semibold">{{ $ar->applicant?->name ?? '-' }}</span></p>
                </div>
            </div>

            {{-- Bukti Transfer --}}
            <div>
                <p class="text-xs font-bold text-bark-muted uppercase tracking-wide mb-3">Bukti Transfer</p>
                @if ($ar->bukti_transfer)
                    <button type="button"
                            onclick="previewPhoto('{{ asset('storage/' . $ar->bukti_transfer) }}', 'Bukti Transfer — {{ $sg->nama ?? 'Sugar Glider' }}')"
                            class="block w-full group relative rounded-xl overflow-hidden border border-cream-dark bg-gray-50 hover:border-sage/40 transition-colors">
                        <img src="{{ asset('storage/' . $ar->bukti_transfer) }}"
                             alt="Bukti Transfer"
                             class="w-full max-h-48 object-contain">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 flex items-center justify-center transition-all">
                            <span class="opacity-0 group-hover:opacity-100 transition-opacity bg-white/90 text-bark text-xs font-bold px-3 py-1.5 rounded-full flex items-center gap-1.5">
                                <i class="bi bi-zoom-in"></i> Lihat Penuh
                            </span>
                        </div>
                    </button>
                    <p class="text-xs text-bark-muted mt-1 text-center">Klik untuk preview</p>
                @else
                    <div class="flex items-center justify-center h-32 rounded-xl border border-cream-dark bg-gray-50 text-bark-muted text-sm flex-col gap-2">
                        <i class="bi bi-image text-2xl"></i>
                        <span class="text-xs">Belum ada bukti</span>
                    </div>
                @endif
            </div>

            {{-- Aksi --}}
            <div class="flex flex-col justify-between">
                <p class="text-xs font-bold text-bark-muted uppercase tracking-wide mb-3">Tindakan</p>
                <div class="space-y-3">
                    <p class="text-xs text-bark-muted">Setelah dikonfirmasi, pemilik akan mendapat notifikasi untuk melakukan pengiriman fisik.</p>
                    <button type="button" onclick="openModal('confirm-pay-{{ $ar->id }}')"
                            class="btn-create w-full justify-center">
                        <i class="bi bi-check-circle-fill"></i> Konfirmasi Pembayaran Diterima
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- Modal: Konfirmasi Pembayaran --}}
    <div id="confirm-pay-{{ $ar->id }}" class="be-modal hidden"
         onclick="if(event.target===this)closeModal('confirm-pay-{{ $ar->id }}')">
        <div class="bg-white rounded-3xl shadow-hover max-w-md w-full p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <span class="inline-flex items-center gap-1 text-xs font-bold px-3 py-1 rounded-full bg-amber-100 text-amber-700">
                        <i class="bi bi-shield-check"></i> Konfirmasi Pembayaran
                    </span>
                    <h3 class="font-bold text-bark text-lg mt-2">{{ $sg->nama ?? 'Sugar Glider' }}</h3>
                </div>
                <button onclick="closeModal('confirm-pay-{{ $ar->id }}')" class="text-bark-muted hover:text-bark">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="bg-cream rounded-2xl p-4 space-y-2 text-sm mb-4">
                <div class="flex justify-between"><span class="text-bark-muted">Pemilik</span><span class="font-semibold text-bark">{{ $owner?->name ?? '-' }}</span></div>
                <div class="flex justify-between"><span class="text-bark-muted">Pemohon</span><span class="font-semibold text-bark">{{ $ar->applicant?->name ?? '-' }}</span></div>
                @if ($ar->platform_fee > 0)
                <div class="flex justify-between"><span class="text-bark-muted">Biaya platform</span><span class="font-semibold text-bark">Rp {{ number_format($ar->platform_fee, 0, ',', '.') }}</span></div>
                @endif
                <div class="flex justify-between border-t border-cream-dark pt-2 mt-1">
                    <span class="font-bold text-bark">Total diterima</span>
                    <span class="font-bold text-sage">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>
            <p class="text-xs text-bark-muted mb-5 text-center">Pastikan dana sudah masuk ke rekening admin sebelum mengkonfirmasi.</p>
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('confirm-pay-{{ $ar->id }}')"
                        class="btn-secondary flex-1 justify-center">Batal</button>
                <form action="{{ route('admin.adoptions.confirm-payment', $ar->id) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="btn-create w-full justify-center">
                        <i class="bi bi-check-circle-fill"></i> Ya, Konfirmasi
                    </button>
                </form>
            </div>
        </div>
    </div>

    @empty
    <div class="be-card p-10 text-center">
        <i class="bi bi-check-circle text-4xl text-sage block mb-3"></i>
        <p class="font-bold text-bark">Tidak ada pembayaran yang menunggu konfirmasi</p>
        <p class="text-bark-muted text-sm mt-1">Semua pembayaran sudah diproses.</p>
    </div>
    @endforelse
</div>

{{-- ══════════════════════════════════════
     TAB: Pencairan Dana
══════════════════════════════════════ --}}
<div id="tab-disburse" class="tab-pane hidden max-w-4xl space-y-4">
    @forelse ($pendingDisbursement as $ar)
    @php
        $adoption  = $ar->adoption;
        $owner     = $adoption?->owner;
        $sg        = $adoption?->collection?->sugarglider;
        $ownerProf = $profiles->get($owner?->id);
        $netAmount = $ar->harga ?? 0;
    @endphp
    <div class="be-card overflow-hidden">
        <div class="px-5 py-3 border-b border-cream-dark bg-cream/40 flex items-center justify-between gap-3 flex-wrap">
            <div class="flex items-center gap-2">
                <i class="bi bi-currency-dollar text-sage text-sm"></i>
                <p class="font-bold text-bark text-sm">{{ $sg->nama ?? 'Sugar Glider' }}</p>
                <span class="text-bark-muted text-xs">·</span>
                <span class="text-xs text-bark-muted">Selesai {{ $ar->updated_at?->diffForHumans() }}</span>
            </div>
            <span class="px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-xs font-bold">Menunggu Pencairan</span>
        </div>
        <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-5">

            {{-- Rekening Pemilik --}}
            <div>
                <p class="text-xs font-bold text-bark-muted uppercase tracking-wide mb-3">Rekening Pemilik</p>
                @if ($ownerProf?->bank_account_number)
                    <div class="p-4 rounded-xl bg-sage/10 border border-sage/20 space-y-1">
                        <p class="text-xs text-bark-muted">Bank</p>
                        <p class="font-bold text-bark text-sm">{{ $ownerProf->bank_name }}</p>
                        <p class="text-xs text-bark-muted mt-2">Nomor Rekening</p>
                        <p class="font-mono font-bold text-bark text-lg tracking-widest">{{ $ownerProf->bank_account_number }}</p>
                        <p class="text-xs text-bark-muted mt-1">{{ $ownerProf->bank_account_name }}</p>
                        <p class="text-xs text-bark-muted mt-2">Pemilik: <span class="font-semibold text-bark">{{ $owner?->name }}</span></p>
                    </div>
                @else
                    <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-sm text-amber-800">
                        <i class="bi bi-exclamation-triangle-fill mr-1"></i>
                        Pemilik belum mengisi rekening bank. Hubungi pemilik untuk mengisi di profil mereka.
                    </div>
                @endif
            </div>

            {{-- Rincian Pencairan --}}
            <div class="flex flex-col justify-between">
                <div>
                    <p class="text-xs font-bold text-bark-muted uppercase tracking-wide mb-3">Rincian Pencairan</p>
                    <div class="text-sm space-y-1 mb-4">
                        <div class="flex justify-between">
                            <span class="text-bark-muted">Diterima dari pemohon</span>
                            <span class="font-semibold">Rp {{ number_format(($ar->harga ?? 0) + ($ar->platform_fee ?? 0), 0, ',', '.') }}</span>
                        </div>
                        @if ($ar->platform_fee > 0)
                        <div class="flex justify-between text-red-600">
                            <span>Biaya platform</span>
                            <span>− Rp {{ number_format($ar->platform_fee, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between border-t border-cream-dark pt-1 mt-1">
                            <span class="font-bold text-bark">Dicairkan ke pemilik</span>
                            <span class="font-bold text-sage">Rp {{ number_format($netAmount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <p class="text-xs text-bark-muted">Pemohon: <span class="text-bark font-semibold">{{ $ar->applicant?->name ?? '-' }}</span></p>
                </div>

                <button type="button" onclick="openModal('disburse-{{ $ar->id }}')"
                        class="btn-create w-full justify-center mt-4"
                        {{ !$ownerProf?->bank_account_number ? 'disabled' : '' }}>
                    <i class="bi bi-send-check-fill"></i> Tandai Dana Sudah Dicairkan
                </button>
            </div>

        </div>
    </div>

    {{-- Modal: Konfirmasi Pencairan --}}
    <div id="disburse-{{ $ar->id }}" class="be-modal hidden"
         onclick="if(event.target===this)closeModal('disburse-{{ $ar->id }}')">
        <div class="bg-white rounded-3xl shadow-hover max-w-md w-full p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <span class="inline-flex items-center gap-1 text-xs font-bold px-3 py-1 rounded-full bg-sage/10 text-sage">
                        <i class="bi bi-send-check"></i> Pencairan Dana
                    </span>
                    <h3 class="font-bold text-bark text-lg mt-2">{{ $sg->nama ?? 'Sugar Glider' }}</h3>
                </div>
                <button onclick="closeModal('disburse-{{ $ar->id }}')" class="text-bark-muted hover:text-bark">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            @if ($ownerProf?->bank_account_number)
            <div class="bg-sage/10 border border-sage/20 rounded-2xl p-4 text-sm mb-4">
                <p class="text-xs text-bark-muted mb-0.5">Transfer ke</p>
                <p class="font-bold text-bark">{{ $ownerProf->bank_name }}</p>
                <p class="font-mono font-bold text-bark text-base tracking-widest mt-1">{{ $ownerProf->bank_account_number }}</p>
                <p class="text-xs text-bark-muted">a.n. {{ $ownerProf->bank_account_name }}</p>
            </div>
            @endif
            <div class="bg-cream rounded-2xl p-4 space-y-2 text-sm mb-4">
                <div class="flex justify-between"><span class="text-bark-muted">Pemilik</span><span class="font-semibold text-bark">{{ $owner?->name ?? '-' }}</span></div>
                <div class="flex justify-between"><span class="text-bark-muted">Pemohon</span><span class="font-semibold text-bark">{{ $ar->applicant?->name ?? '-' }}</span></div>
                <div class="flex justify-between border-t border-cream-dark pt-2 mt-1">
                    <span class="font-bold text-bark">Jumlah dicairkan</span>
                    <span class="font-bold text-sage">Rp {{ number_format($netAmount, 0, ',', '.') }}</span>
                </div>
            </div>
            <p class="text-xs text-bark-muted mb-5 text-center">Pastikan transfer sudah dikirim ke rekening pemilik sebelum melanjutkan.</p>
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('disburse-{{ $ar->id }}')"
                        class="btn-secondary flex-1 justify-center">Batal</button>
                <form action="{{ route('admin.adoptions.disburse', $ar->id) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="btn-create w-full justify-center">
                        <i class="bi bi-send-check-fill"></i> Ya, Sudah Dicairkan
                    </button>
                </form>
            </div>
        </div>
    </div>

    @empty
    <div class="be-card p-10 text-center">
        <i class="bi bi-check-circle text-4xl text-sage block mb-3"></i>
        <p class="font-bold text-bark">Tidak ada dana yang menunggu pencairan</p>
        <p class="text-bark-muted text-sm mt-1">Semua transaksi sudah diproses.</p>
    </div>
    @endforelse
</div>

{{-- ══════════════════════════════════════
     TAB: Semua Proses
══════════════════════════════════════ --}}
<div id="tab-all" class="tab-pane hidden">
<div class="flex flex-col lg:flex-row gap-6">
<div class="flex-1 min-w-0">
    @php
        $arStatusMap = [
            1 => ['label' => 'Menunggu Pemilihan',     'icon' => 'bi-clock',             'class' => 'bg-gray-100 text-gray-600 border-gray-200'],
            5 => ['label' => 'Dipilih',                'icon' => 'bi-stars',             'class' => 'bg-honey-50 text-honey-dark border-honey/30'],
            6 => ['label' => 'Menunggu Konfirmasi',    'icon' => 'bi-shield-check',      'class' => 'bg-blue-50 text-blue-600 border-blue-200'],
            7 => ['label' => 'Dalam Pengiriman',       'icon' => 'bi-house-heart',        'class' => 'bg-purple-50 text-purple-600 border-purple-200'],
            8 => ['label' => 'Selesai',                'icon' => 'bi-check-circle',      'class' => 'bg-sage/10 text-sage border-sage/30'],
        ];
    @endphp
    <div class="be-card overflow-hidden">
        <div class="overflow-x-auto scrollbar-thin">
            <table class="be-table">
                <thead>
                    <tr>
                        <th class="hidden md:table-cell w-10">No</th>
                        <th>Sugar Glider</th>
                        <th class="hidden sm:table-cell">Pemilik</th>
                        <th class="hidden md:table-cell">Harga</th>
                        <th class="hidden lg:table-cell text-center">Permohonan</th>
                        <th>Status Terkini</th>
                        <th class="hidden xl:table-cell">Update</th>
                        <th class="w-20"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($allAdoptions as $adoption)
                    @php
                        $sg    = $adoption->collection?->sugarglider;
                        $owner = $adoption->owner;
                        // Cari request paling aktif (status tertinggi yang bukan MENUNGGU/DIBATALKAN/DITOLAK)
                        $activeRequest = $adoption->requests
                            ->whereNotIn('status', [1, 2, 4])
                            ->sortByDesc('status')
                            ->first();
                        $totalReq = $adoption->total_permohonan;
                    @endphp
                    <tr>
                        <td class="hidden md:table-cell text-bark-muted text-xs">
                            {{ ($allAdoptions->currentPage() - 1) * $allAdoptions->perPage() + $loop->iteration }}
                        </td>
                        <td>
                            <p class="font-bold text-bark text-sm">{{ $sg?->nama ?? '—' }}</p>
                            @if ($sg?->jenis)
                                <span class="text-xs text-bark-muted">{{ $sg->jenis }}</span>
                            @endif
                        </td>
                        <td class="hidden sm:table-cell text-sm text-bark-light">{{ $owner?->name ?? '—' }}</td>
                        <td class="hidden md:table-cell text-sm font-semibold text-bark-light">
                            @if ($adoption->harga == 0)
                                <span class="text-sage font-bold">Gratis</span>
                            @else
                                Rp {{ number_format($adoption->harga, 0, ',', '.') }}
                            @endif
                        </td>
                        <td class="hidden lg:table-cell text-center">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold
                                {{ $totalReq > 0 ? 'bg-bark text-white' : 'bg-gray-100 text-gray-400' }}">
                                {{ $totalReq }}
                            </span>
                        </td>
                        <td>
                            @if ($activeRequest)
                                @php $st = $arStatusMap[$activeRequest->status] ?? ['label' => '—', 'icon' => 'bi-question', 'class' => 'bg-gray-100 text-gray-500 border-gray-200']; @endphp
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-full border {{ $st['class'] }}">
                                    <i class="bi {{ $st['icon'] }}"></i> {{ $st['label'] }}
                                </span>
                                {{-- Sub-keterangan --}}
                                @if ($activeRequest->status == 6 && $activeRequest->confirmed_at)
                                    <p class="text-xs text-sky-600 mt-0.5">Siap dikirim</p>
                                @elseif ($activeRequest->status == 8 && !$activeRequest->disbursed_at && $activeRequest->harga > 0)
                                    <p class="text-xs text-amber-600 mt-0.5">Dana belum cair</p>
                                @elseif ($activeRequest->status == 8 && $activeRequest->disbursed_at)
                                    <p class="text-xs text-sage mt-0.5">Dana dicairkan</p>
                                @endif
                            @elseif ($adoption->status == \App\Enums\AdoptionStatus::NONAKTIF->value)
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-full bg-gray-100 text-gray-500 border border-gray-200">
                                    <i class="bi bi-x-circle"></i> Ditutup
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                                    <i class="bi bi-clock"></i> Menunggu Pemilihan
                                </span>
                            @endif
                        </td>
                        <td class="hidden xl:table-cell text-xs text-bark-muted">
                            {{ $adoption->updated_at?->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            <div class="flex items-center gap-1">
                                @if ($activeRequest && ($activeRequest->bukti_transfer || $activeRequest->bukti_pengiriman))
                                <button type="button" onclick="openModal('eye-{{ $adoption->id }}')"
                                        class="w-8 h-8 rounded-xl bg-cream hover:bg-sage/10 flex items-center justify-center text-bark-muted hover:text-sage transition-colors"
                                        title="Lihat Bukti">
                                    <i class="bi bi-eye text-sm"></i>
                                </button>
                                @endif
                                <a href="{{ route('admin.adoptions.requests', $adoption->id) }}"
                                   class="w-8 h-8 rounded-xl bg-cream hover:bg-sage/10 flex items-center justify-center text-bark-muted hover:text-sage transition-colors"
                                   title="Lihat Permohonan">
                                    <i class="bi bi-arrow-right text-sm"></i>
                                </a>
                            </div>
                        </td>
                    </tr>

                    {{-- Modal eye: bukti dari active request --}}
                    @if ($activeRequest && ($activeRequest->bukti_transfer || $activeRequest->bukti_pengiriman))
                    <div id="eye-{{ $adoption->id }}" class="be-modal hidden"
                         onclick="if(event.target===this)closeModal('eye-{{ $adoption->id }}')">
                        <div class="bg-white rounded-3xl shadow-hover max-w-lg w-full p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <p class="font-bold text-bark text-lg">{{ $sg?->nama ?? 'Sugar Glider' }}</p>
                                    <p class="text-bark-muted text-sm">{{ $owner?->name ?? '—' }}</p>
                                </div>
                                <button onclick="closeModal('eye-{{ $adoption->id }}')" class="text-bark-muted hover:text-bark">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                            @if ($activeRequest->nama_ekspedisi || $activeRequest->resi_pengiriman)
                            <div class="bg-cream rounded-2xl p-3 space-y-1 text-sm mb-4">
                                @if ($activeRequest->nama_ekspedisi)
                                <div class="flex justify-between"><span class="text-bark-muted">Ekspedisi</span><span class="font-semibold text-bark">{{ $activeRequest->nama_ekspedisi }}</span></div>
                                @endif
                                @if ($activeRequest->resi_pengiriman)
                                <div class="flex justify-between"><span class="text-bark-muted">No. Resi</span><span class="font-mono font-bold text-bark">{{ $activeRequest->resi_pengiriman }}</span></div>
                                @endif
                            </div>
                            @endif
                            <div class="grid grid-cols-{{ ($activeRequest->bukti_transfer && $activeRequest->bukti_pengiriman) ? '2' : '1' }} gap-3">
                                @if ($activeRequest->bukti_transfer)
                                <div>
                                    <p class="text-xs text-bark-muted font-semibold mb-1.5">Bukti Transfer</p>
                                    <button type="button"
                                            onclick="previewPhoto('{{ asset('storage/' . $activeRequest->bukti_transfer) }}', 'Bukti Transfer — {{ $sg?->nama }}')"
                                            class="block w-full group relative rounded-xl overflow-hidden border border-cream-dark bg-gray-50 hover:border-sage/40 transition-colors">
                                        <img src="{{ asset('storage/' . $activeRequest->bukti_transfer) }}" class="w-full max-h-40 object-contain">
                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 flex items-center justify-center transition-all">
                                            <span class="opacity-0 group-hover:opacity-100 bg-white/90 text-bark text-xs font-bold px-2 py-1 rounded-full"><i class="bi bi-zoom-in"></i></span>
                                        </div>
                                    </button>
                                </div>
                                @endif
                                @if ($activeRequest->bukti_pengiriman)
                                <div>
                                    <p class="text-xs text-bark-muted font-semibold mb-1.5">Bukti Pengiriman</p>
                                    <button type="button"
                                            onclick="previewPhoto('{{ asset('storage/' . $activeRequest->bukti_pengiriman) }}', 'Bukti Pengiriman — {{ $sg?->nama }}')"
                                            class="block w-full group relative rounded-xl overflow-hidden border border-cream-dark bg-gray-50 hover:border-sage/40 transition-colors">
                                        <img src="{{ asset('storage/' . $activeRequest->bukti_pengiriman) }}" class="w-full max-h-40 object-contain">
                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 flex items-center justify-center transition-all">
                                            <span class="opacity-0 group-hover:opacity-100 bg-white/90 text-bark text-xs font-bold px-2 py-1 rounded-full"><i class="bi bi-zoom-in"></i></span>
                                        </div>
                                    </button>
                                </div>
                                @endif
                            </div>
                            <button onclick="closeModal('eye-{{ $adoption->id }}')" class="btn-secondary w-full justify-center mt-4">Tutup</button>
                        </div>
                    </div>
                    @endif

                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-bark-muted">
                            <i class="bi bi-inbox text-3xl block mb-2"></i>
                            Belum ada listing adopsi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($allAdoptions->hasPages())
            <div class="px-5 py-4 border-t border-cream-dark">
                {{ $allAdoptions->links('pagination::v_pagination') }}
            </div>
        @endif
    </div>
</div>

<x-adoption-flow-guide />

</div>{{-- end flex-row --}}
</div>{{-- end tab-all --}}

<x-photo-preview-modal />

@endsection

@push('scripts')
<script>
function openModal(id) {
    const m = document.getElementById(id);
    m.classList.remove('hidden'); m.classList.add('flex');
}
function closeModal(id) {
    const m = document.getElementById(id);
    m.classList.add('hidden'); m.classList.remove('flex');
}
function switchTab(id) {
    document.querySelectorAll('.tab-pane').forEach(p => p.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.classList.remove('border-sage', 'text-sage');
        b.classList.add('border-transparent', 'text-bark-muted');
    });
    document.getElementById(id).classList.remove('hidden');
    const btn = document.querySelector(`[data-tab="${id}"]`);
    btn.classList.add('border-sage', 'text-sage');
    btn.classList.remove('border-transparent', 'text-bark-muted');
}
</script>
@endpush
