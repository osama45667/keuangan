@extends('layouts.app')

@section('content')
<h5>Laporan Keuangan Keluarga</h5>
<form class="row g-2 mb-3">
    <div class="col-md-3"><input type="date" name="start" value="{{ $start }}" class="form-control"></div>
    <div class="col-md-3"><input type="date" name="end" value="{{ $end }}" class="form-control"></div>
    <div class="col-md-6 text-end">
        <button class="btn btn-primary">Filter</button>
        <a class="btn btn-outline-danger" href="{{ route('family.reports.export', ['format'=>'pdf','start'=>$start,'end'=>$end]) }}">PDF</a>
        <a class="btn btn-outline-success" href="{{ route('family.reports.export', ['format'=>'excel','start'=>$start,'end'=>$end]) }}">Excel</a>
    </div>
</form>

<div class="row g-3 mb-3">
    <div class="col-md-4"><div class="card p-3"><div class="text-muted">Pemasukan (+)</div><div class="fs-4 text-success">{{ formatRupiah($income) }}</div></div></div>
    <div class="col-md-4"><div class="card p-3"><div class="text-muted">Pengeluaran (−)</div><div class="fs-4 text-danger">{{ formatRupiah($expense) }}</div></div></div>
    <div class="col-md-4"><div class="card p-3"><div class="text-muted">Saldo Bersih</div><div class="fs-4">{{ formatRupiah($net) }}</div></div></div>
</div>

<div class="card mb-3 p-3">
    <h6>Grafik Pemasukan vs Pengeluaran (12 Bulan)</h6>
    <canvas id="familyReportChart" height="120"></canvas>
</div>

<div class="card p-3 mb-3">
    <h6 class="mb-2">Rincian Pengeluaran per Anggota (Kategori & Catatan)</h6>
    @php($memberOrder = ['Ayah','Ibu'])
    @foreach($memberOrder as $member)
        @php($rows = $expenseDetailByMember[$member] ?? collect())
        <div class="mb-3">
            <div class="fw-bold mb-2">{{ $member }} — Subtotal: {{ formatRupiah($expenseTotalsByMember[$member] ?? 0) }}</div>
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kategori</th>
                            <th>Catatan</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $r)
                            <tr>
                                <td>{{ formatTanggalID($r->tanggal) }}</td>
                                <td>{{ $r->category?->name }}</td>
                                <td>{{ $r->note }}</td>
                                <td class="text-end">{{ formatRupiah($r->amount) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada pengeluaran {{ strtolower($member) }}.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
    <div class="fw-bold text-danger mt-2">Total Semua Pengeluaran: - {{ formatRupiah($expense) }}</div>
</div>

<div class="card p-3 mb-3">
    <h6 class="mb-2">Rincian Pemasukan per Anggota (Kategori & Catatan)</h6>
    @php($incomeMemberOrder = ['Ayah','Ibu','Umum'])
    @foreach($incomeMemberOrder as $member)
        @php($rows = $incomeDetailByMember[$member] ?? collect())
        <div class="mb-3">
            <div class="fw-bold mb-2">{{ $member }} — Subtotal: {{ formatRupiah($incomeTotalsByMember[$member] ?? 0) }}</div>
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kategori</th>
                            <th>Catatan</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $r)
                            <tr>
                                <td>{{ formatTanggalID($r->tanggal) }}</td>
                                <td>{{ $r->category?->name }}</td>
                                <td>{{ $r->note }}</td>
                                <td class="text-end">{{ formatRupiah($r->amount) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada pemasukan {{ strtolower($member) }}.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
    <div class="fw-bold text-success mt-2">Total Semua Pemasukan: + {{ formatRupiah($income) }}</div>
</div>

<div class="card p-3 mb-3">
    <h6 class="mb-2">Rincian Saldo Akhir</h6>
    <div class="row g-2 align-items-center">
        <div class="col-md-4"><span class="text-muted">Pemasukan</span><div class="fw-bold text-success">{{ formatRupiah($income) }}</div></div>
        <div class="col-md-4"><span class="text-muted">Total Pengeluaran (Ayah + Ibu + Umum)</span><div class="fw-bold text-danger">{{ formatRupiah($expense) }}</div></div>
        <div class="col-md-4"><span class="text-muted">Saldo Akhir</span><div class="fw-bold">{{ formatRupiah($net) }}</div></div>
    </div>
    <div class="text-muted mt-2">Saldo Akhir = Pemasukan - Total Pengeluaran</div>
</div>

<h6 class="mt-3">Detail Transaksi</h6>
<table class="table table-striped">
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
        @foreach($transactions as $t)
            <tr>
                <td>{{ formatTanggalID($t->tanggal) }}</td>
                <td>{{ $t->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}</td>
                <td>{{ $t->category?->name }}</td>
                <td>{{ $t->member_name }}</td>
                <td class="text-end">{{ formatRupiah($t->amount) }}</td>
                <td>{{ $t->note }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctxReport = document.getElementById('familyReportChart');
new Chart(ctxReport, {
    type: 'line',
    data: {
        labels: @json($labels),
        datasets: [
            {
                label: 'Pemasukan',
                data: @json($incomeData),
                borderColor: '#16a34a',
                backgroundColor: 'rgba(22, 163, 74, 0.15)',
                tension: 0.3
            },
            {
                label: 'Pengeluaran',
                data: @json($expenseData),
                borderColor: '#ef4444',
                backgroundColor: 'rgba(239, 68, 68, 0.15)',
                tension: 0.3
            }
        ]
    }
});
</script>
@endpush
