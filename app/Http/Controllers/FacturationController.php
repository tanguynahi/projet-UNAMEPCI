<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\Periode;
use App\Models\Redevance;
use App\Models\Mutualiste;
use App\Models\Facturation;
use Illuminate\Http\Request;
use App\Models\ProjetMutualiste;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreFacturationRequest;
use App\Http\Requests\UpdateFacturationRequest;

class FacturationController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:super-administrateur|administrateur')->only(['index', 'create', 'store', 'show', 'edit', 'update']);
        $this->middleware('role:super-administrateur')->only(['destroy', 'restaureFacturation']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $idAdmin = auth()->user()->administrateur;
        if (etreAdmin() == true) {
            $facturations = Facturation::where('administrateur_id', '!=', null)
                ->orderBy('created_at', 'DESC')
                ->get();
        } else {
            $facturations = Facturation::where('administrateur_id', $idAdmin->id)
                ->orderBy('created_at', 'DESC')
                ->get();
        }
        $module = "Module Facturation";
        $action = "a consulte la liste des facturations";
        Logs::saveLog($module, $action);
        return view('dashboard.facturations.index', compact('facturations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Récupérer tous les mutualistes avec une entrée dans droitAdhesion où le status est égal à 1
        // et où le status du mutualiste est également égal à 1
        $mutualistes = Mutualiste::join('droit_adhesions', 'mutualistes.id', '=', 'droit_adhesions.mutualiste_id')
            ->where('mutualistes.status', 1)
            ->where('droit_adhesions.status', 1)
            ->orderBy('mutualistes.nom', 'ASC')
            ->select('mutualistes.*')
            ->get();

        $redevances = Redevance::where('status', 1)->orderBy('libelle', 'ASC')->get();
        $module = "Module Facturation";
        $action = "a affiche la page de creation d'une facturations";
        Logs::saveLog($module, $action);
        return view('dashboard.facturations.create', compact('mutualistes', 'redevances'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFacturationRequest $request)
    {
        // Log::info('Request received', $request->all());
        // dd($request->all());
        try {
            DB::beginTransaction();
            // Recherche du projet mutualiste par son ID
            $projetmutualiste = ProjetMutualiste::findOrFail($request->projet_mutualiste_id);

            $facturationExists = Facturation::where([
                ['mutualiste_id', $request->mutualiste_id],
                ['projet_mutualiste_id', $projetmutualiste->id],
                ['produit_projet_id', $projetmutualiste->produit_projet_id],
                ['redevance_id', $request->redevance_id], // Correction here
                ['periode_id', $request->periode_id],
            ])->exists();

            if ($facturationExists) {
                toast('Facturation déjà existante!', 'error');

                // Retourner les erreurs de validation avec les messages toast
                $errors = [
                    'facturation_exists' => 'Facturation déjà existante pour ce projet et cette période.',
                ];
                return redirect()->back()->withInput();
            }

            // si période correspond a (Immediat, Aperiodique ou Journalière)
            if ($request->periode_id == 1 || $request->periode_id == 2 || $request->periode_id == 6) {

                $data = $request->only(['mutualiste_id', 'redevance_id', 'periode_id', 'total_apayer', 'reste_apayer', 'date_facturation', 'date_fin_paiement', 'date_debut', 'montant_periodique']);
                // Validation des données pour date_facturation à partir de $data
                if ($request->periode_id == 2) {
                    $validationDate = Validator::make($data, [
                        'date_facturation' => 'required|date',
                        'date_fin_paiement' => 'required|date',
                        'date_debut' => 'required|date',
                        'montant_periodique' => 'required|numeric|min:500|multiple_of:100',

                    ], [
                        'date_facturation.required' => 'La date de facturation est requise.',
                        'date_facturation.date' => 'La date de facturation est invalide.',

                        'date_fin_paiement.required' => 'La date de fin est requise',
                        'date_fin_paiement.date' => 'La date de fin est invalide.',
                        'date_debut.required' => 'La date de debut est requise',
                        'date_debut.date' => 'La date de debut est invalide.',
                        'montant_periodique.required' => 'Le montant périodique est requis.',
                        'montant_periodique.numeric' => 'Le montant périodique doit être un nombre.',
                        'montant_periodique.min' => 'Le montant périodique doit être supérieur ou égal à 500.',
                        'montant_periodique.multiple_of' => 'Le montant périodique doit être un multiple de 100.',

                    ]);
                } else {
                    $validationDate = Validator::make($data, [
                        'date_facturation' => 'required|date',
                        // 'date_fin_paiement' => 'required|date',
                        // 'date_debut' => 'required|date',
                    ], [
                        'date_facturation.required' => 'La date de facturation est requise.',
                        'date_facturation.date' => 'La date de facturation est invalide.',

                        // 'date_fin_paiement.required' => 'La date de fin est requise',
                        // 'date_fin_paiement.date' => 'La date de fin est invalide.',
                        // 'date_debut.required' => 'La date de debut est requise',
                        // 'date_debut.date' => 'La date de debut est invalide.',
                    ]);
                }

                if ($validationDate->fails()) {
                    // Si la validation échoue, récupérez les erreurs et redirigez ou effectuez une action appropriée
                    return redirect()->back()->withErrors($validationDate)->withInput();
                }

                $facturation = Facturation::create([
                    'administrateur_id' => auth()->user()->administrateur->id,
                    'mutualiste_id' => $data['mutualiste_id'],
                    'projet_mutualiste_id' => $projetmutualiste->id,
                    'produit_projet_id' => $projetmutualiste->produit_projet_id,
                    'redevance_id' => $data['redevance_id'],
                    'periode_id' => $data['periode_id'],
                    'total_apayer' => $data['total_apayer'],
                    'total_payer' => 0,
                    'montant_periodique' => $data['montant_periodique'],
                    'reste_apayer' => $data['total_apayer'],
                    'date_facturation' => $data['date_facturation'],
                    'date_debut' => $data['date_debut'],
                    'date_fin' => $data['date_fin_paiement'],
                ]);

                if ($facturation) {
                    $module = "Module Facturation";
                    $action = "facturation ajouter avec succes id : $facturation->id";
                    Logs::saveLog($module, $action);
                }
                // dd($facturation);
            } else { // si période corrrespond a (Hebdomadere,Mensuelle, Annuelle)

                $data = $request->only(['total_apayer', 'montant_periodique', 'frequence', 'date_debut', 'date_fin', 'date_facturation_2']);
                // dd($data);

                // Validation des données pour date_facturation
                $validationData = Validator::make($data, [
                    'montant_periodique' => 'required|numeric|min:500|multiple_of:100',
                    'frequence' => 'required|integer|min:1',
                    'date_debut' => 'required|date',
                    'date_fin' => 'required|date',
                    'date_facturation_2' => 'required|date',
                ], [
                    'montant_periodique.required' => 'Le montant périodique est requis.',
                    'montant_periodique.numeric' => 'Le montant périodique doit être un nombre.',
                    'montant_periodique.min' => 'Le montant périodique doit être supérieur ou égal à 500.',
                    'montant_periodique.multiple_of' => 'Le montant périodique doit être un multiple de 100.',
                    'frequence.required' => 'La fréquence est requise.',
                    'frequence.integer' => 'La fréquence doit être un nombre entier.',
                    'frequence.min' => 'La fréquence doit être supérieur ou égal à 1.',
                    'date_debut.required' => 'La date de début est requise.',
                    'date_debut.date' => 'La date de début est invalide.',
                    'date_fin.required' => 'La date de fin est requise',
                    'date_fin.date' => 'La date de fin est invalide.',
                    'date_facturation_2.required' => 'La date de facturation est requise.',
                    'date_facturation_2.date' => 'La date de facturation est invalide.',
                ]);

                if ($validationData->fails()) {
                    // Si la validation échoue, récupérez les erreurs et redirigez ou effectuez une action appropriée
                    return redirect()->back()->withErrors($validationData)->withInput();
                }

                $facturation = Facturation::create([
                    'administrateur_id' => auth()->user()->administrateur->id,
                    'mutualiste_id' => $request->mutualiste_id,
                    'projet_mutualiste_id' => $projetmutualiste->id,
                    'produit_projet_id' => $projetmutualiste->produit_projet_id,
                    'redevance_id' => $request->redevance_id,
                    'periode_id' => $request->periode_id,
                    'total_apayer' => $data['total_apayer'],
                    'montant_periodique' => $data['montant_periodique'],
                    'frequence' => $data['frequence'],
                    'total_payer' => 0,
                    'reste_apayer' => $data['total_apayer'],
                    'date_debut' => $data['date_debut'],
                    'date_fin' => $data['date_fin'],
                    'date_facturation' => $data['date_facturation_2'],
                ]);
                if ($facturation) {
                    $module = "Module Facturation";
                    $action = "facturation ajouter avec succes id : $facturation->id";
                    Logs::saveLog($module, $action);
                }

                // dd($facturation);
            }

            DB::commit();

            toast('Facturation ajouté avec succès !', 'success');
            $module = "Module Facturation";
            $action = "a enregistre une facturations";
            Logs::saveLog($module, $action);
            return redirect()->route('facturations.index');
        } catch (\Exception $e) {

            DB::rollBack();

            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module Facturation";
            $action = "une erreur s'est produite lors de l'ajoute d'une facturation";
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Facturation $facturation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Facturation $facturation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFacturationRequest $request, Facturation $facturation)
    {
        //
    }

    public function getProjetAcquisMutualistes($mutualisteId)
    {
        try {
            //code...
            $mutualiste = Mutualiste::find($mutualisteId);
            $projetmutualistes = ProjetMutualiste::where('mutualiste_id', $mutualiste->id)
                ->whereStatus("1")
                ->with('produitProjet')
                ->get();

            $projets = $projetmutualistes->map(function ($projetmutualiste) {
                return [
                    'projet_mutualiste_id' => $projetmutualiste->id,
                    'produit_projet_id' => $projetmutualiste->produit_projet_id,
                    'projet' => $projetmutualiste->produitProjet->projet->libelle,
                    'lien_photo' => $projetmutualiste->lien_photo,
                    'libelle' => $projetmutualiste->libelle,
                    'total_apayer' => $projetmutualiste->total_apayer,
                    'date' => $projetmutualiste->date,
                ];
            });

            return response()->json($projets);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => "Aucun projets mutualiste",
                'status' => 'error',
            ], 500);
        }
    }

    public function projetAcquisDetails($projetAcquisId)
    {
        try {
            $projetmutualiste = ProjetMutualiste::where('id', $projetAcquisId)
                ->whereStatus("1")
                ->first();

            if ($projetmutualiste) {
                return response()->json([
                    'total_apayer' => $projetmutualiste->total_apayer,
                    'date' => $projetmutualiste->date
                ]);
            } else {
                return response()->json([
                    'message' => "Aucun projet mutualiste trouvé",
                    'status' => 'error',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Une erreur s'est produite",
                'status' => 'error',
            ], 500);
        }
    }


    public function getRedevancePeriodes($redevanceId)
    {
        try {
            // Trouver la redevance par son ID
            $redevance = Redevance::find($redevanceId);

            // Récupérer la période associée à cette redevance
            $redevancePeriode = Periode::where('id', $redevance->periode_id)
                ->whereStatus(1)
                ->orderBy('libelle', 'ASC')
                ->select('id', 'libelle')
                ->first();

            // Vérifier si une période a été trouvée
            if ($redevancePeriode) {
                // Retourner la période dans un tableau d'objets
                return response()->json([$redevancePeriode]);
            } else {
                return response()->json([
                    'message' => "Aucune période pour cette redevance",
                    'status' => 'error',
                ], 404);
            }
        } catch (\Throwable $th) {
            // Gérer les erreurs et retourner une réponse JSON avec un message d'erreur
            return response()->json([
                'message' => "Une erreur est survenue",
                'status' => 'error',
            ], 500);
        }
    }


    public function restaureFacturation($id)
    {
        try {
            DB::beginTransaction();

            $facturationRestaure = Facturation::withTrashed()->findOrFail($id);

            $facturationRestaure->status = 1;
            $facturationRestaure->restore();
            $message = "Facturation restaurée avec succès !";

            DB::commit();

            toast($message, 'success');
            $module = "Module Facturation";
            $action = "facturation restaure id : $facturationRestaure->id";
            Logs::saveLog($module, $action);

            return redirect()->route('facturations.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module Facturation";
            $action = "Une erreur s'est produit lors de la restauration d'une facturations" . $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Facturation $facturation)
    {
        try {
            DB::beginTransaction();

            $message = "";
            # code...
            $facturation->status = 2;
            $facturation->save();
            $message = "Facturation Supprimée avec succès !";
            $facturation->delete();

            DB::commit();

            toast($message, 'success');
            $module = "Module Facturation";
            $action = "facturation supprimée $facturation->id";
            Logs::saveLog($module, $action);
            return redirect()->route('facturations.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)

            $module = "Module Facturation";
            $action = "Une erreur s'est produit lors de la suppression d'une facturations" . $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }
}
