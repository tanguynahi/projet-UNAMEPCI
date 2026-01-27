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
        Schema::create('fichier_joint_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained('messages','id');
            // $table->foreignId('administrateur_id')->constrained('administrateur','id'); // l'administrateur conserner
            $table->foreignId('conversation_id')->constrained('conversations','id');
            $table->string('lien_document')->nullable();
            $table->enum('statut',[1,2])->default(2);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fichier_joint_messages');
    }
};
