<?php

namespace Database\Seeders;

use App\Models\Periode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeriodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Periode::create([
            'libelle' => "Immediat",
        ]);

        Periode::create([
            'libelle' => "Journalière",
        ]);

        Periode::create([
            'libelle' => "Hebdomadaire",
        ]);

        Periode::create([
            'libelle' => "Mensuelle",
        ]);

        Periode::create([
            'libelle' => "Annuelle",
        ]);

        Periode::create([
            'libelle' => "Aperiodique",
        ]);
    }
}
