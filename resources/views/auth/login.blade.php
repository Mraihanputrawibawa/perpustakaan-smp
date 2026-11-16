@extends('layouts.app')
@section('content')
<div class="card auth">
  <div class="auth-hero"><img src="/images/hero-perpus.jpg" alt="Perpustakaan SMP"></div>
  <h2 style="margin-top:0;color:#14213d">Selamat Datang di PerpusSMP</h2>
  <p class="muted" style="margin-top:-6px">Sistem Informasi Perpustakaan SMP — masuk untuk melihat katalog dan peminjamanmu.</p>
  <form method="POST" action="{{ route('login') }}">
    @csrf
    <label>Email</label>
    <input type="email" name="email" value="{{ old('email') }}" required autofocus>
    <label>Password</label>
    <input type="password" name="password" required>
    <label style="font-weight:400"><input type="checkbox" name="remember" style="width:auto"> Ingat saya</label>
    <button class="btn" type="submit" style="width:100%;margin-top:14px">Login</button>
  </form>
  <p style="font-size:.92rem">Belum punya akun? <a href="{{ route('register') }}">Daftar sebagai siswa</a></p>
  <p style="font-size:.82rem" class="muted">Akun demo (setelah seeding): admin@perpus.test / petugas@perpus.test / siswa@perpus.test — password: <code>password</code></p>
</div>
@endsection
