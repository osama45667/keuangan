@extends('layouts.app')

@section('content')
<div class="row g-3 mt-1">
    <div class="col-md-4">
        <div class="card p-3 border-success">
            <div class="text-muted">Pemasukan Keluarga (Bulan ini)</div>
            <div class="fs-4 text-success">{{ formatRupiah($family_income) }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 border-danger">
            <div class="text-muted">Pengeluaran Keluarga (Bulan ini)</div>
            <div class="fs-4 text-danger">{{ formatRupiah($family_expense) }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 border-primary">
            <div class="text-muted">Saldo Bersih Keluarga</div>
            <div class="fs-4 text-primary">{{ formatRupiah($family_net) }}</div>
        </div>
    </div>
</div>

<div class="row g-3 mt-1">
    <div class="col-md-6">
        <div class="card p-3 border-danger">
            <div class="text-muted">Pengeluaran Ayah (Bulan ini)</div>
            <div class="fs-4 text-danger">{{ formatRupiah($family_expense_ayah) }}</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-3 border-danger">
            <div class="text-muted">Pengeluaran Ibu (Bulan ini)</div>
            <div class="fs-4 text-danger">{{ formatRupiah($family_expense_ibu) }}</div>
        </div>
    </div>
</div>

<div class="card mt-3 p-3">
    <h6>Grafik Pemasukan vs Pengeluaran Keluarga (12 Bulan)</h6>
    <canvas id="chartFamily" height="120"></canvas>
</div>

<div class="card mt-3 p-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="mb-0">Transaksi Keluarga Terbaru</h6>
        <a class="btn btn-sm btn-outline-primary" href="{{ route('family.transactions.index') }}">Lihat Semua</a>
    </div>
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Tipe</th>
                    <th>Kategori</th>
                    <th>Anggota</th>
                    <th>Jumlah</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($family_recent as $t)
                    <tr>
                        <td>{{ formatTanggalID($t->tanggal) }}</td>
                        <td>{{ $t->type }}</td>
                        <td>{{ $t->category?->name }}</td>
                        <td>{{ $t->member_name }}</td>
                        <td class="text-end">{{ formatRupiah($t->amount) }}</td>
                        <td>{{ $t->note }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada transaksi keluarga. Mulai input di menu Transaksi Keluarga.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('chartFamily');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($labels),
        datasets: [
            {
                label: 'Pemasukan',
                data: @json($incomeData),
                borderColor: '#198754',
                backgroundColor: 'rgba(25, 135, 84, 0.15)',
                tension: 0.3
            },
            {
                label: 'Pengeluaran',
                data: @json($expenseData),
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220, 53, 69, 0.15)',
                tension: 0.3
            }
        ]
    }
});
</script>
@endpush
