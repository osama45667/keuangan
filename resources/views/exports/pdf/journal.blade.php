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
<h3>Jurnal Umum</h3>
<p>Periode: {{ formatTanggalID($start) }} s/d {{ formatTanggalID($end) }}</p>
<table>
    <tr>
        <th>Tanggal</th>
        <th>Nomor</th>
        <th>Deskripsi</th>
        <th>Akun</th>
        <th>Debit</th>
        <th>Kredit</th>
    </tr>
    @foreach($journals as $j)
        @foreach($j->lines as $line)
            <tr>
                <td>{{ formatTanggalID($j->tanggal) }}</td>
                <td>{{ $j->nomor_jurnal }}</td>
                <td>{{ $j->deskripsi }}</td>
                <td>{{ $line->account?->nama_akun }}</td>
                <td align="right">{{ formatRupiah($line->debit) }}</td>
                <td align="right">{{ formatRupiah($line->kredit) }}</td>
            </tr>
        @endforeach
    @endforeach
</table>
</body>
</html>
