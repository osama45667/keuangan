@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h5>Kategori Keluarga</h5>
    <a class="btn btn-primary" href="{{ route('family.categories.create') }}">Tambah</a>
</div>

<form class="row g-2 mb-3">
    <div class="col-md-4">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari kategori">
    </div>
    <div class="col-md-2">
        <button class="btn btn-outline-secondary">Cari</button>
    </div>
</form>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Tipe</th>
            <th>Aktif</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($categories as $c)
            <tr>
                <td>{{ $c->name }}</td>
                <td>{{ $c->type }}</td>
                <td>{{ $c->is_active ? 'Ya' : 'Tidak' }}</td>
                <td class="text-end">
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('family.categories.edit', $c) }}">Edit</a>
                    <form method="POST" action="{{ route('family.categories.destroy', $c) }}" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $categories->links() }}
@endsection
