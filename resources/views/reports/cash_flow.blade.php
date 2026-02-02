@extends('layouts.app')
@section('content')
<h5>Arus Kas (Metode Tidak Langsung)</h5>
<form class="row g-2 mb-3">
    <div class="col-md-3"><input type="date" name="start" value="{{ $start }}" class="form-control"></div>
    <div class="col-md-3"><input type="date" name="end" value="{{ $end }}" class="form-control"></div>
    <div class="col-md-6 text-end">
        <button class="btn btn-primary">Filter</button>
        <a class="btn btn-outline-danger" href="{{ route('reports.export',['type'=>'cash-flow','format'=>'pdf','start'=>$start,'end'=>$end]) }}">PDF</a>
        <a class="btn btn-outline-success" href="{{ route('reports.export',['type'=>'cash-flow','format'=>'excel','start'=>$start,'end'=>$end]) }}">Excel</a>
    </div>
</form>

<table class="table table-striped">
    <tr><th>Laba Bersih</th><td class="text-end">{{ formatRupiah($net_income) }}</td></tr>
    <tr><th>Perubahan Kas (Net)</th><td class="text-end">{{ formatRupiah($cash_change) }}</td></tr>
</table>
<div class="text-muted">{{ $assumptions }}</div>
@endsection
