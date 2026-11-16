@extends('layouts.app')
@section('content')
<div class="card">
  <h2>Tambah Buku</h2>
  <form method="POST" action="{{ route('books.store') }}">
    @csrf
    @include('books.form')
    <button type="submit">Simpan Buku</button>
  </form>
</div>
@endsection
