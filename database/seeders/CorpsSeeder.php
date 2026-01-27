<?php

namespace Database\Seeders;

use App\Models\Corps;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CorpsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Corps::create([
            'libelle' => "Gendarme",
        ]);

        Corps::create([
            'libelle' => "Militaire",
        ]);
    }
}
