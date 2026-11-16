@extends('layouts.app')
@section('content')
@php($u = auth()->user())
@php($imgs = ['images/kartu-belajar.jpg', 'images/kartu-novel.jpg', 'images/kartu-rak.jpg', 'images/hero-perpus.jpg'])

{{-- Hero mengikuti referensi --}}
<section class="hero">
  <img class="bg" src="{{ asset('images/hero-perpus.jpg') }}" alt="Perpustakaan sekolah">
  <div class="hero-in">
    <h1>PERPUS<i>SMP</i></h1>
    <p>Temukan buku pelajaran, novel, dan bacaan seru di perpustakaan sekolahmu. Cari, pinjam, dan kembalikan tepat waktu — semua tercatat rapi di sini.</p>
    <form class="hero-search" method="GET" action="{{ route('books.index') }}">
      <input type="text" name="search" placeholder="Cari judul, pengarang, atau ISBN…">
      <button type="submit">Cari Buku</button>
    </form>
    <div class="hero-cta">
      <a class="btn btn-light" href="{{ route('books.index') }}">Lihat Katalog</a>
      <a class="btn btn-outline" href="{{ route('loans.index') }}">{{ $u->isAdmin() ? 'Kelola Peminjaman' : 'Peminjaman Saya' }}</a>
    </div>
  </div>
</section>

{{-- Seksi "Kenapa memilih" mengikuti referensi --}}
<section class="why">
  <div>
    <h2>Kenapa Siswa Memilih PerpusSMP untuk Membaca?</h2>
    <p>Dari buku pelajaran sampai novel best-seller, semua koleksi perpustakaan bisa dicari dari kelas atau rumah. Peminjaman dicatat petugas, jatuh tempo jelas, dan tidak ada lagi kartu anggota yang hilang.</p>
    <div class="mini-stats">
      <div class="ms"><b>{{ $stats['total_buku'] }}</b><span>Judul buku tersedia</span></div>
      <div class="ms"><b>{{ $stats['total_eksemplar'] }}</b><span>Total eksemplar</span></div>
      <div class="ms"><b>{{ $stats['dipinjam'] + $stats['kembali'] }}</b><span>Kali peminjaman tercatat</span></div>
    </div>
    <p style="margin-top:16px">Halo, <b style="color:#16324c">{{ $u->name }}</b> — kamu masuk sebagai {{ $u->isAdmin() ? 'admin perpustakaan' : 'siswa'.($u->kelas ? ' kelas '.$u->kelas : '') }}.</p>
  </div>
  <div class="feats">
    <div class="feat">
      <div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20V4a1 1 0 0 0-1-1H6.5A2.5 2.5 0 0 0 4 5.5v14z"/><path d="M4 19.5A2.5 2.5 0 0 0 6.5 22H20v-5"/></svg></div>
      <div><b>Koleksi Lengkap & Terkurasi</b><span>Buku pelajaran, referensi, dan fiksi pilihan untuk siswa SMP.</span></div>
    </div>
    <div class="feat">
      <div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg></div>
      <div><b>Pinjam & Kembali Tanpa Antre Lama</b><span>Petugas mencatat peminjaman dalam hitungan detik, stok ter-update otomatis.</span></div>
    </div>
    <div class="feat">
      <div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></div>
      <div><b>Jatuh Tempo & Denda Transparan</b><span>Batas pinjam 7 hari dan denda Rp500/hari terlihat jelas, tanpa biaya siluman.</span></div>
    </div>
  </div>
</section>

{{-- Kartu "destinasi" = buku populer, mengikuti referensi --}}
<div class="sec-head">
  <h2>Buku Paling Sering Dipinjam</h2>
  <p>Buku-buku favorit teman-temanmu di sekolah. Klik kartunya untuk melihat detail dan ketersediaannya.</p>
</div>
<div class="dest-grid">
  @forelse($popular as $i => $b)
    @php($tersedia = max(0, $b->stock - $b->dipinjam_count))
    <a class="dest" href="{{ route('books.show', $b) }}">
      <img src="{{ asset($imgs[$i % count($imgs)]) }}" alt="">
      <span class="chip {{ $tersedia === 0 ? 'out' : '' }}">{{ $tersedia > 0 ? 'Tersedia '.$tersedia : 'Sedang habis' }}</span>
      <span class="meta"><b>{{ $b->title }}</b><span>{{ $b->author }} · Rak {{ $b->shelf ?? '-' }} · {{ $b->loans_count }}x dipinjam</span></span>
    </a>
  @empty
    <p class="muted">Belum ada data peminjaman.</p>
  @endforelse
</div>

@if($u->isAdmin() && $overdue->isNotEmpty())
<div class="card">
  <h2>Perlu perhatian — peminjaman terlambat</h2>
  <table>
    <tr><th>Buku</th><th>Peminjam</th><th>Jatuh tempo</th><th>Terlambat</th><th>Denda berjalan</th></tr>
    @foreach($overdue as $loan)
    <tr>
      <td><a href="{{ route('loans.show', $loan) }}">{{ $loan->book->title }}</a></td>
      <td>{{ $loan->user->name }} ({{ $loan->user->kelas ?? '-' }})</td>
      <td>{{ $loan->due_date->format('d M Y') }}</td>
      <td>{{ $loan->lateDays() }} hari</td>
      <td>Rp{{ number_format($loan->currentFine(), 0, ',', '.') }}</td>
    </tr>
    @endforeach
  </table>
</div>
@endif

<div class="card">
  <h2>Peminjaman terbaru</h2>
  <table>
    <tr><th>Buku</th><th>Peminjam</th><th>Jatuh tempo</th><th>Status</th></tr>
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
  <p style="margin:14px 0 0"><a href="{{ route('books.index') }}">Lihat katalog buku</a> · <a href="{{ route('loans.index') }}">Semua peminjaman</a></p>
</div>

{{-- Langkah 1-2-3 mengikuti referensi --}}
<div class="sec-head">
  <h2>Pinjam Buku Semudah 1–2–3</h2>
  <p>Tiga langkah sederhana dari mencari buku sampai membawanya pulang.</p>
</div>
<div class="steps">
  <div class="step"><div class="num">1</div><b>Cari Bukunya</b><span>Telusuri katalog berdasarkan judul, pengarang, atau ISBN, lalu cek rak dan ketersediaannya.</span></div>
  <div class="step"><div class="num">2</div><b>Datang ke Perpustakaan</b><span>Ambil buku di raknya dan serahkan ke petugas untuk dicatat sebagai peminjamanmu.</span></div>
  <div class="step"><div class="num">3</div><b>Baca & Kembalikan Tepat Waktu</b><span>Waktu pinjam 7 hari. Kembalikan sebelum jatuh tempo agar tidak kena denda Rp500 per hari.</span></div>
</div>
@endsection
