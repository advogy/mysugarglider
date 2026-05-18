@extends('layouts.v_backend')

@section('title', 'Cari Adopsi')

@section('content')

<x-page-header
    title="Cari Adopsi"
    subtitle="Daftar sugar glider yang dibuka untuk adopsi oleh pemilik lain."
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
                            <th>Sugar Glider</th>
                            <th class="hidden sm:table-cell">Morph</th>
                            <th class="hidden md:table-cell">Kandang</th>
                            <th class="hidden lg:table-cell">Harga</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($adoptions as $adoption)
                            <tr>
                                <td class="hidden md:table-cell text-bark-muted text-xs">
                                    {{ ($adoptions->currentPage() - 1) * $adoptions->perPage() + $loop->iteration }}
                                </td>
                                <td>
                                    <a href="{{ route('sugarglider.show', $adoption->sgId) }}"
                                       class="font-bold text-bark hover:text-sage transition-colors">
                                        {{ $adoption->sgNama }}
                                    </a>
                                </td>
                                <td class="hidden sm:table-cell">
                                    @if ($adoption->sgJenis)
                                        <span class="badge-sage">{{ $adoption->sgJenis }}</span>
                                    @else <span class="text-bark-muted">—</span> @endif
                                </td>
                                <td class="hidden md:table-cell">
                                    <a href="{{ route('shelter.show', $adoption->sId) }}"
                                       class="text-bark-light text-sm hover:text-sage transition-colors">
                                        {{ $adoption->sNama }}
                                    </a>
                                </td>
                                <td class="hidden lg:table-cell font-semibold text-bark-light text-sm">
                                    @if ($adoption->harga == 0)
                                        <span class="text-sage font-bold">Gratis</span>
                                    @else
                                        Rp {{ number_format($adoption->harga, 0, ',', '.') }}
                                    @endif
                                </td>
                                <td>
                                    @if ($adoption->arId)
                                        @if ($adoption->arStatus == 4)
                                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-red-50 text-red-500 border border-red-200">
                                                <i class="bi bi-x-circle"></i> Ditolak
                                            </span>
                                        @elseif ($adoption->arStatus == 5)
                                            <button type="button" onclick="openModal('selected-{{ $adoption->arId }}')"
                                                    class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-honey-50 text-honey-dark border border-honey/30 hover:bg-honey/20 transition-all">
                                                <i class="bi bi-stars"></i> Terpilih
                                            </button>
                                        @elseif ($adoption->arStatus == \App\Enums\AdoptionRequestStatus::DIBATALKAN->value)
                                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-gray-100 text-gray-500 border border-gray-200">
                                                <i class="bi bi-x-circle"></i> Dibatalkan
                                            </span>
                                        @elseif ($adoption->arStatus == 6 && $adoption->arBukti)
                                            {{-- Bukti diupload, menunggu konfirmasi admin --}}
                                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-blue-50 text-blue-600 border border-blue-200">
                                                <i class="bi bi-shield-check"></i> Menunggu Admin
                                            </span>
                                        @elseif ($adoption->arStatus == 6)
                                            {{-- Dipilih, belum upload bukti --}}
                                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-sky-50 text-sky-500 border border-sky-200">
                                                <i class="bi bi-truck"></i> Siap Dikirim
                                            </span>
                                        @elseif ($adoption->arStatus == 7)
                                            {{-- Dalam pengiriman → konfirmasi terima --}}
                                            <button type="button" onclick="openModal('delivered-{{ $adoption->id }}')"
                                                    class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-purple-50 text-purple-600 border border-purple-200 hover:bg-purple-100 transition-all">
                                                <i class="bi bi-house-heart"></i> Dalam Pengiriman
                                            </button>
                                        @elseif ($adoption->arStatus == 8)
                                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-sage/10 text-sage border border-sage/30">
                                                <i class="bi bi-check-circle"></i> Selesai
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-gray-100 text-gray-500 border border-gray-200">
                                                <i class="bi bi-people-fill"></i> Menunggu
                                            </span>
                                        @endif
                                    @else
                                        <button type="button" onclick="openModal('adopt-{{ $adoption->id }}')"
                                                class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-sage text-white hover:bg-sage-dark transition-all">
                                            <i class="bi bi-send"></i> Ajukan Permohonan
                                        </button>
                                    @endif
                                </td>
                            </tr>

                            {{-- Modal: Ajukan permohonan --}}
                            @if (!$adoption->arId)
                            <div id="adopt-{{ $adoption->id }}" class="be-modal hidden"

                                 onclick="if(event.target===this)closeModal('adopt-{{ $adoption->id }}')">
                                <div class="bg-white rounded-3xl shadow-hover max-w-md w-full p-6">
                                    <div class="flex items-start justify-between mb-4">
                                        <div>
                                            <h3 class="font-bold text-bark text-lg">Permohonan Adopsi</h3>
                                            <p class="text-bark-muted text-sm">{{ $adoption->sgNama }}
                                                @if ($adoption->sgJenis)({{ $adoption->sgJenis }})@endif
                                                —
                                                @if ($adoption->harga == 0)
                                                    <span class="text-sage font-semibold">Gratis</span>
                                                @else
                                                    Rp {{ number_format($adoption->harga, 0, ',', '.') }}
                                                @endif
                                            </p>
                                        </div>
                                        <button onclick="closeModal('adopt-{{ $adoption->id }}')" class="text-bark-muted hover:text-bark">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                    <form action="{{ route('adoptionrequest.store', $adoption->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="adoption_id" value="{{ $adoption->id }}">
                                        <div class="space-y-4">
                                            <div>
                                                <label class="form-label">Kandang Tujuan</label>
                                                <select name="shelter_id" class="input-field" required>
                                                    <option value="">Pilih Kandang</option>
                                                    @foreach ($shelters as $shelter)
                                                        <option value="{{ $shelter->id }}">{{ $shelter->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="form-label">Penawaran Harga (Rp)</label>
                                                <input type="number" name="harga" value="{{ $adoption->harga }}"
                                                       min="0" class="input-field" required>
                                            </div>
                                            <div>
                                                <label class="form-label">Keterangan (Opsional)</label>
                                                <textarea name="keterangan" rows="3" class="input-field"
                                                          placeholder="Perkenalkan diri Anda..."></textarea>
                                            </div>
                                            <div class="flex gap-3 pt-2">
                                                <button type="button" onclick="closeModal('adopt-{{ $adoption->id }}')"
                                                        class="btn-secondary flex-1 justify-center">Batal</button>
                                                <button type="submit" class="btn-create flex-1 justify-center">
                                                    <i class="bi bi-send"></i> Ajukan
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @endif

                            {{-- Modal: Terpilih — hub WA + aksi + batalkan (status 5) --}}
                            @if ($adoption->arId && $adoption->arStatus == 5)
                            @php
                                $rawOwnerTelp = $adoption->ownerTelp ?? '';
                                $waOwnerPhone = preg_replace('/[^0-9]/', '', $rawOwnerTelp);
                                if (str_starts_with($waOwnerPhone, '0')) $waOwnerPhone = '62' . substr($waOwnerPhone, 1);
                                $waOwnerLink = $waOwnerPhone ? 'https://wa.me/' . $waOwnerPhone . '?text=' . urlencode('Halo, saya pemohon adopsi ' . $adoption->sgNama . ' di MySugarGlider.id. Saya ingin bertanya lebih lanjut.') : null;
                            @endphp
                            <div id="selected-{{ $adoption->arId }}" class="be-modal hidden"
                                 onclick="if(event.target===this)closeModal('selected-{{ $adoption->arId }}')">
                                <div class="bg-white rounded-3xl shadow-hover max-w-md w-full p-6">
                                    <div class="flex items-start justify-between mb-4">
                                        <div>
                                            <span class="inline-flex items-center gap-1 text-xs font-bold px-3 py-1 rounded-full bg-honey-50 text-honey-dark border border-honey/30">
                                                <i class="bi bi-stars"></i> Anda Terpilih!
                                            </span>
                                            <h3 class="font-bold text-bark text-lg mt-2">{{ $adoption->sgNama }}</h3>
                                        </div>
                                        <button onclick="closeModal('selected-{{ $adoption->arId }}')" class="text-bark-muted hover:text-bark">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>

                                    <div class="bg-cream rounded-2xl p-4 space-y-2 text-sm mb-4">
                                        <div class="flex justify-between"><span class="text-bark-muted">Sugar Glider</span><span class="font-bold text-bark">{{ $adoption->sgNama }}</span></div>
                                        <div class="flex justify-between"><span class="text-bark-muted">Morph</span><span class="font-bold text-bark">{{ $adoption->sgJenis ?? '—' }}</span></div>
                                        <div class="flex justify-between">
                                            <span class="text-bark-muted">Biaya</span>
                                            <span class="font-bold {{ $adoption->arHarga == 0 ? 'text-sage' : 'text-honey-dark' }}">
                                                {{ $adoption->arHarga == 0 ? 'Gratis' : 'Rp ' . number_format($adoption->arHarga, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Tombol WA ke pemilik --}}
                                    @if ($waOwnerLink)
                                    <a href="{{ $waOwnerLink }}" target="_blank" rel="noopener"
                                       class="flex items-center justify-center gap-2 w-full py-2.5 rounded-2xl bg-[#25D366]/10 border border-[#25D366]/30 text-[#25D366] font-bold text-sm hover:bg-[#25D366] hover:text-white transition-all mb-4">
                                        <i class="bi bi-whatsapp text-lg"></i> Chat dengan Pemilik via WhatsApp
                                    </a>
                                    @else
                                    <div class="text-xs text-bark-muted text-center mb-4 p-2 bg-gray-50 rounded-xl">Pemilik belum mengisi nomor WhatsApp</div>
                                    @endif

                                    {{-- Aksi lanjut: konfirmasi gratis / upload bukti --}}
                                    @if ($adoption->arHarga == 0)
                                    <form action="{{ route('adoptionrequest.confirm-free', $adoption->arId) }}" method="POST" class="mb-3">
                                        @csrf
                                        <button type="submit" class="btn-create w-full justify-center">
                                            <i class="bi bi-check2-circle"></i> Konfirmasi Adopsi Gratis
                                        </button>
                                    </form>
                                    @else
                                    <button type="button"
                                            onclick="closeModal('selected-{{ $adoption->arId }}'); openModal('upload-{{ $adoption->arId }}')"
                                            class="btn-create w-full justify-center mb-3">
                                        <i class="bi bi-cloud-upload"></i> Upload Bukti Transfer
                                    </button>
                                    @endif

                                    {{-- Batalkan --}}
                                    <form action="{{ route('adoptionrequest.cancel', $adoption->arId) }}" method="POST"
                                          onsubmit="return confirm('Yakin ingin membatalkan pilihan ini? Pemohon lain akan dapat dipilih kembali.')">
                                        @csrf
                                        <button type="submit"
                                                class="flex items-center justify-center gap-2 w-full py-2 rounded-2xl border border-red-200 text-red-500 text-sm font-semibold hover:bg-red-50 transition-all">
                                            <i class="bi bi-x-circle"></i> Batalkan Pilihan
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endif

                            {{-- Modal: Konfirmasi adopsi gratis (status 5, harga=0) --}}
                            @if ($adoption->arId && $adoption->arStatus == 5 && $adoption->arHarga == 0)
                            <div id="confirm-free-{{ $adoption->arId }}" class="be-modal hidden"

                                 onclick="if(event.target===this)closeModal('confirm-free-{{ $adoption->arId }}')">
                                <div class="bg-white rounded-3xl shadow-hover max-w-md w-full p-6">
                                    <div class="w-12 h-12 bg-sage/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                        <i class="bi bi-gift text-sage text-xl"></i>
                                    </div>
                                    <h3 class="font-bold text-bark text-lg text-center mb-1">Terpilih — Adopsi Gratis</h3>
                                    <p class="text-bark-muted text-sm text-center mb-4">Anda terpilih untuk mengadopsi sugar glider ini secara gratis. Konfirmasi untuk melanjutkan proses.</p>
                                    <div class="bg-cream rounded-2xl p-4 space-y-2 text-sm mb-5">
                                        <div class="flex justify-between"><span class="text-bark-muted">Sugar Glider</span><span class="font-bold text-bark">{{ $adoption->sgNama }}</span></div>
                                        <div class="flex justify-between"><span class="text-bark-muted">Morph</span><span class="font-bold text-bark">{{ $adoption->sgJenis ?? '—' }}</span></div>
                                        <div class="flex justify-between"><span class="text-bark-muted">Biaya</span><span class="font-bold text-sage">Gratis</span></div>
                                    </div>
                                    <form action="{{ route('adoptionrequest.confirm-free', $adoption->arId) }}" method="POST">
                                        @csrf
                                        <div class="flex gap-3">
                                            <button type="button" onclick="closeModal('confirm-free-{{ $adoption->arId }}')"
                                                    class="btn-secondary flex-1 justify-center">Batal</button>
                                            <button type="submit" class="btn-create flex-1 justify-center">
                                                <i class="bi bi-check-lg"></i> Konfirmasi
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @endif

                            {{-- Modal: Upload bukti transfer (status 5, harga>0) --}}
                            @if ($adoption->arId && $adoption->arStatus == 5 && $adoption->arHarga > 0)
                            <div id="upload-{{ $adoption->arId }}" class="be-modal hidden"

                                 onclick="if(event.target===this)closeModal('upload-{{ $adoption->arId }}')">
                                <div class="bg-white rounded-3xl shadow-hover max-w-md w-full p-6">
                                    <div class="flex items-start justify-between mb-4">
                                        <div>
                                            <span class="badge-honey text-sm">Terpilih — Pembayaran</span>
                                            <h3 class="font-bold text-bark text-lg mt-1">{{ $adoption->sgNama }}</h3>
                                        </div>
                                        <button onclick="closeModal('upload-{{ $adoption->arId }}')" class="text-bark-muted hover:text-bark">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                    @php
                                        $fee      = (int) \App\Models\AppConfig::get('admin_platform_fee', 0);
                                        $total    = $adoption->arHarga + $fee;
                                        $bankName = \App\Models\AppConfig::get('admin_bank_name', '—');
                                        $bankNo   = \App\Models\AppConfig::get('admin_bank_number', '—');
                                        $bankAcc  = \App\Models\AppConfig::get('admin_bank_holder', '—');
                                    @endphp
                                    <div class="bg-cream rounded-2xl p-4 space-y-2 text-sm mb-4">
                                        <div class="flex justify-between"><span class="text-bark-muted">Sugar Glider</span><span class="font-bold text-bark">{{ $adoption->sgNama }}</span></div>
                                        <div class="flex justify-between"><span class="text-bark-muted">Morph</span><span class="font-bold text-bark">{{ $adoption->sgJenis ?? '—' }}</span></div>
                                        <div class="flex justify-between"><span class="text-bark-muted">Harga adopsi</span><span class="font-semibold text-bark">Rp {{ number_format($adoption->arHarga, 0, ',', '.') }}</span></div>
                                        @if ($fee > 0)
                                        <div class="flex justify-between"><span class="text-bark-muted">Biaya platform</span><span class="font-semibold text-bark">Rp {{ number_format($fee, 0, ',', '.') }}</span></div>
                                        @endif
                                        <div class="flex justify-between border-t border-cream-dark pt-2 mt-1"><span class="font-bold text-bark">Total Transfer</span><span class="font-bold text-honey-dark">Rp {{ number_format($total, 0, ',', '.') }}</span></div>
                                    </div>
                                    @if ($bankNo && $bankNo !== '—')
                                    <div class="bg-honey-50 border border-honey/30 rounded-2xl p-4 text-sm mb-5">
                                        <p class="font-bold text-bark mb-2"><i class="bi bi-bank mr-1"></i> Transfer ke rekening admin:</p>
                                        <p class="text-bark font-semibold">{{ $bankName }}</p>
                                        <p class="text-bark text-xs">a.n. {{ $bankAcc }}</p>
                                        <p class="font-mono font-bold text-bark text-base tracking-widest mt-1">{{ $bankNo }}</p>
                                        <p class="text-xs text-bark-muted mt-2"><i class="bi bi-info-circle mr-1"></i>Dana dikelola admin platform sebagai perantara terpercaya.</p>
                                    </div>
                                    @else
                                    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 text-sm mb-5">
                                        <p class="font-bold text-amber-800 mb-1"><i class="bi bi-exclamation-triangle-fill mr-1"></i> Rekening admin belum dikonfigurasi</p>
                                        <p class="text-amber-700 text-xs">Silakan hubungi admin platform untuk mendapatkan informasi rekening transfer.</p>
                                    </div>
                                    @endif
                                    <form action="{{ route('adoptionrequest.upload-payment', $adoption->arId) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-4">
                                            <label class="form-label">Upload Bukti Transfer</label>
                                            <input type="file" name="bukti_transfer" accept="image/*" class="input-field" required>
                                            <p class="text-xs text-bark-muted mt-1">Format: JPG, PNG. Maks 2MB.</p>
                                        </div>
                                        <div class="flex gap-3">
                                            <button type="button" onclick="closeModal('upload-{{ $adoption->arId }}')"
                                                    class="btn-secondary flex-1 justify-center">Batal</button>
                                            <button type="submit" class="btn-create flex-1 justify-center">
                                                <i class="bi bi-cloud-upload"></i> Upload
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @endif

                            {{-- Modal: Konfirmasi sudah terima fisik (status 7) --}}
                            @if ($adoption->arId && $adoption->arStatus == 7)
                            <div id="delivered-{{ $adoption->id }}" class="be-modal hidden"

                                 onclick="if(event.target===this)closeModal('delivered-{{ $adoption->id }}')">
                                <div class="bg-white rounded-3xl shadow-hover max-w-md w-full p-6">
                                    <div class="w-12 h-12 bg-sage/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                        <i class="bi bi-house-heart-fill text-sage text-xl"></i>
                                    </div>
                                    <h3 class="font-bold text-bark text-lg text-center mb-1">Konfirmasi Penerimaan</h3>
                                    <p class="text-bark-muted text-sm text-center mb-4">Konfirmasi bahwa sugar glider sudah Anda terima secara fisik. Data kepemilikan akan berpindah setelah ini.</p>
                                    <div class="bg-cream rounded-2xl p-4 space-y-2 text-sm mb-5">
                                        <div class="flex justify-between"><span class="text-bark-muted">Sugar Glider</span><span class="font-bold text-bark">{{ $adoption->sgNama }}</span></div>
                                        <div class="flex justify-between"><span class="text-bark-muted">Morph</span><span class="font-bold text-bark">{{ $adoption->sgJenis ?? '—' }}</span></div>
                                        <div class="flex justify-between"><span class="text-bark-muted">Kandang Tujuan</span><span class="font-bold text-bark">{{ $adoption->sNama ?? '—' }}</span></div>
                                    </div>
                                    <form action="{{ route('adoptionrequest.finalize', $adoption->cId) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="adoptionrequest_id" value="{{ $adoption->arId }}">
                                        <input type="hidden" name="shelter_id" value="{{ $adoption->arShelterId }}">
                                        <div class="flex gap-3">
                                            <button type="button" onclick="closeModal('delivered-{{ $adoption->id }}')"
                                                    class="btn-secondary flex-1 justify-center">Batal</button>
                                            <button type="submit" class="btn-create flex-1 justify-center">
                                                <i class="bi bi-check-lg"></i> Sudah Diterima
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @endif

                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($adoptions->hasPages())
                <div class="px-5 py-4 border-t border-cream-dark">
                    {{ $adoptions->links('pagination::v_pagination') }}
                </div>
            @endif
        </div>
    </div>

    <x-adoption-flow-guide />

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
