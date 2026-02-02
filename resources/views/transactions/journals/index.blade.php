@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h5>Jurnal Umum</h5>
    <a class="btn btn-primary" href="{{ route('journals.create') }}">Tambah</a>
</div>

<form class="row g-2 mb-3">
    <div class="col-md-3">
        <select name="period_id" class="form-select">
            <option value="">Semua Periode</option>
            @foreach($periods as $p)
                <option value="{{ $p->id }}" @selected(request('period_id') == $p->id)>{{ $p->bulan }}/{{ $p->tahun }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <input type="date" name="start" value="{{ request('start') }}" class="form-control">
    </div>
    <div class="col-md-2">
        <input type="date" name="end" value="{{ request('end') }}" class="form-control">
    </div>
    <div class="col-md-3">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nomor jurnal">
    </div>
    <div class="col-md-2">
        <button class="btn btn-outline-secondary">Filter</button>
    </div>
</form>

<div class="mb-3">
    <a class="btn btn-outline-danger" href="{{ route('reports.export',['type'=>'journal','format'=>'pdf','start'=>request('start'),'end'=>request('end')]) }}">Export PDF</a>
    <a class="btn btn-outline-success" href="{{ route('reports.export',['type'=>'journal','format'=>'excel','start'=>request('start'),'end'=>request('end')]) }}">Export Excel</a>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Nomor</th>
            <th>Deskripsi</th>
            <th>Periode</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($journals as $j)
            <tr>
                <td>{{ formatTanggalID($j->tanggal) }}</td>
                <td>{{ $j->nomor_jurnal }}</td>
                <td>{{ $j->deskripsi }}</td>
                <td>{{ $j->period?->bulan }}/{{ $j->period?->tahun }}</td>
                <td class="text-end">
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('journals.show', $j) }}">Lihat</a>
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('journals.edit', $j) }}">Edit</a>
                    <form method="POST" action="{{ route('journals.destroy', $j) }}" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $journals->links() }}
@endsection
