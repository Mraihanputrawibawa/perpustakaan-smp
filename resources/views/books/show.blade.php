@extends('layouts.app')
@section('content')
<div class="card">
  <h2>{{ $book->title }}</h2>
  <table>
    <tr><td><b>Pengarang</b></td><td>{{ $book->author }}</td></tr>
    <tr><td><b>Penerbit</b></td><td>{{ $book->publisher ?? '-' }}</td></tr>
    <tr><td><b>Tahun</b></td><td>{{ $book->year ?? '-' }}</td></tr>
    <tr><td><b>ISBN</b></td><td>{{ $book->isbn ?? '-' }}</td></tr>
    <tr><td><b>Kategori</b></td><td>{{ $book->category->name }}</td></tr>
    <tr><td><b>Lokasi Rak</b></td><td>{{ $book->shelf ?? '-' }}</td></tr>
    <tr><td><b>Stok Total</b></td><td>{{ $book->stock }}</td></tr>
    <tr><td><b>Sedang Dipinjam</b></td><td>{{ $book->activeLoansCount() }}</td></tr>
    <tr><td><b>Tersedia</b></td><td>{{ $book->availableStock() }}</td></tr>
  </table>
  @if(auth()->user()->isStaff())
    <p style="margin-top:12px">
      <a class="btn btn-gray" href="{{ route('books.edit', $book) }}">Edit</a>
    </p>
    <form method="POST" action="{{ route('books.destroy', $book) }}" onsubmit="return confirm('Hapus buku ini dari katalog?')">
      @csrf @method('DELETE') <button class="btn-red" type="submit">Hapus Buku</button>
    </form>
  @endif
</div>
@endsection
