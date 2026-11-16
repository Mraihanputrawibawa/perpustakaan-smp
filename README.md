# PerpusSMP

Sistem Informasi Perpustakaan SMP berbasis web dengan kontrol akses berbasis
peran (RBAC). Dibuat untuk Tugas Project mata kuliah **Cyber Security**:
pengembangan & pengamanan aplikasi web.

Pengelolaan perpustakaan sekolah yang sebelumnya manual (buku catatan) kini
tercatat secara digital: katalog buku dapat dicari siswa, peminjaman dan
pengembalian dicatat petugas, keterlambatan dan denda dihitung otomatis, dan
setiap siswa hanya dapat mengakses data peminjamannya sendiri.

## Fitur

- Registrasi & login (password ter-hash bcrypt, password lemah ditolak)
- Katalog buku: pencarian judul/pengarang/ISBN, filter kategori, info stok tersedia & lokasi rak
- Pengelolaan data (CRUD): buku, kategori buku, user/anggota (oleh admin)
- Peminjaman & pengembalian oleh petugas: jatuh tempo 7 hari, stok otomatis
  berkurang/bertambah, denda keterlambatan otomatis Rp500/hari
- Laporan (admin): rekap peminjaman per kategori, buku paling sering dipinjam,
  jumlah terlambat, total denda, filter tanggal, ekspor CSV, halaman cetak/PDF
- Log aktivitas (login, logout, tambah/ubah/hapus buku, peminjaman, pengembalian)

## Peran Pengguna

| Peran | Hak akses |
|---|---|
| `siswa` | Lihat katalog, lihat riwayat peminjaman miliknya sendiri |
| `petugas` | Kelola buku, catat peminjaman & pengembalian, lihat semua peminjaman |
| `admin` | Semua hak petugas + kelola kategori, kelola user & peran, laporan |

## Teknologi

- Laravel 12 (PHP 8.2), Blade, Eloquent ORM
- Database: SQLite untuk lokal/demo, dapat diganti MySQL/PostgreSQL lewat `.env`
- Tanpa build frontend tambahan: tampilan memakai Blade + CSS sederhana

## Instalasi & Menjalankan Secara Lokal

Prasyarat: PHP 8.2+ dan Composer.

```bash
git clone <url-repository-ini>
cd perpustakaan-smp
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Buka http://127.0.0.1:8000

### Akun uji coba (hasil seeding, hanya untuk lokal/demo)

| Peran | Email | Password |
|---|---|---|
| Admin | admin@perpus.test | password |
| Petugas | petugas@perpus.test | password |
| Siswa | siswa@perpus.test | password |
| Siswa 2 | siswa2@perpus.test | password |

> Ganti password akun-akun ini sebelum aplikasi dipakai di lingkungan nyata.

## Konfigurasi Environment Variable

Salin `.env.example` menjadi `.env`. Nilai rahasia (APP_KEY, password database,
API key) hanya disimpan di `.env` dan **tidak pernah** ditulis di source code
atau repository (`.env` tercantum di `.gitignore`).

Untuk production: `APP_ENV=production`, `APP_DEBUG=false`,
`SESSION_SECURE_COOKIE=true`, dan gunakan HTTPS.

## Penerapan Keamanan

| Aspek | Penerapan |
|---|---|
| Hashing password | Cast `hashed` (bcrypt) pada model User |
| Anti SQL Injection | Eloquent ORM / parameter binding, tanpa query mentah dari input user |
| Anti XSS | Escaping output Blade `{{ }}` + validasi semua input |
| Manajemen sesi | Regenerasi ID sesi saat login, invalidasi saat logout, cookie HttpOnly & SameSite |
| Kontrol akses | Middleware `role:` + `BookPolicy`/`LoanPolicy` (anti-IDOR: kepemilikan dicek di setiap request) |
| Rate limiting | Login dibatasi 5 percobaan/menit (`throttle:5,1`) |
| CSRF | Token CSRF bawaan Laravel pada semua form POST |
| Security headers | Middleware `SecurityHeaders` (CSP, X-Frame-Options, X-Content-Type-Options, Referrer-Policy, HSTS di HTTPS) |
| Manipulasi peran | Peran tidak pernah diambil dari form registrasi publik (selalu `siswa`) |
| Secret | Environment variable, `.env` tidak ter-commit |
| Log aktivitas | Tabel `activity_logs` |

Detail desain: lihat [docs/DESIGN.md](docs/DESIGN.md).

## Penggunaan AI Agent

Aplikasi ini dikembangkan dengan bantuan AI agent **Muse (Meta)** untuk:
merancang arsitektur & skema database, menulis kode awal (model, controller,
view), debugging, dan menyusun dokumentasi. Aplikasi ini merupakan
pengembangan tema dari rancangan awal penulis dengan domain yang diganti
menjadi perpustakaan SMP.

Contoh prompt utama:
- "Deskripsikan isi PDF tugas Cyber Security ini"
- "Bantu brainstorm ide aplikasi yang bisa jadi skripsi"
- "Ya, buat dengan Laravel"
- "Bisakah ganti jadi sistem perpustakaan SMP?"
- "Buat desain webnya seperti ini" (mengirim contoh desain situs travel modern)

Desain antarmuka diperbarui mengikuti referensi tersebut: hero bergambar
besar, kartu buku bergaya kartu destinasi, dan seksi langkah peminjaman
1-2-3. Gambar hero (`public/images/hero-perpus.jpg`) dibuat dengan
generator gambar AI.

Seluruh kode hasil AI ditinjau dan diuji manual oleh mahasiswa, terutama
bagian autentikasi, kontrol akses (policy), dan validasi input. Mahasiswa
bertanggung jawab penuh atas kode yang dikumpulkan dan siap menjelaskannya
saat demo/tanya jawab.

## Self-Testing Keamanan

Pengujian keamanan dilakukan secara manual (browser/curl) mencakup SQL
Injection, XSS, IDOR, kontrol akses peran, brute force login, manipulasi
peran saat registrasi, HTTPS & security headers, serta audit secret/dependency
(`composer audit`). Tabel skenario, hasil, dan perbaikan: lihat
[docs/SELF_TESTING.md](docs/SELF_TESTING.md).

## Deployment

Aplikasi dapat di-deploy ke layanan seperti Railway, Render, atau VPS dengan
domain publik (FQDN) dan HTTPS aktif. Pastikan `APP_URL` sesuai domain,
jalankan `php artisan migrate --seed --force` dan
`php artisan config:cache` di server.

URL aplikasi aktif: _[isi setelah deployment]_

## Potensi Pengembangan (Skripsi)

Sistem rekomendasi buku berdasarkan riwayat peminjaman dan minat baca siswa,
peminjaman dengan QR Code pada kartu anggota & label buku, serta notifikasi
pengingat jatuh tempo. Data peminjaman dari aplikasi ini menjadi dataset awal.
