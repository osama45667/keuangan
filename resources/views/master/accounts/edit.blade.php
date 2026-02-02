@extends('layouts.app')

@section('content')
<h5>Edit COA</h5>
<form method="POST" action="{{ route('accounts.update', $account) }}">
@csrf @method('PUT')
<div class="row g-2">
    <div class="col-md-3">
        <label class="form-label">Kode Akun</label>
        <input type="text" name="kode_akun" value="{{ $account->kode_akun }}" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Nama Akun</label>
        <input type="text" name="nama_akun" value="{{ $account->nama_akun }}" class="form-control" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Tipe</label>
        <select name="tipe" class="form-select" required>
            @foreach(['Asset','Liability','Equity','Revenue','Expense'] as $t)
                <option value="{{ $t }}" @selected($account->tipe === $t)>{{ $t }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Parent</label>
        <select name="parent_id" class="form-select">
            <option value="">-</option>
            @foreach($parents as $p)
                <option value="{{ $p->id }}" @selected($account->parent_id === $p->id)>{{ $p->kode_akun }} - {{ $p->nama_akun }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Normal Balance</label>
        <select name="normal_balance" class="form-select">
            <option value="">-</option>
            <option value="D" @selected($account->normal_balance === 'D')>Debit</option>
            <option value="K" @selected($account->normal_balance === 'K')>Kredit</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Kas/Bank</label>
        <select name="is_cash_bank" class="form-select">
            <option value="0" @selected(!$account->is_cash_bank)>Tidak</option>
            <option value="1" @selected($account->is_cash_bank)>Ya</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Aktif</label>
        <select name="is_active" class="form-select">
            <option value="1" @selected($account->is_active)>Ya</option>
            <option value="0" @selected(!$account->is_active)>Tidak</option>
        </select>
    </div>
</div>
<button class="btn btn-primary mt-3">Simpan</button>
</form>
@endsection
