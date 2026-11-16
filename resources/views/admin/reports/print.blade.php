<!DOCTYPE html>
<html lang="id"><head><meta charset="utf-8"><title>Laporan Perpustakaan SMP</title>
<style>body{font-family:Arial,sans-serif}table{width:100%;border-collapse:collapse}th,td{border:1px solid #999;padding:6px;font-size:13px;text-align:left}</style>
</head><body>
<h2>Laporan Peminjaman Perpustakaan SMP</h2>
<p>Dicetak: {{ now()->format('d M Y H:i') }} — Total: {{ $loans->count() }} peminjaman. Simpan halaman ini sebagai PDF (Ctrl+P).</p>
<table><tr><th>ID</th><th>Siswa</th><th>Kelas</th><th>Buku</th><th>Tgl Pinjam</th><th>Jatuh Tempo</th><th>Tgl Kembali</th><th>Status</th><th>Denda</th></tr>
@foreach($loans as $l)
<tr><td>{{ $l->id }}</td><td>{{ $l->user->name }}</td><td>{{ $l->user->kelas ?? '-' }}</td><td>{{ $l->book->title }}</td><td>{{ $l->borrowed_at->format('d M Y') }}</td><td>{{ $l->due_date->format('d M Y') }}</td><td>{{ $l->returned_at?->format('d M Y') ?? '-' }}</td><td>{{ $l->statusLabel() }}</td><td>Rp{{ number_format($l->currentFine(), 0, ',', '.') }}</td></tr>
@endforeach
</table>
</body></html>
