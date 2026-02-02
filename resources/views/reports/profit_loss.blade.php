@extends('layouts.app')
@section('content')
<h5>Laba Rugi</h5>
<form class="row g-2 mb-3">
    <div class="col-md-3"><input type="date" name="start" value="{{ $start }}" class="form-control"></div>
    <div class="col-md-3"><input type="date" name="end" value="{{ $end }}" class="form-control"></div>
    <div class="col-md-6 text-end">
        <button class="btn btn-primary">Filter</button>
        <a class="btn btn-outline-danger" href="{{ route('reports.export',['type'=>'profit-loss','format'=>'pdf','start'=>$start,'end'=>$end]) }}">PDF</a>
        <a class="btn btn-outline-success" href="{{ route('reports.export',['type'=>'profit-loss','format'=>'excel','start'=>$start,'end'=>$end]) }}">Excel</a>
    </div>
</form>

<h6>Pendapatan</h6>
<table class="table table-striped">
    <thead><tr><th>Akun</th><th>Jumlah</th></tr></thead>
    <tbody>
        @foreach($revenue['rows'] as $r)
            <tr><td>{{ $r->nama_akun }}</td><td class="text-end">{{ formatRupiah($r->total_kredit - $r->total_debit) }}</td></tr>
        @endforeach
    </tbody>
</table>

<h6>Beban</h6>
<table class="table table-striped">
    <thead><tr><th>Akun</th><th>Jumlah</th></tr></thead>
    <tbody>
        @foreach($expense['rows'] as $r)
            <tr><td>{{ $r->nama_akun }}</td><td class="text-end">{{ formatRupiah($r->total_debit - $r->total_kredit) }}</td></tr>
        @endforeach
    </tbody>
</table>

<div class="fw-bold">Laba Bersih: {{ formatRupiah($net) }}</div>
@endsection
