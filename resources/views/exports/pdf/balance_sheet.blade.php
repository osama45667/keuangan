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
<h3>Neraca</h3>
<p>Per: {{ formatTanggalID($end) }}</p>

<h4>Assets</h4>
<table>
    <tr><th>Akun</th><th>Jumlah</th></tr>
    @foreach($assets['rows'] as $r)
        <tr><td>{{ $r->nama_akun }}</td><td align="right">{{ formatRupiah($r->total_debit - $r->total_kredit) }}</td></tr>
    @endforeach
</table>

<h4>Liabilities</h4>
<table>
    <tr><th>Akun</th><th>Jumlah</th></tr>
    @foreach($liabilities['rows'] as $r)
        <tr><td>{{ $r->nama_akun }}</td><td align="right">{{ formatRupiah($r->total_kredit - $r->total_debit) }}</td></tr>
    @endforeach
</table>

<h4>Equity</h4>
<table>
    <tr><th>Akun</th><th>Jumlah</th></tr>
    @foreach($equity['rows'] as $r)
        <tr><td>{{ $r->nama_akun }}</td><td align="right">{{ formatRupiah($r->total_kredit - $r->total_debit) }}</td></tr>
    @endforeach
</table>
</body>
</html>
