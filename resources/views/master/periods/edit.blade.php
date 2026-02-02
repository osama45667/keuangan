@extends('layouts.app')

@section('content')
<h5>Edit Periode</h5>
<form method="POST" action="{{ route('periods.update', $period) }}">
@csrf @method('PUT')
<div class="row g-2">
    <div class="col-md-2">
        <label class="form-label">Bulan</label>
        <input type="number" name="bulan" value="{{ $period->bulan }}" class="form-control" min="1" max="12" required>
    </div>
    <div class="col-md-2">
        <label class="form-label">Tahun</label>
        <input type="number" name="tahun" value="{{ $period->tahun }}" class="form-control" min="2000" max="2100" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Start</label>
        <input type="date" name="start_date" value="{{ $period->start_date }}" class="form-control" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">End</label>
        <input type="date" name="end_date" value="{{ $period->end_date }}" class="form-control" required>
    </div>
    <div class="col-md-2">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="open" @selected($period->status === 'open')>Open</option>
            <option value="closed" @selected($period->status === 'closed')>Closed</option>
        </select>
    </div>
</div>
<button class="btn btn-primary mt-3">Simpan</button>
</form>
@endsection
