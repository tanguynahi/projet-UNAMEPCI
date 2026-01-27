<?php

namespace Database\Seeders;

use App\Models\Ville;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VilleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ville::create([
            'libelle' => 'Abidjan',
        ]);

        Ville::create([
            'libelle' => 'Abengourou',
        ]);

        Ville::create([
            'libelle' => 'Bassam',
        ]);

        Ville::create([
            'libelle' => 'Bouaké',
        ]);

        Ville::create([
            'libelle' => 'Boundiali',
        ]);

        Ville::create([
            'libelle' => 'Boundoukou',
        ]);

        Ville::create([
            'libelle' => 'Bonoua',
        ]);

        Ville::create([
            'libelle' => 'Bingerville',
        ]);

        Ville::create([
            'libelle' => 'Daloa',
        ]);

        Ville::create([
            'libelle' => 'Ferké',
        ]);

        Ville::create([
            'libelle' => 'Jacqueville',
        ]);

        Ville::create([
            'libelle' => 'Katiola',
        ]);

        Ville::create([
            'libelle' => 'Korhogo',
        ]);

        Ville::create([
            'libelle' => 'Man',
        ]);

        Ville::create([
            'libelle' => 'San-Pédro',
        ]);

        Ville::create([
            'libelle' => 'Yamoussoukro',
        ]);
    }
}
