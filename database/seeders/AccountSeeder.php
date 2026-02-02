<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            ['kode_akun' => '1000', 'nama_akun' => 'Kas', 'tipe' => 'Asset', 'is_cash_bank' => true],
            ['kode_akun' => '1100', 'nama_akun' => 'Bank', 'tipe' => 'Asset', 'is_cash_bank' => true],
            ['kode_akun' => '1200', 'nama_akun' => 'Piutang Usaha', 'tipe' => 'Asset'],
            ['kode_akun' => '1300', 'nama_akun' => 'Persediaan', 'tipe' => 'Asset'],
            ['kode_akun' => '2000', 'nama_akun' => 'Utang Usaha', 'tipe' => 'Liability'],
            ['kode_akun' => '3000', 'nama_akun' => 'Modal Disetor', 'tipe' => 'Equity'],
            ['kode_akun' => '3100', 'nama_akun' => 'Laba Ditahan', 'tipe' => 'Equity'],
            ['kode_akun' => '4000', 'nama_akun' => 'Pendapatan Penjualan', 'tipe' => 'Revenue'],
            ['kode_akun' => '5000', 'nama_akun' => 'Beban Gaji', 'tipe' => 'Expense'],
            ['kode_akun' => '5100', 'nama_akun' => 'Beban Sewa', 'tipe' => 'Expense'],
        ];

        foreach ($accounts as $acc) {
            Account::firstOrCreate(
                ['kode_akun' => $acc['kode_akun']],
                array_merge($acc, ['is_active' => true])
            );
        }
    }
}
