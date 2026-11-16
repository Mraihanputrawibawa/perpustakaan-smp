# Outline Laporan — PerpusSMP (Sistem Informasi Perpustakaan SMP)

Outline ini menggantikan outline lama bertema HelpDeskKu. Disusun mengikuti
10 isi wajib di brief Tugas Project Cyber Security, dengan isi yang sudah
disesuaikan ke tema perpustakaan. Perkiraan total 12–15 halaman.
File akhir: `TugasProject_CyberSec_Raihan_[NIM].pdf` (dikumpulkan di EdLink).

## Halaman Depan

- Cover: Judul "PerpusSMP — Sistem Informasi Perpustakaan SMP Berbasis Web
  dengan Kontrol Akses Berbasis Peran", Nama: Raihan, NIM: [isi],
  Kelas: [isi], Mata Kuliah Cyber Security
- **Tabel Info Pengumpulan (taruh di halaman pertama, dosen pasti cari ini):**
  URL Aplikasi Aktif: [isi setelah deploy], URL Repository: [isi setelah
  push GitHub], Akun Uji Siswa: `siswa@perpus.test` / `password`,
  Akun Uji Petugas: `petugas@perpus.test` / `password`,
  Akun Uji Admin: `admin@perpus.test` / `password`
  (akun keempat untuk pengujian IDOR: `siswa2@perpus.test` / `password`)

## 1. Latar Belakang dan Tujuan Aplikasi

Pencatatan perpustakaan SMP yang masih manual (buku catatan): buku yang
dipinjam tidak terlacak statusnya, stok tidak diketahui pasti, keterlambatan
dan denda dihitung manual, dan riwayat peminjaman siswa lain bisa terlihat
oleh yang tidak berhak. PerpusSMP membuat peminjaman tercatat digital:
katalog bisa dicari siswa, peminjaman/pengembalian dicatat petugas, denda
dihitung otomatis, dan setiap siswa hanya melihat peminjamannya sendiri.
Tulis tujuan untuk siswa, petugas, dan admin/kepala perpustakaan, plus ruang
lingkup aplikasi.

## 2. Fitur Utama

- 2.1 Registrasi dan Login — registrasi (peran selalu `siswa`), login,
  logout, sesi berakhir otomatis
- 2.2 Katalog Buku — cari judul/pengarang/ISBN, filter kategori, info stok
  tersedia & lokasi rak; CRUD buku dan kategori (petugas/admin)
- 2.3 Peminjaman & Pengembalian — dicatat petugas, jatuh tempo 7 hari, stok
  otomatis berkurang saat dipinjam dan bertambah saat kembali, denda
  otomatis Rp500/hari keterlambatan; status Dipinjam / Terlambat / Kembali
- 2.4 Laporan dan Dashboard (admin) — rekap peminjaman per kategori, buku
  paling sering dipinjam, daftar keterlambatan & total denda, filter
  tanggal, ekspor CSV, halaman cetak/PDF
- 2.5 Fitur Lanjutan — log aktivitas (login, tambah/ubah/hapus buku,
  peminjaman, pengembalian), manajemen user & peran oleh admin

## 3. Arsitektur dan Teknologi

Stack: Laravel 12 (PHP 8.2, sesuai PHP di komputer Raihan), Blade, Eloquent
ORM, SQLite untuk lokal/demo (dapat diganti MySQL/PostgreSQL lewat `.env`
tanpa ubah kode). Diagram arsitektur (browser → aplikasi → database),
skema database 5 tabel (`users, categories, books, loans, activity_logs`
— detail kolom di `docs/DESIGN.md`), dan diagram peran/use case untuk
Siswa, Petugas, dan Admin. Data demo awal: 6 buku contoh (termasuk
Matematika Kelas 7 dan Laskar Pelangi) dan 4 akun demo.

## 4. Penerapan Keamanan

Buat dalam tabel: Aspek — Penerapan — Lokasi di Kode/Aplikasi — Alasan.
Isinya: hashing password bcrypt (cast `hashed` di model User), validasi
input anti SQL Injection & XSS (Eloquent/parameter binding, escaping Blade
`{{ }}`), manajemen sesi (regenerasi ID saat login, cookie HttpOnly &
SameSite), kontrol akses RBAC & anti-IDOR (middleware `role:` +
`BookPolicy`/`LoanPolicy` — kepemilikan dicek di setiap request), secret di
environment variable (`.env` tidak ter-commit), rate limiting login
(`throttle:5,1` — 5 percobaan/menit), CSRF protection, security headers
(middleware `SecurityHeaders`: CSP, X-Frame-Options,
X-Content-Type-Options, Referrer-Policy), dan peran tidak bisa dimanipulasi
lewat form registrasi (selalu `siswa`).

## 5. Potensi Pengembangan ke Skripsi

Rumusan masalah awal: rekomendasi buku masih manual — siswa sulit menemukan
buku yang sesuai minatnya dari katalog yang besar, dan petugas tidak punya
data pola baca siswa. Rencana pengembangan: sistem rekomendasi buku dari
riwayat peminjaman siswa, atau peminjaman memakai QR Code kartu anggota.
Data peminjaman dari tugas ini bisa jadi dataset awal skripsi.

## 6. Penggunaan AI Agent

Buat dalam tabel: Alat AI yang dipakai — Bagian yang Dibantu — Contoh
Prompt Utama — Cara Kamu Memverifikasi Hasilnya Secara Manual. Alat:
Muse (Meta). Bagian: rancangan arsitektur & skema database, kode awal
(model, controller, view), debugging, dokumentasi. Contoh prompt utama
(sesuai README): "Deskripsikan isi PDF tugas Cyber Security ini",
"Bantu brainstorm ide aplikasi yang bisa jadi skripsi",
"Ya, buat dengan Laravel", "Bisakah ganti jadi sistem perpustakaan SMP?".
Verifikasi: seluruh kode hasil AI ditinjau dan diuji manual, terutama
autentikasi, kontrol akses (policy), dan validasi input — dan kamu harus
siap menjelaskan kode saat demo/tanya jawab. Bagian ini wajib jujur,
sesuai ketentuan di brief.

## 7. Hasil Self-Testing Keamanan

Tabel utama laporanmu: No — Skenario Uji — Cara Pengujian — Hasil
(Lolos/Gagal) — Perbaikan (Sebelum/Sesudah). Isi dengan 8 skenario di
`docs/SELF_TESTING.md` (SQL Injection, XSS, IDOR, kontrol akses halaman
admin & kelola buku, brute force, HTTPS & security headers, secret &
dependency audit, manipulasi peran saat registrasi). Hasil yang sudah
teruji lokal 16 Nov 2026: login ketiga peran berhasil; siswa membuka
peminjaman siswa lain ditolak 403; siswa membuka `/admin/reports`,
`/books/create`, dan `/loans/create` semuanya 403; petugas berhasil
mencatat peminjaman baru dan memproses pengembalian; denda pinjaman
terlambat 5 hari terhitung tepat Rp2.500; payload SQL Injection gagal
login; judul buku berisi `<script>` tampil ter-escape; `composer audit`
bersih; login salah 5 kali dikembalikan ke halaman login dan percobaan
ke-6 diblokir 429 Too Many Requests (skenario 5, brute force); registrasi
dengan `role=admin` tetap tersimpan sebagai `siswa` dan akun itu ditolak
403 di halaman admin (skenario 8, manipulasi peran). **Yang masih harus
diuji sebelum laporan selesai:** hanya skenario 6 (grade
securityheaders.com/SSL Labs), yang menunggu deployment HTTPS. Lampirkan
bukti screenshot/output tools di sini.

## 8. Tangkapan Layar dan Bukti Deployment

Halaman registrasi/login, katalog buku, halaman peminjaman, halaman
laporan, bukti HTTPS aktif (ikon gembok & detail sertifikat), bukti
konfigurasi hosting/DNS, dan bukti hasil scan header/audit dari Bab 7.
URL aplikasi & repository diisi setelah deploy (lihat `docs/DEPLOY.md`).

## 9. Kendala dan Rencana Perbaikan

Kendala selama pengerjaan — contoh yang nyata di project ini: project
awal dibuat di Laravel 13 yang mensyaratkan PHP 8.3+, sementara PHP lokal
Raihan 8.2, sehingga dibuat ulang di Laravel 12 (paket `laravel/pint`
yang diam-diam minta PHP 8.3 dihapus); ganti tema dari HelpDeskKu ke
PerpusSMP di tengah jalan; deployment & HTTPS menunggu akun GitHub/Railway
Raihan. Tulis rencana perbaikan ke depannya.

## 10. Penutup

Kesimpulan singkat bahwa aplikasi memenuhi fitur dan keamanan wajib, serta
kesiapannya dikembangkan lebih lanjut (rekomendasi buku / QR Code).

Tips: Bab 4 dan 7 adalah yang paling menentukan nilai keamanan (20%), jadi
tulis paling detail dan jangan klaim aman tanpa bukti.
