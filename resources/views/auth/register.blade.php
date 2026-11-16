@extends('layouts.app')
@section('content')
<div class="card auth">
  <div class="auth-hero"><img src="/images/hero-perpus.jpg" alt="Perpustakaan SMP"></div>
  <h2 style="margin-top:0;color:#14213d">Daftar Akun Siswa</h2>
  <form method="POST" action="{{ route('register') }}">
    @csrf
    <label>Nama</label>
    <input type="text" name="name" value="{{ old('name') }}" required>
    <label>Kelas (contoh: 7A)</label>
    <input type="text" name="kelas" value="{{ old('kelas') }}" maxlength="10">
    <label>Email</label>
    <input type="email" name="email" value="{{ old('email') }}" required>
    <label>Password (min. 8 karakter, huruf & angka)</label>
    <input type="password" name="password" required>
    <label>Konfirmasi Password</label>
    <input type="password" name="password_confirmation" required>
    <button class="btn" type="submit" style="width:100%">Daftar</button>
  </form>
  <p style="font-size:14px">Sudah punya akun? <a href="{{ route('login') }}">Login</a></p>
</div>
@endsection
