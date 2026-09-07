<p align="center">
  <img src="public/kan_logo_accreditation.png" width="180" alt="KAN Accreditation Logo">
</p>

<h1 align="center">ESLab LIMS - Environmental Testing Laboratory Module</h1>

<p align="center">
  <strong>Laboratory Information Management System (LIMS) terpadu untuk laboratorium pengujian lingkungan berstandar ISO/IEC 17025 (LP-1813-IDN).</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.2%2B-blue?logo=php" alt="PHP Version">
  <img src="https://img.shields.io/badge/Framework-Laravel%2011-red?logo=laravel" alt="Laravel 11">
  <img src="https://img.shields.io/badge/PWA-Ready-success?logo=pwa" alt="PWA Ready">
  <img src="https://img.shields.io/badge/Accreditation-KAN%20LP--1813--IDN-darkgreen" alt="KAN LP-1813-IDN">
  <img src="https://img.shields.io/badge/License-Proprietary%20%2F%20Open-orange" alt="License">
</p>

---

## 🔬 Ikhtisar Sistem

**ESLab LIMS (Pengujian Lingkungan)** adalah sistem otomasi operasional laboratorium terpadu yang mencakup seluruh alur kerja pengujian analitik lingkungan: mulai dari permintaan & kuotasi klien, perencanaan sampling lapangan, penerimaan & pelacakan sampel (Chain of Custody), verifikasi analisa laboratorium, hingga penerbitan sertifikat digital (*Certificate of Analysis / CoA*) ber-QR Code dengan verifikasi publik secara *real-time*.

Aplikasi dirancang dengan arsitektur **Offline-First / PWA**, memungkinkan petugas sampling lapangan tetap dapat beroperasi dan menginput data di lokasi terpencil sebelum melakukan sinkronisasi dengan server utama.

---

## ✨ Fitur-Fitur Utama

### 1. 📋 Permintaan & Kuotasi (*Quotation & Order Intake*)
- Registrasi penawaran harga dan parameter uji baku mutu lingkungan (Air Limbah, Udara Ambien, Emisi Cerobong, dsb.).
- Unggah dokumen Purchase Order (PO) dan tracking status verifikasi keuangan secara terintegrasi.
- Cetak otomatis format resmi Penawaran Harga (*Quotation Letter*) ber-kop standar.

### 2. 🗓️ Penerbitan Jadwal & Surat Tugas Sampling
- Penjadwalan tanggal sampling dan penugasan teknisi/personel lapangan (*Field Officers*).
- Cetak massal (*batch print*) Surat Tugas Sampling dan Berita Acara Lapangan.

### 3. 📦 Rantai Pengawasan & Sampel (*Chain of Custody - CoC*)
- Registrasi CoC berstandar KAN dengan nomor registrasi unik.
- Pelacakan parameter in-situ (suhu, pH, kelembapan, koordinat GPS titik sampling).
- Duplikasi cepat data CoC untuk sampling berkala (*re-sampling*).

### 4. 🧪 Penerimaan Sampel di Laboratorium (*Sample Intake*)
- Check-in penerimaan fisik sampel di laboratorium (pemeriksaan wadah, segel, dan pengawet).
- Input *batch receive* dengan log penerima dan verifikasi suhu box penyimpanan.

### 5. 🔬 Analisa Parameter Laboratorium
- Input lembar kerja analisa parameter fisika dan kimia.
- Validasi rentang baku mutu regulasi (PerMenLHK / PP RI) secara otomatis.
- Multi-tier approval: Analis Lab ➡️ Penyelia / Manajer Teknis.

### 6. 📜 Penerbitan Certificate of Analysis (CoA) & Verifikasi QR
- Penerbitan CoA digital lengkap dengan tabel parameter uji, metode referensi (SNI, USEPA, APHA), dan kesimpulan baku mutu.
- QR Code dinamis untuk validasi keaslian dokumen oleh regulator/klien secara publik tanpa harus login.
- Fitur *batch print* faktur, kwitansi, Berita Acara Serah Terima (BAST), dan Tanda Setuju Terima (TST).

### 7. 🌐 Portal Mandiri Klien (*Client Self-Service Portal*)
- Klien dapat melacak status pengujian secara mandiri secara transparan (Sampling ➡️ Analisa ➡️ Terbit CoA).
- Unduh CoA digital resmi dan dokumen penagihan.
- Fitur pesan/komunikasi langsung antara klien dan tim Technical Support (TS).

### 8. 📱 PWA & Mobile-Ready
- Desain antarmuka responsif untuk perangkat bergerak (Android APK & PWA).
- Menggunakan aset mandiri (*self-hosted assets* tanpa ketergantungan CDN eksternal) untuk performa instan dan keamanan tinggi.

---

## 🏗️ Arsitektur Teknologi

- **Backend**: PHP >= 8.2 & [Laravel 11](https://laravel.com/)
- **Database**: MySQL 8.0+ / MariaDB 10.4+
- **Frontend**: Blade Templating, Vanilla CSS berdesain modern, JavaScript ES6+
- **Offline / Mobile**: Service Worker (PWA), HTML5 Web Storage, QR Code Scanner
- **Charts & Mapping**: Chart.js / ApexCharts (local vendor), Leaflet GIS (local vendor)

---

## 🚀 Panduan Instalasi Lokal

### Prasyarat
- PHP 8.2 atau lebih baru dengan ekstensi: `pdo_mysql`, `curl`, `mbstring`, `openssl`, `zip`, `gd`
- Web server (Apache / Nginx via XAMPP)
- Composer
- MySQL / MariaDB

### Langkah Instalasi

1. **Clone atau Unduh Repository**
   ```bash
   git clone https://github.com/<username>/eslab-lims-pengujian.git
   cd eslab-lims-pengujian
   ```

2. **Instal Dependensi PHP**
   ```bash
   composer install
   ```

3. **Konfigurasi Environment**
   Salin file `.env.example` menjadi `.env`:
   ```bash
   copy .env.example .env
   php artisan key:generate
   ```

4. **Konfigurasi Database**
   Buka file `.env` dan sesuaikan nama database:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=lims_pengujian
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Jalankan Migrasi & Seeder Awal**
   ```bash
   php artisan migrate --seed
   ```

6. **Tautkan Storage Public**
   ```bash
   php artisan storage:link
   ```

7. **Akses Aplikasi**
   - Jalankan via built-in server:
     ```bash
     php artisan serve
     ```
     Buka di browser: `http://localhost:8000`
   - Atau via XAMPP Apache:
     Buka di browser: `http://localhost/lims_pengujian/public`

---

## 🔒 Kebijakan Keamanan & Privasi

- Seluruh kredensial produksi, IP server, dan file sesi **tidak disertakan** di dalam repository ini.
- Gunakan file `.env` lokal untuk mengkonfigurasi kredensial dan URL endpoint Anda sendiri.

---

## 📄 Lisensi

Hak Cipta © 2026. Hak cipta dilindungi undang-undang.
