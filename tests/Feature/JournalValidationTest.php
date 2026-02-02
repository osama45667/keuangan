<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\AccountingPeriod;
use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JournalValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_debit_must_equal_credit(): void
    {
        $user = User::factory()->create();
        $period = AccountingPeriod::create([
            'bulan' => 1,
            'tahun' => 2026,
            'start_date' => '2026-01-01',
            'end_date' => '2026-01-31',
            'status' => 'open',
        ]);

        $a1 = Account::create([
            'kode_akun' => '1000',
            'nama_akun' => 'Kas',
            'tipe' => 'Asset',
            'is_active' => true,
        ]);
        $a2 = Account::create([
            'kode_akun' => '2000',
            'nama_akun' => 'Utang',
            'tipe' => 'Liability',
            'is_active' => true,
        ]);

        $payload = [
            'tanggal' => '2026-01-10',
            'period_id' => $period->id,
            'lines' => [
                ['account_id' => $a1->id, 'debit' => 1000, 'kredit' => 0],
                ['account_id' => $a2->id, 'debit' => 0, 'kredit' => 900],
            ],
        ];

        $this->actingAs($user)
            ->post(route('journals.store'), $payload)
            ->assertSessionHasErrors('lines');
    }
}
