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
<h3>Arus Kas (Metode Tidak Langsung)</h3>
<p>Periode: {{ formatTanggalID($start) }} s/d {{ formatTanggalID($end) }}</p>
<table>
    <tr><th>Laba Bersih</th><td align="right">{{ formatRupiah($net_income) }}</td></tr>
    <tr><th>Perubahan Kas (Net)</th><td align="right">{{ formatRupiah($cash_change) }}</td></tr>
</table>
<p>{{ $assumptions }}</p>
</body>
</html>
