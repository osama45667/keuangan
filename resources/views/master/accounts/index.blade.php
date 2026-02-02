@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h5>Chart of Accounts</h5>
    <a class="btn btn-primary" href="{{ route('accounts.create') }}">Tambah</a>
</div>

<form class="row g-2 mb-3">
    <div class="col-md-4">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari kode/nama akun">
    </div>
    <div class="col-md-2">
        <button class="btn btn-outline-secondary">Cari</button>
    </div>
</form>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Kode</th>
            <th>Nama</th>
            <th>Tipe</th>
            <th>Aktif</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($accounts as $acc)
            <tr>
                <td>{{ $acc->kode_akun }}</td>
                <td>{{ $acc->nama_akun }}</td>
                <td>{{ $acc->tipe }}</td>
                <td>{{ $acc->is_active ? 'Ya' : 'Tidak' }}</td>
                <td class="text-end">
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('accounts.edit', $acc) }}">Edit</a>
                    <form method="POST" action="{{ route('accounts.destroy', $acc) }}" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $accounts->links() }}
@endsection
