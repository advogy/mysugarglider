@extends('layouts.v_backend')

@section('title', 'Cari Adopsi')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-bark">Cari Adopsi</h2>
        <p class="text-bark-muted text-sm mt-0.5">Daftar sugar glider yang dibuka untuk adopsi oleh pemilik lain.</p>
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
                                            {{-- Tidak terpilih --}}
                                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-red-50 text-red-500 border border-red-200">
                                                <i class="bi bi-x-circle"></i> Tidak Terpilih
                                            </span>
                                        @elseif ($adoption->arStatus == 5)
                                            @if ($adoption->arHarga == 0)
                                                {{-- Gratis → pemohon konfirmasi --}}
                                                <button type="button" onclick="openModal('confirm-free-{{ $adoption->arId }}')"
                                                        class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-sage/10 text-sage border border-sage/30 hover:bg-sage hover:text-white transition-all">
                                                    <i class="bi bi-check2-circle"></i> Terpilih — Konfirmasi
                                                </button>
                                            @else
                                                {{-- Berbayar → upload bukti transfer --}}
                                                <button type="button" onclick="openModal('upload-{{ $adoption->arId }}')"
                                                        class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-honey-50 text-honey-dark border border-honey/30 hover:bg-honey/20 transition-all">
                                                    <i class="bi bi-cloud-upload"></i> Terpilih — Upload Bukti
                                                </button>
                                            @endif
                                        @elseif ($adoption->arStatus == 6)
                                            {{-- Menunggu konfirmasi pemilik / menunggu pengiriman --}}
                                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-sky-50 text-sky-600 border border-sky-200">
                                                <i class="bi bi-hourglass-split"></i>
                                                @if ($adoption->arBukti)
                                                    Menunggu Konfirmasi Pemilik
                                                @else
                                                    Menunggu Pengiriman
                                                @endif
                                            </span>
                                        @elseif ($adoption->arStatus == 7)
                                            {{-- Dalam pengiriman → konfirmasi terima --}}
                                            <button type="button" onclick="openModal('delivered-{{ $adoption->id }}')"
                                                    class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-sage/10 text-sage border border-sage/30 hover:bg-sage hover:text-white transition-all">
                                                <i class="bi bi-house-check"></i> Konfirmasi Diterima
                                            </button>
                                        @elseif ($adoption->arStatus == 8)
                                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-sage/10 text-sage border border-sage/30">
                                                <i class="bi bi-check-circle"></i> Selesai
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-gray-100 text-gray-500 border border-gray-200">
                                                <i class="bi bi-clock"></i> Menunggu
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
                            <div id="adopt-{{ $adoption->id }}" class="fixed inset-0 z-50 hidden items-center justify-center p-4"
                                 style="background:rgba(0,0,0,0.4)"
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

                            {{-- Modal: Konfirmasi adopsi gratis (status 5, harga=0) --}}
                            @if ($adoption->arId && $adoption->arStatus == 5 && $adoption->arHarga == 0)
                            <div id="confirm-free-{{ $adoption->arId }}" class="fixed inset-0 z-50 hidden items-center justify-center p-4"
                                 style="background:rgba(0,0,0,0.4)"
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
                            <div id="upload-{{ $adoption->arId }}" class="fixed inset-0 z-50 hidden items-center justify-center p-4"
                                 style="background:rgba(0,0,0,0.4)"
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
                                    <div class="bg-cream rounded-2xl p-4 space-y-2 text-sm mb-4">
                                        <div class="flex justify-between"><span class="text-bark-muted">Sugar Glider</span><span class="font-bold text-bark">{{ $adoption->sgNama }}</span></div>
                                        <div class="flex justify-between"><span class="text-bark-muted">Morph</span><span class="font-bold text-bark">{{ $adoption->sgJenis ?? '—' }}</span></div>
                                        <div class="flex justify-between"><span class="text-bark-muted">Total Bayar</span><span class="font-bold text-honey-dark">Rp {{ number_format($adoption->arHarga, 0, ',', '.') }}</span></div>
                                    </div>
                                    <div class="bg-honey-50 border border-honey/30 rounded-2xl p-4 text-sm mb-5">
                                        <p class="font-bold text-bark mb-2"><i class="bi bi-bank me-1"></i> Transfer ke:</p>
                                        <p class="text-bark">Bank Rakyat Indonesia (BRI)</p>
                                        <p class="text-bark">a.n. Tanuarto Simatupang</p>
                                        <p class="font-mono font-bold text-bark text-base tracking-widest mt-1">122801002406500</p>
                                    </div>
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
                            <div id="delivered-{{ $adoption->id }}" class="fixed inset-0 z-50 hidden items-center justify-center p-4"
                                 style="background:rgba(0,0,0,0.4)"
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

    {{-- Sidebar --}}
    <div class="lg:w-64 flex-shrink-0">
        <div class="be-card p-5">
            <h3 class="font-bold text-bark mb-4">Alur Adopsi</h3>
            <div class="space-y-3 text-sm">
                <div class="flex items-start gap-3">
                    <span class="w-8 h-8 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="bi bi-clock text-gray-500"></i>
                    </span>
                    <span class="text-bark-light">Permohonan dikirim, menunggu dipilih pemilik</span>
                </div>
                <div class="flex items-start gap-3">
                    <span class="w-8 h-8 rounded-xl bg-sage/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="bi bi-check2-circle text-sage"></i>
                    </span>
                    <span class="text-bark-light">Terpilih — konfirmasi atau selesaikan pembayaran</span>
                </div>
                <div class="flex items-start gap-3">
                    <span class="w-8 h-8 rounded-xl bg-sky-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="bi bi-hourglass-split text-sky-500"></i>
                    </span>
                    <span class="text-bark-light">Menunggu pemilik mengkonfirmasi & mengirimkan</span>
                </div>
                <div class="flex items-start gap-3">
                    <span class="w-8 h-8 rounded-xl bg-sage/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="bi bi-house-check text-sage"></i>
                    </span>
                    <span class="text-bark-light">Konfirmasi penerimaan fisik → data berpindah</span>
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
