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
        Schema::create('produit_projets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('administrateur_id')->nullable()->constrained('administrateurs','id');
            $table->foreignId('projet_id')->constrained('projets','id');
            $table->foreignId('type_paiement_id')->constrained('type_paiements','id');
            $table->string('lien_photo');
            $table->string('libelle');
            $table->bigInteger('cout');
            $table->bigInteger('contribution');
            $table->integer('quantite')->nullable();
            $table->longText('description')->nullable();
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
        Schema::dropIfExists('produit_projets');
    }
};
