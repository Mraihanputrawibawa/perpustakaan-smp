@extends('layouts.app')
@section('content')
<div class="card">
  <h2>Edit Buku</h2>
  <form method="POST" action="{{ route('books.update', $book) }}">
    @csrf @method('PUT')
    @include('books.form')
    <button type="submit">Simpan Perubahan</button>
  </form>
</div>
@endsection
