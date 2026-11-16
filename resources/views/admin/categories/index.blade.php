@extends('layouts.app')
@section('content')
<h2>Kelola Kategori</h2>
<div class="card">
  <form method="POST" action="{{ route('admin.categories.store') }}">
    @csrf
    <label>Nama Kategori Baru</label>
    <input type="text" name="name" required maxlength="100">
    <label>Deskripsi</label>
    <input type="text" name="description" maxlength="255">
    <button type="submit">Tambah</button>
  </form>
</div>
<div class="card">
  <table><tr><th>Nama</th><th>Deskripsi</th><th>Jumlah Buku</th><th>Aksi</th></tr>
  @foreach($categories as $c)
    <tr>
      <td>{{ $c->name }}</td><td>{{ $c->description }}</td><td>{{ $c->books_count }}</td>
      <td>
        <form method="POST" action="{{ route('admin.categories.destroy', $c) }}" onsubmit="return confirm('Hapus kategori ini?')">
          @csrf @method('DELETE') <button class="btn-red" type="submit">Hapus</button>
        </form>
      </td>
    </tr>
  @endforeach
  </table>
</div>
@endsection
