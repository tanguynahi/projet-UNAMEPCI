<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

     // les status  1 solde a l'instant , 2 en attente ,3 supprimer ,4 solde ne s'affiche pas chez le mutualiste
    public function up(): void
    {
        Schema::create('cotisation_mutualistes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('administrateur_id')->nullable()->constrained('administrateurs','id');
            $table->foreignId('mutualiste_id')->constrained('mutualistes','id');
            $table->foreignId('cotisation_id')->constrained('cotisations','id');
            $table->foreignId('type_paiement_id')->constrained('type_paiements','id');
            $table->bigInteger('montant');
            $table->string('frequence_paiement');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->enum('status',[1,2,3,4,5])->default(2); // si 4 c'est que la cotisation journaliere a deja ete solde et est dans l'achive et la nouvelle cotisation est active
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotisation_mutualistes');
    }
};
