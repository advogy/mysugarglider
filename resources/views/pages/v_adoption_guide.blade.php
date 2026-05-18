@extends('layouts.v_main')
@section('title', 'Panduan Adopsi — MySugarGlider')

@section('content')

<header class="premium-page-header">
    <div class="header-blob-1"></div>
    <h1 class="page-title">Panduan Adopsi</h1>
    <p class="page-subtitle">Sistem adopsi MySugarGlider menggunakan mekanisme escrow — admin platform menjadi perantara terpercaya antara pemilik dan pemohon.</p>
</header>

<div class="max-w-4xl mx-auto px-4 sm:px-6 py-12 font-ui">

    {{-- Tab: Pemohon / Pemilik --}}
    <div class="flex justify-center gap-2 mb-8">
        <button data-tab="tab-buyer" onclick="switchTab('tab-buyer')"
                class="tab-btn px-5 py-2.5 rounded-full text-sm font-bold bg-sage text-white transition-all">
            <i class="bi bi-person-heart mr-1"></i> Saya Pemohon (Adopter)
        </button>
        <button data-tab="tab-seller" onclick="switchTab('tab-seller')"
                class="tab-btn px-5 py-2.5 rounded-full text-sm font-bold bg-cream text-bark-muted hover:bg-cream-dark transition-all">
            <i class="bi bi-house-heart mr-1"></i> Saya Pemilik (Shelter)
        </button>
    </div>

    {{-- ════ TAB PEMOHON ════ --}}
    <div id="tab-buyer" class="tab-pane space-y-4">

        @php $buyerSteps = [
            [
                'num'   => 1,
                'icon'  => 'bi-search',
                'color' => 'sage',
                'title' => 'Temukan Sugar Glider',
                'desc'  => 'Telusuri listing adopsi di halaman Koleksi. Filter berdasarkan morph, usia, atau lokasi kandang.',
                'note'  => null,
            ],
            [
                'num'   => 2,
                'icon'  => 'bi-send-fill',
                'color' => 'honey',
                'title' => 'Ajukan Permohonan',
                'desc'  => 'Pilih kandang tujuan Anda dan tulis pesan perkenalan. Satu akun hanya bisa mengajukan satu permohonan per listing.',
                'note'  => 'Pastikan Anda sudah memiliki kandang terdaftar sebelum mengajukan.',
            ],
            [
                'num'   => 3,
                'icon'  => 'bi-hourglass-split',
                'color' => 'bark',
                'title' => 'Menunggu Pemilik Memilih',
                'desc'  => 'Pemilik akan meninjau semua permohonan masuk. Jika Anda dipilih, status berubah menjadi "Terpilih".',
                'note'  => 'Pemohon lain yang tidak dipilih akan otomatis ditolak.',
            ],
            [
                'num'   => 4,
                'icon'  => 'bi-bank',
                'color' => 'sky',
                'title' => 'Transfer ke Rekening Admin',
                'desc'  => 'Untuk adopsi berbayar, Anda akan mendapat info rekening admin platform. Transfer total (harga + biaya platform) ke rekening tersebut, lalu upload bukti transfer.',
                'note'  => 'Untuk adopsi gratis, langsung klik "Konfirmasi Adopsi Gratis" tanpa perlu transfer.',
            ],
            [
                'num'   => 5,
                'icon'  => 'bi-shield-check',
                'color' => 'blue',
                'title' => 'Admin Verifikasi Pembayaran',
                'desc'  => 'Tim admin akan memeriksa bukti transfer Anda. Setelah dikonfirmasi, pemilik mendapat notifikasi untuk mengirimkan sugar glider.',
                'note'  => 'Proses verifikasi dilakukan dalam 1×24 jam hari kerja.',
            ],
            [
                'num'   => 6,
                'icon'  => 'bi-truck',
                'color' => 'purple',
                'title' => 'Sugar Glider Dikirim',
                'desc'  => 'Pemilik mengirimkan sugar glider secara fisik. Status berubah menjadi "Dalam Pengiriman". Pantau melalui dashboard Anda.',
                'note'  => null,
            ],
            [
                'num'   => 7,
                'icon'  => 'bi-house-heart-fill',
                'color' => 'sage',
                'title' => 'Konfirmasi Penerimaan',
                'desc'  => 'Setelah sugar glider tiba, klik "Sudah Diterima". Kepemilikan akan berpindah ke kandang Anda secara otomatis.',
                'note'  => 'Setelah konfirmasi, admin akan mencairkan dana ke pemilik. Jangan konfirmasi jika belum menerima!',
            ],
        ]; @endphp

        @foreach ($buyerSteps as $step)
        <div class="flex gap-4">
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 rounded-2xl bg-{{ $step['color'] }}/10 flex items-center justify-center flex-shrink-0 border border-{{ $step['color'] }}/20">
                    <i class="bi {{ $step['icon'] }} text-{{ $step['color'] === 'bark' ? 'bark-muted' : $step['color'] }}"></i>
                </div>
                @if (!$loop->last)
                <div class="w-px flex-1 bg-cream-dark mt-2 min-h-[1.5rem]"></div>
                @endif
            </div>
            <div class="pb-6 flex-1">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xs font-bold text-bark-muted">Langkah {{ $step['num'] }}</span>
                </div>
                <h3 class="font-number font-bold text-bark text-base mb-1">{{ $step['title'] }}</h3>
                <p class="text-bark-muted text-sm">{{ $step['desc'] }}</p>
                @if ($step['note'])
                <div class="mt-2 flex items-start gap-2 text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-xl px-3 py-2">
                    <i class="bi bi-exclamation-triangle-fill flex-shrink-0 mt-0.5"></i>
                    <span>{{ $step['note'] }}</span>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- ════ TAB PEMILIK ════ --}}
    <div id="tab-seller" class="tab-pane hidden space-y-4">

        @php $sellerSteps = [
            [
                'num'   => 1,
                'icon'  => 'bi-plus-circle-fill',
                'color' => 'sage',
                'title' => 'Buat Listing Adopsi',
                'desc'  => 'Buka menu Adopsi → Buat Adopsi. Pilih sugar glider dari koleksi Anda (berstatus Privat atau Publik), tentukan harga (atau gratis), dan tambah keterangan.',
                'note'  => 'Pastikan rekening bank Anda sudah diisi di Profil → Rekening Bank agar dana bisa dicairkan.',
            ],
            [
                'num'   => 2,
                'icon'  => 'bi-people-fill',
                'color' => 'honey',
                'title' => 'Tinjau Permohonan Masuk',
                'desc'  => 'Buka detail listing Anda. Semua permohonan dari calon adopter akan tampil di sini beserta kandang tujuan dan keterangan mereka.',
                'note'  => null,
            ],
            [
                'num'   => 3,
                'icon'  => 'bi-check2-circle',
                'color' => 'emerald',
                'title' => 'Pilih Pemohon Terpilih',
                'desc'  => 'Klik "Pilih" di samping nama pemohon yang Anda inginkan. Pemohon lain otomatis ditolak. Pemohon terpilih mendapat notifikasi.',
                'note'  => null,
            ],
            [
                'num'   => 4,
                'icon'  => 'bi-shield-check',
                'color' => 'blue',
                'title' => 'Tunggu Admin Konfirmasi Pembayaran',
                'desc'  => 'Untuk adopsi berbayar, pemohon akan transfer ke rekening admin. Admin memverifikasi dan mengkonfirmasi pembayaran. Anda tidak perlu melakukan apa pun di tahap ini.',
                'note'  => 'Untuk adopsi gratis, pemohon langsung mengkonfirmasi dan Anda bisa langsung kirim.',
            ],
            [
                'num'   => 5,
                'icon'  => 'bi-truck',
                'color' => 'sky',
                'title' => 'Kirimkan Sugar Glider',
                'desc'  => 'Setelah admin mengkonfirmasi pembayaran, kirimkan sugar glider secara fisik. Klik "Tandai Terkirim", lalu isi nama ekspedisi, nomor resi pengiriman, dan unggah foto bukti pengiriman (opsional).',
                'note'  => 'Kirim dengan kurir terpercaya yang berpengalaman menangani hewan hidup.',
            ],
            [
                'num'   => 6,
                'icon'  => 'bi-hourglass-split',
                'color' => 'purple',
                'title' => 'Tunggu Konfirmasi Penerimaan',
                'desc'  => 'Pemohon mengkonfirmasi bahwa sugar glider sudah diterima secara fisik. Kepemilikan berpindah otomatis.',
                'note'  => null,
            ],
            [
                'num'   => 7,
                'icon'  => 'bi-send-check-fill',
                'color' => 'sage',
                'title' => 'Dana Dicairkan ke Rekening Anda',
                'desc'  => 'Setelah pemohon konfirmasi, admin akan mentransfer dana (setelah dikurangi biaya platform) ke rekening bank yang Anda daftarkan di profil.',
                'note'  => 'Pencairan dilakukan dalam 1×24 jam hari kerja setelah konfirmasi penerimaan.',
            ],
        ]; @endphp

        @foreach ($sellerSteps as $step)
        <div class="flex gap-4">
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 rounded-2xl bg-{{ $step['color'] }}/10 flex items-center justify-center flex-shrink-0 border border-{{ $step['color'] }}/20">
                    <i class="bi {{ $step['icon'] }} text-{{ $step['color'] === 'bark' ? 'bark-muted' : $step['color'] }}"></i>
                </div>
                @if (!$loop->last)
                <div class="w-px flex-1 bg-cream-dark mt-2 min-h-[1.5rem]"></div>
                @endif
            </div>
            <div class="pb-6 flex-1">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xs font-bold text-bark-muted">Langkah {{ $step['num'] }}</span>
                </div>
                <h3 class="font-number font-bold text-bark text-base mb-1">{{ $step['title'] }}</h3>
                <p class="text-bark-muted text-sm">{{ $step['desc'] }}</p>
                @if ($step['note'])
                <div class="mt-2 flex items-start gap-2 text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-xl px-3 py-2">
                    <i class="bi bi-exclamation-triangle-fill flex-shrink-0 mt-0.5"></i>
                    <span>{{ $step['note'] }}</span>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- ── Escrow Info Card ── --}}
    <div class="mt-10 rounded-3xl border border-sage/20 bg-sage/5 p-6 sm:p-8">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl bg-sage/10 flex items-center justify-center flex-shrink-0">
                <i class="bi bi-shield-fill-check text-sage text-xl"></i>
            </div>
            <div>
                <h3 class="font-number font-bold text-bark text-lg mb-2">Mengapa Sistem Escrow?</h3>
                <p class="text-bark-muted text-sm mb-4">
                    Sistem escrow melindungi <strong>kedua pihak</strong> dari risiko penipuan. Dana tidak langsung diterima pemilik — melainkan ditampung admin platform terlebih dahulu.
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                    <div class="flex items-start gap-2">
                        <i class="bi bi-check-circle-fill text-sage flex-shrink-0 mt-0.5"></i>
                        <span class="text-bark-light"><strong>Pemohon aman:</strong> dana tidak hilang jika SG tidak dikirim</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <i class="bi bi-check-circle-fill text-sage flex-shrink-0 mt-0.5"></i>
                        <span class="text-bark-light"><strong>Pemilik aman:</strong> SG baru dikirim setelah pembayaran dikonfirmasi</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <i class="bi bi-check-circle-fill text-sage flex-shrink-0 mt-0.5"></i>
                        <span class="text-bark-light"><strong>Admin sebagai mediator</strong> terpercaya antara kedua belah pihak</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CTA --}}
    <div class="mt-8 text-center">
        <a href="{{ route('collections', ['status' => 'adopsi']) }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-sage text-white font-bold hover:bg-sage-dark transition-colors">
            <i class="bi bi-search"></i> Cari Sugar Glider untuk Diadopsi
        </a>
    </div>

</div>
@endsection

@push('scripts')
<script>
function switchTab(id) {
    document.querySelectorAll('.tab-pane').forEach(p => p.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.classList.remove('bg-sage', 'text-white');
        b.classList.add('bg-cream', 'text-bark-muted');
    });
    document.getElementById(id).classList.remove('hidden');
    const btn = document.querySelector(`[data-tab="${id}"]`);
    btn.classList.add('bg-sage', 'text-white');
    btn.classList.remove('bg-cream', 'text-bark-muted');
}
</script>
@endpush
