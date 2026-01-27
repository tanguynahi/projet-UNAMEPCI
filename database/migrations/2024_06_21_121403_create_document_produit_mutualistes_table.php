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
        Schema::create('document_produit_mutualistes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('administrateur_id')->nullable()->constrained('administrateurs','id');
            $table->foreignId('demande_produit_id')->constrained('demande_produits','id');
            $table->foreignId('produit_projet_id')->constrained('produit_projets','id');
            $table->foreignId('type_document_id')->constrained('type_documents','id');
            $table->string('lien_document')->nullable();
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
        Schema::dropIfExists('document_produit_mutualistes');
    }
};
