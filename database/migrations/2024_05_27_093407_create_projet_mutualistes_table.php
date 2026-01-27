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
        Schema::create('projet_mutualistes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('administrateur_id')->nullable()->constrained('administrateurs','id');
            $table->foreignId('mutualiste_id')->constrained('mutualistes','id');
            $table->foreignId('demande_produit_id')->constrained('demande_produits','id');
            $table->foreignId('produit_projet_id')->constrained('produit_projets','id');
            $table->string('lien_photo');
            $table->string('libelle');
            $table->bigInteger('montant_produit');
            $table->bigInteger('total_apayer');
            $table->date('date');
            $table->string('commentaire')->nullable();
            $table->enum('status',[1,2,3])->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projet_mutualistes');
    }
};
