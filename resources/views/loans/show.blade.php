@extends('layouts.app')
@section('content')
<div class="card">
  <h2>Detail Peminjaman #{{ $loan->id }}</h2>
  <table>
    <tr><td><b>Buku</b></td><td>{{ $loan->book->title }} ({{ $loan->book->category->name }})</td></tr>
    <tr><td><b>Peminjam</b></td><td>{{ $loan->user->name }} @if($loan->user->kelas)— Kelas {{ $loan->user->kelas }}@endif</td></tr>
    <tr><td><b>Tanggal Pinjam</b></td><td>{{ $loan->borrowed_at->format('d M Y') }}</td></tr>
    <tr><td><b>Jatuh Tempo</b></td><td>{{ $loan->due_date->format('d M Y') }}</td></tr>
    <tr><td><b>Tanggal Kembali</b></td><td>{{ $loan->returned_at?->format('d M Y') ?? '-' }}</td></tr>
    <tr><td><b>Status</b></td><td><span class="badge">{{ $loan->statusLabel() }}</span></td></tr>
    <tr><td><b>Keterlambatan</b></td><td>{{ $loan->lateDays() }} hari</td></tr>
    <tr><td><b>Denda</b></td><td>Rp{{ number_format($loan->currentFine(), 0, ',', '.') }} (Rp500/hari)</td></tr>
  </table>
  @if(auth()->user()->isStaff() && !$loan->isReturned())
    <form method="POST" action="{{ route('loans.return', $loan) }}" style="margin-top:14px" onsubmit="return confirm('Proses pengembalian buku ini?')">
      @csrf <button type="submit">Proses Pengembalian</button>
    </form>
  @endif
</div>
@endsection
