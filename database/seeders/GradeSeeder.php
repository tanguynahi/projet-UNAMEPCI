<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Grade::create([
            'libelle' => "Général d'Armée",
            'description' => 'Généraux'
        ]);

        Grade::create([
            'libelle' => "Général de Corps d'Armée",
            'description' => 'Généraux'
        ]);

        Grade::create([
            'libelle' => "Général de Division",
            'description' => 'Généraux'
        ]);

        Grade::create([
            'libelle' => "Général de Brigade",
            'description' => 'Généraux'
        ]);

        Grade::create([
            'libelle' => "Colonel",
            'description' => 'Officiers supérieurs'
        ]);

        Grade::create([
            'libelle' => "Lieutenant-Colonel",
            'description' => 'Officiers supérieurs'
        ]);

        Grade::create([
            'libelle' => "Commandant",
            'description' => 'Officiers supérieurs'
        ]);

        Grade::create([
            'libelle' => "Capitaine",
            'description' => 'Officiers subalternes'
        ]);

        Grade::create([
            'libelle' => "Lieutenant",
            'description' => 'Officiers subalternes'
        ]);

        Grade::create([
            'libelle' => "Sous-Lieutenant",
            'description' => 'Officiers subalternes'
        ]);

        Grade::create([
            'libelle' => "Adjudant-Chef Major",
            'description' => 'Sous-officiers supérieurs'
        ]);

        Grade::create([
            'libelle' => "Adjudant-Chef",
            'description' => 'Sous-officiers supérieurs'
        ]);

        Grade::create([
            'libelle' => "Adjudant",
            'description' => 'Sous-officiers supérieurs'
        ]);

        Grade::create([
            'libelle' => "Sergent-Chef",
            'description' => 'Sous-officiers'
        ]);

        Grade::create([
            'libelle' => "Sergent",
            'description' => 'Sous-officiers'
        ]);


        Grade::create([
            'libelle' => "Caporal-Chef",
            'description' => 'Caporaux'
        ]);

        Grade::create([
            'libelle' => "Caporal",
            'description' => 'Caporaux'
        ]);

        Grade::create([
            'libelle' => "Soldat de Première Classe",
            'description' => 'Soldats'
        ]);

        Grade::create([
            'libelle' => "Soldat de Deuxième Classe",
            'description' => 'Soldats'
        ]);
    }
}
