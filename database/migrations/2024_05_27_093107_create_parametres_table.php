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
        Schema::create('parametres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('administrateur_id')->constrained('administrateurs','id');
            $table->string('mode_theme')->nullable();
            $table->string('nom_site_web')->nullable();
            $table->string('lien_logo')->nullable();
            $table->text('mot_du_directeur')->nullable();
            $table->string('contact_1')->nullable();
            $table->string('contact_2')->nullable();
            $table->string('email_1')->nullable();
            $table->string('email_2')->nullable();
            $table->string('adresse')->nullable();
            $table->text('lien_photo_directeur')->nullable();
            $table->string('lien_video')->nullable();
            $table->string('lien_facebook')->nullable();
            $table->string('lien_twitter')->nullable();
            $table->string('lien_instagram')->nullable();
            $table->string('lien_linkedin')->nullable();
            $table->string('lien_youtube')->nullable();
            $table->string('lien_whatsapp')->nullable();
            $table->text('lien_google_map')->nullable();
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
        Schema::dropIfExists('parametres');
    }
};
