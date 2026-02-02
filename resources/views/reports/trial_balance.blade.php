@extends('layouts.app')
@section('content')
<h5>Neraca Saldo</h5>
<form class="row g-2 mb-3">
    <div class="col-md-3"><input type="date" name="start" value="{{ $start }}" class="form-control"></div>
    <div class="col-md-3"><input type="date" name="end" value="{{ $end }}" class="form-control"></div>
    <div class="col-md-6 text-end">
        <button class="btn btn-primary">Filter</button>
        <a class="btn btn-outline-danger" href="{{ route('reports.export',['type'=>'trial-balance','format'=>'pdf','start'=>$start,'end'=>$end]) }}">PDF</a>
        <a class="btn btn-outline-success" href="{{ route('reports.export',['type'=>'trial-balance','format'=>'excel','start'=>$start,'end'=>$end]) }}">Excel</a>
    </div>
</form>
<table class="table table-striped">
    <thead><tr><th>Kode</th><th>Akun</th><th>Debit</th><th>Kredit</th></tr></thead>
    <tbody>
        @foreach($rows as $r)
            <tr>
                <td>{{ $r->kode_akun }}</td>
                <td>{{ $r->nama_akun }}</td>
                <td class="text-end">{{ formatRupiah($r->total_debit) }}</td>
                <td class="text-end">{{ formatRupiah($r->total_kredit) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
