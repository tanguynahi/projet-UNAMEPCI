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
        Schema::create('droit_adhesions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('administrateur_id')->nullable()->constrained('administrateurs','id');
            $table->foreignId('mutualiste_id')->constrained('mutualistes','id');
            $table->foreignId('type_paiement_id')->constrained('type_paiements','id');
            $table->string('libelle');
            $table->bigInteger('montant');
            $table->enum('status',[1,2])->default(2);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('droit_adhesions');
    }
};
