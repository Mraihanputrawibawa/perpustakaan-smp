# Desain PerpusSMP — Sistem Informasi Perpustakaan SMP

## Peran
- siswa: lihat katalog buku, lihat peminjaman miliknya sendiri
- admin: merangkap petugas perpustakaan — kelola buku & kategori, catat peminjaman & pengembalian, kelola user/peran, laporan

## Tabel
- users: id, name, email unique, password (bcrypt), role (siswa|admin), kelas, timestamps
- categories: id, name, description, timestamps
- books: id, category_id FK, title, author, publisher, year, isbn unique nullable,
  shelf, stock (total eksemplar), timestamps
- loans: id, user_id FK (siswa), book_id FK, borrowed_at, due_date, returned_at nullable,
  fine_amount, timestamps
- activity_logs: id, user_id nullable, action, description, timestamps

## Aturan Bisnis
- Lama pinjam standar 7 hari, denda Rp500/hari keterlambatan (dihitung otomatis saat pengembalian)
- Stok tersedia = stock - peminjaman aktif; peminjaman ditolak jika stok habis
- Status tampil: Dipinjam / Terlambat / Kembali / Kembali (Terlambat) — diturunkan dari tanggal

## Route Utama
- Auth: /register, /login (throttle:5,1), /logout
- GET /dashboard, resource /books (katalog & CRUD buku)
- /loans: daftar, catat (staff), detail, POST /loans/{loan}/return (staff)
- Admin: /admin/categories, /admin/users, /admin/reports (+ export.csv, print/PDF)

## Keamanan
- Hash bcrypt (cast 'hashed'), validasi semua input, Eloquent (anti SQL Injection)
- Blade escaping (anti XSS), CSRF bawaan Laravel
- BookPolicy & LoanPolicy: anti-IDOR (siswa hanya peminjamannya sendiri), kelola hanya staff
- Middleware role: untuk area admin, SecurityHeaders untuk semua respons
- Cookie sesi HttpOnly & SameSite, regenerasi sesi saat login, .env tidak ter-commit
