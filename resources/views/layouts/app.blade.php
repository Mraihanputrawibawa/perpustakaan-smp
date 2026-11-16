<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $title ?? 'PerpusSMP' }} — PerpusSMP</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  * { box-sizing: border-box; }
  body { font-family: 'Poppins', -apple-system, 'Segoe UI', Roboto, Arial, sans-serif; margin: 0; background: #bcced2; color: #43586e; font-size: 15px; line-height: 1.55; padding: 18px 14px 30px; }
  a { color: #17375a; }
  h1, h2, h3 { font-family: 'Poppins', sans-serif; font-weight: 700; color: #16324c; letter-spacing: -.2px; }

  .panel { max-width: 1060px; margin: 0 auto; background: #fff; border-radius: 28px; box-shadow: 0 18px 45px rgba(22,50,76,.18); overflow: hidden; }

  .topbar-in { padding: 16px 30px 6px; display: flex; align-items: center; gap: 22px; flex-wrap: wrap; }
  .brand { text-decoration: none; color: #16324c; line-height: 1.1; }
  .brand b { font-size: 1.3rem; font-weight: 800; letter-spacing: -.3px; }
  .brand b i { font-style: normal; color: #7d99b5; }
  .brand small { display: block; color: #71879c; font-size: .68rem; letter-spacing: 1.2px; text-transform: uppercase; font-weight: 500; }
  nav.main { display: flex; gap: 2px; flex-wrap: wrap; flex: 1; }
  nav.main a { color: #43586e; text-decoration: none; font-size: .88rem; font-weight: 500; padding: 7px 11px; border-radius: 999px; }
  nav.main a:hover { background: #edf2f5; color: #16324c; }
  .who { font-size: .82rem; color: #71879c; white-space: nowrap; }
  .who b { color: #16324c; font-weight: 600; }
  .logoutbtn { background: #17375a; border: 1px solid #17375a; color: #fff; border-radius: 999px; padding: 7px 18px; font-size: .85rem; font-weight: 600; cursor: pointer; font-family: inherit; }
  .logoutbtn:hover { background: #10293f; }

  .wrap { padding: 18px 30px 34px; }
  .pagehead { margin-bottom: 18px; }
  .pagehead h1 { font-size: 1.6rem; margin: 0 0 2px; }
  .pagehead p { margin: 0; color: #71879c; font-size: .92rem; }

  .card { background: #fff; border: 1px solid #dfe7ed; border-radius: 20px; padding: 22px 24px; margin-bottom: 20px; }
  .card h2 { font-size: 1.15rem; margin: 0 0 12px; }

  button, .btn { display: inline-block; background: #17375a; color: #fff; border: 1px solid #17375a; padding: 9px 20px; border-radius: 999px; font-size: .9rem; font-weight: 600; font-family: inherit; cursor: pointer; text-decoration: none; }
  button:hover, .btn:hover { background: #10293f; }
  .btn-light { background: #fff; color: #16324c; border-color: #fff; }
  .btn-light:hover { background: #edf2f5; }
  .btn-outline { background: transparent; color: #fff; border: 1.5px solid rgba(255,255,255,.85); }
  .btn-outline:hover { background: rgba(255,255,255,.14); }
  .btn-secondary { background: #fff; color: #16324c; border: 1px solid #c3d0da; }
  .btn-secondary:hover { background: #edf2f5; }
  .btn-danger { background: #b23a30; border-color: #b23a30; }
  .btn-danger:hover { background: #933025; }
  .btn-sm { padding: 5px 13px; font-size: .83rem; }

  /* --- Hero mengikuti referensi --- */
  .hero { position: relative; border-radius: 22px; overflow: hidden; margin-bottom: 30px; min-height: 340px; display: flex; align-items: flex-end; }
  .hero img.bg { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
  .hero::after { content: ''; position: absolute; inset: 0; background: linear-gradient(180deg, rgba(18,40,64,.05) 40%, rgba(18,40,64,.55)); }
  .hero-in { position: relative; z-index: 2; padding: 30px 34px 30px; width: 100%; }
  .hero h1 { color: #fff; font-size: clamp(2.6rem, 7.5vw, 4.6rem); font-weight: 800; line-height: .98; margin: 0 0 8px; letter-spacing: -.5px; text-shadow: 0 2px 14px rgba(10,25,40,.35); }
  .hero h1 i { font-style: normal; font-size: .32em; font-weight: 600; vertical-align: super; letter-spacing: 1px; }
  .hero p { color: rgba(255,255,255,.92); max-width: 520px; margin: 0 0 16px; font-size: .95rem; }
  .hero-search { display: flex; gap: 8px; background: #fff; border-radius: 999px; padding: 5px; max-width: 520px; box-shadow: 0 8px 24px rgba(10,25,40,.25); margin-bottom: 14px; }
  .hero-search input { flex: 1; border: 0 !important; background: transparent !important; padding: 8px 14px !important; font-size: .92rem; }
  .hero-search input:focus { outline: none; }
  .hero-cta { display: flex; gap: 10px; flex-wrap: wrap; }

  /* --- Seksi "Kenapa memilih" mengikuti referensi --- */
  .why { display: grid; grid-template-columns: 1.15fr .85fr; gap: 26px; margin-bottom: 34px; }
  .why h2 { font-size: 1.45rem; margin: 0 0 10px; line-height: 1.3; }
  .why p { margin: 0 0 18px; font-size: .93rem; }
  .mini-stats { display: flex; gap: 30px; flex-wrap: wrap; }
  .mini-stats .ms b { display: block; font-size: 1.45rem; font-weight: 700; color: #16324c; }
  .mini-stats .ms span { font-size: .8rem; color: #71879c; }
  .feats { display: flex; flex-direction: column; gap: 12px; }
  .feat { display: flex; gap: 14px; background: #dbe5ee; border-radius: 16px; padding: 14px 16px; align-items: flex-start; }
  .feat .ic { flex: 0 0 42px; width: 42px; height: 42px; border-radius: 11px; background: #17375a; color: #fff; display: flex; align-items: center; justify-content: center; }
  .feat .ic svg { width: 22px; height: 22px; }
  .feat b { display: block; color: #16324c; font-size: .93rem; font-weight: 600; }
  .feat span { font-size: .83rem; color: #43586e; }

  /* --- Kartu destinasi mengikuti referensi --- */
  .sec-head { display: flex; justify-content: space-between; align-items: flex-end; gap: 20px; margin-bottom: 16px; }
  .sec-head h2 { font-size: 1.45rem; margin: 0; }
  .sec-head p { margin: 0; font-size: .87rem; color: #71879c; max-width: 360px; text-align: right; }
  .dest-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 30px; }
  .dest { position: relative; border-radius: 18px; overflow: hidden; min-height: 250px; display: flex; align-items: flex-end; text-decoration: none; }
  .dest img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; transition: transform .35s ease; }
  .dest:hover img { transform: scale(1.05); }
  .dest::after { content: ''; position: absolute; inset: 0; background: linear-gradient(180deg, rgba(18,40,64,.12) 45%, rgba(18,40,64,.72)); }
  .dest .chip { position: absolute; top: 12px; right: 12px; z-index: 2; background: rgba(255,255,255,.92); color: #16324c; font-size: .72rem; font-weight: 600; border-radius: 999px; padding: 4px 10px; }
  .dest .chip.out { background: #b23a30; color: #fff; }
  .dest .meta { position: relative; z-index: 2; padding: 14px 16px; color: #fff; width: 100%; }
  .dest .meta b { display: block; font-size: 1rem; font-weight: 700; line-height: 1.3; }
  .dest .meta span { font-size: .8rem; color: rgba(255,255,255,.85); }

  /* --- Langkah 1-2-3 mengikuti referensi --- */
  .steps { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 30px; }
  .step { background: #dbe5ee; border-radius: 18px; padding: 20px; text-align: center; }
  .step .num { width: 44px; height: 44px; margin: 0 auto 10px; border-radius: 50%; background: #17375a; color: #fff; font-weight: 700; font-size: 1.1rem; display: flex; align-items: center; justify-content: center; }
  .step b { display: block; color: #16324c; font-weight: 600; margin-bottom: 4px; }
  .step span { font-size: .85rem; }

  .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); background: #fff; border: 1px solid #dfe7ed; border-radius: 20px; margin-bottom: 20px; overflow: hidden; }
  .stat { padding: 16px 18px; border-left: 1px solid #e6edf2; }
  .stat:first-child { border-left: 0; }
  .stat b { display: block; font-size: 1.5rem; font-weight: 700; color: #16324c; }
  .stat span { color: #71879c; font-size: .83rem; }

  table { width: 100%; border-collapse: collapse; }
  th, td { text-align: left; padding: 9px 10px; border-bottom: 1px solid #e6edf2; font-size: .93rem; vertical-align: top; }
  th { font-size: .78rem; text-transform: uppercase; letter-spacing: .6px; color: #71879c; font-weight: 600; border-bottom: 1px solid #d5dfe7; }
  tr:last-child td { border-bottom: 0; }
  tbody tr:hover { background: #f4f8fa; }

  .badge { display: inline-block; padding: 2px 10px; border-radius: 999px; font-size: .78rem; font-weight: 500; color: #43586e; background: #e7eef3; white-space: nowrap; }
  .badge-late { color: #933025; background: #f9e9e7; }
  .badge-back { color: #175c33; background: #e8f3ec; }

  label { display: block; font-weight: 600; color: #16324c; margin: 13px 0 5px; font-size: .9rem; }
  input[type=text], input[type=email], input[type=password], input[type=number], input[type=date], select, textarea { width: 100%; padding: 10px 13px; border: 1px solid #c3d0da; border-radius: 12px; font-size: .94rem; font-family: inherit; background: #fff; color: #26394f; }
  input:focus, select:focus, textarea:focus { outline: 2px solid #17375a; outline-offset: 0; border-color: #17375a; }
  .alert { border-radius: 14px; padding: 11px 15px; margin-bottom: 16px; font-size: .92rem; }
  .alert-ok { background: #e8f3ec; color: #175c33; }
  .alert-err { background: #f9e9e7; color: #86211a; }
  .muted { color: #71879c; }
  .filterbar { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; margin-bottom: 18px; background: #edf2f5; border-radius: 999px; padding: 6px; }
  .filterbar input[type=text] { flex: 1; min-width: 210px; width: auto; border: 0; background: transparent; padding: 8px 14px; }
  .filterbar input[type=text]:focus { outline: none; }
  .filterbar select { width: auto; border-radius: 999px; border: 0; background: #fff; padding: 8px 12px; }
  nav.pager { margin-top: 14px; font-size: .9rem; }
  nav.pager a, nav.pager span { margin-right: 6px; }
  .auth { max-width: 420px; margin: 26px auto; }
  .auth h1 { font-size: 1.35rem; margin: 0 0 4px; }
  footer.site { padding: 4px 30px 24px; color: #93a7ba; font-size: .8rem; }
  @media (max-width: 860px) { .why { grid-template-columns: 1fr; } .dest-grid { grid-template-columns: repeat(2, 1fr); } .steps { grid-template-columns: 1fr; } .sec-head p { text-align: left; } }
  @media (max-width: 640px) { .stat { border-left: 0; border-top: 1px solid #e6edf2; } .stat:first-child { border-top: 0; } .wrap { padding: 14px 18px 26px; } .topbar-in { padding: 14px 18px 4px; } }
</style>
</head>
<body>
<div class="panel">
<header>
  <div class="topbar-in">
    <a class="brand" href="{{ route('dashboard') }}"><b>PERPUS<i>SMP</i></b><small>Perpustakaan Sekolah</small></a>
    @auth
    <nav class="main">
      <a href="{{ route('dashboard') }}">Home</a>
      <a href="{{ route('books.index') }}">Katalog Buku</a>
      <a href="{{ route('loans.index') }}">Peminjaman</a>
      @if(auth()->user()->isAdmin())
      <a href="{{ route('loans.create') }}">Catat Peminjaman</a>
      <a href="{{ route('books.create') }}">Tambah Buku</a>
      <a href="{{ route('admin.categories.index') }}">Kategori</a>
      <a href="{{ route('admin.users.index') }}">User</a>
      <a href="{{ route('admin.reports.index') }}">Laporan</a>
      @endif
    </nav>
    <span class="who"><b>{{ auth()->user()->name }}</b> · {{ auth()->user()->isAdmin() ? 'Admin' : 'Siswa'.(auth()->user()->kelas ? ' '.auth()->user()->kelas : '') }}</span>
    <form method="POST" action="{{ route('logout') }}" style="margin:0">@csrf<button class="logoutbtn" type="submit">Keluar</button></form>
    @endauth
  </div>
</header>

<main class="wrap">
  @if(session('success'))<div class="alert alert-ok">{{ session('success') }}</div>@endif
  @if($errors->any())
    <div class="alert alert-err"><strong>Periksa lagi:</strong><ul style="margin:6px 0 0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif

  @yield('content')
</main>
<footer class="site">PerpusSMP — Sistem Informasi Perpustakaan SMP. Buku yang dipinjam wajib dikembalikan paling lambat 7 hari; keterlambatan dikenakan denda Rp500 per hari.</footer>
</div>
</body>
</html>
