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
        Schema::create('comptes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_compte_id')->constrained('type_comptes','id');
            $table->foreignId('mutualiste_id')->nullable()->unique()->constrained('mutualistes','id');
            $table->string('numero_compte')->unique();
            $table->bigInteger('solde');
            $table->bigInteger('quota')->nullable();
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
        Schema::dropIfExists('comptes');
    }
};
