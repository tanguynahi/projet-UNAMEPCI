<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accompagnements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('administrateur_id')->nullable()->constrained('administrateurs','id');
            $table->foreignId('mutualiste_id')->constrained('mutualistes','id');
            $table->foreignId('type_paiement_id')->constrained('type_paiements','id');
            $table->foreignId('demande_accompagnement_id')->constrained('demande_accompagnements','id');
            $table->string('libelle');
            $table->string('commentaire')->nullable();
            $table->string('contact_tresormoney');
            $table->bigInteger('montant_demande');
            $table->bigInteger('montant_recue')->nullable();
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->double('taux_interet', 3, 1)->default(0.0);
            $table->double('taux_penalite', 3, 1)->default(0.0);
            $table->enum('status',[1,2,3])->default(2);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accompagnements');
    }
};
