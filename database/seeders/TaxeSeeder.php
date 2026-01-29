<?php

namespace Database\Seeders;

use App\Models\Taxe;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TaxeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Taxe::create([
            'libelle' => "Droit d'adhesion",
            'description' => null,
            'montant' => 10000,
        ]);
        Taxe::create([
            'libelle' => "Reglement Carte Membre",
            'description' => null,
            'montant' => 5000,
        ]);
    }
}
