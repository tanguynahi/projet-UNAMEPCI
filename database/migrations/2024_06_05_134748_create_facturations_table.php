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
        Schema::create('facturations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('administrateur_id')->constrained('administrateurs','id')->nullable();
            $table->foreignId('mutualiste_id')->constrained('mutualistes','id');
            $table->foreignId('projet_mutualiste_id')->constrained('projet_mutualistes','id');
            $table->foreignId('produit_projet_id')->constrained('produit_projets','id');
            $table->foreignId('redevance_id')->constrained('redevances','id');
            $table->foreignId('periode_id')->constrained('periodes','id');
            $table->bigInteger('total_apayer');
            $table->bigInteger('montant_periodique')->nullable();
            $table->integer('frequence')->nullable();
            $table->bigInteger('total_payer')->nullable();
            $table->bigInteger('reste_apayer')->nullable();
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->date('date_facturation');
            $table->date('date_prochain_paiement')->nullable();
            $table->enum('status',[1,2,3,4])->default(1); // 4 pour masque cher le mutualiste
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturations');
    }
};
