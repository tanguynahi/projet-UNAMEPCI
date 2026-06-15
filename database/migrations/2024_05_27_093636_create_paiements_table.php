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
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->nullable(); // lorsque reference est renseigne il s'agit de paiement cash
            $table->string('code_paiement')->nullable(); // lorsque codepaiement est renseigne il s'agit de paiement en ligne
            $table->foreignId('mutualiste_id')->nullable()->constrained('mutualistes', 'id');
            $table->foreignId('type_paiement_id')->constrained('type_paiements', 'id');
            $table->bigInteger('correspondance_id')->nullable(); // les facturations
            $table->bigInteger('montant_initial');
            $table->bigInteger('p_cash')->nullable();
            $table->bigInteger('montant_total');
            $table->string('moyen_paiement');
            $table->string('contact_paiement');
            $table->bigInteger('produit_id')->nullable(); // le produit
            $table->string('cheque_numero')->nullable(); // ajouter le numero de cheque
            $table->string('banque_autre')->nullable(); // ajouter le nom de la banque
            $table->string('date_paiement_final')->nullable();
            $table->string('heure_paiement_final')->nullable();
            $table->foreignId('admin_pay')->nullable();
            $table->enum('status', [1, 2, 3])->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
