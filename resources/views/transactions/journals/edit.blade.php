@extends('layouts.app')
@section('content')
<h5>Edit Jurnal Umum</h5>
<form method="POST" action="{{ route('journals.update', $journal) }}" enctype="multipart/form-data">
@csrf @method('PUT')
<div class="row g-2 mb-3">
    <div class="col-md-3">
        <input type="date" name="tanggal" value="{{ $journal->tanggal }}" class="form-control" required>
    </div>
    <div class="col-md-3">
        <select name="period_id" class="form-select" required>
            @foreach($periods as $p)
                <option value="{{ $p->id }}" @selected($journal->period_id === $p->id)>{{ $p->bulan }}/{{ $p->tahun }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <input type="text" name="reference_no" value="{{ $journal->reference_no }}" class="form-control" placeholder="Ref No">
    </div>
    <div class="col-md-3">
        <input type="text" name="deskripsi" value="{{ $journal->deskripsi }}" class="form-control" placeholder="Deskripsi">
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
        @foreach($journal->lines as $i => $line)
        <tr>
            <td>
                <select name="lines[{{ $i }}][account_id]" class="form-select" required>
                    @foreach($accounts as $acc)
                        <option value="{{ $acc->id }}" @selected($line->account_id === $acc->id)>{{ $acc->kode_akun }} - {{ $acc->nama_akun }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" name="lines[{{ $i }}][debit]" value="{{ $line->debit }}" class="form-control" step="0.01"></td>
            <td><input type="number" name="lines[{{ $i }}][kredit]" value="{{ $line->kredit }}" class="form-control" step="0.01"></td>
            <td><input type="text" name="lines[{{ $i }}][memo]" value="{{ $line->memo }}" class="form-control"></td>
            <td><button type="button" class="btn btn-danger btn-sm remove-row">X</button></td>
        </tr>
        @endforeach
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
let idx = {{ $journal->lines->count() }};
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
