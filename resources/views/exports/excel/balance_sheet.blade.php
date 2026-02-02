<table>
    <tr><th colspan="2">Neraca</th></tr>
    <tr><th colspan="2">Per: {{ formatTanggalID($end) }}</th></tr>
    <tr><th colspan="2">Assets</th></tr>
    @foreach($assets['rows'] as $r)
        <tr><td>{{ $r->nama_akun }}</td><td>{{ $r->total_debit - $r->total_kredit }}</td></tr>
    @endforeach
    <tr><th colspan="2">Liabilities</th></tr>
    @foreach($liabilities['rows'] as $r)
        <tr><td>{{ $r->nama_akun }}</td><td>{{ $r->total_kredit - $r->total_debit }}</td></tr>
    @endforeach
    <tr><th colspan="2">Equity</th></tr>
    @foreach($equity['rows'] as $r)
        <tr><td>{{ $r->nama_akun }}</td><td>{{ $r->total_kredit - $r->total_debit }}</td></tr>
    @endforeach
</table>
