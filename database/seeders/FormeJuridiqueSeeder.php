<?php

namespace Database\Seeders;

use App\Models\FormeJuridique;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FormeJuridiqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $formes = [
            ['libelle' => 'Entreprise Individuelle (EI)', 'description' => 'Entreprise appartenant à une seule personne', 'status' => 1],
            ['libelle' => 'Auto-Entrepreneur', 'description' => 'Régime simplifié pour petites activités', 'status' => 1],
            ['libelle' => 'SARL', 'description' => 'Société à Responsabilité Limitée', 'status' => 1],
            ['libelle' => 'SARL Unipersonnelle (SARLU)', 'description' => 'SARL avec un seul associé', 'status' => 1],
            ['libelle' => 'SA', 'description' => 'Société Anonyme', 'status' => 1],
            ['libelle' => 'SAS', 'description' => 'Société par Actions Simplifiée', 'status' => 1],
            ['libelle' => 'SNC', 'description' => 'Société en Nom Collectif', 'status' => 1],
            ['libelle' => 'SCS', 'description' => 'Société en Commandite Simple', 'status' => 1],
            ['libelle' => 'SCA', 'description' => 'Société en Commandite par Actions', 'status' => 1],
            ['libelle' => 'GIE', 'description' => 'Groupement d’Intérêt Économique', 'status' => 1],
            ['libelle' => 'Association', 'description' => 'Organisation à but non lucratif', 'status' => 1],
            ['libelle' => 'ONG', 'description' => 'Organisation Non Gouvernementale', 'status' => 1],
            ['libelle' => 'Coopérative', 'description' => 'Société coopérative', 'status' => 1],
            ['libelle' => 'Succursale', 'description' => 'Représentation d’une société étrangère', 'status' => 1],
            ['libelle' => 'Filiale', 'description' => 'Société contrôlée par une autre', 'status' => 1],
            ['libelle' => 'Autre', 'description' => 'Non defini ', 'status' => 1],
        ];

        foreach ($formes as $forme) {
            FormeJuridique::updateOrCreate(
                ['libelle' => $forme['libelle']],
                $forme
            );
        }
    }
}
