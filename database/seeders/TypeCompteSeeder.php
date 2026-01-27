<?php

namespace Database\Seeders;

use App\Models\TypeCompte;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TypeCompteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TypeCompte::create([
            'libelle' => "Compte FPM",
        ]);

        TypeCompte::create([
            'libelle' => "Compte Mutualiste",
        ]);

        TypeCompte::create([
            'libelle' => "Compte Transitoire",
        ]);
    }
}
