@extends('layouts.v_backend')
@section('title', 'Detail Permohonan Adopsi')
@section('content')

<x-page-header
    :title="$sugarglider->nama . ($sugarglider->jenis ? ' (' . $sugarglider->jenis . ')' : '')"
    subtitle="{{ __('text.adoption_request') }}"
    :backRoute="route('admin.adoptions.index')"
/>

<div class="flex flex-col lg:flex-row gap-6">

    {{-- Main table --}}
    <div class="flex-1 min-w-0">
        <div class="be-card overflow-hidden">
            <div class="overflow-x-auto scrollbar-thin">
                <table class="be-table">
                    <thead>
                        <tr>
                            <th class="hidden md:table-cell w-12">No</th>
                            <th>Pemohon</th>
                            <th class="hidden sm:table-cell">Kandang</th>
                            <th class="hidden md:table-cell">Tanggal</th>
                            <th class="hidden lg:table-cell">Penawaran</th>
                            <th>Status</th>
                            <th class="w-10"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($adoptionrequests as $ar)
                        <tr>
                            <td class="hidden md:table-cell text-bark-muted text-xs">
                                {{ ($adoptionrequests->currentPage() - 1) * $adoptionrequests->perPage() + $loop->iteration }}
                            </td>
                            <td class="font-bold text-bark">{{ $ar->nama }}</td>
                            <td class="hidden sm:table-cell text-bark-light text-sm">{{ $ar->kandang ?? '—' }}</td>
                            <td class="hidden md:table-cell text-bark-muted text-sm">
                                {{ $ar->created_at->format('d/m/Y') }}
                            </td>
                            <td class="hidden lg:table-cell font-semibold text-bark-light text-sm">
                                @if ($ar->harga == 0)
                                    <span class="text-sage font-bold">Gratis</span>
                                @else
                                    Rp {{ number_format($ar->harga, 0, ',', '.') }}
                                @endif
                            </td>
                            <td>
                                @if ($ar->status == 1)
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                                        <i class="bi bi-clock"></i> Menunggu
                                    </span>
                                @elseif ($ar->status == 2)
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-gray-100 text-gray-500 border border-gray-200">
                                        <i class="bi bi-x-circle"></i> Dibatalkan
                                    </span>
                                @elseif ($ar->status == 4)
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-red-50 text-red-500 border border-red-200">
                                        <i class="bi bi-x-circle"></i> Tidak Terpilih
                                    </span>
                                @elseif ($ar->status == 5)
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-honey-50 text-honey-dark border border-honey/30">
                                        <i class="bi bi-stars"></i> Dipilih
                                    </span>
                                @elseif ($ar->status == 6 && is_null($ar->confirmed_at))
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-blue-50 text-blue-600 border border-blue-200">
                                        <i class="bi bi-shield-check"></i> Menunggu Admin
                                    </span>
                                @elseif ($ar->status == 6 && !is_null($ar->confirmed_at))
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-sky-50 text-sky-600 border border-sky-200">
                                        <i class="bi bi-truck"></i> Siap Dikirim
                                    </span>
                                @elseif ($ar->status == 7)
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-purple-50 text-purple-600 border border-purple-200">
                                        <i class="bi bi-house-heart"></i> Dalam Pengiriman
                                    </span>
                                @elseif ($ar->status == 8)
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-sage/10 text-sage border border-sage/30">
                                        <i class="bi bi-check-circle"></i> Selesai
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if ($ar->bukti_transfer || $ar->bukti_pengiriman)
                                <button type="button" onclick="openModal('detail-{{ $ar->id }}')"
                                        class="w-8 h-8 rounded-xl bg-cream hover:bg-sage/10 flex items-center justify-center text-bark-muted hover:text-sage transition-colors">
                                    <i class="bi bi-eye text-sm"></i>
                                </button>
                                @endif
                            </td>
                        </tr>

                        {{-- Modal detail bukti --}}
                        @if ($ar->bukti_transfer || $ar->bukti_pengiriman)
                        <div id="detail-{{ $ar->id }}" class="be-modal hidden"
                             onclick="if(event.target===this)closeModal('detail-{{ $ar->id }}')">
                            <div class="bg-white rounded-3xl shadow-hover max-w-lg w-full p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <p class="font-bold text-bark text-lg">{{ $ar->nama }}</p>
                                        <p class="text-bark-muted text-sm">{{ $ar->kandang ?? '—' }}</p>
                                    </div>
                                    <button onclick="closeModal('detail-{{ $ar->id }}')" class="text-bark-muted hover:text-bark">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                                @if ($ar->nama_ekspedisi || $ar->resi_pengiriman)
                                <div class="bg-cream rounded-2xl p-3 space-y-1 text-sm mb-4">
                                    @if ($ar->nama_ekspedisi)
                                    <div class="flex justify-between"><span class="text-bark-muted">Ekspedisi</span><span class="font-semibold text-bark">{{ $ar->nama_ekspedisi }}</span></div>
                                    @endif
                                    @if ($ar->resi_pengiriman)
                                    <div class="flex justify-between"><span class="text-bark-muted">No. Resi</span><span class="font-mono font-bold text-bark">{{ $ar->resi_pengiriman }}</span></div>
                                    @endif
                                </div>
                                @endif
                                <div class="grid grid-cols-{{ ($ar->bukti_transfer && $ar->bukti_pengiriman) ? '2' : '1' }} gap-3">
                                    @if ($ar->bukti_transfer)
                                    <div>
                                        <p class="text-xs text-bark-muted font-semibold mb-1.5">Bukti Transfer</p>
                                        <button type="button"
                                                onclick="previewPhoto('{{ asset('storage/' . $ar->bukti_transfer) }}', 'Bukti Transfer — {{ $ar->nama }}')"
                                                class="block w-full group relative rounded-xl overflow-hidden border border-cream-dark bg-gray-50 hover:border-sage/40 transition-colors">
                                            <img src="{{ asset('storage/' . $ar->bukti_transfer) }}" alt="Bukti Transfer" class="w-full max-h-40 object-contain">
                                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 flex items-center justify-center transition-all">
                                                <span class="opacity-0 group-hover:opacity-100 bg-white/90 text-bark text-xs font-bold px-2 py-1 rounded-full"><i class="bi bi-zoom-in"></i></span>
                                            </div>
                                        </button>
                                    </div>
                                    @endif
                                    @if ($ar->bukti_pengiriman)
                                    <div>
                                        <p class="text-xs text-bark-muted font-semibold mb-1.5">Bukti Pengiriman</p>
                                        <button type="button"
                                                onclick="previewPhoto('{{ asset('storage/' . $ar->bukti_pengiriman) }}', 'Bukti Pengiriman — {{ $ar->nama }}')"
                                                class="block w-full group relative rounded-xl overflow-hidden border border-cream-dark bg-gray-50 hover:border-sage/40 transition-colors">
                                            <img src="{{ asset('storage/' . $ar->bukti_pengiriman) }}" alt="Bukti Pengiriman" class="w-full max-h-40 object-contain">
                                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 flex items-center justify-center transition-all">
                                                <span class="opacity-0 group-hover:opacity-100 bg-white/90 text-bark text-xs font-bold px-2 py-1 rounded-full"><i class="bi bi-zoom-in"></i></span>
                                            </div>
                                        </button>
                                    </div>
                                    @endif
                                </div>
                                <button onclick="closeModal('detail-{{ $ar->id }}')" class="btn-secondary w-full justify-center mt-4">Tutup</button>
                            </div>
                        </div>
                        @endif

                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($adoptionrequests->hasPages())
                <div class="px-5 py-4 border-t border-cream-dark">
                    {{ $adoptionrequests->links('pagination::v_pagination') }}
                </div>
            @endif
        </div>
    </div>

    <x-adoption-flow-guide />

</div>

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
</script>
@endpush
