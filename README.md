# MySugarGlider.id

> Karena Sugar Glider Anda begitu penting.

Platform manajemen Sugar Glider berbasis web — catat, kelola, dan bagikan data Sugar Glider Anda termasuk silsilah, kandang, penempatan, hingga proses adopsi dengan sistem escrow.

## Demo

[https://www.mysugarglider.id](https://www.mysugarglider.id) — Daftar gratis sebagai pengguna

---

## Fitur

### Pengguna
- **Profil** — kode profil unik (3 huruf) sebagai inisial kode Sugar Glider; termasuk data rekening bank untuk pencairan dana adopsi
- **Kandang** — kelola data kandang beserta foto, alamat, dan lokasi Google Maps
- **Sugar Glider** — input data lengkap (jenis, warna, genetika, fenotype, foto) dengan kode otomatis berformat `ABC-0001`
- **Silsilah (Pedigree)** — rekam indukan jantan dan betina, tampilkan pohon silsilah hingga 4 generasi
- **Penempatan** — atur status koleksi (Privat, Publik, Adopsi, Mati); status **Selesai - Diadopsi** otomatis terset saat adopsi selesai dan tidak dapat diedit/dihapus
- **Adopsi** — sistem adopsi berbasis escrow dengan alur lengkap:
  - Buat listing adopsi (berbayar atau gratis)
  - Tinjau permohonan masuk dari calon adopter
  - Pilih pemohon terpilih; pemohon lain otomatis ditolak
  - Pemohon upload bukti transfer ke rekening admin (atau konfirmasi langsung untuk adopsi gratis)
  - Admin verifikasi pembayaran → pemilik kirim SG dengan input nama ekspedisi, nomor resi, dan foto bukti pengiriman
  - Pemohon konfirmasi penerimaan → kepemilikan berpindah otomatis
  - Admin mencairkan dana ke rekening pemilik
- **Notifikasi Desktop** — notifikasi real-time di dalam aplikasi (polling 30 detik) dan browser desktop via Web Notification API untuk setiap langkah proses adopsi
- **Poin & Level** — kumpulkan poin dari setiap aktivitas (tambah SG, lengkapi profil, foto, silsilah, adopsi, dll)
- **Penukaran Poin** — tukarkan poin dengan reward yang tersedia

### Breeding Tools
- **Inbreeding Calculator** — hitung koefisien inbreeding (Wright's Path Coefficient) dari dua calon indukan; mendukung mode database (pilih SG terdaftar) dan mode manual (input pohon silsilah sendiri); searchable dropdown dengan data milik sendiri maupun pengguna lain
- **Morph Predictor** — prediksi morph keturunan berdasarkan Hukum Mendel; mendukung morph dominan, resesif, dan kombinasi (Platinum Mosaic / TPM); pilih morph utama yang diekspresikan dan gen het carrier dari setiap indukan

### Publik
- Direktori kandang dan Sugar Glider yang dipublikasikan
- Halaman detail kandang dengan pratinjau peta Google Maps (bisa diperbesar)
- Halaman detail Sugar Glider dengan tampilan silsilah
- Halaman panduan adopsi dengan penjelasan alur escrow untuk pemohon dan pemilik

### Admin
- **Dashboard** — statistik global (pengguna, kandang, SG, penempatan, adopsi aktif, testimoni pending)
- **Manajemen Adopsi** — konfirmasi pembayaran masuk (setelah bukti transfer diunggah pemohon), tandai pencairan dana ke pemilik, pantau semua listing adopsi
- **Manajemen Konten** — kelola halaman publik (Tentang, dll) via konfigurasi dinamis
- **Manajemen Data** — lihat dan kelola semua data kandang, Sugar Glider, dan penempatan seluruh user
- **Manajemen User** — ban/aktifkan akun, ubah role, hapus user
- **Poin & Reward** — pantau poin semua user, kelola reward items, proses penukaran, konfigurasi nilai poin
- **Sistem Konfigurasi** — pengaturan situs (nama, kontak, media sosial, biaya platform, dll)

---

## Persyaratan

| Komponen | Versi |
|---|---|
| PHP | ^8.2 |
| Laravel | ^12.0 |
| Database | MySQL / MariaDB |
| Node.js | untuk build assets (Tailwind CSS) |

---

## Instalasi

```bash
# 1. Clone repositori
git clone <repo-url>
cd mysugarglider

# 2. Install dependensi PHP
composer install

# 3. Install dependensi Node
npm install

# 4. Salin file environment
cp .env.example .env
php artisan key:generate

# 5. Konfigurasi database di .env
# DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 6. Jalankan migrasi dan seeder
php artisan migrate --seed

# 7. Buat symlink storage
php artisan storage:link

# 8. Build assets
npm run build

# 9. Jalankan server lokal
php artisan serve
```

---

## Dependensi Utama

**Backend (Composer)**

| Package | Kegunaan |
|---|---|
| `laravel/framework ^12.0` | Framework utama |
| `intervention/image ^3.0` | Resize dan crop foto avatar, kandang, SG |
| `laravel/ui ^4.5` | Scaffolding autentikasi |
| `laravel/sanctum ^4.0` | API token authentication |

**Frontend (NPM)**

| Package | Kegunaan |
|---|---|
| `tailwindcss ^3.4` | Utility-first CSS framework |
| `vite ^5.0` | Asset bundler |
| `@tailwindcss/forms ^0.5` | Reset style untuk form elements |

**CDN (di-load via Blade)**

| Library | Kegunaan |
|---|---|
| Bootstrap Icons | Icon set |
| Tom Select v2 | Searchable dropdown (Inbreeding Calculator) |

---

## Kontribusi

Tertarik berkontribusi? Hubungi [mr.fightto@gmail.com](mailto:mr.fightto@gmail.com)

---

## Roadmap

- [ ] Forum Tanya Jawab
- [ ] Artikel / Blog
- [ ] Ekspor data silsilah (PDF)
- [ ] Ekspor hasil prediksi morph (PDF / gambar)
- [ ] Analitik breeding (riwayat perkawinan & hasil keturunan)
