# Self-Testing Keamanan — PerpusSMP

Dokumen ini disiapkan untuk Bab Hasil Self-Testing pada laporan. Isi kolom
Hasil Aktual dan Perbaikan setelah pengujian benar-benar dijalankan, dan
lampirkan tangkapan layar / keluaran tools sebagai bukti.

| No | Skenario Uji | Cara Pengujian | Hasil yang Diharapkan | Hasil Aktual | Perbaikan |
|----|--------------|----------------|----------------------|--------------|-----------|
| 1 | SQL Injection pada form login & pencarian katalog | Masukkan `' OR '1'='1` pada email login dan kolom pencarian buku | Login gagal, tidak ada data bocor, tidak ada error SQL | Lolos — diuji lokal 16 Nov 2026: login dengan payload SQLi gagal, diarahkan kembali ke halaman login | Query menggunakan Eloquent/parameter binding |
| 2 | XSS pada judul buku & nama kategori | Tambah buku berjudul `<script>alert(1)</script>` | Script tampil sebagai teks (ter-escape oleh Blade `{{ }}`), tidak dieksekusi | Lolos — diuji lokal 16 Nov 2026: buku berjudul script tampil ter-escape (&lt;script&gt;) di katalog | Escaping output + validasi input |
| 3 | IDOR — akses peminjaman milik siswa lain | Login sebagai Siswa B, buka URL detail peminjaman milik Siswa A dengan mengganti ID | Ditolak 403 | Lolos — diuji lokal 16 Nov 2026: siswa membuka peminjaman siswa lain menerima 403 | LoanPolicy: kepemilikan dicek di setiap request |
| 4 | Kontrol akses halaman admin & kelola buku | Login sebagai siswa, buka `/admin/users` dan halaman tambah buku | Ditolak 403 | Lolos — diuji lokal 16 Nov 2026: siswa membuka /admin/reports, /books/create, dan /loans/create semuanya 403 | Middleware `role:admin` + BookPolicy (hanya staff) |
| 5 | Brute force login | Lakukan login salah >5 kali dalam 1 menit | Kena rate limit (429 / pesan throttle) | Belum diuji manual — mekanisme throttle:5,1 terpasang pada route login | `throttle:5,1` pada route login |
| 6 | HTTPS & security headers | Cek URL publik di securityheaders.com dan SSL Labs | Grade baik, header CSP/X-Frame-Options/X-Content-Type-Options/Referrer-Policy terpasang | Sebagian — security headers terverifikasi lokal lewat curl (CSP, X-Frame-Options, X-Content-Type-Options); grade securityheaders.com menunggu deployment HTTPS | Middleware SecurityHeaders |
| 7 | Secret & dependency audit | Cek riwayat Git tidak memuat `.env`; jalankan `composer audit` | Tidak ada secret ter-commit, tidak ada kerentanan kritis | Lolos — composer audit: "No security vulnerability advisories found"; .env tercantum di .gitignore | `.env` di `.gitignore`, gunakan `.env.example` tanpa nilai rahasia |
| 8 | Manipulasi peran saat registrasi | Tambahkan parameter `role=admin` pada form registrasi | Peran tetap `siswa` (input peran publik diabaikan) | Belum diuji manual — kode registrasi tidak membaca input role (selalu siswa), uji saat pengerjaan laporan | Kolom role tidak diambil dari request registrasi |

## Alat yang digunakan
- Browser / curl untuk pengujian manual
- OWASP ZAP atau Burp Suite Community (opsional)
- securityheaders.com / SSL Labs untuk header & TLS
- `composer audit` untuk kerentanan dependency
