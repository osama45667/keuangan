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
<h3>Neraca Saldo</h3>
<p>Periode: {{ formatTanggalID($start) }} s/d {{ formatTanggalID($end) }}</p>
<table>
    <tr><th>Kode</th><th>Akun</th><th>Debit</th><th>Kredit</th></tr>
    @foreach($rows as $r)
        <tr>
            <td>{{ $r->kode_akun }}</td>
            <td>{{ $r->nama_akun }}</td>
            <td align="right">{{ formatRupiah($r->total_debit) }}</td>
            <td align="right">{{ formatRupiah($r->total_kredit) }}</td>
        </tr>
    @endforeach
</table>
</body>
</html>
