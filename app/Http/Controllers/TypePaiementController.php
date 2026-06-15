<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\TypePaiement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTypePaiementRequest;
use App\Http\Requests\UpdateTypePaiementRequest;

class TypePaiementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $typepaiements = TypePaiement::orderBy('libelle', 'ASC')->withTrashed()->get();
        $module = "Module Type Paiement ";
        $action = "a consulte la liste des type de paiements";
        Logs::saveLog($module, $action);
        return view('dashboard.typespaiement.index', compact('typepaiements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $module = "Module Type Paiement ";
        $action = "a affiche la page de creation d'un type de paiement";
        Logs::saveLog($module, $action);
        return view('dashboard.typespaiement.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTypePaiementRequest $request)
    {
        try {
            DB::beginTransaction();

            $typepaiement =  TypePaiement::create([
                'libelle' => $request->libelle,
                'description' => $request->description ?? ''
            ]);

            DB::commit();
            $module = "Module Type Paiement ";
            $action = "a ajoute le type de paiement ayant pour id = $typepaiement->id et pour libelle  $typepaiement->libelle";
            Logs::saveLog($module, $action);
            toast('Type de paiement ajouté avec succès !', 'success');

            // Rediriger l'utilisateur ou effectuer d'autres actions
            return redirect()->route('typepaiements.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();


            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module Type Paiement ";
            $action = "une erreur s'est produit lors de l'ajoute d'un type de paiement " . $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TypePaiement $typepaiement)
    {
        $module = "Module Type Paiement ";
        $action = "a affiche la page de detail du type de paiement ayant pour id $typepaiement->id ";
        Logs::saveLog($module, $action);
        return view('dashboard.typespaiement.show', compact('typepaiement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TypePaiement $typepaiement)
    {
        $module = "Module Type Paiement ";
        $action = "a afficher pa page d'edition le type de paiement ayant pour id $typepaiement->id ";
        Logs::saveLog($module, $action);
        return view('dashboard.typespaiement.edit', compact('typepaiement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTypePaiementRequest $request, TypePaiement $typepaiement)
    {
        try {
            DB::beginTransaction();

            // update typePiece libelle if libelle has changed
            if ($typepaiement->libelle !== $request->libelle) {
                $libelleExists = TypePaiement::where('libelle', $request->libelle)->withTrashed()->exists();

                if ($libelleExists) {
                    DB::rollBack();
                    toast("Ce Type de paiement '$request->libelle' existe déjà, mise à jour annulée.", 'error');
                    return redirect()->back();
                }

                $typepaiement->libelle = $request->libelle;
            }

            if ($typepaiement->description !== $request->description) {
                $typepaiement->description = $request->description;
            }

            $typepaiement->save();
            DB::commit();
            $module = "Module Type Paiement ";
            $action = "a modifier le type de paiement ayant pour id $typepaiement->id ";
            Logs::saveLog($module, $action);
            toast('Type de paiement modifié avec succès !', 'success');

            return redirect()->route('typepaiements.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module Type Paiement ";
            $action = "une erreur s'est produit lors de la mise a jour d'un type de paiement " . $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }


    /**
     * Update the specified resource from storage.
     */
    public function restaureTypePaiement($id)
    {
        try {
            DB::beginTransaction();

            $TypePaiementRestaure = TypePaiement::withTrashed()->findOrFail($id);

            $TypePaiementRestaure->status = 1;
            $TypePaiementRestaure->restore();
            $message = "Type de paiement restauré avec succès !";
            // $ville->save();

            DB::commit();

            toast($message, 'success');
            $module = "Module Type Paiement ";
            $action = "a restaure le type de paiement ayant pour id $TypePaiementRestaure->id ";
            Logs::saveLog($module, $action);
            return redirect()->route('typepaiements.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module Type Paiement ";
            $action = "une erreur s'est produit lors du restauration d'un type de paiement " . $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TypePaiement $typepaiement)
    {
        try {
            DB::beginTransaction();

            $message = "";
            # code...
            $typepaiement->status = 2;
            $typepaiement->save();
            $message = "Type de paiement Supprimé avec succès !";
            $typepaiement->delete();


            DB::commit();

            toast($message, 'success');
            $module = "Module Type Paiement ";
            $action = "a supprimer le type de paiement ayant pour id $typepaiement->id ";
            Logs::saveLog($module, $action);
            return redirect()->route('typepaiements.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module Type Paiement ";
            $action = "une erreur s'est produit lors de la suppression d'un type de paiement " . $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }
}
