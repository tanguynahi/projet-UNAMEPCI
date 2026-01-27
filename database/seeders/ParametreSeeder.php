<?php

namespace Database\Seeders;

use App\Models\Parametre;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ParametreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Parametre::create([
            'administrateur_id' => 1,
            'mode_theme' => 'light',
            'nom_site_web' => 'MUTUALPAY',
        ]);
    }
}
