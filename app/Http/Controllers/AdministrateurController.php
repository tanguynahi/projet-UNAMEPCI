<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\User;
use App\Models\Ville;
use App\Models\Administrateur;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreAdministrateurRequest;
use App\Http\Requests\UpdateAdministrateurRequest;

class AdministrateurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $administrateurs = Administrateur::where('id', '<>', 1)->orderBy('created_at', 'DESC')->get();
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
        $module = "Module Administrateur ";
        $action = "a affiché la page de création d'un administrateur  ";
        Logs::saveLog($module, $action);
        return view('dashboard.admins.create', compact('villes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdministrateurRequest $request)
    {
        try {
            DB::beginTransaction();

            // Récupérer les données validées
            $request->validated();
            //create user account
            $user = User::create([
                "email" => $request->email,
                "password" => Hash::make($request->password)
            ]);

            // assigner un role
            $user->assignRole('administrateur');

            if ($request->hasFile('lien_photo')) {
                $file_name = Carbon::now()->timestamp . '.' . $request->lien_photo->extension();
                $request->lien_photo->storeAs('images-administrateurs/', $file_name);
                $lien_photo = 'src-files/images-administrateurs/' . $file_name;
            }

            $data = $request->except(["email", "password"]);

            $data["user_id"] = $user->id;
            $data['ville_id'] = $request->ville_id;
            $data['nom'] = $request->nom;
            $data['prenom'] = $request->prenom;
            $data['email'] = $request->email ?? "";
            $data['contact'] = $request->contact;
            $data['adresse'] = $request->adresse ?? "";
            $data['genre'] = $request->genre;
            $data['lien_photo'] = $lien_photo ?? null;

            // creer administrateur
            Administrateur::create($data);

            DB::commit();

            // Envoyer un message de succès
            // Alert::success('Succès', 'Administrateur créer avec succès.');
            toast('Compte administrateur créer avec succès !', 'success');
            $module = "Module Administrateur ";
            $action = "a enregistre l'administrateur : $request->nom , $request->prenom ";
            Logs::saveLog($module, $action);
            // Rediriger l'utilisateur ou effectuer d'autres actions
            return redirect()->route('administrateurs.index');

        } catch (\Exception $e) {
            //throw $th;
            // dd($th);
            DB::rollback();

            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
            // Capturer toute autre exception (erreur 500)
            Log::error('Erreur interne du serveur: ' . $e->getMessage());
            $module = "Module Administrateur ";
            $action = "Une erreur s'est produite lors de l'enregistrement d'un administrateur" . $e->getMessage();
            Logs::saveLog($module, $action);

            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Administrateur $administrateur)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Administrateur $administrateur)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdministrateurRequest $request, Administrateur $administrateur)
    {
        //
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

            // if ($administrateur->user) {
            //     $administrateur->user->delete();
            // }


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
}
