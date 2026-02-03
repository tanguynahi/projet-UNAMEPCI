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
        Schema::create('carte_membres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('administrateur_id')->nullable()->constrained('administrateurs', 'id');
            $table->foreignId('mutualiste_id')->unique()->constrained('mutualistes', 'id');
            $table->foreignId('type_paiement_id')->constrained('type_paiements', 'id');
            $table->string('libelle');
            $table->bigInteger('montant');
            $table->date('date_delivre')->nullable();
            $table->date('date_expiration')->nullable();
            $table->enum('genere', [1, 2, 3])->default(2); // en attent de generation
            $table->enum('status', [1, 2])->default(2); // en attent de paiement
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carte_membres');
    }
};
