<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Akun demo untuk pengujian dosen (HANYA untuk lokal/demo —
     * ganti password sebelum dipakai di lingkungan nyata):
     * - admin@perpus.test / password
     * - petugas@perpus.test / password
     * - siswa@perpus.test / password
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin Perpustakaan',
            'email' => 'admin@perpus.test',
            'password' => 'password',
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Petugas Perpustakaan',
            'email' => 'petugas@perpus.test',
            'password' => 'password',
            'role' => 'petugas',
        ]);

        $siswa = User::create([
            'name' => 'Siswa Demo',
            'email' => 'siswa@perpus.test',
            'password' => 'password',
            'role' => 'siswa',
            'kelas' => '8A',
        ]);

        $siswa2 = User::create([
            'name' => 'Siswa Kedua',
            'email' => 'siswa2@perpus.test',
            'password' => 'password',
            'role' => 'siswa',
            'kelas' => '7B',
        ]);

        $pelajaran = Category::create(['name' => 'Buku Pelajaran', 'description' => 'Buku teks kelas 7-9']);
        $fiksi = Category::create(['name' => 'Fiksi & Novel', 'description' => 'Novel dan cerita']);
        $ipa = Category::create(['name' => 'IPA', 'description' => 'Ilmu Pengetahuan Alam']);
        $ips = Category::create(['name' => 'IPS', 'description' => 'Ilmu Pengetahuan Sosial']);
        Category::create(['name' => 'Bahasa', 'description' => 'Bahasa Indonesia & Inggris']);
        Category::create(['name' => 'Agama', 'description' => 'Pendidikan agama']);

        $mtk = Book::create(['category_id' => $pelajaran->id, 'title' => 'Matematika Kelas 7', 'author' => 'Tim Kemendikbud', 'publisher' => 'Kemendikbud', 'year' => 2022, 'isbn' => '9786024277811', 'shelf' => 'A1', 'stock' => 20]);
        $ipaBook = Book::create(['category_id' => $ipa->id, 'title' => 'IPA Terpadu Kelas 8', 'author' => 'Tim Kemendikbud', 'publisher' => 'Kemendikbud', 'year' => 2022, 'isbn' => '9786024277828', 'shelf' => 'A2', 'stock' => 15]);
        $ipsBook = Book::create(['category_id' => $ips->id, 'title' => 'IPS Kelas 9', 'author' => 'Tim Kemendikbud', 'publisher' => 'Kemendikbud', 'year' => 2021, 'isbn' => '9786024277835', 'shelf' => 'A3', 'stock' => 12]);
        $laskar = Book::create(['category_id' => $fiksi->id, 'title' => 'Laskar Pelangi', 'author' => 'Andrea Hirata', 'publisher' => 'Bentang Pustaka', 'year' => 2005, 'isbn' => '9789793062792', 'shelf' => 'B1', 'stock' => 5]);
        Book::create(['category_id' => $fiksi->id, 'title' => 'Negeri 5 Menara', 'author' => 'Ahmad Fuadi', 'publisher' => 'Gramedia', 'year' => 2009, 'isbn' => '9789792248616', 'shelf' => 'B1', 'stock' => 4]);
        Book::create(['category_id' => $pelajaran->id, 'title' => 'Bahasa Indonesia Kelas 8', 'author' => 'Tim Kemendikbud', 'publisher' => 'Kemendikbud', 'year' => 2022, 'isbn' => '9786024277842', 'shelf' => 'A1', 'stock' => 18]);

        // Peminjaman aktif (belum jatuh tempo)
        Loan::create([
            'user_id' => $siswa->id, 'book_id' => $laskar->id,
            'borrowed_at' => today()->subDays(2), 'due_date' => today()->addDays(5),
        ]);
        // Peminjaman terlambat (sudah lewat jatuh tempo, belum kembali)
        Loan::create([
            'user_id' => $siswa->id, 'book_id' => $mtk->id,
            'borrowed_at' => today()->subDays(12), 'due_date' => today()->subDays(5),
        ]);
        // Peminjaman selesai tepat waktu oleh siswa lain
        Loan::create([
            'user_id' => $siswa2->id, 'book_id' => $ipaBook->id,
            'borrowed_at' => today()->subDays(10), 'due_date' => today()->subDays(3),
            'returned_at' => today()->subDays(4), 'fine_amount' => 0,
        ]);
        // Peminjaman selesai terlambat 2 hari -> denda 2 x Rp500
        Loan::create([
            'user_id' => $siswa2->id, 'book_id' => $ipsBook->id,
            'borrowed_at' => today()->subDays(14), 'due_date' => today()->subDays(7),
            'returned_at' => today()->subDays(5), 'fine_amount' => 1000,
        ]);
    }
}
