<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\Direction;
use App\Models\Cotisation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCotisationRequest;
use App\Http\Requests\UpdateCotisationRequest;

class CotisationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $cotisations = Cotisation::orderBy('libelle', 'ASC')->withTrashed()->get();
        $module = "Module Cotisation";
        $action = "a consulte la liste des cotisations";
        Logs::saveLog($module, $action);
        return view('dashboard.cotisations.index', compact('cotisations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $directions = Direction::orderBy('libelle', 'ASC')->withTrashed()->get();
        $module = "Module Cotisation";
        $action = "a afficher la page de creation d'une cotisations";
        Logs::saveLog($module, $action);
        return view('dashboard.cotisations.create', compact('directions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCotisationRequest $request)
    {
        try {
            DB::beginTransaction();

            $cotisationExists = Cotisation::where('direction_id', $request->direction_id)
                ->where('libelle', $request->libelle)
                ->exists();

            if ($cotisationExists) {
                DB::rollBack();
                toast("Cette cotisation '$request->libelle' existe déjà pour cette direction, ajout annulé.", 'error');
                return redirect()->back();
            }



            Cotisation::create([
                'direction_id' => $request->direction_id,
                'libelle' => $request->libelle,
                'montant_a_payer' => $request->montant_a_payer,
                'frequence_paiement' => $request->frequence_paiement,
                'date_debut' => $request->date_debut,
                'date_fin' => $request->date_fin,
                'status' => 1,
            ]);

            DB::commit();

            toast('Cotisation ajoutée avec succès !', 'success');
            $module = "Module Cotisation";
            $action = "a ajouter la cotisation : $request->libelle ";
            Logs::saveLog($module, $action);
            // Rediriger l'utilisateur ou effectuer d'autres actions
            return redirect()->route('cotisations.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();
            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
            // Capturer toute autre exception (erreur 500)
            Log::error('Erreur interne du serveur: ' . $e->getMessage());
            $module = "Module Cotisation";
            $action = "Une erreur s'est produite lors de la creation d'une cotisation  ". $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cotisation $cotisation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cotisation $cotisation)
    {
        // dd($cotisation);
        $directions = Direction::orderBy('libelle', 'ASC')->withTrashed()->get();
        $module = "Module Cotisation";
        $action = "A affiche la page edition de la cotisation : $cotisation->libelle ";
        Logs::saveLog($module, $action);
        return view('dashboard.cotisations.edit', compact('cotisation', 'directions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCotisationRequest $request, Cotisation $cotisation)
    {

        try {
            DB::beginTransaction();
            $cotisationExists = Cotisation::where('direction_id', $request->direction_id)
                ->where('libelle', $request->libelle)
                ->where('montant_a_payer', $request->montant_a_payer)
                ->where('frequence_paiement', $request->frequence_paiement)
                ->where('date_debut', $request->date_debut)
                ->where('date_fin', $request->date_fin)
                ->exists();

            if ($cotisationExists) {
                DB::rollBack();
                toast("Cette cotisation  existe déjà pour cette direction, mise a jour annulé.", 'error');
                return redirect()->back();
            }
            $cotisation->direction_id = $request->direction_id;
            $cotisation->libelle = $request->libelle;
            $cotisation->montant_a_payer = $request->montant_a_payer;
            $cotisation->frequence_paiement = $request->frequence_paiement;
            $cotisation->date_debut = $request->date_debut;
            $cotisation->date_fin = $request->date_fin;

            // N'oubliez pas de sauvegarder l'objet après avoir assigné les valeurs
            $cotisation->save();


            DB::commit();

            toast('Cotisation mise a jour avec succès !', 'success');
            $module = "Module Cotisation";
            $action = "A modifier une cotisation nouveau nom : $request->libelle ";
            Logs::saveLog($module, $action);
            // Rediriger l'utilisateur ou effectuer d'autres actions
            return redirect()->route('cotisations.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            toast('Une erreur s\'est produite, veuillez réessayer.', 'error');
            Log::error('Erreur interne du serveur: ' . $e->getMessage());
            $module = "Module Cotisation";
            $action = "Une erreur s'est produite lors de la mise a jour d'une cotisation ";
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cotisation $cotisation)
    {
        //
    }
}
