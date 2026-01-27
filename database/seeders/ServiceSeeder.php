<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::create([
            'libelle' => 'ALLOCATION MATERNITE',
            'description' => null,
            'montant_maximum' => 5000000
        ]);

        Service::create([
            'libelle' => 'CREDEX',
            'description' => null,
            'montant_maximum' => 5000000
        ]);

        Service::create([
            'libelle' => 'CREDIMO',
            'description' => null,
            'montant_maximum' => 5000000
        ]);

        Service::create([
            'libelle' => 'DROITS OUVERTS',
            'description' => null,
            'montant_maximum' => 5000000
        ]);

        Service::create([
            'libelle' => 'FRAIS FUNERAIRE',
            'description' => null,
            'montant_maximum' => 5000000
        ]);

        Service::create([
            'libelle' => 'FRAIS MEDICAUX',
            'description' => null,
            'montant_maximum' => 5000000
        ]);


        Service::create([
            'libelle' => 'PEL',
            'description' => null,
            'montant_maximum' => 5000000
        ]);

        Service::create([
            'libelle' => 'PREMED',
            'description' => null,
            'montant_maximum' => 5000000
        ]);
    }
}
