<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\TypeCompte;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTypeCompteRequest;
use App\Http\Requests\UpdateTypeCompteRequest;

class TypeCompteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $typecomptes = TypeCompte::orderBy('libelle', 'ASC')->withTrashed()->get();
        $module = "Module Type Compte ";
        $action = "a consulte la liste des type de compte";
        Logs::saveLog($module, $action);
        return view('dashboard.typescompte.index',compact('typecomptes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $module = "Module Type Compte ";
        $action = "a affiche la page de creation d'un type de compte";
        Logs::saveLog($module, $action);
        return view('dashboard.typescompte.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTypeCompteRequest $request)
    {
        try {
            DB::beginTransaction();

          $typeComptes =  TypeCompte::create([
                'libelle' => $request->libelle,
                'description' => $request->description ?? ''
            ]);

            DB::commit();
            $module = "Module Type Compte ";
            $action = "a ajouter un type de compte ayant l'id : $typeComptes->id";
            Logs::saveLog($module, $action);

            toast('Type de compte ajouté avec succès !', 'success');

            // Rediriger l'utilisateur ou effectuer d'autres actions
            return redirect()->route('typecomptes.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();


            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module Type Compte ";
            $action = "une erreur s'est produite lors de l'ajout d'un type de compte ". $e->getMessage();
            Logs::saveLog($module, $action);

            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TypeCompte $typecompte)
    {
        $module = "Module Type Compte ";
        $action = "a consulte les detail d'un type de compte ayant l'id : $typecompte->id ";
        Logs::saveLog($module, $action);
        return view('dashboard.typescompte.show', compact('typecompte'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TypeCompte $typecompte)
    {
        $module = "Module Type Compte ";
        $action = "a consulte la page d'edition d'un type de compte ayant l'id : $typecompte->id ";
        Logs::saveLog($module, $action);
        return view('dashboard.typescompte.edit', compact('typecompte'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTypeCompteRequest $request, TypeCompte $typecompte)
    {
        try {
            DB::beginTransaction();

            // update typePiece libelle if libelle has changed
            if ($typecompte->libelle !== $request->libelle) {
                $libelleExists = TypeCompte::where('libelle', $request->libelle)->withTrashed()->exists();

                if ($libelleExists) {
                    DB::rollBack();
                    toast("Ce Type de compte '$request->libelle' existe déjà, mise à jour annulée.", 'error');
                    return redirect()->back();
                }

                $typecompte->libelle = $request->libelle;
            }

            if ($typecompte->description !== $request->description) {
                $typecompte->description = $request->description;
            }

            $typecompte->save();
            DB::commit();

            $module = "Module Type Compte ";
            $action = "a modifier un type de compte ayant l'id : $typecompte->id";
            Logs::saveLog($module, $action);

            toast('Type de compte modifié avec succès !', 'success');

            return redirect()->route('typecomptes.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module Type Compte ";
            $action = "une erreur s'est produite lors de la mise a jour d'un type de compte ". $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource from storage.
     */
    public function restaureTypeCompte($id)
    {
        try {
            DB::beginTransaction();

            $TypeCompteRestaure = TypeCompte::withTrashed()->findOrFail($id);

            $TypeCompteRestaure->status = 1;
            $TypeCompteRestaure->restore();
            $message = "Type de compte restauré avec succès !";
            // $ville->save();

            DB::commit();
            $module = "Module Type Compte ";
            $action = "a restaure le type de compte ayant pour id : $id";
            Logs::saveLog($module, $action);

            toast($message, 'success');

            return redirect()->route('typecomptes.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module Type Compte ";
            $action = "une erreur s'est produite lors de la restauration d'un type de compte ". $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TypeCompte $typecompte)
    {
        try {
            DB::beginTransaction();

            $message = "";
            # code...
            $typecompte->status = 2;
            $typecompte->save();
            $message = "Type de compte Supprimé avec succès !";
            $typecompte->delete();


            DB::commit();

            toast($message, 'success');
            $module = "Module Type Compte ";
            $action = "a supprime le type de compte ayant pour id : $typecompte->id";
            Logs::saveLog($module, $action);
            return redirect()->route('typecomptes.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            $module = "Module Type Compte ";
            $action = "une erreur s'est produite lors de la suppression d'un type de compte ". $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }
}
