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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained('messages','id');
            $table->text('message')->nullable();
            $table->enum('statut',[1,2])->default(2); // voir le status du message (lu ou pas encore lu) si le recepteur a vu
            $table->enum('recepteur',[1,2,3])->default(2); // a qui le message es destiner (mutualiste, administrateur et particulier) 2 = administrateur 3 = particulier
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
