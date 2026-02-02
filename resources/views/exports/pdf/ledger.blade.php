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
<h3>Buku Besar</h3>
<p>Periode: {{ formatTanggalID($start) }} s/d {{ formatTanggalID($end) }}</p>
<table>
    <tr><th>Tanggal</th><th>Nomor Jurnal</th><th>Debit</th><th>Kredit</th><th>Memo</th><th>Saldo</th></tr>
    <tr>
        <td colspan="5"><strong>Saldo Awal</strong></td>
        <td align="right"><strong>{{ formatRupiah($ledger['opening']) }}</strong></td>
    </tr>
    @foreach($ledger['lines'] as $line)
        <tr>
            <td>{{ formatTanggalID($line->journal->tanggal) }}</td>
            <td>{{ $line->journal->nomor_jurnal }}</td>
            <td align="right">{{ formatRupiah($line->debit) }}</td>
            <td align="right">{{ formatRupiah($line->kredit) }}</td>
            <td>{{ $line->memo }}</td>
            <td align="right">{{ formatRupiah($line->running_balance) }}</td>
        </tr>
    @endforeach
</table>
</body>
</html>
