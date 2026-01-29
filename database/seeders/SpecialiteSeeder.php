<?php

namespace Database\Seeders;

use App\Models\Specialite;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SpecialiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $specialites = [
            ['libelle' => 'Médecine Générale', 'description' => 'Soins médicaux de base', 'status' => 1],
            ['libelle' => 'Cardiologie', 'description' => 'Maladies du cœur et des vaisseaux', 'status' => 1],
            ['libelle' => 'Dermatologie', 'description' => 'Maladies de la peau', 'status' => 1],
            ['libelle' => 'Pédiatrie', 'description' => 'Soins médicaux des enfants', 'status' => 1],
            ['libelle' => 'Gynécologie-Obstétrique', 'description' => 'Santé de la femme et grossesse', 'status' => 1],
            ['libelle' => 'Chirurgie Générale', 'description' => 'Interventions chirurgicales générales', 'status' => 1],
            ['libelle' => 'Ophtalmologie', 'description' => 'Maladies des yeux', 'status' => 1],
            ['libelle' => 'ORL', 'description' => 'Oto-rhino-laryngologie', 'status' => 1],
            ['libelle' => 'Neurologie', 'description' => 'Maladies du système nerveux', 'status' => 1],
            ['libelle' => 'Psychiatrie', 'description' => 'Troubles mentaux', 'status' => 1],
            ['libelle' => 'Urologie', 'description' => 'Appareil urinaire et reproducteur masculin', 'status' => 1],
            ['libelle' => 'Néphrologie', 'description' => 'Maladies des reins', 'status' => 1],
            ['libelle' => 'Gastro-entérologie', 'description' => 'Appareil digestif', 'status' => 1],
            ['libelle' => 'Pneumologie', 'description' => 'Maladies respiratoires', 'status' => 1],
            ['libelle' => 'Endocrinologie', 'description' => 'Maladies hormonales', 'status' => 1],
            ['libelle' => 'Rhumatologie', 'description' => 'Maladies des os et articulations', 'status' => 1],
            ['libelle' => 'Hématologie', 'description' => 'Maladies du sang', 'status' => 1],
            ['libelle' => 'Oncologie', 'description' => 'Traitement du cancer', 'status' => 1],
            ['libelle' => 'Radiologie', 'description' => 'Imagerie médicale', 'status' => 1],
            ['libelle' => 'Anesthésie-Réanimation', 'description' => 'Gestion de la douleur et réanimation', 'status' => 1],
            ['libelle' => 'Médecine du Travail', 'description' => 'Santé en milieu professionnel', 'status' => 1],
            ['libelle' => 'Médecine Interne', 'description' => 'Maladies complexes', 'status' => 1],
            ['libelle' => 'Médecine Légale', 'description' => 'Expertise médico-judiciaire', 'status' => 1],
            ['libelle' => 'Infectiologie', 'description' => 'Maladies infectieuses', 'status' => 1],
            ['libelle' => 'Gériatrie', 'description' => 'Soins des personnes âgées', 'status' => 1],
            ['libelle' => 'Néonatologie', 'description' => 'Soins aux nouveau-nés', 'status' => 1],
            ['libelle' => 'Allergologie', 'description' => 'Allergies et hypersensibilités', 'status' => 1],
            ['libelle' => 'Immunologie', 'description' => 'Système immunitaire', 'status' => 1],
            ['libelle' => 'Parasitologie', 'description' => 'Maladies parasitaires', 'status' => 1],
            ['libelle' => 'Nutrition', 'description' => 'Alimentation et santé', 'status' => 1],
            ['libelle' => 'Médecine Physique et Réadaptation', 'description' => 'Rééducation fonctionnelle', 'status' => 1],
            ['libelle' => 'Chirurgie Orthopédique', 'description' => 'Os, articulations, traumatologie', 'status' => 1],
            ['libelle' => 'Chirurgie Pédiatrique', 'description' => 'Chirurgie chez l’enfant', 'status' => 1],
            ['libelle' => 'Chirurgie Cardiaque', 'description' => 'Interventions du cœur', 'status' => 1],
            ['libelle' => 'Chirurgie Plastique', 'description' => 'Chirurgie réparatrice et esthétique', 'status' => 1],
            ['libelle' => 'Chirurgie Vasculaire', 'description' => 'Vaisseaux sanguins', 'status' => 1],
            ['libelle' => 'Chirurgie Digestive', 'description' => 'Appareil digestif', 'status' => 1],
        ];

        foreach ($specialites as $specialite) {
            Specialite::updateOrCreate(
                ['libelle' => $specialite['libelle']],
                $specialite
            );
        }
    }
}
