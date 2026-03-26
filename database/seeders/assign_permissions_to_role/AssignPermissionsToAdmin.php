<?php

namespace Database\Seeders\assign_permissions_to_role;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AssignPermissionsToAdmin extends Seeder
{
    // public function run(): void
    // {
    //     // $allPermissions = [

    //     //     // Paramètres
    //     //     "liste-parametres",
    //     //     "infos-parametres",
    //     //     "ajouter-parametres",
    //     //     "modifier-parametres",
    //     //     "supprimer-parametres",



    //     //     // Actualités
    //     //     "liste-actualites",
    //     //     "infos-actualites",
    //     //     "ajouter-actualites",
    //     //     "modifier-actualites",
    //     //     "supprimer-actualites",

    //     //     // Cotisations
    //     //     "liste-cotisations",
    //     //     "infos-cotisations",
    //     //     "ajouter-cotisations",
    //     //     "modifier-cotisations",
    //     //     "supprimer-cotisations",

    //     //     // Projets
    //     //     "liste-projets",
    //     //     "infos-projets",
    //     //     "ajouter-projets",
    //     //     "modifier-projets",
    //     //     "supprimer-projets",

    //     //     // Images projets
    //     //     "liste-images-projets",
    //     //     "infos-images-projets",
    //     //     "ajouter-images-projets",
    //     //     "modifier-images-projets",
    //     //     "supprimer-images-projets",

    //     //     // Accompagnements
    //     //     "liste-accompagnements",
    //     //     "infos-accompagnements",
    //     //     "ajouter-accompagnements",
    //     //     "modifier-accompagnements",
    //     //     "supprimer-accompagnements",

    //     //     // Droits adhésion
    //     //     "liste-droits-adhesion",
    //     //     "infos-droits-adhesion",
    //     //     "ajouter-droits-adhesion",
    //     //     "modifier-droits-adhesion",
    //     //     "supprimer-droits-adhesion",

    //     //     // Cotisations mutualistes
    //     //     "liste-cotisations-mutualistes",
    //     //     "infos-cotisations-mutualistes",
    //     //     "ajouter-cotisations-mutualistes",
    //     //     "modifier-cotisations-mutualistes",
    //     //     "supprimer-cotisations-mutualistes",

    //     //     // Projets mutualistes
    //     //     "liste-projets-mutualistes",
    //     //     "infos-projets-mutualistes",
    //     //     "ajouter-projets-mutualistes",
    //     //     "modifier-projets-mutualistes",
    //     //     "supprimer-projets-mutualistes",

    //     //     // Slides
    //     //     "liste-slides",
    //     //     "infos-slides",
    //     //     "ajouter-slides",
    //     //     "modifier-slides",
    //     //     "supprimer-slides",

    //     //     // Lecture seule (tes "voir la liste")
    //     //     "liste-codes-validation",
    //     //     "liste-types-compte",
    //     //     "liste-paiements",
    //     //     "liste-types-paiement",
    //     //     "liste-corps-armee",
    //     //     "liste-grades",
    //     //     "liste-villes",
    //     //     "liste-directions"
    //     // ];


    //     $allPermissions = [
    //         "liste-roles",
    //         "infos-roles",
    //         "ajouter-roles",
    //         "modifier-roles",
    //         "supprimer-roles",

    //         "liste-permissions",
    //         "infos-permissions",
    //         "ajouter-permissions",
    //         "modifier-permissions",
    //         "supprimer-permissions",

    //         "liste-departements",
    //         "infos-departements",
    //         "ajouter-departements",
    //         "modifier-departements",
    //         "supprimer-departements",

    //         "liste-administrateurs",
    //         "infos-administrateurs",
    //         "ajouter-administrateurs",
    //         "modifier-administrateurs",
    //         "supprimer-administrateurs",
    //         "restaurer-administrateurs",
    //         "changer-statut-administrateurs",
    //         "modifier-permissions-administrateurs",



    //         // Mutualistes
    //         "liste-mutualistes",
    //         "infos-mutualistes",
    //         "ajouter-mutualistes",
    //         "modifier-mutualistes",
    //         "supprimer-mutualistes",

    //         "liste-utilisateurs",
    //         "infos-utilisateurs",
    //         "ajouter-utilisateurs",
    //         "modifier-utilisateurs",
    //         "supprimer-utilisateurs",
    //         "restaurer-utilisateurs",
    //         "changer-statut-utilisateurs",
    //         "modifier-permissions-utilisateurs",

    //         "liste-paiements",
    //         "infos-paiements",
    //         "ajouter-paiements",
    //         "modifier-paiements",
    //         "supprimer-paiements",
    //         "restaurer-paiements",

    //         "liste-typePieces",
    //         "infos-typePieces",
    //         "ajouter-typePieces",
    //         "modifier-typePieces",
    //         "supprimer-typePieces",
    //         "restaurer-typePieces",

    //         "liste-typeDeclarants",
    //         "infos-typeDeclarants",
    //         "ajouter-typeDeclarants",
    //         "modifier-typeDeclarants",
    //         "supprimer-typeDeclarants",
    //         "restaurer-typeDeclarants",



    //         "liste-incidentSignificatifs",
    //         "infos-incidentSignificatifs",
    //         "ajouter-incidentSignificatifs",
    //         "modifier-incidentSignificatifs",
    //         "supprimer-incidentSignificatifs",
    //         "restaurer-incidentSignificatifs",

    //         // "liste-typesPaiements",
    //         // "infos-typesPaiements",
    //         // "ajouter-typesPaiements",
    //         // "modifier-typesPaiements",
    //         // "supprimer-typesPaiements",
    //         // "restaurer-typesPaiements",

    //         "liste-typesPieces",
    //         "infos-typesPieces",
    //         "ajouter-typesPieces",
    //         "modifier-typesPieces",
    //         "supprimer-typesPieces",
    //         "restaurer-typesPieces",

    //         "liste-sousCategorieIncidents",
    //         "infos-sousCategorieIncidents",
    //         "ajouter-sousCategorieIncidents",
    //         "modifier-sousCategorieIncidents",
    //         "supprimer-sousCategorieIncidents",
    //         "restaurer-sousCategorieIncidents",

    //         "liste-slides",
    //         "infos-slides",
    //         "ajouter-slides",
    //         "modifier-slides",
    //         "supprimer-slides",
    //         "restaurer-slides",

    //         "liste-secteursActivite",
    //         "infos-secteursActivite",
    //         "ajouter-secteursActivite",
    //         "modifier-secteursActivite",
    //         "supprimer-secteursActivite",
    //         "restaurer-secteursActivite",

    //         "liste-ressources",
    //         "infos-ressources",
    //         "ajouter-ressources",
    //         "modifier-ressources",
    //         "supprimer-ressources",
    //         "restaurer-ressources",

    //         "liste-serviceDeclaration",
    //         "infos-serviceDeclaration",
    //         "ajouter-serviceDeclaration",
    //         "modifier-serviceDeclaration",
    //         "supprimer-serviceDeclaration",
    //         "restaurer-serviceDeclaration",

    //         "liste-faqs",
    //         "infos-faqs",
    //         "ajouter-faqs",
    //         "modifier-faqs",
    //         "supprimer-faqs",
    //         "restaurer-faqs",

    //         "liste-actualites",
    //         "infos-actualites",
    //         "ajouter-actualites",
    //         "modifier-actualites",
    //         "supprimer-actualites",
    //         "restaurer-actualites",

    //         "liste-documents",
    //         "infos-documents",
    //         "ajouter-documents",
    //         "modifier-documents",
    //         "supprimer-documents",
    //         "restaurer-documents",


    //         "liste-declarations",
    //         "map-declarations",
    //         "infos-declarations",
    //         "ajouter-declarations",
    //         "modifier-declarations",
    //         "receptionner-declarations",
    //         "analyser-declarations",
    //         "rattacher-declarations",
    //         "cloturer-declarations",
    //         "qualification-declarations",




    //         "liste-services",
    //         "infos-services",
    //         "ajouter-services",
    //         "modifier-services",
    //         "supprimer-services",
    //         "restaurer-services",
    //         "receptionner-services",
    //         "rejeter-services",
    //         // "analyser-documents-demandesAutorisation",

    //         "liste-regions",
    //         "infos-regions",
    //         "ajouter-regions",
    //         "modifier-regions",
    //         "supprimer-regions",
    //         "restaurer-regions",

    //         "liste-villes",
    //         "infos-villes",
    //         "ajouter-villes",
    //         "modifier-villes",
    //         "supprimer-villes",
    //         "restaurer-villes",

    //         "liste-particuliers",
    //         "infos-particuliers",
    //         "ajouter-particuliers",
    //         "modifier-particuliers",
    //         "supprimer-particuliers",
    //         "restaurer-particuliers",


    //         "liste-operateurs",
    //         "infos-operateurs",
    //         "ajouter-operateurs",
    //         "modifier-operateurs",
    //         "supprimer-operateurs",
    //         "restaurer-operateurs",


    //         "liste-departements",
    //         "infos-departements",
    //         "ajouter-departements",
    //         "modifier-departements",
    //         "supprimer-departements",



    //         "liste-natureIncidents",
    //         "infos-natureIncidents",
    //         "ajouter-natureIncidents",
    //         "modifier-natureIncidents",
    //         "supprimer-natureIncidents",
    //         "restaurer-natureIncidents",

    //         "liste-communes",
    //         "infos-communes",
    //         "ajouter-communes",
    //         "modifier-communes",
    //         "supprimer-communes",
    //         "restaurer-communes",

    //         "liste-categorieIncidents",
    //         "infos-categorieIncidents",
    //         "ajouter-categorieIncidents",
    //         "modifier-categorieIncidents",
    //         "supprimer-categorieIncidents",
    //         "restaurer-categorieIncidents",
    //         "approuver-categorieIncidents",
    //         "rejeter-categorieIncidents",

    //         // "liste-badgesLabelisation",
    //         // "infos-badgesLabelisation",
    //         // "ajouter-badgesLabelisation",
    //         // "modifier-badgesLabelisation",
    //         // "supprimer-badgesLabelisation",
    //         // "restaurer-badgesLabelisation",

    //         "liste-statistiques",
    //         "infos-statistiques",
    //         "ajouter-statistiques",
    //         "modifier-statistiques",
    //         "supprimer-statistiques",
    //         "restaurer-statistiques",

    //         "liste-messages",
    //         "infos-messages",
    //         "envoyer-messages",
    //         "repondre-messages",
    //         "modifier-messages",
    //         "supprimer-messages",
    //         "restaurer-messages",

    //         "liste-parametres",
    //         "voir-parametres",
    //         "voir-profil-parametres",
    //         "modifier-profil-parametres",
    //         "modifier-mot-de-passe-parametres",
    //         "modifier-infos-plateforme-parametres",


    //         "voir-montant-total-dashboard",
    //         "voir-total-incident-dashboard",
    //         "voir-total-incident-en-cours-dashboard",
    //         "voir-total-incident-resolus-dashboard",
    //         "voir-total-incident-critique-actif-dashboard",
    //         "voir-total-incident-clotures-dashboard",
    //         "voir-total-incident-non-conformes-dashboard",
    //         "voir-total-utilisateurs-dashboard",
    //         "voir-taux-de-disponibilites-dashboard",

    //         "voir-total-visiteurs-dashboard",

    //         "voir-statistiques-par-periode-dashboard"
    //     ];

    //     /*
    //     |----------------------------------
    //     | Création permissions
    //     |----------------------------------
    //     */
    //     foreach ($allPermissions as $permission) {
    //         Permission::firstOrCreate([
    //             'name' => $permission,
    //             'guard_name' => 'web'
    //         ]);
    //     }

    //     /*
    //     |----------------------------------
    //     | Roles
    //     |----------------------------------
    //     */
    //     $superAdmin = Role::firstOrCreate(['name' => 'super-administrateur']);
    //     $admin = Role::firstOrCreate(['name' => 'administrateur']);

    //     /*
    //     |----------------------------------
    //     | Super Admin → toutes permissions
    //     |----------------------------------
    //     */
    //     $superAdmin->syncPermissions($allPermissions);

    //     /*
    //     |----------------------------------
    //     | Admin → permissions limitées
    //     |----------------------------------
    //     */
    //     $admin->syncPermissions([
    //         "liste-mutualistes",
    //         "infos-mutualistes",
    //         "ajouter-mutualistes",
    //         "modifier-mutualistes",

    //         "liste-actualites",
    //         "ajouter-actualites",
    //         "modifier-actualites",

    //         "liste-cotisations",
    //         "ajouter-cotisations",
    //         "modifier-cotisations",

    //         "liste-projets",
    //         "ajouter-projets",
    //         "modifier-projets",

    //         "liste-slides",
    //         "ajouter-slides",
    //         "modifier-slides"
    //     ]);
    // }

    public function run(): void
    {
        // $allPermissions = [
        //     "liste-roles",
        //     "infos-roles",
        //     "ajouter-roles",
        //     "modifier-roles",
        //     "supprimer-roles",

        //     "liste-permissions",
        //     "infos-permissions",
        //     "ajouter-permissions",
        //     "modifier-permissions",
        //     "supprimer-permissions",

        //     "liste-departements",
        //     "infos-departements",
        //     "ajouter-departements",
        //     "modifier-departements",
        //     "supprimer-departements",

        //     "liste-administrateurs",
        //     "infos-administrateurs",
        //     "ajouter-administrateurs",
        //     "modifier-administrateurs",
        //     "supprimer-administrateurs",
        //     "restaurer-administrateurs",
        //     "changer-statut-administrateurs",
        //     "modifier-permissions-administrateurs",

        //     "liste-utilisateurs",
        //     "infos-utilisateurs",
        //     "ajouter-utilisateurs",
        //     "modifier-utilisateurs",
        //     "supprimer-utilisateurs",
        //     "restaurer-utilisateurs",
        //     "changer-statut-utilisateurs",
        //     "modifier-permissions-utilisateurs",

        //     "liste-paiements",
        //     "infos-paiements",
        //     "ajouter-paiements",
        //     "modifier-paiements",
        //     "supprimer-paiements",
        //     "restaurer-paiements",

        //     "liste-typePieces",
        //     "infos-typePieces",
        //     "ajouter-typePieces",
        //     "modifier-typePieces",
        //     "supprimer-typePieces",
        //     "restaurer-typePieces",

        //     "liste-typeDeclarants",
        //     "infos-typeDeclarants",
        //     "ajouter-typeDeclarants",
        //     "modifier-typeDeclarants",
        //     "supprimer-typeDeclarants",
        //     "restaurer-typeDeclarants",



        //     "liste-incidentSignificatifs",
        //     "infos-incidentSignificatifs",
        //     "ajouter-incidentSignificatifs",
        //     "modifier-incidentSignificatifs",
        //     "supprimer-incidentSignificatifs",
        //     "restaurer-incidentSignificatifs",

        //     // "liste-typesPaiements",
        //     // "infos-typesPaiements",
        //     // "ajouter-typesPaiements",
        //     // "modifier-typesPaiements",
        //     // "supprimer-typesPaiements",
        //     // "restaurer-typesPaiements",

        //     "liste-typesPieces",
        //     "infos-typesPieces",
        //     "ajouter-typesPieces",
        //     "modifier-typesPieces",
        //     "supprimer-typesPieces",
        //     "restaurer-typesPieces",

        //     "liste-sousCategorieIncidents",
        //     "infos-sousCategorieIncidents",
        //     "ajouter-sousCategorieIncidents",
        //     "modifier-sousCategorieIncidents",
        //     "supprimer-sousCategorieIncidents",
        //     "restaurer-sousCategorieIncidents",

        //     "liste-slides",
        //     "infos-slides",
        //     "ajouter-slides",
        //     "modifier-slides",
        //     "supprimer-slides",
        //     "restaurer-slides",

        //     "liste-secteursActivite",
        //     "infos-secteursActivite",
        //     "ajouter-secteursActivite",
        //     "modifier-secteursActivite",
        //     "supprimer-secteursActivite",
        //     "restaurer-secteursActivite",

        //     "liste-ressources",
        //     "infos-ressources",
        //     "ajouter-ressources",
        //     "modifier-ressources",
        //     "supprimer-ressources",
        //     "restaurer-ressources",

        //     "liste-serviceDeclaration",
        //     "infos-serviceDeclaration",
        //     "ajouter-serviceDeclaration",
        //     "modifier-serviceDeclaration",
        //     "supprimer-serviceDeclaration",
        //     "restaurer-serviceDeclaration",

        //     "liste-faqs",
        //     "infos-faqs",
        //     "ajouter-faqs",
        //     "modifier-faqs",
        //     "supprimer-faqs",
        //     "restaurer-faqs",

        //     "liste-actualites",
        //     "infos-actualites",
        //     "ajouter-actualites",
        //     "modifier-actualites",
        //     "supprimer-actualites",
        //     "restaurer-actualites",

        //     "liste-documents",
        //     "infos-documents",
        //     "ajouter-documents",
        //     "modifier-documents",
        //     "supprimer-documents",
        //     "restaurer-documents",


        //     "liste-declarations",
        //     "map-declarations",
        //     "infos-declarations",
        //     "ajouter-declarations",
        //     "modifier-declarations",
        //     "receptionner-declarations",
        //     "analyser-declarations",
        //     "rattacher-declarations",
        //     "cloturer-declarations",
        //     "qualification-declarations",




        //     "liste-services",
        //     "infos-services",
        //     "ajouter-services",
        //     "modifier-services",
        //     "supprimer-services",
        //     "restaurer-services",
        //     "receptionner-services",
        //     "rejeter-services",
        //     // "analyser-documents-demandesAutorisation",

        //     "liste-regions",
        //     "infos-regions",
        //     "ajouter-regions",
        //     "modifier-regions",
        //     "supprimer-regions",
        //     "restaurer-regions",

        //     "liste-villes",
        //     "infos-villes",
        //     "ajouter-villes",
        //     "modifier-villes",
        //     "supprimer-villes",
        //     "restaurer-villes",

        //     "liste-particuliers",
        //     "infos-particuliers",
        //     "ajouter-particuliers",
        //     "modifier-particuliers",
        //     "supprimer-particuliers",
        //     "restaurer-particuliers",


        //     "liste-operateurs",
        //     "infos-operateurs",
        //     "ajouter-operateurs",
        //     "modifier-operateurs",
        //     "supprimer-operateurs",
        //     "restaurer-operateurs",


        //     "liste-departements",
        //     "infos-departements",
        //     "ajouter-departements",
        //     "modifier-departements",
        //     "supprimer-departements",



        //     "liste-natureIncidents",
        //     "infos-natureIncidents",
        //     "ajouter-natureIncidents",
        //     "modifier-natureIncidents",
        //     "supprimer-natureIncidents",
        //     "restaurer-natureIncidents",

        //     "liste-communes",
        //     "infos-communes",
        //     "ajouter-communes",
        //     "modifier-communes",
        //     "supprimer-communes",
        //     "restaurer-communes",

        //     "liste-categorieIncidents",
        //     "infos-categorieIncidents",
        //     "ajouter-categorieIncidents",
        //     "modifier-categorieIncidents",
        //     "supprimer-categorieIncidents",
        //     "restaurer-categorieIncidents",
        //     "approuver-categorieIncidents",
        //     "rejeter-categorieIncidents",

        //     // "liste-badgesLabelisation",
        //     // "infos-badgesLabelisation",
        //     // "ajouter-badgesLabelisation",
        //     // "modifier-badgesLabelisation",
        //     // "supprimer-badgesLabelisation",
        //     // "restaurer-badgesLabelisation",

        //     "liste-statistiques",
        //     "infos-statistiques",
        //     "ajouter-statistiques",
        //     "modifier-statistiques",
        //     "supprimer-statistiques",
        //     "restaurer-statistiques",

        //     "liste-messages",
        //     "infos-messages",
        //     "envoyer-messages",
        //     "repondre-messages",
        //     "modifier-messages",
        //     "supprimer-messages",
        //     "restaurer-messages",

        //     "liste-parametres",
        //     "voir-parametres",
        //     "voir-profil-parametres",
        //     "modifier-profil-parametres",
        //     "modifier-mot-de-passe-parametres",
        //     "modifier-infos-plateforme-parametres",


        //     "voir-montant-total-dashboard",
        //     "voir-total-incident-dashboard",
        //     "voir-total-incident-en-cours-dashboard",
        //     "voir-total-incident-resolus-dashboard",
        //     "voir-total-incident-critique-actif-dashboard",
        //     "voir-total-incident-clotures-dashboard",
        //     "voir-total-incident-non-conformes-dashboard",
        //     "voir-total-utilisateurs-dashboard",
        //     "voir-taux-de-disponibilites-dashboard",

        //     "voir-total-visiteurs-dashboard",

        //     "voir-statistiques-par-periode-dashboard"
        // ];

         $allPermissions = [
            "liste-roles",
            "infos-roles",
            "ajouter-roles",
            "modifier-roles",
            "supprimer-roles",

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


          "liste-documents",
            "infos-documents",
            "ajouter-documents",
            "modifier-documents",
            "supprimer-documents",


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
        // Créer toutes les permissions si elles n'existent pas
        foreach ($allPermissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }
    }
}
