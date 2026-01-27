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
        Schema::create('equipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('administrateur_id')->constrained('administrateurs','id');
            $table->foreignId('grade_id')->constrained('grades','id');
            $table->string('unite');
            $table->string('nom');
            $table->string('prenom');
            $table->string('poste');
            $table->string('adresse')->nullable();
            $table->string('lien_image');
            $table->enum('status',[1,2])->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipes');
    }
};
