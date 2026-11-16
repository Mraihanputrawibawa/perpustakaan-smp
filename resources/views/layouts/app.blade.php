<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $title ?? 'PerpusSMP' }} — PerpusSMP</title>
<style>
  * { box-sizing: border-box; }
  body { font-family: -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; margin: 0; background: #f4f4f2; color: #26292e; font-size: 15px; line-height: 1.55; }
  a { color: #175c33; }
  h1, h2, h3, .serif { font-family: Georgia, 'Times New Roman', serif; font-weight: 600; color: #1c1f23; }

  .topbar { background: #fff; border-bottom: 1px solid #dcdcdc; }
  .topbar-in { max-width: 1020px; margin: 0 auto; padding: 0 20px; display: flex; align-items: center; gap: 26px; min-height: 58px; flex-wrap: wrap; }
  .brand { text-decoration: none; color: #1c1f23; line-height: 1.15; }
  .brand b { font-family: Georgia, 'Times New Roman', serif; font-size: 1.18rem; letter-spacing: .2px; }
  .brand small { display: block; color: #767c84; font-size: .72rem; letter-spacing: .4px; text-transform: uppercase; }
  nav.main { display: flex; gap: 2px; flex-wrap: wrap; flex: 1; }
  nav.main a { color: #3c4249; text-decoration: none; font-size: .92rem; padding: 6px 10px; border-radius: 4px; }
  nav.main a:hover { background: #f0efe9; color: #1c1f23; }
  .who { font-size: .85rem; color: #767c84; white-space: nowrap; }
  .who b { color: #26292e; }
  .logoutbtn { background: none; border: 1px solid #c9c9c9; color: #3c4249; border-radius: 4px; padding: 5px 12px; font-size: .85rem; cursor: pointer; font-family: inherit; }
  .logoutbtn:hover { background: #f0efe9; }

  .wrap { max-width: 1020px; margin: 0 auto; padding: 26px 20px 40px; }
  .pagehead { margin-bottom: 18px; }
  .pagehead h1 { font-size: 1.55rem; margin: 0 0 2px; }
  .pagehead p { margin: 0; color: #767c84; font-size: .92rem; }

  .card { background: #fff; border: 1px solid #ddd; border-radius: 6px; padding: 20px 22px; margin-bottom: 18px; }
  .card h2 { font-size: 1.12rem; margin: 0 0 12px; }

  button, .btn { display: inline-block; background: #175c33; color: #fff; border: 1px solid #175c33; padding: 8px 16px; border-radius: 4px; font-size: .9rem; font-family: inherit; cursor: pointer; text-decoration: none; }
  button:hover, .btn:hover { background: #114527; }
  .btn-secondary { background: #fff; color: #26292e; border: 1px solid #c9c9c9; }
  .btn-secondary:hover { background: #f0efe9; }
  .btn-danger { background: #a4271f; border-color: #a4271f; }
  .btn-danger:hover { background: #86211a; }
  .btn-sm { padding: 4px 10px; font-size: .83rem; }

  .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); background: #fff; border: 1px solid #ddd; border-radius: 6px; margin-bottom: 18px; overflow: hidden; }
  .stat { padding: 16px 18px; border-left: 1px solid #e7e7e7; }
  .stat:first-child { border-left: 0; }
  .stat b { display: block; font-size: 1.5rem; font-family: Georgia, serif; font-weight: 600; color: #1c1f23; }
  .stat span { color: #767c84; font-size: .83rem; }

  table { width: 100%; border-collapse: collapse; }
  th, td { text-align: left; padding: 9px 10px; border-bottom: 1px solid #e7e7e7; font-size: .93rem; vertical-align: top; }
  th { font-size: .8rem; text-transform: uppercase; letter-spacing: .5px; color: #767c84; font-weight: 600; border-bottom: 1px solid #d5d5d5; }
  tr:last-child td { border-bottom: 0; }
  tbody tr:hover { background: #fafaf7; }

  .badge { display: inline-block; padding: 1px 8px; border: 1px solid #c9c9c9; border-radius: 3px; font-size: .78rem; color: #3c4249; background: #f7f7f4; white-space: nowrap; }
  .badge-late { border-color: #d9a49f; color: #a4271f; background: #fbf1f0; }
  .badge-back { border-color: #a9c6b2; color: #175c33; background: #f1f7f2; }

  label { display: block; font-weight: 600; margin: 13px 0 5px; font-size: .9rem; }
  input[type=text], input[type=email], input[type=password], input[type=number], input[type=date], select, textarea { width: 100%; padding: 8px 10px; border: 1px solid #c4c4c4; border-radius: 4px; font-size: .94rem; font-family: inherit; background: #fff; }
  input:focus, select:focus, textarea:focus { outline: 2px solid #175c33; outline-offset: 0; border-color: #175c33; }
  .alert { border: 1px solid; border-radius: 4px; padding: 10px 14px; margin-bottom: 16px; font-size: .92rem; }
  .alert-ok { background: #f1f7f2; border-color: #a9c6b2; color: #175c33; }
  .alert-err { background: #fbf1f0; border-color: #d9a49f; color: #86211a; }
  .muted { color: #767c84; }
  .filterbar { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; margin-bottom: 16px; }
  .filterbar input[type=text] { flex: 1; min-width: 210px; width: auto; }
  .filterbar select { width: auto; }
  nav.pager { margin-top: 14px; font-size: .9rem; }
  nav.pager a, nav.pager span { margin-right: 6px; }
  .auth { max-width: 400px; margin: 34px auto; }
  .auth h1 { font-size: 1.3rem; margin: 0 0 4px; }
  footer.site { max-width: 1020px; margin: 0 auto; padding: 0 20px 26px; color: #9a9fa6; font-size: .8rem; }
  @media (max-width: 640px) { .stat { border-left: 0; border-top: 1px solid #e7e7e7; } .stat:first-child { border-top: 0; } }
</style>
</head>
<body>
<header class="topbar">
  <div class="topbar-in">
    <a class="brand" href="{{ route('dashboard') }}"><b>PerpusSMP</b><small>Perpustakaan Sekolah</small></a>
    @auth
    <nav class="main">
      <a href="{{ route('dashboard') }}">Dashboard</a>
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
</body>
</html>
