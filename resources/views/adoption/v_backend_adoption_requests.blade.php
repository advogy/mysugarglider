@extends('layouts.v_backend')

@section('title', 'Permohonan Adopsi')

@section('content')

<x-page-header
    :title="$sugarglider->nama . ($sugarglider->jenis ? ' (' . $sugarglider->jenis . ')' : '')"
    subtitle="{{ __('text.adoption_request') }}"
    :backRoute="route('adoption.index')"
/>

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
                                            <i class="bi bi-x-circle"></i> Tidak Terpilih
                                        </span>

                                    @elseif ($adoptionrequest->status == 5)
                                        {{-- DIPILIH: buka modal hub WA + batalkan --}}
                                        <button type="button" onclick="openModal('dipilih-{{ $adoptionrequest->id }}')"
                                                class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-honey-50 text-honey-dark border border-honey/30 hover:bg-honey/20 transition-all">
                                            <i class="bi bi-hourglass-split"></i> Menunggu Pemohon
                                        </button>
                                    @elseif ($adoptionrequest->status == \App\Enums\AdoptionRequestStatus::DIBATALKAN->value)
                                        <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-gray-100 text-gray-500 border border-gray-200">
                                            <i class="bi bi-x-circle"></i> Dibatalkan
                                        </span>

                                    @elseif ($adoptionrequest->status == 6 && is_null($adoptionrequest->confirmed_at))
                                        {{-- DIBAYAR: menunggu admin konfirmasi pembayaran --}}
                                        <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-blue-50 text-blue-600 border border-blue-200">
                                            <i class="bi bi-shield-check"></i> Menunggu Admin
                                        </span>

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

                            {{-- MODAL: Status 5 — hub WA + batalkan --}}
                            @if ($adoptionrequest->status == 5)
                            @php
                                $rawApplicantTelp = $adoptionrequest->applicantTelp ?? '';
                                $waApplicantPhone = preg_replace('/[^0-9]/', '', $rawApplicantTelp);
                                if (str_starts_with($waApplicantPhone, '0')) $waApplicantPhone = '62' . substr($waApplicantPhone, 1);
                                $waApplicantLink = $waApplicantPhone ? 'https://wa.me/' . $waApplicantPhone . '?text=' . urlencode('Halo ' . $adoptionrequest->nama . ', saya pemilik ' . $sugarglider->nama . ' di MySugarGlider.id. Saya ingin mendiskusikan proses adopsi lebih lanjut.') : null;
                            @endphp
                            <div id="dipilih-{{ $adoptionrequest->id }}" class="be-modal hidden"
                                 onclick="if(event.target===this)closeModal('dipilih-{{ $adoptionrequest->id }}')">
                                <div class="bg-white rounded-3xl shadow-hover max-w-md w-full p-6">
                                    <div class="flex items-start justify-between mb-4">
                                        <div>
                                            <span class="inline-flex items-center gap-1 text-xs font-bold px-3 py-1 rounded-full bg-honey-50 text-honey-dark border border-honey/30">
                                                <i class="bi bi-hourglass-split"></i> Pemohon Dipilih
                                            </span>
                                            <h3 class="font-bold text-bark text-lg mt-2">{{ $adoptionrequest->nama }}</h3>
                                        </div>
                                        <button onclick="closeModal('dipilih-{{ $adoptionrequest->id }}')" class="text-bark-muted hover:text-bark">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>

                                    <div class="bg-cream rounded-2xl p-4 space-y-2 text-sm mb-4">
                                        <div class="flex justify-between"><span class="text-bark-muted">Kandang</span><span class="font-bold text-bark">{{ $adoptionrequest->kandang }}</span></div>
                                        <div class="flex justify-between"><span class="text-bark-muted">Penawaran</span><span class="font-bold text-honey-dark">Rp {{ number_format($adoptionrequest->harga, 0, ',', '.') }}</span></div>
                                        @if ($adoptionrequest->keterangan)
                                        <div><span class="text-bark-muted block mb-0.5">Keterangan</span><span class="text-bark text-sm">{{ $adoptionrequest->keterangan }}</span></div>
                                        @endif
                                    </div>

                                    <div class="text-center bg-honey-50 border border-honey/30 rounded-2xl p-3 text-xs font-semibold text-honey-dark mb-4">
                                        @if ($sugarglider->harga > 0)
                                            Menunggu pemohon mengunggah bukti transfer ke rekening admin
                                        @else
                                            Menunggu pemohon mengkonfirmasi penerimaan
                                        @endif
                                    </div>

                                    {{-- WA ke pemohon --}}
                                    @if ($waApplicantLink)
                                    <a href="{{ $waApplicantLink }}" target="_blank" rel="noopener"
                                       class="flex items-center justify-center gap-2 w-full py-2.5 rounded-2xl bg-[#25D366]/10 border border-[#25D366]/30 text-[#25D366] font-bold text-sm hover:bg-[#25D366] hover:text-white transition-all mb-3">
                                        <i class="bi bi-whatsapp text-lg"></i> Chat dengan Pemohon via WhatsApp
                                    </a>
                                    @else
                                    <div class="text-xs text-bark-muted text-center mb-3 p-2 bg-gray-50 rounded-xl">Pemohon belum mengisi nomor WhatsApp</div>
                                    @endif

                                    {{-- Batalkan --}}
                                    <form action="{{ route('adoptionrequest.cancel', $adoptionrequest->id) }}" method="POST"
                                          onsubmit="return confirm('Batalkan pilihan ini? Semua pemohon lain yang ditolak akan dikembalikan ke status menunggu dan Anda bisa memilih ulang.')">
                                        @csrf
                                        <button type="submit"
                                                class="flex items-center justify-center gap-2 w-full py-2 rounded-2xl border border-red-200 text-red-500 text-sm font-semibold hover:bg-red-50 transition-all">
                                            <i class="bi bi-x-circle"></i> Batalkan Pilihan Ini
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endif

                            {{-- MODAL: Status 6 + confirmed_at NOT NULL — tandai terkirim --}}
                            @if ($adoptionrequest->status == 6 && !is_null($adoptionrequest->confirmed_at))
                            <div id="shipping-{{ $adoptionrequest->id }}" class="be-modal hidden"

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
                                                    <form action="{{ route('adoptionrequest.shipping', $adoptionrequest->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="adoption_id" value="{{ $sugarglider->id }}">
                                        <div class="space-y-4 mb-5">
                                            <div>
                                                <label class="form-label">Nama Ekspedisi <span class="text-red-400">*</span></label>
                                                <input type="text" name="nama_ekspedisi" class="input-field"
                                                       placeholder="Contoh: JNE, J&T, SiCepat, Wahana..." required>
                                            </div>
                                            <div>
                                                <label class="form-label">Nomor Resi / Pelacakan <span class="text-red-400">*</span></label>
                                                <input type="text" name="resi_pengiriman" class="input-field font-mono"
                                                       placeholder="Contoh: JX123456789ID" required>
                                            </div>
                                            <div>
                                                <label class="form-label">Bukti Pengiriman <span class="text-bark-muted font-normal">(Opsional)</span></label>
                                                <input type="file" name="bukti_pengiriman" accept="image/*" class="input-field">
                                                <p class="text-xs text-bark-muted mt-1">Foto label pengiriman atau struk ekspedisi. Maks 2MB.</p>
                                            </div>
                                        </div>
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
                            <div id="shipped-{{ $adoptionrequest->id }}" class="be-modal hidden"

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
                                        @if ($adoptionrequest->nama_ekspedisi || $adoptionrequest->resi_pengiriman)
                                        <div class="border-t border-cream-dark pt-2 mt-1 space-y-1">
                                            @if ($adoptionrequest->nama_ekspedisi)
                                            <div class="flex justify-between">
                                                <span class="text-bark-muted">Ekspedisi</span>
                                                <span class="font-semibold text-bark">{{ $adoptionrequest->nama_ekspedisi }}</span>
                                            </div>
                                            @endif
                                            @if ($adoptionrequest->resi_pengiriman)
                                            <div class="flex justify-between">
                                                <span class="text-bark-muted">No. Resi</span>
                                                <span class="font-mono font-bold text-bark">{{ $adoptionrequest->resi_pengiriman }}</span>
                                            </div>
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                    @if ($adoptionrequest->bukti_pengiriman)
                                    <button type="button"
                                            onclick="previewPhoto('{{ asset('storage/' . $adoptionrequest->bukti_pengiriman) }}', 'Bukti Pengiriman — {{ $sugarglider->nama }}')"
                                            class="w-full group relative rounded-xl overflow-hidden border border-cream-dark bg-gray-50 hover:border-sky-300 transition-colors mb-4 block">
                                        <img src="{{ asset('storage/' . $adoptionrequest->bukti_pengiriman) }}"
                                             alt="Bukti Pengiriman" class="w-full max-h-40 object-contain">
                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 flex items-center justify-center transition-all">
                                            <span class="opacity-0 group-hover:opacity-100 transition-opacity bg-white/90 text-bark text-xs font-bold px-3 py-1.5 rounded-full flex items-center gap-1.5">
                                                <i class="bi bi-zoom-in"></i> Lihat Bukti
                                            </span>
                                        </div>
                                    </button>
                                    @endif
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
        <div class="be-card overflow-hidden">
            <div class="px-4 py-3 border-b border-cream-dark bg-cream/40 flex items-center gap-2">
                <i class="bi bi-signpost-2-fill text-sage text-sm"></i>
                <p class="font-ui font-bold text-bark text-sm">Alur Proses (Pemilik)</p>
            </div>
            <div class="p-4 space-y-3 text-sm">
                <div class="flex items-start gap-3">
                    <span class="w-8 h-8 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="bi bi-people-fill text-gray-500"></i>
                    </span>
                    <div>
                        <p class="font-bold text-bark text-xs">Pilih Pemohon</p>
                        <p class="text-bark-muted text-xs">Pilih dari daftar yang mengajukan</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <span class="w-8 h-8 rounded-xl bg-honey-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="bi bi-hourglass-split text-honey-dark"></i>
                    </span>
                    <div>
                        <p class="font-bold text-bark text-xs">Menunggu Pemohon</p>
                        <p class="text-bark-muted text-xs">Transfer ke rekening admin</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <span class="w-8 h-8 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="bi bi-shield-check text-blue-600"></i>
                    </span>
                    <div>
                        <p class="font-bold text-bark text-xs">Menunggu Admin</p>
                        <p class="text-bark-muted text-xs">Admin mengkonfirmasi pembayaran diterima</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <span class="w-8 h-8 rounded-xl bg-sky-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="bi bi-truck text-sky-500"></i>
                    </span>
                    <div>
                        <p class="font-bold text-bark text-xs">Tandai Terkirim</p>
                        <p class="text-bark-muted text-xs">Kirim sugar glider ke pemohon</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <span class="w-8 h-8 rounded-xl bg-purple-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="bi bi-house-heart text-purple-600"></i>
                    </span>
                    <div>
                        <p class="font-bold text-bark text-xs">Menunggu Terima</p>
                        <p class="text-bark-muted text-xs">Pemohon konfirmasi penerimaan fisik</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <span class="w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="bi bi-send-check text-emerald-600"></i>
                    </span>
                    <div>
                        <p class="font-bold text-bark text-xs">Pencairan Dana</p>
                        <p class="text-bark-muted text-xs">Admin cairkan dana ke rekening Anda</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <span class="w-8 h-8 rounded-xl bg-sage/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="bi bi-check-circle text-sage"></i>
                    </span>
                    <div>
                        <p class="font-bold text-bark text-xs">Selesai</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<x-photo-preview-modal />

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
