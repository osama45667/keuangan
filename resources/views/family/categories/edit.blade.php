@extends('layouts.app')

@section('content')
<h5>Edit Kategori</h5>
<form method="POST" action="{{ route('family.categories.update', $category) }}">
@csrf @method('PUT')
<div class="row g-2">
    <div class="col-md-6">
        <label class="form-label">Nama</label>
        <input type="text" name="name" value="{{ $category->name }}" class="form-control" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Tipe</label>
        <select name="type" class="form-select" required>
            <option value="income" @selected($category->type === 'income')>Pemasukan</option>
            <option value="expense" @selected($category->type === 'expense')>Pengeluaran</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Aktif</label>
        <select name="is_active" class="form-select">
            <option value="1" @selected($category->is_active)>Ya</option>
            <option value="0" @selected(!$category->is_active)>Tidak</option>
        </select>
    </div>
</div>
<button class="btn btn-primary mt-3">Simpan</button>
</form>
@endsection
