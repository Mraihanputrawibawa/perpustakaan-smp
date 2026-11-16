@extends('layouts.app')
@section('content')
<div class="sec-head" style="margin-top:6px">
  <h2 style="font-size:1.6rem">Katalog Buku</h2>
  <p>Jelajahi semua koleksi perpustakaan — klik sampul buku untuk detail dan ketersediaannya.</p>
</div>

<form class="filterbar" method="GET" style="margin-bottom:20px">
  <input type="text" name="search" placeholder="Cari judul / pengarang / ISBN…" value="{{ request('search') }}">
  <select name="category_id">
    <option value="">Semua kategori</option>
    @foreach($categories as $c)<option value="{{ $c->id }}" @selected(request('category_id')==$c->id)>{{ $c->name }}</option>@endforeach
  </select>
  <button class="btn" type="submit">Cari</button>
  @if(auth()->user()->isStaff())<a class="btn btn-green" href="{{ route('books.create') }}">+ Tambah Buku</a>@endif
</form>

<div class="book-grid">
  @forelse($books as $i => $b)
    @php($tersedia = max(0, $b->stock - $b->dipinjam_count))
    <div class="book-card">
      <a class="cover g{{ ($i % 5) + 1 }}" href="{{ route('books.show', $b) }}">
        <span class="cat">{{ $b->category->name }}</span>
        <span class="avail {{ $tersedia <= 0 ? 'empty' : '' }}">{{ $tersedia > 0 ? 'Tersedia '.$tersedia : 'Habis' }}</span>
        <h3>{{ $b->title }}</h3>
        <small>{{ $b->author }}</small>
      </a>
      <div class="book-meta">
        <span class="loc">Rak {{ $b->shelf ?? '-' }} · Stok {{ $b->stock }}</span>
        @if(auth()->user()->isStaff())<a href="{{ route('books.edit', $b) }}">Edit</a>@endif
      </div>
    </div>
  @empty
    <div class="card"><p class="muted" style="margin:0">Tidak ada buku yang cocok. Coba kata kunci atau kategori lain.</p></div>
  @endforelse
</div>

<nav class="pager">{{ $books->links() }}</nav>
@endsection
