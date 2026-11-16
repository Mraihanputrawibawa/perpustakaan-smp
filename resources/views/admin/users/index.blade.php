@extends('layouts.app')
@section('content')
<h2>Kelola User</h2>
<div class="card">
  <table><tr><th>Nama</th><th>Email</th><th>Peran</th><th>Aksi</th></tr>
  @foreach($users as $u)
    <tr>
      <td>{{ $u->name }}</td><td>{{ $u->email }}</td>
      <td>
        <form method="POST" action="{{ route('admin.users.update', $u) }}" style="display:flex;gap:6px">
          @csrf @method('PUT')
          <select name="role" style="margin:0">
            @foreach(['siswa','petugas','admin'] as $r)<option value="{{ $r }}" @selected($u->role===$r)>{{ $r }}</option>@endforeach
          </select>
          <button type="submit">Simpan</button>
        </form>
      </td>
      <td>
        @if($u->id !== auth()->id())
        <form method="POST" action="{{ route('admin.users.destroy', $u) }}" onsubmit="return confirm('Hapus user ini?')">
          @csrf @method('DELETE') <button class="btn-red" type="submit">Hapus</button>
        </form>
        @endif
      </td>
    </tr>
  @endforeach
  </table>
  <div style="margin-top:12px">{{ $users->links() }}</div>
</div>
@endsection
