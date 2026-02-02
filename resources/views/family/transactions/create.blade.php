@extends('layouts.app')

@section('content')
<h5>Tambah Transaksi Keluarga (Multi-Input)</h5>
<form method="POST" action="{{ route('family.transactions.store') }}">
@csrf

<div class="table-responsive">
    <table class="table table-bordered align-middle" id="tx-table">
        <thead class="table-light">
            <tr>
                <th>Tanggal</th>
                <th>Tipe</th>
                <th>Kategori</th>
                <th>Anggota</th>
                <th>Jumlah</th>
                <th>Catatan</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input type="date" name="transactions[0][tanggal]" class="form-control" required></td>
                <td>
                    <select name="transactions[0][type]" class="form-select tx-type" required>
                        <option value="income">Pemasukan</option>
                        <option value="expense_father">Pengeluaran Ayah</option>
                        <option value="expense_mother">Pengeluaran Ibu</option>
                        <option value="expense_total">Total Semua Pengeluaran</option>
                    </select>
                </td>
                <td>
                    <select name="transactions[0][category_id]" class="form-select" required>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->type }})</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select name="transactions[0][member_name]" class="form-select tx-member" disabled>
                        <option value="">-</option>
                        <option value="Ayah">Ayah</option>
                        <option value="Ibu">Ibu</option>
                        <option value="Umum">Umum</option>
                    </select>
                </td>
                <td><input type="number" name="transactions[0][amount]" class="form-control" step="0.01" required></td>
                <td><input type="text" name="transactions[0][note]" class="form-control" placeholder="Contoh: uang sekolah"></td>
                <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm remove-row">X</button></td>
            </tr>
        </tbody>
    </table>
</div>

<div class="d-flex gap-2">
    <button type="button" class="btn btn-outline-primary" id="add-row">Tambah Baris</button>
    <button class="btn btn-primary">Simpan Semua</button>
</div>
</form>
@endsection

@push('scripts')
<script>
let rowIndex = 1;
document.getElementById('add-row').addEventListener('click', () => {
    const tbody = document.querySelector('#tx-table tbody');
    const row = document.createElement('tr');
    row.innerHTML = `
        <td><input type="date" name="transactions[${rowIndex}][tanggal]" class="form-control" required></td>
        <td>
            <select name="transactions[${rowIndex}][type]" class="form-select tx-type" required>
                <option value="income">Pemasukan</option>
                <option value="expense_father">Pengeluaran Ayah</option>
                <option value="expense_mother">Pengeluaran Ibu</option>
                <option value="expense_total">Total Semua Pengeluaran</option>
            </select>
        </td>
        <td>
            <select name="transactions[${rowIndex}][category_id]" class="form-select" required>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->type }})</option>
                @endforeach
            </select>
        </td>
        <td>
            <select name="transactions[${rowIndex}][member_name]" class="form-select tx-member" disabled>
                <option value="">-</option>
                <option value="Ayah">Ayah</option>
                <option value="Ibu">Ibu</option>
                <option value="Umum">Umum</option>
            </select>
        </td>
        <td><input type="number" name="transactions[${rowIndex}][amount]" class="form-control" step="0.01" required></td>
        <td><input type="text" name="transactions[${rowIndex}][note]" class="form-control" placeholder="Contoh: uang sekolah"></td>
        <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm remove-row">X</button></td>
    `;
    tbody.appendChild(row);
    rowIndex++;
});
document.addEventListener('click', (e) => {
    if (e.target.classList.contains('remove-row')) {
        const row = e.target.closest('tr');
        if (document.querySelectorAll('#tx-table tbody tr').length > 1) {
            row.remove();
        }
    }
});

function syncTypeToMember(row) {
    const type = row.querySelector('.tx-type')?.value;
    const member = row.querySelector('.tx-member');
    if (!member) return;
    if (type === 'expense_father') {
        member.value = 'Ayah';
        member.setAttribute('disabled', 'disabled');
    } else if (type === 'expense_mother') {
        member.value = 'Ibu';
        member.setAttribute('disabled', 'disabled');
    } else if (type === 'expense_total') {
        member.value = 'Umum';
        member.setAttribute('disabled', 'disabled');
    } else {
        member.value = '';
        member.setAttribute('disabled', 'disabled');
    }
}

document.addEventListener('change', (e) => {
    if (e.target.classList.contains('tx-type')) {
        syncTypeToMember(e.target.closest('tr'));
    }
});

// initialize first row
syncTypeToMember(document.querySelector('#tx-table tbody tr'));
</script>
@endpush
