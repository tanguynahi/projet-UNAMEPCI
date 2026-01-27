<?php

namespace Database\Seeders;

use App\Models\TypeDocument;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TypeDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $typeDocumentLibelles = [
            'Carte d\'Identité Militaire',
            'Certificat de présence',
            'Relevé bancaire',
            'Bulletin de solde'
        ];

        foreach ($typeDocumentLibelles as $typeDocumentLibelle) {
            TypeDocument::create([
                'libelle' => $typeDocumentLibelle,
            ]);
        }
    }
}
