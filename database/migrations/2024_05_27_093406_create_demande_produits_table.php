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
        Schema::create('demande_produits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('administrateur_id')->nullable()->constrained('administrateurs','id');
            $table->foreignId('mutualiste_id')->constrained('mutualistes','id');
            $table->foreignId('produit_projet_id')->constrained('produit_projets','id');
            $table->string('commentaire')->nullable();
            $table->bigInteger('montant');
            $table->enum('status',[1,2,3,4])->default(2);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demande_produits');
    }
};
