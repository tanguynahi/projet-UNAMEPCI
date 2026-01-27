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
        
        Schema::create('document_paiements', function (Blueprint $table) {
            $table->id();
            $table->string('facturation_id')->nullable();
            $table->string('pret_id')->nullable();
            $table->foreignId('paiement_initiale_id')->constrained('paiement_initiales','id');
            $table->string('lien_photo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_paiements');
    }
};
