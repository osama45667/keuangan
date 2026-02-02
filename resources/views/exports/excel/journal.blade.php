<table>
    <tr><th colspan="6">Jurnal Umum</th></tr>
    <tr><th colspan="6">Periode: {{ formatTanggalID($start) }} s/d {{ formatTanggalID($end) }}</th></tr>
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
                <td>{{ $j->tanggal }}</td>
                <td>{{ $j->nomor_jurnal }}</td>
                <td>{{ $j->deskripsi }}</td>
                <td>{{ $line->account?->nama_akun }}</td>
                <td>{{ $line->debit }}</td>
                <td>{{ $line->kredit }}</td>
            </tr>
        @endforeach
    @endforeach
</table>
