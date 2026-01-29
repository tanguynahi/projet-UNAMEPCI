<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use Database\Seeders\TaxeSeeder;
use Database\Seeders\CorpsSeeder;
use Database\Seeders\GradeSeeder;
use Database\Seeders\VilleSeeder;
use Database\Seeders\CompteSeeder;
use Illuminate\Support\Facades\DB;
use Database\Seeders\PeriodeSeeder;
use Database\Seeders\ServiceSeeder;
use Database\Seeders\DirectionSeeder;
use Database\Seeders\ParametreSeeder;
use Database\Seeders\TypePieceSeeder;
use Database\Seeders\CotisationSeeder;
use Database\Seeders\roles\RoleSeeder;
use Database\Seeders\SpecialiteSeeder;
use Database\Seeders\TypeCompteSeeder;
use Database\Seeders\TypeDocumentSeeder;
use Database\Seeders\TypePaiementSeeder;
use Database\Seeders\AdministrateurSeeder;
use Database\Seeders\FormeJuridiqueSeeder;
use Database\Seeders\permissions\PermissionSeeder;
use Database\Seeders\assign_permissions_to_role\AssignPermissionsToAdmin;
use Database\Seeders\assign_permissions_to_role\AssignPermissionsToMutualiste;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);


        DB::table('permissions')->delete();
        DB::table('roles')->delete();


        //create roles
        $this->call(RoleSeeder::class);

        //create permission
        $this->call(PermissionSeeder::class);

        //assign permission to admin role
        $this->call(AssignPermissionsToAdmin::class);

        //assign permission to payer
        $this->call(AssignPermissionsToMutualiste::class);

        //create City
        $this->call(VilleSeeder::class);

        //create Direction
        $this->call(DirectionSeeder::class);

        //create Admin
        $this->call(AdministrateurSeeder::class);

        //create Parametre
        $this->call(ParametreSeeder::class);

        //create Corps d'armee
        $this->call(CorpsSeeder::class);

        //create grade
        $this->call(GradeSeeder::class);

        //create periode
        $this->call(PeriodeSeeder::class);

        //create type document
        $this->call(TypeDocumentSeeder::class);

        //create type compte
        $this->call(TypeCompteSeeder::class);

        // create compte
        $this->call(CompteSeeder::class);

        //create type payment
        $this->call(TypePaiementSeeder::class);

        //create type payment
        $this->call(TypePieceSeeder::class);

        // create service
        $this->call(ServiceSeeder::class);

        // create paiement
        $this->call(PaiementSeeder::class);

        $this->call(SpecialiteSeeder::class);
        $this->call(FormeJuridiqueSeeder::class);
        $this->call(TaxeSeeder::class);
        $this->call(CotisationSeeder::class);
    }
}
