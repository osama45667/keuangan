@extends('layouts.app')
@section('content')
<h5>Detail Jurnal</h5>
<div class="mb-3">
    <div><strong>Nomor:</strong> {{ $journal->nomor_jurnal }}</div>
    <div><strong>Tanggal:</strong> {{ formatTanggalID($journal->tanggal) }}</div>
    <div><strong>Periode:</strong> {{ $journal->period?->bulan }}/{{ $journal->period?->tahun }}</div>
    <div><strong>Deskripsi:</strong> {{ $journal->deskripsi }}</div>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Akun</th>
            <th>Debit</th>
            <th>Kredit</th>
            <th>Memo</th>
        </tr>
    </thead>
    <tbody>
        @foreach($journal->lines as $line)
            <tr>
                <td>{{ $line->account?->kode_akun }} - {{ $line->account?->nama_akun }}</td>
                <td class="text-end">{{ formatRupiah($line->debit) }}</td>
                <td class="text-end">{{ formatRupiah($line->kredit) }}</td>
                <td>{{ $line->memo }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
