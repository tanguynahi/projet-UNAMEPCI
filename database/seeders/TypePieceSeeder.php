<?php

namespace Database\Seeders;

use App\Models\TypePiece;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TypePieceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TypePiece::create([
            'libelle' => "CNI",
            'description' => "Carte nationale d'identité"
        ]);

        TypePiece::create([
            'libelle' => "CARTE MILITAIRE",
            'description' => "Carte de militaire"
        ]);

        TypePiece::create([
            'libelle' => "PASSEPORT",
            'description' => "Passeport"
        ]);
    }
}
