# Wireframe Detail per Layar — PerpusSMP

Wireframe ini menggambarkan layar aplikasi **sebagaimana sudah dibangun**
(Laravel 12 + Blade), bukan rancangan baru. Cocok dilampirkan pada bab
desain antarmuka di laporan. Konvensi: `[ ... ]` = tombol/tombol aksi,
`< ... >` = kolom isian, `( ... )` = pilihan dropdown.

> **Pembaruan desain visual (16 Nov 2026):** tampilan diperbarui mengikuti
> referensi situs travel modern — kerangka halaman terang dengan sudut
> membulat, hero bergambar besar di dashboard, kartu buku bergaya
> "Top Destinations" di katalog, dan seksi langkah "Pinjam Semudah 1-2-3".
> Struktur, elemen, dan alur pada wireframe di bawah ini tidak berubah;
> yang berubah hanya gaya visualnya.
>
> **Pembaruan ketiga (16 Nov 2026, malam):** atas permintaan penulis,
> tampilan dikembalikan mengikuti referensi situs travel secara lebih
> setia — font Poppins, latar abu-biru `#bcced2`, panel putih membulat,
> aksen navy `#16324c`, hero bergambar dengan pencarian pill, kotak
> fitur abu-biru, statistik, kartu buku bergaya destinasi di dashboard
> & katalog, dan seksi langkah 1-2-3. Peran tetap dua (siswa & admin).
> Catatan pembaruan kedua di atas kini riwayat saja.

## 0. Kerangka Umum (Semua Layar Setelah Login)

```
+------------------------------------------------------------------+
| PerpusSMP | Dashboard | Katalog Buku | Peminjaman |              |
|           | [Catat Peminjaman]* [Tambah Buku]*                   |
|           | [Kategori]** [User]** [Laporan]**                     |
|                                      Nama User (peran) [Logout]  |
+------------------------------------------------------------------+
| [Pesan sukses: hijau] / [Daftar error validasi: merah]           |
|                                                                  |
|                        ISI HALAMAN                               |
|                  (lebar maks. 1000px, tengah)                    |
+------------------------------------------------------------------+
```
- `*` dan `**` hanya tampil untuk admin (admin merangkap petugas perpustakaan).
- Menu disembunyikan berdasarkan peran di tampilan, **dan** tetap dijaga
  di server (middleware `role:` + Policy) — menyembunyikan menu bukan
  satu-satunya pengaman.
- Setiap aksi berhasil menampilkan pesan sukses hijau; setiap kegagalan
  validasi menampilkan daftar error merah di area yang sama.

---

## 1. Login — `GET /login`

**Akses:** publik (tamu). **Tujuan:** autentikasi semua peran.

```
        +------------------------------------------+
        | Login PerpusSMP                          |
        | Sistem Informasi Perpustakaan SMP        |
        |                                          |
        | Email                                    |
        | < email                                > |
        | Password                                 |
        | < password (tersembunyi)               > |
        | [ ] Ingat saya                           |
        | [            Login (penuh)             ] |
        |                                          |
        | Belum punya akun? Daftar                 |
        | Akun demo: admin@ / siswa@perpus.test  |
        | — password: password                     |
        +------------------------------------------+
```
- Kartu terpusat, lebar maks. 420px.
- Perilaku: email lama (`old input`) dipertahankan saat gagal; password
  tidak pernah ditampilkan kembali.
- Keamanan: rate limit 5 percobaan/menit; ID sesi diregenerasi setelah
  login berhasil; pesan error generik "Email atau password salah"
  (tidak membocorkan mana yang salah).
- Catatan produksi: blok info akun demo **harus dihapus** setelah seeding
  demo tidak lagi dipakai.

## 2. Registrasi Siswa — `GET /register`

**Akses:** publik. **Tujuan:** pendaftaran akun siswa mandiri.

```
        +------------------------------------------+
        | Daftar Akun Siswa                        |
        | Nama          < nama lengkap           > |
        | Kelas         < contoh: 7A             > |
        | Email         < email                  > |
        | Password      < min. 8, huruf & angka  > |
        | Konfirmasi    < ulangi password        > |
        | [           Daftar (penuh)             ] |
        | Sudah punya akun? Login                  |
        +------------------------------------------+
```
- Validasi: email unik; password minimal 8 karakter dengan huruf **dan**
  angka, harus sama dengan konfirmasi; kelas opsional, maks. 10 karakter.
- Keamanan: peran **selalu `siswa`** dari sisi server — parameter
  `role` apa pun dari form diabaikan (anti manipulasi peran).

## 3. Dashboard — `GET /dashboard`

**Akses:** semua peran. **Tujuan:** ringkasan angka + aktivitas terakhir.

```
+------------------------------------------------------------------+
| Dashboard Perpustakaan                                           |
| +-------+ +-------+ +-------+ +-------+ +-------+ +-------+    |
| |   6   | |  71   | |   2   | |   1   | |   2   | | Rp1.000|   |
| | Judul | |Eksmplr| |Pinjam*| |Terlmbt| |Kembali| | Denda  |   |
| +-------+ +-------+ +-------+ +-------+ +-------+ +-------+    |
| Peminjaman Terbaru                                               |
| +--------------------------------------------------------------+ |
| | Buku (tautan) | Peminjam | Jatuh Tempo | [Status badge]    | |
| +--------------------------------------------------------------+ |
+------------------------------------------------------------------+
```
- 6 kartu statistik: Judul Buku, Total Eksemplar, Sedang Dipinjam,
  Terlambat, Sudah Kembali, Total Denda.
- Perbedaan per peran: **siswa** — angka peminjaman/terlambat/kembali/
  denda hanya miliknya, label kartu "Pinjaman Saya Aktif", tabel berisi
  peminjamannya sendiri. **Staff** — angka keseluruhan perpustakaan,
  tabel berisi peminjaman terbaru semua siswa.
- Status badge teks: `Dipinjam` / `Terlambat` / `Kembali` /
  `Kembali (Terlambat)`.
- Keadaan kosong: baris tabel "Belum ada peminjaman."

## 4. Katalog Buku — `GET /books`

**Akses:** semua peran. **Tujuan:** mencari & melihat ketersediaan buku.

```
+------------------------------------------------------------------+
| Katalog Buku                                                     |
| +--------------------------------------------------------------+ |
| | < Cari judul / pengarang / ISBN... > (Semua kategori v)    | |
| |                                        [ Cari ]            | |
| +--------------------------------------------------------------+ |
| | Judul* | Pengarang | Kategori | Rak | Stok | Tersedia |Aksi#| |
| | Laskar Pelangi | Andrea Hirata | Fiksi & Novel | B1 | 5 | 4 | |
| +--------------------------------------------------------------+ |
| [<< Prev]  1 2 3  [Next >>]        (paginasi 12 per halaman)     |
+------------------------------------------------------------------+
```
- `*` Judul adalah tautan ke Detail Buku. `#` Kolom Aksi (Edit) hanya
  untuk staff.
- **Tersedia** = Stok total − jumlah yang sedang dipinjam; dihitung
  server per baris, tidak disimpan di database.
- Pencarian mencakup judul, pengarang, dan ISBN (LIKE, terikat parameter
  — anti SQL Injection). Filter kategori berupa dropdown.
- Parameter pencarian & filter dipertahankan antar halaman paginasi.
- Keadaan kosong: "Tidak ada buku."

## 5. Detail Buku — `GET /books/{id}`

**Akses:** semua peran. **Tujuan:** informasi lengkap satu buku.

```
+------------------------------------------+
| Laskar Pelangi                           |
| Pengarang   : Andrea Hirata              |
| Penerbit    : Bentang Pustaka            |
| Tahun       : 2005                       |
| ISBN        : 9789793062792              |
| Kategori    : Fiksi & Novel              |
| Lokasi Rak  : B1                         |
| Stok Total  : 5                          |
| Sedang Dipinjam : 1                      |
| Tersedia    : 4                          |
| [ Edit ]*  [ Hapus Buku ]* (konfirmasi)  |
+------------------------------------------+
```
- `*` Tombol Edit & Hapus hanya untuk staff (dijaga BookPolicy).
- Hapus memakai dialog konfirmasi browser; server menolak hapus jika
  masih ada eksemplar yang sedang dipinjam.

## 6. Form Buku (Tambah & Edit) — `GET /books/create`, `GET /books/{id}/edit`

**Akses:** staff saja (siswa menerima 403). **Tujuan:** CRUD data buku.

```
+------------------------------------------+
| Tambah Buku / Edit Buku                  |
| Judul Buku   < wajib, maks 200         > |
| Pengarang    < wajib, maks 150         > |
| Penerbit     < opsional                > |
| Tahun Terbit < 1900 s.d. tahun depan   > |
| ISBN         < opsional, unik          > |
| Lokasi Rak   < contoh: A1              > |
| Kategori     ( dropdown kategori     v ) |
| Stok         < angka >= 0              > |
| [ Simpan Buku / Simpan Perubahan ]       |
+------------------------------------------+
```
- Form Tambah dan Edit memakai potongan form yang sama; Edit terisi
  nilai lama dan memakai method PUT.
- Validasi khusus Edit: stok baru tidak boleh lebih kecil dari jumlah
  yang sedang dipinjam (pesan error spesifik).

## 7. Daftar Peminjaman — `GET /loans`

**Akses:** semua peran. **Tujuan:** memantau peminjaman.

```
+------------------------------------------------------------------+
| Data Peminjaman (staff) / Peminjaman Saya (siswa)                |
| +--------------------------------------------------------------+ |
| | < Cari judul buku... > (Semua status v)      [ Filter ]    | |
| +--------------------------------------------------------------+ |
| | Buku* | Siswa# | Kelas# | Tgl Pinjam | Jatuh Tempo |       | |
| |       | [Status badge] | Denda (Rp)                        | |
| +--------------------------------------------------------------+ |
| [<< Prev]  1 2 3  [Next >>]        (paginasi 12 per halaman)     |
+------------------------------------------------------------------+
```
- `*` Judul buku adalah tautan ke Detail Peminjaman. `#` Kolom Siswa &
  Kelas hanya tampil untuk staff; siswa hanya melihat baris miliknya
  (penyaringan di query server, bukan sekadar tampilan).
- Filter status: `Dipinjam` (belum kembali, belum jatuh tempo),
  `Terlambat` (belum kembali, lewat jatuh tempo), `Kembali`.
- Kolom Denda menampilkan denda berjalan untuk yang belum kembali
  (hari terlambat × Rp500) dan denda final untuk yang sudah kembali.

## 8. Catat Peminjaman — `GET /loans/create`

**Akses:** staff saja (siswa menerima 403). **Tujuan:** mencatat peminjaman baru.

```
+------------------------------------------+
| Catat Peminjaman Baru                    |
| Siswa Peminjam                           |
| ( dropdown: Nama Siswa (Kelas)       v ) |
| Buku                                     |
| ( dropdown: Judul — tersedia N       v ) |
| Tanggal Pinjam                           |
| < tanggal (kosong = hari ini)          > |
| Jatuh Tempo                              |
| < tanggal (kosong = +7 hari)           > |
| [ Simpan Peminjaman ]                    |
+------------------------------------------+
```
- Dropdown buku menampilkan sisa tersedia per judul untuk mencegah
  pencatatan melebihi stok; server tetap memeriksa ulang stok saat
  menyimpan (stok habis → error "semua eksemplar dipinjam").
- Peminjam harus berperan `siswa`; tanggal jatuh tempo tidak boleh
  sebelum tanggal pinjam.
- Berhasil → diarahkan ke Detail Peminjaman yang baru dibuat.

## 9. Detail Peminjaman — `GET /loans/{id}`

**Akses:** pemilik (siswa) & staff — siswa lain menerima **403**
(anti-IDOR, LoanPolicy). **Tujuan:** rincian satu peminjaman + pengembalian.

```
+------------------------------------------+
| Detail Peminjaman #2                     |
| Buku          : Matematika Kelas 7       |
|               (Buku Pelajaran)           |
| Peminjam      : Siswa Demo — Kelas 8A    |
| Tanggal Pinjam: 04 Nov 2026              |
| Jatuh Tempo   : 11 Nov 2026              |
| Tanggal Kembali: -                       |
| Status        : [Terlambat]              |
| Keterlambatan : 5 hari                   |
| Denda         : Rp2.500 (Rp500/hari)     |
| [ Proses Pengembalian ]* (konfirmasi)    |
+------------------------------------------+
```
- `*` Tombol pengembalian hanya untuk staff dan hanya saat buku belum
  kembali; memakai dialog konfirmasi.
- Setelah pengembalian: Tanggal Kembali terisi, status berubah menjadi
  `Kembali` / `Kembali (Terlambat)`, denda dikunci sebagai denda final
  tersimpan, dan pesan sukses menampilkan nominal denda.
- Untuk siswa, layar ini bersifat hanya-lihat.

## 10. Kelola Kategori — `GET /admin/categories`

**Akses:** admin saja. **Tujuan:** CRUD kategori buku.

```
+------------------------------------------------------------------+
| Kelola Kategori                                                  |
| +--------------------------------------------------------------+ |
| | Nama Kategori Baru < wajib, unik                       >   | |
| | Deskripsi          < opsional                          >   | |
| | [ Tambah ]                                                 | |
| +--------------------------------------------------------------+ |
| | Nama | Deskripsi | Jumlah Buku | Aksi                      | |
| | Buku Pelajaran | Buku teks kelas 7-9 | 3 | [ Hapus ]       | |
| +--------------------------------------------------------------+ |
+------------------------------------------------------------------+
```
- Jumlah Buku dihitung dengan `withCount` (jumlah judul per kategori).
- Hapus memakai konfirmasi; server menolak jika kategori masih dipakai
  buku, dengan pesan error yang jelas.

## 11. Kelola User — `GET /admin/users`

**Akses:** admin saja. **Tujuan:** mengatur peran anggota.

```
+------------------------------------------------------------------+
| Kelola User                                                      |
| | Nama | Email | Peran (dropdown v) [Simpan] | Aksi          | |
| | Siswa Demo | siswa@perpus.test | siswa  | [ Hapus ]       | |
| | Siswa Kedua | siswa2@perpus.test | siswa | [ Hapus ]          | |
| +--------------------------------------------------------------+ |
| [<< Prev]  1 2 3  [Next >>]        (paginasi 15 per halaman)     |
+------------------------------------------------------------------+
```
- Peran diubah lewat dropdown sebaris (`siswa` / `admin`)
  lalu tombol Simpan pada baris yang sama.
- Pengaman: admin tidak dapat menurunkan perannya sendiri, dan tombol
  Hapus tidak tampil untuk akun sendiri; server memeriksa ulang kedua
  aturan ini.

## 12. Laporan — `GET /admin/reports`

**Akses:** admin saja. **Tujuan:** rekap untuk kepala perpustakaan &
bahan laporan tugas.

```
+------------------------------------------------------------------+
| Laporan Perpustakaan                                             |
| +--------------------------------------------------------------+ |
| | Dari (tgl pinjam) <tanggal> Sampai <tanggal> [ Filter ]    | |
| | [ Ekspor CSV ] [ Cetak / PDF ]                             | |
| +--------------------------------------------------------------+ |
| +--------+ +--------+ +--------+ +------------+                  |
| |   4    | |   2    | |   1    | |  Rp1.000   |                  |
| | Total  | |Dipinjam| |Terlmbt | |Total Denda |                  |
| +--------+ +--------+ +--------+ +------------+                  |
| Rekap per Kategori Buku                                          |
| | Fiksi & Novel | 2 kali dipinjam |                            |
| Buku Paling Sering Dipinjam                                      |
| | Laskar Pelangi | 1 kali |                                    |
| Detail Peminjaman                                                |
| | ID | Siswa | Buku | Tgl Pinjam | Status | Denda |             |
| [<< Prev]  1 2 3  [Next >>]        (paginasi 15 per halaman)     |
+------------------------------------------------------------------+
```
- Filter tanggal berdasarkan tanggal pinjam; tombol ekspor meneruskan
  filter yang sama, jadi CSV/PDF selalu sesuai tampilan.
- Ekspor CSV: kolom ID, Siswa, Kelas, Buku, Kategori, Tgl Pinjam,
  Jatuh Tempo, Tgl Kembali, Status, Denda.

## 13. Cetak Laporan (PDF) — `GET /admin/reports/print`

**Akses:** admin saja. **Tujuan:** dokumen PDF dari browser (Ctrl+P →
Save as PDF).

```
+------------------------------------------------------------------+
| Laporan Peminjaman Perpustakaan SMP            (tanpa header    |
| Dicetak: 16 Nov 2026 13:40 — Total: 4          aplikasi,        |
| +--------------------------------------------------------------+ |
| | ID | Siswa | Kelas | Buku | Pinjam | Tempo | Kembali |     | |
| |    | Status | Denda |                    (tabel bergaris)   | |
| +--------------------------------------------------------------+ |
+------------------------------------------------------------------+
```
- Halaman mandiri tanpa navigasi aplikasi, tabel bergaris penuh agar
  terbaca saat dicetak hitam-putih.
- Isi mengikuti filter tanggal dari layar Laporan.

---

## Ringkasan Akses per Layar

| Layar | Siswa | Admin |
|---|:---:|:---:|
| Login & Registrasi | ✓ (publik) | ✓ |
| Dashboard | ✓ (data sendiri) | ✓ (semua) |
| Katalog & Detail Buku | ✓ lihat | ✓ + kelola |
| Form Buku | 403 | ✓ |
| Daftar Peminjaman | ✓ (sendiri) | ✓ (semua) |
| Catat Peminjaman | 403 | ✓ |
| Detail Peminjaman | ✓ (sendiri) | ✓ |
| Proses Pengembalian | — | ✓ |
| Kelola Kategori | 403 | ✓ |
| Kelola User | 403 | ✓ |
| Laporan & Cetak/PDF | 403 | ✓ |

Catatan: hanya ada dua peran. Admin merangkap petugas perpustakaan,
sehingga seluruh tugas pengelolaan (buku, peminjaman, pengembalian)
dilakukan akun admin.
