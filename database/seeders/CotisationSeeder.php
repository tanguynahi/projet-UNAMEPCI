<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Cotisation;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CotisationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1er janvier de l’année en cours
        $dateDebut = Carbon::now()->startOfYear()->format('Y-m-d');

        // 31 décembre de l’année en cours
        $dateFin = Carbon::now()->endOfYear()->format('Y-m-d');

        Cotisation::create([
            'libelle' => 'Cotisation Annuelle',
            'montant_a_payer' => 120000,
            'frequence_paiement' => 'Annuelle',
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
        ]);
    }
}
