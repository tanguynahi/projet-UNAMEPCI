<?php

namespace Database\Seeders;

use App\Models\Compte;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CompteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $compte = Compte::create([
            'type_compte_id' => 1,
            'solde' => 10000000,
            'quota' => 0,
            'status' => 1,
        ]);

        $compte2 = Compte::create([
            'type_compte_id' => 3,
            'solde' => 0,
            'quota' => 0,
            'status' => 1,
        ]);
    }
}
