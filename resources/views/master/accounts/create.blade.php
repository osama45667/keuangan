@extends('layouts.app')

@section('content')
<h5>Tambah COA</h5>
<form method="POST" action="{{ route('accounts.store') }}">
@csrf
<div class="row g-2">
    <div class="col-md-3">
        <label class="form-label">Kode Akun</label>
        <input type="text" name="kode_akun" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Nama Akun</label>
        <input type="text" name="nama_akun" class="form-control" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Tipe</label>
        <select name="tipe" class="form-select" required>
            <option>Asset</option>
            <option>Liability</option>
            <option>Equity</option>
            <option>Revenue</option>
            <option>Expense</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Parent</label>
        <select name="parent_id" class="form-select">
            <option value="">-</option>
            @foreach($parents as $p)
                <option value="{{ $p->id }}">{{ $p->kode_akun }} - {{ $p->nama_akun }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Normal Balance</label>
        <select name="normal_balance" class="form-select">
            <option value="">-</option>
            <option value="D">Debit</option>
            <option value="K">Kredit</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Kas/Bank</label>
        <select name="is_cash_bank" class="form-select">
            <option value="0">Tidak</option>
            <option value="1">Ya</option>
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
