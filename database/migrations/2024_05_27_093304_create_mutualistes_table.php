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
        Schema::create('mutualistes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users','id');
            $table->foreignId('corp_id')->nullable()->constrained('corps','id');
            $table->foreignId('grade_id')->nullable()->constrained('grades','id');
            $table->foreignId('ville_id')->nullable()->constrained('villes','id');
            $table->foreignId('type_piece_id')->nullable()->constrained('type_pieces','id');
            $table->string('numero_piece')->nullable();
            $table->date('date_etablissement_piece')->nullable();
            $table->string('lieu_etablissement_piece')->nullable();
            $table->string('matricule')->unique();
            $table->string('unite')->nullable();
            $table->string('nom');
            $table->string('prenom');
            $table->string('contact')->unique()->nullable();
            $table->string('contact_2')->unique()->nullable();
            $table->string('email')->unique();
            $table->string('adresse')->nullable();
            $table->string("genre")->nullable();
            $table->date("date_naissance")->nullable();
            $table->string("lieu_naissance")->nullable();
            $table->string("lien_photo")->nullable();
            $table->string("photo_couverture")->nullable();
            $table->string("documents")->nullable();
            $table->text('lien_email')->nullable();
            $table->text('code')->unique();// code a envoyer dans le liens car l'id n'est pas securiser 
            $table->enum('disponibilite',['hors ligne','en ligne'])->default('hors ligne');
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
        Schema::dropIfExists('mutualistes');
    }
};
