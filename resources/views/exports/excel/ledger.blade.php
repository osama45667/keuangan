<table>
    <tr><th colspan="5">Buku Besar</th></tr>
    <tr><th colspan="5">Periode: {{ formatTanggalID($start) }} s/d {{ formatTanggalID($end) }}</th></tr>
    <tr>
        <th>Tanggal</th>
        <th>Nomor Jurnal</th>
        <th>Debit</th>
        <th>Kredit</th>
        <th>Memo</th>
        <th>Saldo</th>
    </tr>
    <tr>
        <td colspan="5">Saldo Awal</td>
        <td>{{ $ledger['opening'] }}</td>
    </tr>
    @foreach($ledger['lines'] as $line)
        <tr>
            <td>{{ $line->journal->tanggal }}</td>
            <td>{{ $line->journal->nomor_jurnal }}</td>
            <td>{{ $line->debit }}</td>
            <td>{{ $line->kredit }}</td>
            <td>{{ $line->memo }}</td>
            <td>{{ $line->running_balance }}</td>
        </tr>
    @endforeach
</table>
