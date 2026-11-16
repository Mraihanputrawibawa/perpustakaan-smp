@extends('layouts.app')
@section('content')
@php($imgs = ['images/kartu-belajar.jpg', 'images/kartu-novel.jpg', 'images/kartu-rak.jpg', 'images/hero-perpus.jpg'])
<div class="sec-head">
  <div>
    <h2 style="font-size:1.6rem">Katalog Buku</h2>
    <p style="text-align:left;max-width:none;margin-top:4px">Seluruh koleksi perpustakaan sekolah. Klik kartu buku untuk detailnya.</p>
  </div>
  @if(auth()->user()->isAdmin())<a class="btn" href="{{ route('books.create') }}">Tambah Buku</a>@endif
</div>

<form class="filterbar" method="GET">
  <input type="text" name="search" placeholder="Cari judul, pengarang, atau ISBN…" value="{{ request('search') }}">
  <select name="category_id">
    <option value="">Semua kategori</option>
    @foreach($categories as $c)<option value="{{ $c->id }}" @selected(request('category_id')==$c->id)>{{ $c->name }}</option>@endforeach
  </select>
  <button type="submit">Cari</button>
</form>

<div class="dest-grid" style="grid-template-columns:repeat(auto-fill,minmax(215px,1fr))">
  @forelse($books as $i => $b)
    @php($tersedia = max(0, $b->stock - $b->dipinjam_count))
    <a class="dest" href="{{ route('books.show', $b) }}">
      <img src="{{ asset($imgs[$i % count($imgs)]) }}" alt="">
      <span class="chip {{ $tersedia === 0 ? 'out' : '' }}">{{ $tersedia > 0 ? 'Tersedia '.$tersedia : 'Sedang habis' }}</span>
      <span class="meta"><b>{{ $b->title }}</b><span>{{ $b->author }} · {{ $b->category->name }} · Rak {{ $b->shelf ?? '-' }}</span></span>
    </a>
  @empty
    <p class="muted">Tidak ada buku yang cocok dengan pencarian.</p>
  @endforelse
</div>
<nav class="pager">{{ $books->links() }}</nav>
@endsection
