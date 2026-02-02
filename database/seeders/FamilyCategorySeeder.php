<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FamilyCategory;

class FamilyCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Gaji', 'type' => 'income'],
            ['name' => 'Usaha', 'type' => 'income'],
            ['name' => 'Bonus', 'type' => 'income'],
            ['name' => 'Makan & Dapur', 'type' => 'expense'],
            ['name' => 'Listrik', 'type' => 'expense'],
            ['name' => 'Air', 'type' => 'expense'],
            ['name' => 'Internet', 'type' => 'expense'],
            ['name' => 'Transport', 'type' => 'expense'],
            ['name' => 'Sekolah', 'type' => 'expense'],
            ['name' => 'Kesehatan', 'type' => 'expense'],
            ['name' => 'Cicilan', 'type' => 'expense'],
            ['name' => 'Belanja Bulanan', 'type' => 'expense'],
            ['name' => 'Kebutuhan Ayah', 'type' => 'expense'],
            ['name' => 'Kebutuhan Ibu', 'type' => 'expense'],
            ['name' => 'Kebutuhan Anak', 'type' => 'expense'],
        ];

        foreach ($categories as $cat) {
            FamilyCategory::firstOrCreate(
                ['name' => $cat['name'], 'type' => $cat['type']],
                ['is_active' => true]
            );
        }
    }
}
