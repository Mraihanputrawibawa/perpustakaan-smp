@extends('layouts.app')
@section('content')
@php($u = auth()->user())

<section class="hero">
  <div class="hero-in">
    <h1>Perpus<br>SMP</h1>
    <p>Jelajahi koleksi buku perpustakaan sekolahmu — dari buku pelajaran sampai novel favorit. Cari, pinjam, dan kembalikan tepat waktu, semua tercatat rapi.</p>
    <form class="hero-search" method="GET" action="{{ route('books.index') }}">
      <input type="text" name="search" placeholder="Cari judul, pengarang, atau ISBN…">
      <button type="submit">Cari Buku</button>
    </form>
  </div>
</section>

<div class="sec-head">
  <h2>Kenapa Meminjam di PerpusSMP?</h2>
  <p>Dari katalog pelajaran sampai bacaan santai, semua koleksi bisa dicari dan dipinjam dengan mudah.</p>
</div>
<div class="features">
  <div class="feature"><div class="ic">📚</div><div><b>Koleksi Terkurasi</b><p>Buku pelajaran kelas 7–9, fiksi, dan referensi dipilih untuk menunjang belajar siswa SMP.</p></div></div>
  <div class="feature"><div class="ic">🗂️</div><div><b>Peminjaman Tercatat Rapi</b><p>Setiap peminjaman dicatat petugas dengan jatuh tempo 7 hari dan stok yang terupdate otomatis.</p></div></div>
  <div class="feature"><div class="ic">⏰</div><div><b>Denda Transparan</b><p>Terlambat? Denda dihitung otomatis Rp500 per hari — tidak ada biaya siluman, semua terlihat jelas.</p></div></div>
</div>

<div class="sec-head">
  <h2>Sekilas Perpustakaan</h2>
  <p>{{ $u->isStaff() ? 'Angka keseluruhan perpustakaan saat ini.' : 'Koleksi perpustakaan dan ringkasan peminjamanmu.' }}</p>
</div>
<div class="stats">
  <div class="stat"><b>{{ $stats['total_buku'] }}</b><span>Judul Buku</span></div>
  <div class="stat"><b>{{ $stats['total_eksemplar'] }}</b><span>Total Eksemplar</span></div>
  <div class="stat"><b>{{ $stats['dipinjam'] }}</b><span>{{ $u->isStaff() ? 'Sedang Dipinjam' : 'Pinjaman Saya Aktif' }}</span></div>
  <div class="stat"><b>{{ $stats['terlambat'] }}</b><span>Terlambat</span></div>
  <div class="stat"><b>{{ $stats['kembali'] }}</b><span>Sudah Kembali</span></div>
  <div class="stat"><b>Rp{{ number_format($stats['total_denda'], 0, ',', '.') }}</b><span>Total Denda</span></div>
</div>

<div class="sec-head">
  <h2>Buku Paling Sering Dipinjam</h2>
  <p>Buku-buku favorit teman-temanmu di sekolah — siapa tahu jadi bacaanmu berikutnya.</p>
</div>
<div class="book-grid">
  @forelse($popularBooks as $i => $book)
    @php($tersedia = $book->stock - $book->active_loans_count)
    <div class="book-card">
      <a class="cover g{{ ($i % 5) + 1 }}" href="{{ route('books.show', $book) }}">
        <span class="cat">{{ $book->category->name }}</span>
        <span class="avail {{ $tersedia <= 0 ? 'empty' : '' }}">{{ $tersedia > 0 ? 'Tersedia '.$tersedia : 'Habis' }}</span>
        <h3>{{ $book->title }}</h3>
        <small>{{ $book->author }} · {{ $book->loans_count }}× dipinjam</small>
      </a>
      <div class="book-meta"><span class="loc">Rak {{ $book->shelf ?? '-' }}</span><span>{{ $book->year ?? '' }}</span></div>
    </div>
  @empty
    <p class="muted">Belum ada buku.</p>
  @endforelse
</div>

<div class="sec-head">
  <h2>Pinjam Buku Semudah 1-2-3</h2>
  <p>Tiga langkah sederhana dari rak buku sampai meja belajarmu.</p>
</div>
<div class="steps">
  <div class="step"><div class="num">1</div><b>Pilih Bukumu</b><p>Cari buku di katalog, cek kategori dan sisa stok yang tersedia.</p></div>
  <div class="step"><div class="num">2</div><b>Catat di Petugas</b><p>Datang ke perpustakaan, petugas mencatat peminjamanmu — jatuh tempo 7 hari.</p></div>
  <div class="step"><div class="num">3</div><b>Baca &amp; Kembalikan</b><p>Nikmati bukunya, lalu kembalikan tepat waktu agar bebas denda.</p></div>
</div>

<div class="sec-head"><h2>Peminjaman Terbaru</h2></div>
<div class="card" style="padding:6px 18px">
<table>
  <tr><th>Buku</th><th>Peminjam</th><th>Jatuh Tempo</th><th>Status</th></tr>
  @forelse($latest as $loan)
  <tr>
    <td><a href="{{ route('loans.show', $loan) }}">{{ $loan->book->title }}</a></td>
    <td>{{ $loan->user->name }}</td>
    <td>{{ $loan->due_date->format('d M Y') }}</td>
    <td><span class="badge">{{ $loan->statusLabel() }}</span></td>
  </tr>
  @empty
  <tr><td colspan="4" class="muted">Belum ada peminjaman.</td></tr>
  @endforelse
</table>
</div>
@endsection
