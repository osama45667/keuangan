@extends('layouts.app')

@section('content')
<h5>Tambah Periode</h5>
<form method="POST" action="{{ route('periods.store') }}">
@csrf
<div class="row g-2">
    <div class="col-md-2">
        <label class="form-label">Bulan</label>
        <input type="number" name="bulan" class="form-control" min="1" max="12" required>
    </div>
    <div class="col-md-2">
        <label class="form-label">Tahun</label>
        <input type="number" name="tahun" class="form-control" min="2000" max="2100" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Start</label>
        <input type="date" name="start_date" class="form-control" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">End</label>
        <input type="date" name="end_date" class="form-control" required>
    </div>
    <div class="col-md-2">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="open">Open</option>
            <option value="closed">Closed</option>
        </select>
    </div>
</div>
<button class="btn btn-primary mt-3">Simpan</button>
</form>
@endsection
