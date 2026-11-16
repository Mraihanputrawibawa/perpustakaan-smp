@extends('layouts.app')
@section('content')
<h2>{{ auth()->user()->isStaff() ? 'Data Peminjaman' : 'Peminjaman Saya' }}</h2>
<div class="card">
  <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap">
    <input type="text" name="search" placeholder="Cari judul buku..." value="{{ request('search') }}" style="flex:2;margin:0">
    <select name="status" style="flex:1;margin:0">
      <option value="">Semua status</option>
      @foreach(['dipinjam','terlambat','kembali'] as $s)<option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst($s) }}</option>@endforeach
    </select>
    <button type="submit">Filter</button>
  </form>
</div>
<div class="card">
  <table>
    <tr><th>Buku</th>@if(auth()->user()->isStaff())<th>Siswa</th><th>Kelas</th>@endif<th>Tgl Pinjam</th><th>Jatuh Tempo</th><th>Status</th><th>Denda</th></tr>
    @forelse($loans as $l)
      <tr>
        <td><a href="{{ route('loans.show', $l) }}">{{ $l->book->title }}</a></td>
        @if(auth()->user()->isStaff())<td>{{ $l->user->name }}</td><td>{{ $l->user->kelas ?? '-' }}</td>@endif
        <td>{{ $l->borrowed_at->format('d M Y') }}</td><td>{{ $l->due_date->format('d M Y') }}</td>
        <td><span class="badge">{{ $l->statusLabel() }}</span></td>
        <td>Rp{{ number_format($l->currentFine(), 0, ',', '.') }}</td>
      </tr>
    @empty
      <tr><td colspan="7">Belum ada peminjaman.</td></tr>
    @endforelse
  </table>
  <div style="margin-top:12px">{{ $loans->links() }}</div>
</div>
@endsection
