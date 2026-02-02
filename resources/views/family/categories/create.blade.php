@extends('layouts.app')

@section('content')
<h5>Tambah Kategori</h5>
<form method="POST" action="{{ route('family.categories.store') }}">
@csrf
<div class="row g-2">
    <div class="col-md-6">
        <label class="form-label">Nama</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Tipe</label>
        <select name="type" class="form-select" required>
            <option value="income">Pemasukan</option>
            <option value="expense">Pengeluaran</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Aktif</label>
        <select name="is_active" class="form-select">
            <option value="1">Ya</option>
            <option value="0">Tidak</option>
        </select>
    </div>
</div>
<button class="btn btn-primary mt-3">Simpan</button>
</form>
@endsection
