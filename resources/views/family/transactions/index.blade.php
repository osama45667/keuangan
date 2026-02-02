@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h5>Transaksi Keluarga</h5>
    <a class="btn btn-primary" href="{{ route('family.transactions.create') }}">Tambah</a>
</div>

<form class="row g-2 mb-3">
    <div class="col-md-2">
        <select name="type" class="form-select">
            <option value="">Semua Tipe</option>
            <option value="income" @selected(request('type')==='income')>Pemasukan</option>
            <option value="expense" @selected(request('type')==='expense')>Pengeluaran</option>
        </select>
    </div>
    <div class="col-md-3">
        <select name="category_id" class="form-select">
            <option value="">Semua Kategori</option>
            @foreach($categories as $c)
                <option value="{{ $c->id }}" @selected(request('category_id')==$c->id)>{{ $c->name }} ({{ $c->type }})</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <select name="member_name" class="form-select">
            <option value="">Semua Anggota</option>
            <option value="Ayah" @selected(request('member_name')==='Ayah')>Ayah</option>
            <option value="Ibu" @selected(request('member_name')==='Ibu')>Ibu</option>
            <option value="Anak" @selected(request('member_name')==='Anak')>Anak</option>
            <option value="Umum" @selected(request('member_name')==='Umum')>Umum</option>
        </select>
    </div>
    <div class="col-md-2">
        <input type="date" name="start" value="{{ request('start') }}" class="form-control">
    </div>
    <div class="col-md-2">
        <input type="date" name="end" value="{{ request('end') }}" class="form-control">
    </div>
    <div class="col-md-1">
        <button class="btn btn-outline-secondary w-100">Cari</button>
    </div>
</form>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Tipe</th>
            <th>Kategori</th>
            <th>Anggota</th>
            <th>Jumlah</th>
            <th>Catatan</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($transactions as $t)
            <tr>
                <td>{{ formatTanggalID($t->tanggal) }}</td>
                <td>{{ $t->type }}</td>
                <td>{{ $t->category?->name }}</td>
                <td>{{ $t->member_name }}</td>
                <td class="text-end">{{ formatRupiah($t->amount) }}</td>
                <td>{{ $t->note }}</td>
                <td class="text-end">
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('family.transactions.edit', $t) }}">Edit</a>
                    <form method="POST" action="{{ route('family.transactions.destroy', $t) }}" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $transactions->links() }}
@endsection
