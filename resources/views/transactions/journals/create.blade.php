@extends('layouts.app')
@section('content')
<h5>Input Jurnal Umum</h5>
<form method="POST" action="{{ route('journals.store') }}" enctype="multipart/form-data">
@csrf
<div class="row g-2 mb-3">
    <div class="col-md-3">
        <input type="date" name="tanggal" class="form-control" required>
    </div>
    <div class="col-md-3">
        <select name="period_id" class="form-select" required>
            @foreach($periods as $p)
                <option value="{{ $p->id }}">{{ $p->bulan }}/{{ $p->tahun }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <input type="text" name="reference_no" class="form-control" placeholder="Ref No">
    </div>
    <div class="col-md-3">
        <input type="text" name="deskripsi" class="form-control" placeholder="Deskripsi">
    </div>
</div>

<table class="table table-bordered" id="lines-table">
    <thead>
        <tr>
            <th>Akun</th>
            <th>Debit</th>
            <th>Kredit</th>
            <th>Memo</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <select name="lines[0][account_id]" class="form-select" required>
                    @foreach($accounts as $acc)
                        <option value="{{ $acc->id }}">{{ $acc->kode_akun }} - {{ $acc->nama_akun }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" name="lines[0][debit]" class="form-control" step="0.01"></td>
            <td><input type="number" name="lines[0][kredit]" class="form-control" step="0.01"></td>
            <td><input type="text" name="lines[0][memo]" class="form-control"></td>
            <td><button type="button" class="btn btn-danger btn-sm remove-row">X</button></td>
        </tr>
    </tbody>
</table>
<button type="button" class="btn btn-outline-secondary mb-3" id="add-row">Tambah Baris</button>

<div class="mb-3">
    <label class="form-label">Lampiran</label>
    <input type="file" name="attachments[]" class="form-control" multiple>
</div>

<button class="btn btn-primary">Simpan</button>
</form>
@endsection

@push('scripts')
<script>
let idx = 1;
document.getElementById('add-row').addEventListener('click', () => {
    const tbody = document.querySelector('#lines-table tbody');
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>
            <select name="lines[${idx}][account_id]" class="form-select" required>
                @foreach($accounts as $acc)
                    <option value="{{ $acc->id }}">{{ $acc->kode_akun }} - {{ $acc->nama_akun }}</option>
                @endforeach
            </select>
        </td>
        <td><input type="number" name="lines[${idx}][debit]" class="form-control" step="0.01"></td>
        <td><input type="number" name="lines[${idx}][kredit]" class="form-control" step="0.01"></td>
        <td><input type="text" name="lines[${idx}][memo]" class="form-control"></td>
        <td><button type="button" class="btn btn-danger btn-sm remove-row">X</button></td>
    `;
    tbody.appendChild(row);
    idx++;
});
document.addEventListener('click', (e) => {
    if (e.target.classList.contains('remove-row')) {
        e.target.closest('tr').remove();
    }
});
</script>
@endpush
