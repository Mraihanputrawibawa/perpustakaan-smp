@extends('layouts.app')
@section('content')
@php($u = auth()->user())

<div class="pagehead">
  <h1>Selamat datang, {{ $u->name }}</h1>
  <p>{{ $u->isAdmin() ? 'Anda masuk sebagai admin perpustakaan.' : 'Anda masuk sebagai siswa'.($u->kelas ? ' kelas '.$u->kelas : '').'.' }}</p>
</div>

<div class="stats">
  <div class="stat"><b>{{ $stats['total_buku'] }}</b><span>Judul buku</span></div>
  <div class="stat"><b>{{ $stats['total_eksemplar'] }}</b><span>Total eksemplar</span></div>
  <div class="stat"><b>{{ $stats['dipinjam'] }}</b><span>{{ $u->isAdmin() ? 'Sedang dipinjam' : 'Pinjaman saya aktif' }}</span></div>
  <div class="stat"><b>{{ $stats['terlambat'] }}</b><span>Terlambat</span></div>
  <div class="stat"><b>{{ $stats['kembali'] }}</b><span>Sudah kembali</span></div>
  <div class="stat"><b>Rp{{ number_format($stats['total_denda'], 0, ',', '.') }}</b><span>Total denda</span></div>
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
@endsection
