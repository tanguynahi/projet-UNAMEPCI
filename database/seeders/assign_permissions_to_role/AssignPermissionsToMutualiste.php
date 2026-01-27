<?php

namespace Database\Seeders\assign_permissions_to_role;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AssignPermissionsToMutualiste extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientRole = Role::where("name","mutualiste")->first();

        $clientRole->givePermissionTo([
            'Afficher un accompagnement',
            'Afficher un droit d\'ahdesion',
            'Afficher le detail d\'un projet de mutualiste',
            'Afficher une cotisation',
            'Afficher le detail d\'une actualite',
            'Afficher le detail d\'un projet',
            'Afficher son profil',
            'voir la liste des actualites',
            'voir la liste des projets',
            'voir la liste de ses paiements',
            'voir la liste de ses accompagnements',
            'voir la liste de ses droits d\'adhesion',
            'voir la liste de ses cotisations',
            'voir la liste de ses projets',
        ]);
    }
}
