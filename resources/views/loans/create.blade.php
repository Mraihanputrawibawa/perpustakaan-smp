@extends('layouts.app')
@section('content')
<div class="card">
  <h2>Catat Peminjaman Baru</h2>
  <form method="POST" action="{{ route('loans.store') }}">
    @csrf
    <label>Siswa Peminjam</label>
    <select name="user_id" required>
      @foreach($students as $s)<option value="{{ $s->id }}">{{ $s->name }} @if($s->kelas)({{ $s->kelas }})@endif</option>@endforeach
    </select>
    <label>Buku</label>
    <select name="book_id" required>
      @foreach($books as $b)<option value="{{ $b->id }}">{{ $b->title }} — tersedia {{ $b->availableStock() }}</option>@endforeach
    </select>
    <label>Tanggal Pinjam (kosongkan = hari ini)</label>
    <input type="date" name="borrowed_at" value="{{ old('borrowed_at') }}">
    <label>Jatuh Tempo (kosongkan = 7 hari)</label>
    <input type="date" name="due_date" value="{{ old('due_date') }}">
    <button type="submit">Simpan Peminjaman</button>
  </form>
</div>
@endsection
