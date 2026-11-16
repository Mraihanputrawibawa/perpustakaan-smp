@extends('layouts.app')
@section('content')
<h2>Laporan Perpustakaan</h2>
<div class="card">
  <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap;align-items:end">
    <div><label>Dari (tgl pinjam)</label><input type="date" name="from" value="{{ request('from') }}" style="margin:0"></div>
    <div><label>Sampai</label><input type="date" name="to" value="{{ request('to') }}" style="margin:0"></div>
    <button type="submit">Filter</button>
    <a class="btn btn-gray" href="{{ route('admin.reports.csv', request()->query()) }}">Ekspor CSV</a>
    <a class="btn btn-gray" href="{{ route('admin.reports.print', request()->query()) }}" target="_blank">Cetak / PDF</a>
  </form>
</div>
<div class="stats">
  <div class="stat"><b>{{ $summary['total'] }}</b>Total Peminjaman</div>
  <div class="stat"><b>{{ $summary['dipinjam'] }}</b>Masih Dipinjam</div>
  <div class="stat"><b>{{ $summary['terlambat'] }}</b>Terlambat</div>
  <div class="stat"><b>Rp{{ number_format($summary['total_denda'], 0, ',', '.') }}</b>Total Denda Terkumpul</div>
</div>
<div class="card" style="margin-top:16px">
  <h3>Rekap per Kategori Buku</h3>
  <table>@foreach($summary['by_category'] as $k=>$v)<tr><td>{{ $k }}</td><td>{{ $v }} kali dipinjam</td></tr>@endforeach</table>
  <h3>Buku Paling Sering Dipinjam</h3>
  <table>@foreach($summary['top_books'] as $k=>$v)<tr><td>{{ $k }}</td><td>{{ $v }} kali</td></tr>@endforeach</table>
</div>
<div class="card">
  <h3>Detail Peminjaman</h3>
  <table><tr><th>ID</th><th>Siswa</th><th>Buku</th><th>Tgl Pinjam</th><th>Status</th><th>Denda</th></tr>
  @foreach($loans as $l)<tr><td>#{{ $l->id }}</td><td>{{ $l->user->name }}</td><td>{{ $l->book->title }}</td><td>{{ $l->borrowed_at->format('d M Y') }}</td><td>{{ $l->statusLabel() }}</td><td>Rp{{ number_format($l->currentFine(), 0, ',', '.') }}</td></tr>@endforeach
  </table>
  <div style="margin-top:12px">{{ $loans->links() }}</div>
</div>
@endsection
