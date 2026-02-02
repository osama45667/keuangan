<table>
    <tr>
        <th colspan="4">Neraca Saldo</th>
    </tr>
    <tr>
        <th colspan="4">Periode: {{ formatTanggalID($start) }} s/d {{ formatTanggalID($end) }}</th>
    </tr>
    <tr>
        <th>Kode</th>
        <th>Akun</th>
        <th>Debit</th>
        <th>Kredit</th>
    </tr>
    @foreach($rows as $r)
        <tr>
            <td>{{ $r->kode_akun }}</td>
            <td>{{ $r->nama_akun }}</td>
            <td>{{ $r->total_debit }}</td>
            <td>{{ $r->total_kredit }}</td>
        </tr>
    @endforeach
</table>
