# Deploy PerpusSMP — GitHub + Railway (HTTPS publik)

Panduan ini disiapkan supaya langkah berikutnya — push ke GitHub lalu deploy
publik ber-HTTPS — bisa langsung dijalankan tanpa menebak konfigurasi.
Nilai rubrik yang dikejar di sini: **Deployment & domain 20%**, plus bukti
HTTPS untuk Bab 7 laporan (skenario 6 di `docs/SELF_TESTING.md`).

Catatan: panduan ini sebelumnya masih tertulis untuk tema lama (HelpDeskKu)
dan sudah diperbaiki ke PerpusSMP pada 16 Nov 2026 — nama aplikasi, nama
repo, akun demo, dan langkah verifikasi di bawah ini semuanya untuk
PerpusSMP.

## 0. Yang sudah dicek sebelum deploy

- Zip project bersih: tidak berisi `.env`, `vendor/`, maupun
  `database/database.sqlite` (semuanya sudah di-`.gitignore`).
- Tidak perlu `npm run build`: satu-satunya view yang memakai `@vite` adalah
  `resources/views/welcome.blade.php`, dan view itu tidak pernah dirender —
  route `/` langsung redirect ke `/dashboard` atau `/login`
  (`routes/web.php`). Jadi `public/build` yang tidak ada bukan masalah.
- Tidak ada `Procfile`/`railway.json` — Railway (builder Railpack, default
  saat ini) mendeteksi Laravel dan menjalankan `php artisan migrate --force`
  secara otomatis. Jangan tambahkan perintah migrate sendiri di build.

## 1. Push ke GitHub (sekali, dari komputer sendiri)

```bash
cd perpustakaan-smp
git init
git add .
git status        # pastikan .env TIDAK muncul; kalau muncul, jangan lanjut
git commit -m "PerpusSMP: Laravel library system with RBAC (Cyber Security project)"
git branch -M main
git remote add origin https://github.com/<username>/perpustakaan-smp.git
git push -u origin main
```

## 2. Buat project di Railway

1. Railway → New Project → **Deploy from GitHub repo** → pilih
   `perpustakaan-smp`.
2. Di project yang sama: New → **Database → PostgreSQL**.
3. Service aplikasi → Settings → Networking → **Generate Domain**.
   Catat domainnya, misal `https://perpustakaan-smp-production.up.railway.app` —
   ini `APP_URL` di langkah 3.

## 3. Environment variables (Railway → service aplikasi → Variables)

Buat `APP_KEY` dulu di lokal (jangan pakai key dari `.env` lokal):

```bash
php artisan key:generate --show
```

| Variable | Nilai |
|---|---|
| `APP_NAME` | `PerpusSMP` |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_KEY` | hasil `key:generate --show` di atas |
| `APP_URL` | domain Railway dari langkah 2 (isi **setelah** domain dibuat) |
| `DB_CONNECTION` | `pgsql` |
| `DB_URL` | `${{Postgres.DATABASE_URL}}` (referensi ke service Postgres) |
| `SESSION_DRIVER` | `database` |
| `CACHE_STORE` | `database` |
| `SESSION_SECURE_COOKIE` | `true` |

Catatan:

- `DB_URL` memakai alamat privat Postgres di dalam Railway — jangan
  jalankan migrate saat build; Railpack sudah menjalankan migrate otomatis
  saat deploy.
- Jangan pernah memasukkan nilai `.env` lokal atau password asli ke
  GitHub, prompt AI, atau laporan.

## 4. Seed SEKALI saja (penting!)

`DatabaseSeeder` memakai `User::create(...)` (bukan `firstOrCreate`), jadi
menjalankan seed dua kali akan **gagal karena email demo sudah ada**
(buku, kategori, dan peminjaman demo juga akan terduplikasi).
Seed hanya sekali, setelah deploy pertama berhasil, lewat Railway CLI
atau terminal service:

```bash
railway run php artisan db:seed --force
```

Akun demo hasil seed (untuk dosen, seperti di README):

- Admin: `admin@perpus.test` / `password`
- Petugas: `petugas@perpus.test` / `password`
- Siswa: `siswa@perpus.test` / `password`
- Siswa 2 (untuk uji IDOR): `siswa2@perpus.test` / `password`

## 5. Verifikasi setelah deploy

- [ ] Buka domain Railway: halaman login tampil, HTTPS aktif (ikon gembok).
- [ ] Login sebagai ketiga peran; siswa biasa buka `/admin/reports` → 403.
- [ ] Sebagai petugas, catat 1 peminjaman baru dari katalog, lalu proses
      pengembaliannya; cek stok buku berkurang lalu bertambah lagi, dan
      denda Rp500/hari muncul untuk pengembalian terlambat.
- [ ] Cek laporan admin dan ekspor CSV (`/admin/reports`).
- [ ] Cek https://securityheaders.com dan SSL Labs untuk domain tersebut —
      hasilnya melengkapi skenario 6 di `docs/SELF_TESTING.md`
      (sebelum deploy, yang terverifikasi baru header via curl lokal).
- [ ] Aplikasi harus **tetap aktif sampai nilai diumumkan** (ketentuan brief).

## 6. Yang dibutuhkan untuk laporan

Setelah deploy, isi ke laporan (`TugasProject_CyberSec_NamaLengkap_NIM.pdf`,
outline di `docs/LAPORAN_OUTLINE.md`):

- URL aplikasi aktif (domain Railway) dan URL repository GitHub.
- Screenshot katalog, peminjaman, laporan, bukti HTTPS, dan hasil
  securityheaders.com.
- NIM dan kelas — belum ada di project ini, isi manual di laporan.
