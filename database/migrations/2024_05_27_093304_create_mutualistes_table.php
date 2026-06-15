<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mutualistes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users', 'id');
            $table->enum('typeAdhesion', ['nouveau', 'revision', 'modification'])->nullable();
            $table->string("lien_photo")->nullable();
            $table->string('nom');
            $table->string('prenom');
            $table->string('contact')->nullable()->unique();
            $table->string('contact_2')->nullable()->unique();
            // $table->string('fax')->nullable();
            $table->string('email')->unique();
            $table->string('adresse')->nullable();
            $table->enum('civilite', ['M.', 'Mme', 'Mlle'])->default('M.');
            $table->date("date_naissance")->nullable();
            $table->string("lieu_naissance")->nullable();
            $table->string("nationalite")->nullable();
            $table->string('situation_matrimoniale')->nullable();
            // $table->string('nombre_charge')->nullable();
            $table->date("date_adhesion_unamepci")->nullable();
            // document d'identification
            $table->foreignId('type_piece_id')->nullable()->constrained('type_pieces', 'id');
            $table->string('numero_piece')->nullable();
            $table->date('date_etablissement_piece')->nullable();
            $table->string('lieu_etablissement_piece')->nullable();
            $table->string("pieces_joints_recto")->nullable();
            $table->string("pieces_joints_verso")->nullable();
            $table->string('numero_inscription_ONMCI')->nullable();
            // $table->string('pseudonyme_recon_ONMCI')->nullable();
            //- infos taf principale
            $table->string('matricule')->unique();
            $table->string("raison_social_primaire")->nullable();
            $table->foreignId('specialite_id')->nullable()->constrained('specialites', 'id');
            $table->string("fonction")->nullable();
            $table->date("date_debut_metier")->nullable();
            $table->string("nombre_annee_experience")->nullable();
            $table->string("nom_employeur_principale")->nullable();
            $table->string("statut_emploi")->nullable();
            // $table->string("domaine_activite")->nullable();
            $table->date("date_recrutement")->nullable();
            // $table->string("montant_cotis_annuel")->nullable();
            $table->string("sigle")->nullable();
            $table->date("date_creation")->nullable();
            $table->string("numero_autorisation")->nullable();
            $table->string("num_immatriculation")->nullable();
            $table->foreignId('forme_juridique_id')->nullable()->constrained('forme_juridiques', 'id');
            $table->string("precise_forme_juridique")->nullable();
            $table->foreignId('ville_id')->nullable()->constrained('villes', 'id');
            $table->string("commune")->nullable();
            $table->string("quartier")->nullable();
            $table->string("rue")->nullable();
            $table->string("adresse_postale_entreprise")->nullable();
            $table->string("localisation_entreprise")->nullable();
            $table->string("email_entreprise")->nullable();
            $table->string("telephone_entreprise")->nullable();
            $table->string("fax_entreprise")->nullable();

            // $table->tinyInteger('relation_tiers')->default(0);
            // $table->string("nom_relation")->nullable();
            // $table->tinyInteger('etre_auteur')->default(0);
            // $table->string("nom_auteur")->nullable();


            $table->date("date_expiration_piece")->nullable();
            $table->foreignId('ville_personnel_id')->nullable()->constrained('villes', 'id');
            $table->string("commune_personnel")->nullable();
            $table->string("niveau_intervention")->nullable();
            $table->string("precise_intervention")->nullable();



            //- info taf freelance
            // $table->string("raison_social_secondaire_freelance")->nullable();
            // $table->string("fonction_occupe_freelance")->nullable();
            // $table->string("type_contrat_freelance")->nullable();
            // $table->string("telephone_freelance")->nullable();
            // $table->string("fax_freelance")->nullable();
            // $table->string("localisation_freelance")->nullable();
            // $table->string("adresse_postale_freelance")->nullable();
            // $table->string("domaine_activite_freelance")->nullable();
            // //-> document
            // $table->string("avatar")->nullable();
            $table->string("photo_couverture")->nullable();
            $table->string("document_carte_inscript_ONMCI")->nullable();
            $table->string("document_autorisation_ouverture")->nullable();
            $table->string("photo_identite_1")->nullable();

            $table->string("signature")->nullable();
            $table->enum('disponibilite', ['hors ligne', 'en ligne'])->default('hors ligne');
            $table->text("lien_email")->nullable();
            $table->text("message")->nullable();
            $table->text('code')->unique(); // code a envoyer dans le liens car l'id n'est pas securiser
            $table->text('codePlay')->nullable(); // code a envoyer dans le liens car l'id n'est pas securiser
            $table->enum('status', [1, 2, 3,4])->default(2); // 4 en attent de paiement apres exportation de la liste des mutualistes pour le paiement en ligne
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutualistes');
    }
};
