<!doctype html>
<html>
<head>
    <style>
        body{font-family:DejaVu Sans;font-size:12px;color:#111827;}
        .header{border-bottom:2px solid #e11d48;margin-bottom:10px;padding-bottom:6px;}
        .title{font-size:16px;font-weight:bold;}
        .muted{color:#6b7280;}
        .summary{width:100%;border-collapse:collapse;margin:8px 0 12px;}
        .summary td{padding:6px;border:1px solid #e5e7eb;}
        table{width:100%;border-collapse:collapse;margin-bottom:10px;}
        th{background:#f1f5f9;text-align:left;}
        td,th{border:1px solid #e5e7eb;padding:6px;}
    </style>
</head>
<body>
<div class="header">
    <div class="title">Laporan Keuangan Keluarga</div>
    <div class="muted">Periode: {{ formatTanggalID($start) }} s/d {{ formatTanggalID($end) }}</div>
</div>

<table class="summary">
    <tr>
        <td><strong>Pemasukan (+)</strong><br>{{ formatRupiah($income) }}</td>
        <td><strong>Pengeluaran (−)</strong><br>{{ formatRupiah($expense) }}</td>
        <td><strong>Saldo Bersih</strong><br>{{ formatRupiah($net) }}</td>
    </tr>
</table>

<h4>Rincian Pengeluaran Ayah (Kategori & Catatan)</h4>
<table>
    <tr><th>Tanggal</th><th>Kategori</th><th>Catatan</th><th>Jumlah</th></tr>
    @forelse(($expenseDetailByMember['Ayah'] ?? collect()) as $r)
        <tr>
            <td>{{ formatTanggalID($r->tanggal) }}</td>
            <td>{{ $r->category?->name }}</td>
            <td>{{ $r->note }}</td>
            <td align="right">{{ formatRupiah($r->amount) }}</td>
        </tr>
    @empty
        <tr><td colspan="4" align="center">Belum ada pengeluaran ayah.</td></tr>
    @endforelse
    <tr><th colspan="3">Subtotal Ayah</th><th align="right">{{ formatRupiah($expenseTotalsByMember['Ayah'] ?? 0) }}</th></tr>
</table>

<h4>Rincian Pengeluaran Ibu (Kategori & Catatan)</h4>
<table>
    <tr><th>Tanggal</th><th>Kategori</th><th>Catatan</th><th>Jumlah</th></tr>
    @forelse(($expenseDetailByMember['Ibu'] ?? collect()) as $r)
        <tr>
            <td>{{ formatTanggalID($r->tanggal) }}</td>
            <td>{{ $r->category?->name }}</td>
            <td>{{ $r->note }}</td>
            <td align="right">{{ formatRupiah($r->amount) }}</td>
        </tr>
    @empty
        <tr><td colspan="4" align="center">Belum ada pengeluaran ibu.</td></tr>
    @endforelse
    <tr><th colspan="3">Subtotal Ibu</th><th align="right">{{ formatRupiah($expenseTotalsByMember['Ibu'] ?? 0) }}</th></tr>
</table>

</body>
</html>
