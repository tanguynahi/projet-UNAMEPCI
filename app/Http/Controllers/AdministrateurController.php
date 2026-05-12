<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\User;
use App\Models\Ville;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Administrateur;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\StoreAdministrateurRequest;
use App\Http\Requests\UpdateAdministrateurRequest;

class AdministrateurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $administrateurs = Administrateur::where('id', '<>', 1)->orderBy('created_at', 'DESC')->get();
$administrateurs = Administrateur::whereHas('user.roles', function ($query) {
        // $query->where('name', 'super-administrateur');
        $query->where('name', 'administrateur');
    })
    ->orderBy('created_at', 'DESC')
    ->get();
        $villes = Ville::orderBy('libelle', 'ASC')->get();
        $module = "Module Administrateur ";
        $action = " a consulté la liste des administrateurs ";
        Logs::saveLog($module, $action);
        return view('dashboard.admins.index', compact(['administrateurs', 'villes']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $villes = Ville::orderBy('libelle', 'ASC')->get();

        $roles = Role::where('id', '!=', 1)
            ->where('id', '!=', 3)
            ->where('id', '!=', 4)
            ->orderBy('name', 'asc')
            ->get();
        // dd($roles);
        return view('dashboard.admins.create', compact('villes', 'roles'));
    }





    public function store(StoreAdministrateurRequest $request)
    {
        try {

            DB::beginTransaction();

            $request->validated();

            // Création user
            $user = User::create([
                "email" => $request->email,
                "password" => Hash::make($request->password)
            ]);

            // Roles
            $roleIds = $request->input('role_id');

            $roleNames = Role::whereIn('id', (array) $roleIds)
                ->pluck('name')
                ->toArray();

            $user->syncRoles($roleNames);

            // Upload photo
            $lien_photo = null;
            if ($request->hasFile('lien_photo')) {
                $file_name = Carbon::now()->timestamp . '.' . $request->lien_photo->extension();
                $request->lien_photo->storeAs('images-administrateurs/', $file_name);
                $lien_photo = 'src-files/images-administrateurs/' . $file_name;
            }

            // retirer champs inutiles
            $data = [
                "nom" => $request->nom,
                "prenom" => $request->prenom,
                "ville_id" => $request->ville_id,
                "adresse" => $request->adresse,
                "contact" => $request->contact,
                "genre" => $request->genre,
                "email" => $request->email,
                "user_id" => $user->id,
                "lien_photo" => $lien_photo,
            ];

            Administrateur::create($data);

            DB::commit();

            toast('Compte administrateur créé avec succès !', 'success');

            return redirect()->route('administrateurs.index');
        } catch (\Exception $e) {

            DB::rollback();

            toast('Une erreur s\'est produite, veuillez réessayer.', 'error');

            Log::error('Erreur interne du serveur: ' . $e->getMessage());

            return redirect()->back();
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Administrateur $administrateur)
    {
        //
        $us = Auth()->user()->administrateur;
        $roles = Role::where('id', '!=', 1)
            ->where('id', '!=', 3)
            ->where('id', '!=', 4)
            ->orderBy('name', 'asc')
            ->get();

        // Récupérer les IDs des rôles actuels de l'utilisateur (pour la pré-sélection dans le formulaire)
        $userRoles = $administrateur->user->roles->pluck('id')->toArray();


        $roleNames = $administrateur->user->getRoleNames();

        // Récupère toutes les permissions liées aux rôles
        $permissionsViaRoles = collect();
        foreach ($roleNames as $roleName) {
            $role = Role::findByName($roleName);
            $permissionsViaRoles = $permissionsViaRoles->merge($role->permissions);
        }

        // Toutes les permissions sauf celles des rôles
        $permissions = Permission::all()->diff($permissionsViaRoles);

        // Grouper par type
        $groupedPermissions = $permissions->groupBy(function ($permission) {
            return Str::afterLast($permission->name, '-'); // ex: 'edit' dans 'articles-edit'
        });


        return view('dashboard.admins.show', compact(
            'administrateur',
            'roles',
            'userRoles',
            'groupedPermissions',
            'roleNames',
            'permissionsViaRoles',
            'us'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Administrateur $administrateur)
    {
        //
        $villes = Ville::orderBy('libelle', 'ASC')->get();

        $roles = Role::where('id', '!=', 3)
            ->where('id', '!=', 4)
            ->orderBy('name', 'asc')
            ->get();
        return view('dashboard.admins.edit', compact('villes', 'roles', 'administrateur'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdministrateurRequest $request, Administrateur $administrateur)
    {


          try {

            DB::beginTransaction();

            $request->validated();

            // Création user
            $user = User::where('email', $administrateur->email)->first();


            // Roles
            $roleIds = $request->input('role_id');

            $roleNames = Role::whereIn('id', (array) $roleIds)
                ->pluck('name')
                ->toArray();

            $user->syncRoles($roleNames);

            // Upload photo
            $administrateur = Administrateur::findOrFail($administrateur->id);
            $lien_photo = null;
            if ($request->hasFile('lien_photo')) {
                $file_name = Carbon::now()->timestamp . '.' . $request->lien_photo->extension();
                $request->lien_photo->storeAs('images-administrateurs/', $file_name);
                $lien_photo = 'src-files/images-administrateurs/' . $file_name;
            }

            // retirer champs inutiles
            $data = [
                "nom" => $request->nom,
                "prenom" => $request->prenom,
                "ville_id" => $request->ville_id,
                "adresse" => $request->adresse,
                "contact" => $request->contact,
                "genre" => $request->genre,
                "email" => $request->email,
                "user_id" => $user->id,
                "lien_photo" => $lien_photo,
            ];

            $administrateur->update($data);

            DB::commit();

            toast('Compte administrateur mis à jour avec succès !', 'success');

            return redirect()->route('administrateurs.index');
        } catch (\Exception $e) {

            DB::rollback();

            toast('Une erreur s\'est produite, veuillez réessayer.', 'error');

            Log::error('Erreur interne du serveur: ' . $e->getMessage());

            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Administrateur $administrateur)
    {
        try {
            //code...
            DB::beginTransaction();

            $userId = $administrateur->user_id;
            $user = User::findOrFail($userId);

            if ($administrateur->lien_photo) {
                delete_file($administrateur->lien_photo);
            }

            $administrateur->delete();
            $user->delete();

            if ($administrateur->user) {
                $administrateur->user->delete();
            }


            DB::commit();

            toast('Administrateur Supprimé avec succès', 'success');
            $module = "Module Administrateur ";
            $action = "Un administrateur a étè supprimé avec succès";
            Logs::saveLog($module, $action);
            return redirect()->back();
        } catch (\Exception $e) {
            //throw $th;
            // dd($th);
            DB::rollback();

            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
            // Capturer toute autre exception (erreur 500)
            Log::error('Erreur interne du serveur: ' . $e->getMessage());

            return redirect()->back();
        }
    }


    // permissions
    public function listePermission($id)
    {
        $us = Auth()->user()->administrateur;
        $administrateur = Administrateur::findOrFail($id);
        $user = $administrateur->user;
        $roleNames = $user->getRoleNames();

        // Permissions héritées des rôles
        $permissionsViaRoles = collect();
        foreach ($roleNames as $roleName) {
            $role = Role::findByName($roleName);
            $permissionsViaRoles = $permissionsViaRoles->merge($role->permissions);
        }
        $permissionsViaRoles = $permissionsViaRoles->unique();

        // Permissions directes de l'utilisateur
        $directPermissions = $user->getDirectPermissions();
        $directPermissionNames = $directPermissions->pluck('name')->toArray();

        // Toutes les permissions sauf celles des rôles (pour les proposer)
        $availablePermissions = Permission::all()->diff($permissionsViaRoles);

        // Grouper par type
        $groupedPermissions = $availablePermissions->groupBy(function ($permission) {
            return Str::afterLast($permission->name, '-');
        });

        return view('dashboard.admins.permission', compact(
            'administrateur',
            'groupedPermissions',
            'roleNames',
            'us',
            'permissionsViaRoles',
            'directPermissions',
            'directPermissionNames'
        ));
    }


    public function permissionStoreAdmin(Request $request, $id)
    {

        try {
            $administrateur = Administrateur::findOrFail($id);
            $user = $administrateur->user;

            // Vérifier si l'utilisateur lié existe
            if (!$user) {
                Log::error("Utilisateur introuvable pour l'administrateur ID : {$administrateur->id}");
                toast('Utilisateur non trouvé.', 'error');
                return back()->withErrors(['error' => 'Utilisateur non trouvé.']);
            }

            // Valider les permissions
            $validated = $request->validate([
                'permissions' => 'array',
                'permissions.*' => 'string|distinct',
            ]);

            DB::beginTransaction();

            // Synchroniser uniquement les permissions directes
            $permissions = $validated['permissions'] ?? [];
            $user->syncPermissions($permissions);

            DB::commit();

            toast('Permissions mises à jour avec succès.', 'success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error("Erreur lors de la mise à jour des permissions : " . $e->getMessage());

            toast("Erreur lors de la mise à jour des permissions", 'error');

            $action = "Erreur lors de la mise à jour des permissions de l'utilisateur {$administrateur->user->nom} {$administrateur->user->prenom} : " . $e->getMessage();
            Logs::saveLog("Mise à jour des permissions de l'utilisateur", $action);

            return redirect()->back()->withInput();
        }
    }

    // profils
    public function profilAdministrateur()
    {
        $user = Auth()->user()->administrateur;
        return view('dashboard.profils', compact('user'));
    }

    public function traitementProfil(Request $request)
    {

        try {

            DB::beginTransaction();
            $administrateur = Auth()->user()->administrateur;

            if ($request->hasFile('lien_photo')) {
                $file_name = Carbon::now()->timestamp . '.' . $request->lien_photo->extension();
                $request->lien_photo->storeAs('images-administrateurs/', $file_name);
                $lien_photo = 'src-files/images-administrateurs/' . $file_name;
                $administrateur->lien_photo = $lien_photo;
            }
            $administrateur->nom = $request->nom;
            $administrateur->prenom = $request->prenom;
            $administrateur->email = $request->email;
            $administrateur->contact = $request->contact;
            $administrateur->adresse = $request->adresse;
            $administrateur->genre = $request->genre;
            $administrateur->save();
            DB::commit();

            toast('Profils Supprimé avec succès', 'success');
            $module = "Module Administrateur ";
            $action = "Un administrateur a mi a jour ses informations  avec succès";
            Logs::saveLog($module, $action);

            return redirect()->back();
        } catch (\Exception $e) {
            //throw $th;
            // dd($th);
            DB::rollback();

            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
            // Capturer toute autre exception (erreur 500)
            Log::error('Erreur interne du serveur: ' . $e->getMessage());

            return redirect()->back();
        }
    }
    // public function traitementAcces(Request $request)
    // {

    //     dd($request->all());
    //     try {

    //         DB::beginTransaction();
    //         $administrateur = Auth()->user()->administrateur;
    //         $user = User::findOrFail($administrateur->user_id);

    //         if ($user && password_verify($request->password, $user->password)) {
    //             $user->password = Hash::make($request->new_password_confirmation);
    //             $user->save();
    //         } else {
    //         }

    //         DB::commit();

    //         toast('Profils Supprimé avec succès', 'success');
    //         $module = "Module Administrateur ";
    //         $action = "Un administrateur a mi a jour ses actes  avec succès";
    //         Logs::saveLog($module, $action);

    //         return redirect()->back();
    //     } catch (\Exception $e) {
    //         //throw $th;
    //         // dd($th);
    //         DB::rollback();

    //         toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
    //         // Capturer toute autre exception (erreur 500)
    //         Log::error('Erreur interne du serveur: ' . $e->getMessage());

    //         return redirect()->back();
    //     }
    // }


    public function traitementAcces(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required|string|min:8',
        ], [
            'current_password.required' => 'Le mot de passe actuel est obligatoire.',
            'new_password.required' => 'Le nouveau mot de passe est obligatoire.',
            'new_password.min' => 'Le nouveau mot de passe doit contenir au moins 8 caractères.',
            'new_password.confirmed' => 'Les nouveaux mots de passe ne correspondent pas.',
        ]);

        try {
            DB::beginTransaction();

            // Récupération de l'utilisateur connecté
            $user = Auth::user();

            if (!$user) {
                throw new \Exception('Utilisateur non authentifié.');
            }

            // Vérification du mot de passe actuel
            if (!Hash::check($request->current_password, $user->password)) {
                DB::rollback();

                toast('Le mot de passe actuel est incorrect.', 'error');
                return redirect()->back()
                    ->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.'])
                    ->withInput();
            }

            // Vérification que le nouveau mot de passe est différent de l'ancien
            if (Hash::check($request->new_password, $user->password)) {
                DB::rollback();

                toast('Le nouveau mot de passe doit être différent de l\'ancien.', 'error');
                return redirect()->back()
                    ->withErrors(['new_password' => 'Le nouveau mot de passe doit être différent de l\'ancien.'])
                    ->withInput();
            }

            // Mise à jour du mot de passe
            $user->password = Hash::make($request->new_password);
            $user->save();

            DB::commit();

            // Journalisation des logs
            $module = "Module Administrateur";
            $action = "L'administrateur " . ($user->administrateur->nom ?? 'N/A') . " " . ($user->administrateur->prenom ?? 'N/A') . " a modifié son mot de passe avec succès";

            if (class_exists('Logs')) {
                Logs::saveLog($module, $action);
            }

            // Notification de succès
            toast('Votre mot de passe a été modifié avec succès.', 'success');

            // Optionnel: Déconnecter l'utilisateur pour qu'il se reconnecte avec le nouveau mot de passe
            // Auth::logout();
            // return redirect()->route('login')->with('success', 'Mot de passe modifié. Veuillez vous reconnecter.');

            return redirect()->back();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollback();
            Log::error('Utilisateur non trouvé: ' . $e->getMessage());

            toast('Utilisateur non trouvé.', 'error');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();

            // Log détaillé de l'erreur
            Log::error('Erreur lors du changement de mot de passe: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => Auth::id() ?? null
            ]);

            toast('Une erreur s\'est produite lors du changement de mot de passe. Veuillez réessayer.', 'error');
            return redirect()->back();
        }
    }
}
