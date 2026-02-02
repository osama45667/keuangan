@extends('layouts.app')
@section('content')
<h5>Buku Besar</h5>
<form class="row g-2 mb-3">
    <div class="col-md-4">
        <select name="account_id" class="form-select">
            <option value="">Pilih Akun</option>
            @foreach($accounts as $acc)
                <option value="{{ $acc->id }}" @selected($accountId == $acc->id)>{{ $acc->kode_akun }} - {{ $acc->nama_akun }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3"><input type="date" name="start" value="{{ $start }}" class="form-control"></div>
    <div class="col-md-3"><input type="date" name="end" value="{{ $end }}" class="form-control"></div>
    <div class="col-md-2 text-end">
        <button class="btn btn-primary">Filter</button>
    </div>
</form>

<div class="mb-3">
    <a class="btn btn-outline-danger" href="{{ route('reports.export',['type'=>'ledger','format'=>'pdf','account_id'=>$accountId,'start'=>$start,'end'=>$end]) }}">PDF</a>
    <a class="btn btn-outline-success" href="{{ route('reports.export',['type'=>'ledger','format'=>'excel','account_id'=>$accountId,'start'=>$start,'end'=>$end]) }}">Excel</a>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Nomor Jurnal</th>
            <th>Debit</th>
            <th>Kredit</th>
            <th>Memo</th>
            <th>Saldo</th>
        </tr>
    </thead>
    <tbody>
        @if($ledger)
            <tr>
                <td colspan="5"><strong>Saldo Awal</strong></td>
                <td class="text-end"><strong>{{ formatRupiah($ledger['opening']) }}</strong></td>
            </tr>
            @foreach($ledger['lines'] as $line)
                <tr>
                    <td>{{ formatTanggalID($line->journal->tanggal) }}</td>
                    <td>{{ $line->journal->nomor_jurnal }}</td>
                    <td class="text-end">{{ formatRupiah($line->debit) }}</td>
                    <td class="text-end">{{ formatRupiah($line->kredit) }}</td>
                    <td>{{ $line->memo }}</td>
                    <td class="text-end">{{ formatRupiah($line->running_balance) }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
@endsection
