<table>
    <tr><th colspan="2">Laporan Laba Rugi</th></tr>
    <tr><th colspan="2">Periode: {{ formatTanggalID($start) }} s/d {{ formatTanggalID($end) }}</th></tr>
    <tr><th colspan="2">Pendapatan</th></tr>
    @foreach($revenue['rows'] as $r)
        <tr><td>{{ $r->nama_akun }}</td><td>{{ $r->total_kredit - $r->total_debit }}</td></tr>
    @endforeach
    <tr><th colspan="2">Beban</th></tr>
    @foreach($expense['rows'] as $r)
        <tr><td>{{ $r->nama_akun }}</td><td>{{ $r->total_debit - $r->total_kredit }}</td></tr>
    @endforeach
    <tr><th>Laba Bersih</th><th>{{ $net }}</th></tr>
</table>
