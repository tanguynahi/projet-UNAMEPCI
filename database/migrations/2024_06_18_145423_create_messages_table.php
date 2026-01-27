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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mutualiste_id')->nullable()->constrained('mutualistes','id');
            $table->text('sujet')->nullable();
            $table->string('email')->nullable();
            $table->enum('statut',[1,2,3])->default(2); // voir le status du message (lu ou pas encore lu)
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
