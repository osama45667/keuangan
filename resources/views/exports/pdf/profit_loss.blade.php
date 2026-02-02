<!doctype html>
<html>
<head>
    <style>
        body{font-family:DejaVu Sans;font-size:12px;}
        table{width:100%;border-collapse:collapse;}
        td,th{border:1px solid #ddd;padding:6px;}
    </style>
</head>
<body>
<h3>Laporan Laba Rugi</h3>
<p>Periode: {{ formatTanggalID($start) }} s/d {{ formatTanggalID($end) }}</p>

<h4>Pendapatan</h4>
<table>
    <tr><th>Akun</th><th>Jumlah</th></tr>
    @foreach($revenue['rows'] as $r)
        <tr><td>{{ $r->nama_akun }}</td><td align="right">{{ formatRupiah($r->total_kredit - $r->total_debit) }}</td></tr>
    @endforeach
</table>

<h4>Beban</h4>
<table>
    <tr><th>Akun</th><th>Jumlah</th></tr>
    @foreach($expense['rows'] as $r)
        <tr><td>{{ $r->nama_akun }}</td><td align="right">{{ formatRupiah($r->total_debit - $r->total_kredit) }}</td></tr>
    @endforeach
</table>

<h4>Laba Bersih: {{ formatRupiah($net) }}</h4>
</body>
</html>
