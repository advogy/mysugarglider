@extends('layouts.v_backend')

@section('title', 'Permohonan Adopsi')

@section('content')

<div class="flex items-center gap-4 mb-6">
    <a href="{{ route('adoption.index') }}" class="text-bark-muted hover:text-bark transition-colors">
        <i class="bi bi-arrow-left text-xl"></i>
    </a>
    <div>
        <h2 class="text-xl font-bold text-bark">{{ $sugarglider->nama }}
            @if($sugarglider->jenis)
                <span class="text-bark-muted font-normal text-base">({{ $sugarglider->jenis }})</span>
            @endif
        </h2>
        <p class="text-bark-muted text-sm mt-0.5">{{ __('text.adoption_request') }}</p>
    </div>
</div>

@if (session('pesan'))
    <div class="alert-success mb-5">
        <i class="bi bi-check-circle-fill text-lg"></i>
        <p class="font-semibold">{{ session('pesan') }}</p>
    </div>
@endif
@if (session('error'))
    <div class="alert-danger mb-5">
        <i class="bi bi-exclamation-circle-fill text-lg"></i>
        <p class="font-semibold">{{ session('error') }}</p>
    </div>
@endif
@if ($errors->any())
    <div class="alert-danger mb-5">
        <i class="bi bi-exclamation-circle-fill text-lg"></i>
        <div>@foreach ($errors->all() as $err)<p>{{ $err }}</p>@endforeach</div>
    </div>
@endif

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
                            <th>Proses</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($adoptionrequests as $adoptionrequest)
                            <tr>
                                <td class="hidden md:table-cell text-bark-muted text-xs">
                                    {{ ($adoptionrequests->currentPage() - 1) * $adoptionrequests->perPage() + $loop->iteration }}
                                </td>
                                <td class="font-bold text-bark">{{ $adoptionrequest->nama }}</td>
                                <td class="hidden sm:table-cell">
                                    <a href="{{ route('shelter.show', $adoptionrequest->kandang_id) }}"
                                       class="text-bark-light text-sm hover:text-sage transition-colors">
                                        {{ $adoptionrequest->kandang }}
                                    </a>
                                </td>
                                <td class="hidden md:table-cell text-bark-muted text-sm">
                                    {{ $adoptionrequest->created_at->format('d/m/Y') }}
                                </td>
                                <td class="hidden lg:table-cell font-semibold text-bark-light text-sm">
                                    Rp {{ number_format($adoptionrequest->harga, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if ($adoptionrequest->status == 1)
                                        {{-- MENUNGGU: pemilik memilih pemohon --}}
                                        <form action="{{ route('adoptionrequest.select') }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="adoption_id" value="{{ $sugarglider->id }}">
                                            <input type="hidden" name="adoption_request_id" value="{{ $adoptionrequest->id }}">
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-honey-50 text-honey-dark border border-honey/30 hover:bg-honey/20 transition-all">
                                                <i class="bi bi-check2-circle"></i> Pilih
                                            </button>
                                        </form>

                                    @elseif ($adoptionrequest->status == 4)
                                        {{-- DITOLAK --}}
                                        <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-red-50 text-red-500 border border-red-200">
                                            <i class="bi bi-file-earmark-excel"></i> Tidak Terpilih
                                        </span>

                                    @elseif ($adoptionrequest->status == 5)
                                        {{-- DIPILIH: menunggu respons pemohon (gratis=konfirmasi / berbayar=upload bukti) --}}
                                        <button type="button" onclick="openModal('waiting-{{ $adoptionrequest->id }}')"
                                                class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-honey-50 text-honey-dark border border-honey/30 hover:bg-honey/20 transition-all">
                                            <i class="bi bi-hourglass-split"></i> Menunggu Pemohon
                                        </button>

                                    @elseif ($adoptionrequest->status == 6 && is_null($adoptionrequest->confirmed_at))
                                        {{-- DIBAYAR tapi belum dikonfirmasi pemilik: perlu konfirmasi pembayaran --}}
                                        <button type="button" onclick="openModal('confirm-pay-{{ $adoptionrequest->id }}')"
                                                class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 transition-all">
                                            <i class="bi bi-check2-square"></i> Konfirmasi Pembayaran
                                        </button>

                                    @elseif ($adoptionrequest->status == 6 && !is_null($adoptionrequest->confirmed_at))
                                        {{-- DIBAYAR & dikonfirmasi: siap dikirim --}}
                                        <button type="button" onclick="openModal('shipping-{{ $adoptionrequest->id }}')"
                                                class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-sky-50 text-sky-600 border border-sky-200 hover:bg-sky-100 transition-all">
                                            <i class="bi bi-truck"></i> Tandai Terkirim
                                        </button>

                                    @elseif ($adoptionrequest->status == 7)
                                        {{-- DIKIRIM: menunggu konfirmasi terima dari pemohon --}}
                                        <button type="button" onclick="openModal('shipped-{{ $adoptionrequest->id }}')"
                                                class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-purple-50 text-purple-600 border border-purple-200 hover:bg-purple-100 transition-all">
                                            <i class="bi bi-house-heart"></i> Menunggu Terima
                                        </button>

                                    @elseif ($adoptionrequest->status == 8)
                                        {{-- SELESAI --}}
                                        <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-sage/10 text-sage border border-sage/30">
                                            <i class="bi bi-check-circle"></i> Selesai
                                        </span>

                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-red-50 text-red-400 border border-red-200">
                                            <i class="bi bi-file-earmark-excel"></i> Tidak Terpilih
                                        </span>
                                    @endif
                                </td>
                            </tr>

                            {{-- MODAL: Status 5 — menunggu respons pemohon --}}
                            @if ($adoptionrequest->status == 5)
                            <div id="waiting-{{ $adoptionrequest->id }}" class="fixed inset-0 z-50 hidden items-center justify-center p-4"
                                 style="background:rgba(0,0,0,0.4)"
                                 onclick="if(event.target===this)closeModal('waiting-{{ $adoptionrequest->id }}')">
                                <div class="bg-white rounded-3xl shadow-hover max-w-md w-full p-6">
                                    <div class="flex items-start justify-between mb-4">
                                        <div>
                                            <span class="inline-flex items-center gap-1 text-xs font-bold px-3 py-1 rounded-full bg-honey-50 text-honey-dark border border-honey/30">
                                                <i class="bi bi-hourglass-split"></i> Menunggu Pemohon
                                            </span>
                                            <h3 class="font-bold text-bark text-lg mt-2">{{ $adoptionrequest->nama }}</h3>
                                        </div>
                                        <button onclick="closeModal('waiting-{{ $adoptionrequest->id }}')" class="text-bark-muted hover:text-bark">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                    <div class="bg-cream rounded-2xl p-4 space-y-2 text-sm mb-4">
                                        <div class="flex justify-between"><span class="text-bark-muted">Kandang</span><span class="font-bold text-bark">{{ $adoptionrequest->kandang }}</span></div>
                                        <div class="flex justify-between"><span class="text-bark-muted">Penawaran</span><span class="font-bold text-honey-dark">Rp {{ number_format($adoptionrequest->harga, 0, ',', '.') }}</span></div>
                                        @if ($adoptionrequest->keterangan)
                                            <div class="flex justify-between"><span class="text-bark-muted">Keterangan</span><span class="text-bark text-right max-w-[60%]">{{ $adoptionrequest->keterangan }}</span></div>
                                        @endif
                                    </div>
                                    <div class="text-center bg-honey-50 border border-honey/30 rounded-2xl p-4 text-sm font-semibold text-honey-dark mb-4">
                                        @if ($sugarglider->harga > 0)
                                            Menunggu pemohon mengunggah bukti transfer
                                        @else
                                            Menunggu pemohon mengkonfirmasi penerimaan
                                        @endif
                                    </div>
                                    <button onclick="closeModal('waiting-{{ $adoptionrequest->id }}')" class="btn-secondary w-full justify-center">Tutup</button>
                                </div>
                            </div>
                            @endif

                            {{-- MODAL: Status 6 + confirmed_at NULL — konfirmasi pembayaran --}}
                            @if ($adoptionrequest->status == 6 && is_null($adoptionrequest->confirmed_at))
                            <div id="confirm-pay-{{ $adoptionrequest->id }}" class="fixed inset-0 z-50 hidden items-center justify-center p-4"
                                 style="background:rgba(0,0,0,0.4)"
                                 onclick="if(event.target===this)closeModal('confirm-pay-{{ $adoptionrequest->id }}')">
                                <div class="bg-white rounded-3xl shadow-hover max-w-md w-full p-6">
                                    <div class="flex items-start justify-between mb-4">
                                        <div>
                                            <span class="inline-flex items-center gap-1 text-xs font-bold px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <i class="bi bi-receipt"></i> Bukti Transfer
                                            </span>
                                            <h3 class="font-bold text-bark text-lg mt-2">{{ $adoptionrequest->nama }}</h3>
                                        </div>
                                        <button onclick="closeModal('confirm-pay-{{ $adoptionrequest->id }}')" class="text-bark-muted hover:text-bark">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                    <div class="bg-cream rounded-2xl p-4 space-y-2 text-sm mb-4">
                                        <div class="flex justify-between"><span class="text-bark-muted">Pemohon</span><span class="font-bold text-bark">{{ $adoptionrequest->nama }}</span></div>
                                        <div class="flex justify-between"><span class="text-bark-muted">Kandang</span><span class="font-bold text-bark">{{ $adoptionrequest->kandang }}</span></div>
                                        <div class="flex justify-between"><span class="text-bark-muted">Jumlah Bayar</span><span class="font-bold text-honey-dark">Rp {{ number_format($adoptionrequest->harga, 0, ',', '.') }}</span></div>
                                        @if ($adoptionrequest->paid_at)
                                            <div class="flex justify-between"><span class="text-bark-muted">Waktu Transfer</span><span class="font-semibold text-bark">{{ $adoptionrequest->paid_at->format('d/m/Y H:i') }}</span></div>
                                        @endif
                                    </div>
                                    @if ($adoptionrequest->bukti_transfer)
                                        <div class="rounded-2xl overflow-hidden mb-4 border border-cream-dark">
                                            <img src="{{ Storage::url($adoptionrequest->bukti_transfer) }}"
                                                 alt="Bukti Transfer"
                                                 class="w-full max-h-64 object-contain bg-cream">
                                        </div>
                                    @endif
                                    <form action="{{ route('adoptionrequest.confirm-payment', $adoptionrequest->id) }}" method="POST">
                                        @csrf
                                        <div class="flex gap-3">
                                            <button type="button" onclick="closeModal('confirm-pay-{{ $adoptionrequest->id }}')"
                                                    class="btn-secondary flex-1 justify-center">Batal</button>
                                            <button type="submit" class="btn-create flex-1 justify-center">
                                                <i class="bi bi-check2-square"></i> Konfirmasi
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @endif

                            {{-- MODAL: Status 6 + confirmed_at NOT NULL — tandai terkirim --}}
                            @if ($adoptionrequest->status == 6 && !is_null($adoptionrequest->confirmed_at))
                            <div id="shipping-{{ $adoptionrequest->id }}" class="fixed inset-0 z-50 hidden items-center justify-center p-4"
                                 style="background:rgba(0,0,0,0.4)"
                                 onclick="if(event.target===this)closeModal('shipping-{{ $adoptionrequest->id }}')">
                                <div class="bg-white rounded-3xl shadow-hover max-w-md w-full p-6">
                                    <div class="flex items-start justify-between mb-4">
                                        <div>
                                            <span class="inline-flex items-center gap-1 text-xs font-bold px-3 py-1 rounded-full bg-sky-50 text-sky-600 border border-sky-200">
                                                <i class="bi bi-truck"></i> Kirim Sugar Glider
                                            </span>
                                            <h3 class="font-bold text-bark text-lg mt-2">{{ $sugarglider->nama }}</h3>
                                        </div>
                                        <button onclick="closeModal('shipping-{{ $adoptionrequest->id }}')" class="text-bark-muted hover:text-bark">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                    <div class="bg-cream rounded-2xl p-4 space-y-2 text-sm mb-4">
                                        <div class="flex justify-between"><span class="text-bark-muted">Penerima</span><span class="font-bold text-bark">{{ $adoptionrequest->nama }}</span></div>
                                        <div class="flex justify-between"><span class="text-bark-muted">Kandang Tujuan</span><span class="font-bold text-bark">{{ $adoptionrequest->kandang }}</span></div>
                                        <div class="flex justify-between"><span class="text-bark-muted">Sugar Glider</span><span class="font-bold text-bark">{{ $sugarglider->nama }}</span></div>
                                        <div class="flex justify-between"><span class="text-bark-muted">Morph</span><span class="font-bold text-bark">{{ $sugarglider->jenis }}</span></div>
                                    </div>
                                    <div class="text-center bg-sky-50 border border-sky-200 rounded-2xl p-3 text-xs font-semibold text-sky-700 mb-5">
                                        Pastikan sugar glider sudah dikirim sebelum menekan tombol ini.
                                    </div>
                                    <form action="{{ route('adoptionrequest.shipping', $adoptionrequest->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="adoption_id" value="{{ $sugarglider->id }}">
                                        <div class="flex gap-3">
                                            <button type="button" onclick="closeModal('shipping-{{ $adoptionrequest->id }}')"
                                                    class="btn-secondary flex-1 justify-center">Batal</button>
                                            <button type="submit" class="btn-create flex-1 justify-center">
                                                <i class="bi bi-truck"></i> Tandai Terkirim
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @endif

                            {{-- MODAL: Status 7 — sudah dikirim, menunggu konfirmasi pemohon --}}
                            @if ($adoptionrequest->status == 7)
                            <div id="shipped-{{ $adoptionrequest->id }}" class="fixed inset-0 z-50 hidden items-center justify-center p-4"
                                 style="background:rgba(0,0,0,0.4)"
                                 onclick="if(event.target===this)closeModal('shipped-{{ $adoptionrequest->id }}')">
                                <div class="bg-white rounded-3xl shadow-hover max-w-md w-full p-6 text-center">
                                    <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                        <i class="bi bi-house-heart text-purple-500 text-xl"></i>
                                    </div>
                                    <h3 class="font-bold text-bark text-lg mb-1">Sudah Dikirim</h3>
                                    <p class="text-bark-muted text-sm mb-4">Menunggu {{ $adoptionrequest->nama }} mengkonfirmasi bahwa sugar glider sudah diterima.</p>
                                    <div class="bg-cream rounded-2xl p-4 space-y-2 text-sm my-4 text-left">
                                        <div class="flex justify-between"><span class="text-bark-muted">Penerima</span><span class="font-bold text-bark">{{ $adoptionrequest->nama }}</span></div>
                                        <div class="flex justify-between"><span class="text-bark-muted">Kandang</span><span class="font-bold text-bark">{{ $adoptionrequest->kandang }}</span></div>
                                        <div class="flex justify-between"><span class="text-bark-muted">Sugar Glider</span><span class="font-bold text-bark">{{ $sugarglider->nama }}</span></div>
                                    </div>
                                    <button onclick="closeModal('shipped-{{ $adoptionrequest->id }}')" class="btn-secondary w-full justify-center">Tutup</button>
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

    {{-- Sidebar: Process guide --}}
    <div class="lg:w-64 flex-shrink-0">
        <div class="be-card p-5">
            <h3 class="font-bold text-bark mb-4">Alur Proses</h3>
            <div class="space-y-3 text-sm">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0">
                        <i class="bi bi-file-earmark-text text-gray-500"></i>
                    </span>
                    <span class="text-bark-light">Menunggu pilihan Anda</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl bg-honey-50 flex items-center justify-center flex-shrink-0">
                        <i class="bi bi-hourglass-split text-honey-dark"></i>
                    </span>
                    <span class="text-bark-light">Menunggu respons pemohon</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0">
                        <i class="bi bi-check2-square text-emerald-600"></i>
                    </span>
                    <span class="text-bark-light">Konfirmasi pembayaran</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl bg-sky-50 flex items-center justify-center flex-shrink-0">
                        <i class="bi bi-truck text-sky-500"></i>
                    </span>
                    <span class="text-bark-light">Kirim sugar glider</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl bg-purple-50 flex items-center justify-center flex-shrink-0">
                        <i class="bi bi-house-heart text-purple-500"></i>
                    </span>
                    <span class="text-bark-light">Menunggu konfirmasi terima</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl bg-sage/10 flex items-center justify-center flex-shrink-0">
                        <i class="bi bi-check-circle text-sage"></i>
                    </span>
                    <span class="text-bark-light">Selesai</span>
                </div>
                <div class="border-t border-cream-dark pt-3 flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl bg-red-50 flex items-center justify-center flex-shrink-0">
                        <i class="bi bi-file-earmark-excel text-red-500"></i>
                    </span>
                    <span class="text-bark-light">Tidak terpilih</span>
                </div>
            </div>
        </div>
    </div>

</div>

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

@endsection
