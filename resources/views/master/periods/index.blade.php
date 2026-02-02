@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h5>Periode Akuntansi</h5>
    <a class="btn btn-primary" href="{{ route('periods.create') }}">Tambah</a>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Bulan</th>
            <th>Tahun</th>
            <th>Start</th>
            <th>End</th>
            <th>Status</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($periods as $p)
            <tr>
                <td>{{ $p->bulan }}</td>
                <td>{{ $p->tahun }}</td>
                <td>{{ formatTanggalID($p->start_date) }}</td>
                <td>{{ formatTanggalID($p->end_date) }}</td>
                <td>{{ $p->status }}</td>
                <td class="text-end">
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('periods.edit', $p) }}">Edit</a>
                    <form method="POST" action="{{ route('periods.destroy', $p) }}" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $periods->links() }}
@endsection
