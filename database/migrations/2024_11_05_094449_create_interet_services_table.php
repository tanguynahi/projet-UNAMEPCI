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
        Schema::create('interet_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('administrateur_id')->nullable()->constrained('administrateurs', 'id');
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade')->nullable();
            $table->integer('montant_debut');
            $table->integer('montant_fin');
            $table->integer('taux_interet');
            $table->enum('status',[1,2])->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('interet_services', function (Blueprint $table) {
            if (Schema::hasColumn('interet_services', 'deleted_at')) {
                $table->dropSoftDeletes(); // Supprime la colonne `deleted_at` uniquement si elle existe
            }
        });
    }
};
