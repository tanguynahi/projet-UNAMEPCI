<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Administrateur;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdministrateurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            "email" => "admin@gmail.com",
            "password" => Hash::make('12345678')
        ]);

    // Vérifie si le rôle existe, sinon le crée
        $role = Role::firstOrCreate(['name' => 'super-administrateur']);

        // Récupère toutes les permissions
        $permissions = Permission::all();

        // Attribue toutes les permissions au rôle
        $role->syncPermissions($permissions);

        // Associe le rôle à l'utilisateur
        $user->assignRole($role);


        Administrateur::create([
            "user_id" => $user->id,
            "ville_id" => 1,
            "nom" => "Super",
            "prenom" => "Admin",
            "contact" => "0707070707",
            "email" => $user->email,
            "genre" => "Homme",
            "adresse" => "Abidjan, Cocody Riviera Palmeraie",
        ]);
    }
}
