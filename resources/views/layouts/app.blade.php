<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $title ?? 'PerpusSMP' }} — PerpusSMP</title>
<style>
  * { box-sizing: border-box; }
  body { font-family: 'Segoe UI', system-ui, -apple-system, Arial, sans-serif; margin: 0; background: #dfe8ee; color: #22304a; }
  a { color: #14532d; }
  .page { max-width: 1080px; margin: 22px auto; background: #fbfcfd; border-radius: 26px; padding: 22px 30px 34px; box-shadow: 0 18px 50px rgba(20,33,61,.14); }

  /* ===== Top bar (ala referensi) ===== */
  .topbar { display: flex; align-items: center; gap: 22px; flex-wrap: wrap; padding: 4px 0 16px; }
  .logo { font-size: 1.45rem; font-weight: 800; letter-spacing: .5px; color: #14213d; text-decoration: none; }
  .logo span { color: #2e7d46; }
  .topnav { display: flex; gap: 4px; flex-wrap: wrap; flex: 1; }
  .topnav a { color: #41506b; text-decoration: none; font-size: .92rem; font-weight: 600; padding: 8px 12px; border-radius: 999px; }
  .topnav a:hover { background: #eef3f6; color: #14213d; }
  .userchip { display: flex; align-items: center; gap: 10px; }
  .userchip .who { font-size: .85rem; color: #5b6b84; text-align: right; line-height: 1.25; }
  .userchip .who b { color: #14213d; display: block; font-size: .92rem; }
  .logoutbtn { background: #14213d; color: #fff; border: 0; border-radius: 999px; padding: 9px 18px; font-weight: 700; cursor: pointer; font-size: .88rem; }
  .logoutbtn:hover { background: #22345e; }

  /* ===== Hero ===== */
  .hero { position: relative; border-radius: 22px; overflow: hidden; min-height: 340px; display: flex; align-items: flex-end; background: #14213d url('/images/hero-perpus.jpg') center 38%/cover no-repeat; margin-bottom: 26px; }
  .hero::after { content: ''; position: absolute; inset: 0; background: linear-gradient(180deg, rgba(20,33,61,.05) 30%, rgba(20,33,61,.72)); }
  .hero-in { position: relative; z-index: 1; padding: 30px 34px; width: 100%; }
  .hero h1 { color: #fff; font-size: 3.4rem; margin: 0 0 6px; letter-spacing: 2px; font-weight: 800; line-height: 1.02; text-transform: uppercase; }
  .hero p { color: #e6edf3; margin: 0 0 18px; max-width: 560px; font-size: 1rem; }
  .hero .cta { display: flex; gap: 10px; flex-wrap: wrap; }
  .hero-search { display: flex; gap: 8px; background: #fff; border-radius: 999px; padding: 6px; max-width: 560px; box-shadow: 0 8px 24px rgba(0,0,0,.25); }
  .hero-search input { flex: 1; border: 0; padding: 10px 16px; font-size: .95rem; background: transparent; }
  .hero-search input:focus { outline: none; }
  .hero-search button { background: #14213d; color: #fff; border: 0; border-radius: 999px; padding: 10px 22px; font-weight: 700; cursor: pointer; }

  /* ===== Tombol ===== */
  button { background: #14213d; color: #fff; padding: 9px 20px; border-radius: 999px; border: 0; cursor: pointer; font-size: .9rem; font-weight: 700; font-family: inherit; }
  button:hover { background: #22345e; }
  .btn { display: inline-block; background: #14213d; color: #fff; padding: 10px 22px; border-radius: 999px; text-decoration: none; border: 0; cursor: pointer; font-size: .92rem; font-weight: 700; }
  .btn:hover { background: #22345e; }
  .btn-light { background: #fff; color: #14213d; }
  .btn-light:hover { background: #eef3f6; }
  .btn-outline { background: transparent; color: #fff; border: 2px solid rgba(255,255,255,.85); }
  .btn-outline:hover { background: rgba(255,255,255,.15); }
  .btn-green { background: #2e7d46; }
  .btn-green:hover { background: #256b3a; }
  .btn-sm { padding: 6px 14px; font-size: .82rem; }
  .btn-danger { background: #b3261e; }
  .btn-danger:hover { background: #8f1d17; }

  /* ===== Kartu & seksi ===== */
  .card { background: #fff; border: 1px solid #e4ebf0; border-radius: 18px; padding: 22px; margin-bottom: 20px; box-shadow: 0 4px 18px rgba(20,33,61,.05); }
  .sec-head { display: flex; justify-content: space-between; align-items: flex-end; gap: 16px; margin: 30px 0 14px; }
  .sec-head h2 { margin: 0; color: #14213d; font-size: 1.35rem; }
  .sec-head p { margin: 0; color: #74839a; font-size: .88rem; max-width: 420px; text-align: right; }
  h1.pagetitle { color: #14213d; font-size: 1.7rem; margin: 6px 0 16px; }

  /* ===== Statistik ala referensi ===== */
  .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 14px; margin-bottom: 8px; }
  .stat { background: #fff; border: 1px solid #e4ebf0; border-radius: 18px; padding: 18px; text-align: center; }
  .stat b { display: block; font-size: 1.7rem; color: #14213d; }
  .stat span { color: #74839a; font-size: .85rem; }

  /* ===== Fitur (kotak abu-biru) ===== */
  .features { display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 14px; }
  .feature { background: #eef3f6; border-radius: 18px; padding: 20px; display: flex; gap: 14px; align-items: flex-start; }
  .feature .ic { background: #14213d; color: #fff; border-radius: 12px; width: 46px; height: 46px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0; }
  .feature b { color: #14213d; display: block; margin-bottom: 4px; }
  .feature p { margin: 0; color: #5b6b84; font-size: .88rem; line-height: 1.5; }

  /* ===== Langkah 1-2-3 ===== */
  .steps { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px; }
  .step { background: #fff; border: 1px solid #e4ebf0; border-radius: 18px; padding: 20px; text-align: center; }
  .step .num { width: 44px; height: 44px; border-radius: 50%; background: #2e7d46; color: #fff; font-weight: 800; font-size: 1.15rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; }
  .step b { color: #14213d; display: block; margin-bottom: 4px; }
  .step p { margin: 0; color: #5b6b84; font-size: .88rem; }

  /* ===== Grid kartu buku (ala Top Destinations) ===== */
  .book-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(225px, 1fr)); gap: 16px; }
  .book-card { border-radius: 18px; overflow: hidden; background: #fff; border: 1px solid #e4ebf0; box-shadow: 0 4px 18px rgba(20,33,61,.06); display: flex; flex-direction: column; }
  .cover { height: 168px; padding: 16px; display: flex; flex-direction: column; justify-content: flex-end; color: #fff; position: relative; text-decoration: none; }
  .cover .cat { position: absolute; top: 12px; left: 12px; background: rgba(255,255,255,.92); color: #14213d; font-size: .72rem; font-weight: 700; padding: 4px 10px; border-radius: 999px; }
  .cover .avail { position: absolute; top: 12px; right: 12px; background: #14213d; color: #fff; font-size: .72rem; font-weight: 700; padding: 4px 10px; border-radius: 999px; }
  .cover .avail.empty { background: #b3261e; }
  .cover h3 { margin: 0 0 2px; font-size: 1.12rem; line-height: 1.25; text-shadow: 0 2px 10px rgba(0,0,0,.35); }
  .cover small { opacity: .92; }
  .g1 { background: linear-gradient(150deg, #2e7d46, #14213d); }
  .g2 { background: linear-gradient(150deg, #b3541e, #5b2a0e); }
  .g3 { background: linear-gradient(150deg, #1d6fb8, #0d2f52); }
  .g4 { background: linear-gradient(150deg, #6d4fc2, #2a1b52); }
  .g5 { background: linear-gradient(150deg, #0e7c7b, #083f3e); }
  .book-meta { padding: 14px 16px; display: flex; justify-content: space-between; align-items: center; gap: 8px; font-size: .85rem; color: #5b6b84; }
  .book-meta .loc { font-weight: 600; color: #41506b; }

  /* ===== Tabel & form ===== */
  table { width: 100%; border-collapse: collapse; background: #fff; }
  th, td { text-align: left; padding: 11px 12px; border-bottom: 1px solid #e8eef2; font-size: .93rem; }
  th { color: #14213d; background: #f2f6f8; }
  tr:last-child td { border-bottom: 0; }
  .badge { display: inline-block; padding: 3px 12px; border-radius: 999px; font-size: .78rem; font-weight: 700; background: #e8eef2; color: #41506b; }
  .badge-green { background: #e2f2e6; color: #256b3a; }
  .badge-red { background: #fbe7e5; color: #b3261e; }
  .badge-amber { background: #fdf0d5; color: #8a5a00; }
  label { display: block; font-weight: 600; margin: 14px 0 6px; color: #14213d; font-size: .92rem; }
  input[type=text], input[type=email], input[type=password], input[type=number], input[type=date], select, textarea { width: 100%; padding: 11px 14px; border: 1px solid #ccd6df; border-radius: 12px; font-size: .95rem; background: #fff; }
  input:focus, select:focus, textarea:focus { outline: 2px solid #2e7d46; border-color: transparent; }
  .alert { border-radius: 14px; padding: 13px 18px; margin-bottom: 16px; font-size: .93rem; }
  .alert-ok { background: #e2f2e6; color: #256b3a; border: 1px solid #bfe3c9; }
  .alert-err { background: #fbe7e5; color: #8f1d17; border: 1px solid #f3c1bd; }
  .muted { color: #74839a; }
  .filterbar { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
  .filterbar input[type=text] { flex: 1; min-width: 200px; border-radius: 999px; padding: 11px 18px; }
  .filterbar select { width: auto; border-radius: 999px; padding: 11px 16px; }
  nav.pager { margin-top: 16px; font-size: .9rem; }
  nav.pager a, nav.pager span { margin-right: 6px; }
  .auth-wrap { max-width: 430px; margin: 26px auto; }
  .card.auth { max-width: 430px; margin: 26px auto; }
  .auth-hero { border-radius: 18px; overflow: hidden; margin-bottom: 16px; }
  .auth-hero img { width: 100%; height: 170px; object-fit: cover; display: block; }
  footer.site { text-align: center; color: #74839a; font-size: .82rem; margin-top: 28px; }
  @media (max-width: 640px) { .page { padding: 16px; margin: 10px; } .hero h1 { font-size: 2.2rem; } .sec-head { flex-direction: column; align-items: flex-start; } .sec-head p { text-align: left; } }
</style>
</head>
<body>
<div class="page">
  <div class="topbar">
    <a class="logo" href="{{ route('dashboard') }}">PERPUS<span>SMP</span></a>
    @auth
    <nav class="topnav">
      <a href="{{ route('dashboard') }}">Dashboard</a>
      <a href="{{ route('books.index') }}">Katalog Buku</a>
      <a href="{{ route('loans.index') }}">Peminjaman</a>
      @if(auth()->user()->isStaff())
      <a href="{{ route('loans.create') }}">Catat Peminjaman</a>
      <a href="{{ route('books.create') }}">Tambah Buku</a>
      @endif
      @if(auth()->user()->isAdmin())
      <a href="{{ route('admin.categories.index') }}">Kategori</a>
      <a href="{{ route('admin.users.index') }}">User</a>
      <a href="{{ route('admin.reports.index') }}">Laporan</a>
      @endif
    </nav>
    <div class="userchip">
      <div class="who"><b>{{ auth()->user()->name }}</b>{{ ucfirst(auth()->user()->role) }}@if(auth()->user()->kelas) · Kelas {{ auth()->user()->kelas }}@endif</div>
      <form method="POST" action="{{ route('logout') }}" style="margin:0">@csrf<button class="logoutbtn" type="submit">Logout</button></form>
    </div>
    @endauth
  </div>

  @if(session('success'))<div class="alert alert-ok">{{ session('success') }}</div>@endif
  @if($errors->any())
    <div class="alert alert-err"><strong>Periksa lagi:</strong><ul style="margin:6px 0 0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif

  @yield('content')

  <footer class="site">PerpusSMP — Sistem Informasi Perpustakaan SMP · Pinjam buku semudah 1-2-3</footer>
</div>
</body>
</html>
