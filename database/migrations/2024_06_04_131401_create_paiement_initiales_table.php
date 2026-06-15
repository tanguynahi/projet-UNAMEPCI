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
        Schema::create('paiement_initiales', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->nullable(); // lorsque reference est renseigne il s'agit de paiement cash
            $table->string('code_paiement')->nullable(); // lorsque codepaiement est renseigne il s'agit de paiement en ligne
            $table->foreignId('mutualiste_id')->nullable()->constrained('mutualistes', 'id');
            $table->foreignId('type_paiement_id')->constrained('type_paiements', 'id');
            $table->bigInteger('correspondance_id')->nullable(); // correspondance_id est renseigne lorsque le paiement en ligne est effectue pour une mise a jour de paiement effectuer en cash
            $table->bigInteger('montant_initial');
            $table->bigInteger('p_cash')->nullable();
            $table->bigInteger('montant_total')->nullable();
            $table->string('moyen_paiement')->nullable(); //  savoir si c'est en ligne ou pas
            $table->string('contact_paiement')->nullable();
            $table->string('cheque_numero')->nullable();
            $table->string('banque_autre')->nullable();
            $table->string('date_paiement_initial')->nullable();
            $table->string('heure_paiement_initial')->nullable();
            $table->bigInteger('produit_id')->nullable(); // les produits
            $table->foreignId('admin_pay')->nullable();
            // $table->string('document')->nullable(); /// document a implode si sa concerne une mise a jour de paiment effectuer en cash
            $table->enum('status', [1, 2, 3])->default(2); // le status 4 est du a la mise a jour d'information (paiement effectuer en cash)
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiement_initiales');
    }
};
