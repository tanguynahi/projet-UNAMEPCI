<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\Ville;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreVilleRequest;
use App\Http\Requests\UpdateVilleRequest;

class VilleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $villes = Ville::orderBy('libelle', 'ASC')->withTrashed()->get();
        $module = "Module Ville ";
        $action = "a consulte la liste des villes";
        Logs::saveLog($module, $action);
        return view('dashboard.villes.index', compact('villes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $module = "Module Ville ";
        $action = "a affiche la page de creation d'une ville";
        Logs::saveLog($module, $action);
        return view('dashboard.villes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVilleRequest $request)
    {
        try {
            DB::beginTransaction();

        $ville =  Ville::create([
                'libelle' => $request->libelle,
                'description' => $request->description ?? ''
            ]);

            DB::commit();
            $module = "Module Ville ";
            $action = "a ajoute la ville $ville->libelle , ayant pour id : $ville->id";
            Logs::saveLog($module, $action);
            toast('Ville ajoutée avec succès !', 'success');

            // Rediriger l'utilisateur ou effectuer d'autres actions
            return redirect()->route('villes.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();
            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module Ville ";
            $action = "une erreur s'est produit lors de la creation d'une ville ". $e->getMessage();
            Logs::saveLog($module, $action);

            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Ville $ville)
    {
        $module = "Module Ville ";
        $action = "a affiche la page de detail de la ville $ville->libelle ayant pour id $ville->id";
        Logs::saveLog($module, $action);
        return view('dashboard.villes.show', compact('ville'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ville $ville)
    {
        $module = "Module Ville ";
        $action = "a affiche la page d'edition de la ville $ville->libelle ayant pour id $ville->id";
        Logs::saveLog($module, $action);
        return view('dashboard.villes.edit', compact('ville'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVilleRequest $request, Ville $ville)
    {
        try {
            DB::beginTransaction();

            // update ville label if label has changed
            if ($ville->libelle !== $request->libelle) {
                $libelleExists = Ville::where('libelle', $request->libelle)->withTrashed()->exists();

                if ($libelleExists) {
                    DB::rollBack();
                    toast("Cette ville '$request->libelle' existe déjà, mise à jour annulée.", 'error');
                    return redirect()->back();
                }
                $ville->libelle = $request->libelle;
            }

            if ($ville->description !== $request->description) {
                $ville->description = $request->description;
            }

            $ville->save();
            DB::commit();
            $module = "Module Ville ";
            $action = "a modifier  la ville $ville->libelle ayant pour id $ville->id";
            Logs::saveLog($module, $action);
            toast('Ville modifiée avec succès !', 'success');

            return redirect()->route('villes.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module Ville ";
            $action = "une erreur s'est produit lors de la modification d'une ville ". $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function restaureVille($id)
    {
        try {
            DB::beginTransaction();

            $VilleRestaure = Ville::withTrashed()->findOrFail($id);

            $VilleRestaure->status = 1;
            $VilleRestaure->restore();
            $message = "Ville restaurée avec succès !";

            DB::commit();

            toast($message, 'success');
            $module = "Module Ville ";
            $action = "a modifier  la ville $VilleRestaure->libelle ayant pour id $VilleRestaure->id";
            Logs::saveLog($module, $action);
            return redirect()->route('villes.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module Ville ";
            $action = "une erreur s'est produit lors de la restauration  d'une ville ". $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }



    public function destroy(Ville $ville)
    {
        try {
            DB::beginTransaction();

            $message = "";
            # code...
            $ville->status = 2;
            $ville->save();
            $message = "Ville Supprimée avec succès !";
            $ville->delete();

            $module = "Module Ville ";
            $action = "a supprimer la ville ayant pour id $ville->id , libelle : $ville->libelle ";
            Logs::saveLog($module, $action);

            DB::commit();

            toast($message, 'success');

            return redirect()->route('villes.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            $module = "Module Ville ";
            $action = "une erreur s'est produit lors de la suppression d'une ville ". $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }
}
