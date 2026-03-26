<?php

namespace Database\Seeders\assign_permissions_to_role;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AssignPermissionsToMutualiste extends Seeder
{
    public function run(): void
    {
        $clientRole = Role::firstOrCreate(['name' => 'mutualiste']);

        $clientRole->syncPermissions([

            // Profil mutualiste
            "infos-mutualistes",

            // Actualités
            "liste-actualites",
            "infos-actualites",

            // Projets
            "liste-projets",
            "infos-projets",

            // Cotisations
            "liste-cotisations",
            "infos-cotisations",

            // Paiements (lecture seule)
            "liste-paiements",
            "infos-paiements",

            // Messages (optionnel si mutualiste communique)
            "liste-messages",
            "infos-messages",
            "envoyer-messages",
            "repondre-messages",

            // Documents (si mutualiste peut consulter)
            "liste-documents",
            "infos-documents",

            // Services / accompagnements
            "liste-services",
            "infos-services",


            "infos-carteMembres",

        ]);
    }
}
