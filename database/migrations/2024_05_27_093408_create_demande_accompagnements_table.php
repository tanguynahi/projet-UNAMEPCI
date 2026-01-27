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
        Schema::create('demande_accompagnements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('administrateur_id')->nullable()->constrained('administrateurs','id');
            $table->foreignId('mutualiste_id')->constrained('mutualistes','id');
            $table->foreignId('service_id')->constrained('services','id');
            $table->string('contact_tresormoney');
            $table->bigInteger('montant_voulue');
            $table->bigInteger('montant_apayer');
            $table->bigInteger('payer')->nullable();
            $table->string('commentaire')->nullable();
            $table->string('rejet')->nullable();
            $table->enum('status',[1,2,3])->default(2);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demande_accompagnements');
    }
};
