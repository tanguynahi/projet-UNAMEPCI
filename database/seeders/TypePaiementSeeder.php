<?php

namespace Database\Seeders;

use App\Models\TypePaiement;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TypePaiementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TypePaiement::create([
            'libelle' => "Adhésion"
        ]);

        TypePaiement::create([
            'libelle' => "Cotisation"
        ]);

        TypePaiement::create([
            'libelle' => "Prêt"
        ]);

        TypePaiement::create([
            'libelle' => "Projet"
        ]);
    }
}
