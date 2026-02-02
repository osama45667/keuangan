@extends('layouts.app')

@section('content')
<h5>Edit Transaksi Keluarga</h5>
<form method="POST" action="{{ route('family.transactions.update', $transaction) }}">
@csrf @method('PUT')
<div class="row g-2">
    <div class="col-md-3">
        <label class="form-label">Tanggal</label>
        <input type="date" name="tanggal" value="{{ $transaction->tanggal }}" class="form-control" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Tipe</label>
        <select name="type" class="form-select" required>
            <option value="income" @selected($transaction->type==='income')>Pemasukan</option>
            <option value="expense" @selected($transaction->type==='expense')>Pengeluaran</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Kategori</label>
        <select name="category_id" class="form-select" required>
            @foreach($categories as $c)
                <option value="{{ $c->id }}" @selected($transaction->category_id===$c->id)>{{ $c->name }} ({{ $c->type }})</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Anggota</label>
        <select name="member_name" class="form-select">
            <option value="" @selected(empty($transaction->member_name))>- (Pemasukan)</option>
            <option value="Ayah" @selected($transaction->member_name === 'Ayah')>Ayah</option>
            <option value="Ibu" @selected($transaction->member_name === 'Ibu')>Ibu</option>
            <option value="Anak" @selected($transaction->member_name === 'Anak')>Anak</option>
            <option value="Umum" @selected($transaction->member_name === 'Umum')>Umum</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Jumlah</label>
        <input type="number" name="amount" value="{{ $transaction->amount }}" class="form-control" step="0.01" required>
    </div>
    <div class="col-md-9">
        <label class="form-label">Catatan</label>
        <input type="text" name="note" value="{{ $transaction->note }}" class="form-control">
    </div>
</div>
<button class="btn btn-primary mt-3">Simpan</button>
</form>
@endsection
