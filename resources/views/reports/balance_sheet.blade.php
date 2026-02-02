@extends('layouts.app')
@section('content')
<h5>Neraca</h5>
<form class="row g-2 mb-3">
    <div class="col-md-3"><input type="date" name="end" value="{{ $end }}" class="form-control"></div>
    <div class="col-md-9 text-end">
        <button class="btn btn-primary">Filter</button>
        <a class="btn btn-outline-danger" href="{{ route('reports.export',['type'=>'balance-sheet','format'=>'pdf','end'=>$end]) }}">PDF</a>
        <a class="btn btn-outline-success" href="{{ route('reports.export',['type'=>'balance-sheet','format'=>'excel','end'=>$end]) }}">Excel</a>
    </div>
</form>

<div class="row">
    <div class="col-md-4">
        <h6>Assets</h6>
        <ul class="list-group mb-3">
            @foreach($assets['rows'] as $r)
                <li class="list-group-item d-flex justify-content-between">
                    <span>{{ $r->nama_akun }}</span>
                    <span>{{ formatRupiah($r->total_debit - $r->total_kredit) }}</span>
                </li>
            @endforeach
        </ul>
        <div class="fw-bold">Total Assets: {{ formatRupiah($assets['total']) }}</div>
    </div>
    <div class="col-md-4">
        <h6>Liabilities</h6>
        <ul class="list-group mb-3">
            @foreach($liabilities['rows'] as $r)
                <li class="list-group-item d-flex justify-content-between">
                    <span>{{ $r->nama_akun }}</span>
                    <span>{{ formatRupiah($r->total_kredit - $r->total_debit) }}</span>
                </li>
            @endforeach
        </ul>
        <div class="fw-bold">Total Liabilities: {{ formatRupiah($liabilities['total']) }}</div>
    </div>
    <div class="col-md-4">
        <h6>Equity</h6>
        <ul class="list-group mb-3">
            @foreach($equity['rows'] as $r)
                <li class="list-group-item d-flex justify-content-between">
                    <span>{{ $r->nama_akun }}</span>
                    <span>{{ formatRupiah($r->total_kredit - $r->total_debit) }}</span>
                </li>
            @endforeach
        </ul>
        <div class="fw-bold">Total Equity: {{ formatRupiah($equity['total']) }}</div>
    </div>
</div>
@endsection
