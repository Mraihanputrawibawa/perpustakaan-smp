@extends('layouts.app')
@section('content')
<div class="pagehead">
  <h1>Katalog Buku</h1>
  <p>Seluruh koleksi perpustakaan. Kolom "Tersedia" menunjukkan eksemplar yang bisa dipinjam saat ini.</p>
</div>

<form class="filterbar" method="GET">
  <input type="text" name="search" placeholder="Cari judul, pengarang, atau ISBN" value="{{ request('search') }}">
  <select name="category_id">
    <option value="">Semua kategori</option>
    @foreach($categories as $c)<option value="{{ $c->id }}" @selected(request('category_id')==$c->id)>{{ $c->name }}</option>@endforeach
  </select>
  <button type="submit">Cari</button>
  @if(auth()->user()->isAdmin())<a class="btn btn-secondary" href="{{ route('books.create') }}">Tambah Buku</a>@endif
</form>

<div class="card" style="padding:8px 18px">
<table>
  <tr><th>Judul</th><th>Pengarang</th><th>Kategori</th><th>Rak</th><th>Stok</th><th>Tersedia</th>@if(auth()->user()->isAdmin())<th></th>@endif</tr>
  @forelse($books as $b)
    @php($tersedia = max(0, $b->stock - $b->dipinjam_count))
    <tr>
      <td><a href="{{ route('books.show', $b) }}">{{ $b->title }}</a></td>
      <td>{{ $b->author }}</td>
      <td>{{ $b->category->name }}</td>
      <td>{{ $b->shelf ?? '-' }}</td>
      <td>{{ $b->stock }}</td>
      <td>{{ $tersedia > 0 ? $tersedia : 'Habis' }}</td>
      @if(auth()->user()->isAdmin())<td><a href="{{ route('books.edit', $b) }}">Edit</a></td>@endif
    </tr>
  @empty
    <tr><td colspan="7" class="muted">Tidak ada buku yang cocok dengan pencarian.</td></tr>
  @endforelse
</table>
</div>
<nav class="pager">{{ $books->links() }}</nav>
@endsection
