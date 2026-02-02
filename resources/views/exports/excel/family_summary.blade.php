<table>
    <tr><th colspan="6">Laporan Keuangan Keluarga</th></tr>
    <tr><th colspan="6">Periode: {{ formatTanggalID($start) }} s/d {{ formatTanggalID($end) }}</th></tr>
    <tr><th>Pemasukan (+)</th><td>{{ $income }}</td><th>Pengeluaran (−)</th><td>{{ $expense }}</td><th>Saldo</th><td>{{ $net }}</td></tr>
</table>

<table>
    <tr><th colspan="2">Ringkasan per Anggota</th></tr>
    <tr><th>Anggota</th><th>Total</th></tr>
    @foreach($byMember as $row)
        <tr><td>{{ $row->member_name ?? '-' }}</td><td>{{ $row->total }}</td></tr>
    @endforeach
</table>

<table>
    <tr><th colspan="2">Pengeluaran Ayah, Ibu & Total (Subtotal)</th></tr>
    <tr><th>Anggota</th><th>Total Pengeluaran</th></tr>
    <tr><td>Ayah</td><td>- {{ $expenseByMember['Ayah'] ?? 0 }}</td></tr>
    <tr><td>Ibu</td><td>- {{ $expenseByMember['Ibu'] ?? 0 }}</td></tr>
    <tr><td>Total Pengeluaran</td><td>- {{ $expenseByMember['Umum'] ?? 0 }}</td></tr>
    <tr><th>Subtotal Pengeluaran</th><th>- {{ $expense }}</th></tr>
</table>
