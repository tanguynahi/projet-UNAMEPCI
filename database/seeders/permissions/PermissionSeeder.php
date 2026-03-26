<?php

namespace Database\Seeders\permissions;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {



        $permissions = [
            "liste-roles",
            "infos-roles",
            "ajouter-roles",
            "modifier-roles",
            "supprimer-roles",



            "liste-documents",
            "infos-documents",
            "ajouter-documents",
            "modifier-documents",
            "supprimer-documents",






            // Mutualistes
            "liste-mutualistes",
            "infos-mutualistes",
            "ajouter-mutualistes",
            "modifier-mutualistes",
            "supprimer-mutualistes",





            "liste-inscriptions",
            "infos-inscriptions",
            "ajouter-inscriptions",
            "modifier-inscriptions",
            "supprimer-inscriptions",
            "restaurer-inscriptions",


            "liste-demandeProduits",
            "map-demandeProduits",
            "infos-demandeProduits",
            "ajouter-demandeProduits",
            "modifier-demandeProduits",
            "receptionner-demandeProduits",
            "analyser-demandeProduits",
            "rattacher-demandeProduits",
            "cloturer-demandeProduits",
            "qualification-demandeProduits",


            "liste-messages",
            "infos-messages",
            "envoyer-messages",
            "repondre-messages",
            "modifier-messages",
            "supprimer-messages",
            "restaurer-messages",


            "liste-redevances",
            "infos-redevances",
            "envoyer-redevances",
            "repondre-redevances",
            "modifier-redevances",
            "supprimer-redevances",
            "restaurer-redevances",

            "liste-projetMutualistes",
            "infos-projetMutualistes",
            "envoyer-projetMutualistes",
            "repondre-projetMutualistes",
            "modifier-projetMutualistes",
            "supprimer-projetMutualistes",
            "restaurer-projetMutualistes",

            "liste-facturations",
            "infos-facturations",
            "modifier-facturations",
            "supprimer-facturations",
            "restaurer-facturations",


            "liste-services",
            "infos-services",
            "ajouter-services",
            "modifier-services",
            "supprimer-services",
            "restaurer-services",
            "receptionner-services",
            "rejeter-services",


            "liste-projets",
            "infos-projets",
            "ajouter-projets",
            "modifier-projets",
            "supprimer-projets",

            "liste-demandeAccompagnements",
            "infos-demandeAccompagnements",
            "ajouter-demandeAccompagnements",
            "modifier-demandeAccompagnements",
            "supprimer-demandeAccompagnements",


            // cotisations
            "liste-cotisations",
            "infos-cotisations",
            "ajouter-cotisations",
            "modifier-cotisations",
            "supprimer-cotisations",

            "liste-cotisationMutualistes",
            "infos-cotisationMutualistes",
            "ajouter-cotisationMutualistes",
            "modifier-cotisationMutualistes",
            "supprimer-cotisationMutualistes",


            "liste-paiements",
            "espece-paiements",
            "infos-paiements",
            "ajouter-paiements",
            "modifier-paiements",
            "supprimer-paiements",
            "restaurer-paiements",



            "liste-actualites",
            "infos-actualites",
            "ajouter-actualites",
            "modifier-actualites",
            "supprimer-actualites",
            "restaurer-actualites",





            "liste-villes",
            "infos-villes",
            "ajouter-villes",
            "modifier-villes",
            "supprimer-villes",
            "restaurer-villes",


            "liste-slides",
            "infos-slides",
            "ajouter-slides",
            "modifier-slides",
            "supprimer-slides",
            "restaurer-slides",

            "liste-typeDocuments",
            "infos-typeDocuments",
            "ajouter-typeDocuments",
            "modifier-typeDocuments",
            "supprimer-typeDocuments",
            "restaurer-typeDocuments",



            "liste-typePieces",
            "infos-typePieces",
            "ajouter-typePieces",
            "modifier-typePieces",
            "supprimer-typePieces",
            "restaurer-typePieces",

            "liste-taxes",
            "infos-taxes",
            "ajouter-taxes",
            "modifier-taxes",
            "supprimer-taxes",
            "restaurer-taxes",


            "liste-formeJuridiques",
            "infos-formeJuridiques",
            "ajouter-formeJuridiques",
            "modifier-formeJuridiques",
            "supprimer-formeJuridiques",
            "restaurer-formeJuridiques",

            "liste-specialites",
            "infos-specialites",
            "ajouter-specialites",
            "modifier-specialites",
            "supprimer-specialites",
            "restaurer-specialites",



            "voir-parametres",
            "voir-profil-parametres",
            "modifier-profil-parametres",
            "modifier-mot-de-passe-parametres",
            "modifier-infos-plateforme-parametres",




            "liste-administrateurs",
            "infos-administrateurs",
            "ajouter-administrateurs",
            "modifier-administrateurs",
            "supprimer-administrateurs",
            "restaurer-administrateurs",
            "changer-statut-administrateurs",
            "afficher-permissions-administrateurs",
            "modifier-permissions-administrateurs",

            "liste-permissions",
            "infos-permissions",
            "ajouter-permissions",
            "modifier-permissions",
            "supprimer-permissions",



            "liste-carteMembres",
            "infos-carteMembres",
            "ajouter-carteMembres",
            "modifier-carteMembres",
            "supprimer-carteMembres",
            "restaurer-carteMembres",




            "voir-montant-total-dashboard",
            "voir-total-adhesion-dashboard",
            "voir-total-projet-dashboard",
            "voir-total-cotisation-dashboard",
            "voir-total-pret-dashboard",
            "voir-total-mutualiste-dashboard",
            "voir-total-administrateur-dashboard",



        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
    }
}
