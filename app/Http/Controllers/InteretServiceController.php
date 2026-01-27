<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\Service;
use App\Models\InteretService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\StoreInteretServiceRequest;
use App\Http\Requests\UpdateInteretServiceRequest;

class InteretServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {}
    public function indexListe($id)
    {
        //
        $InteretServices = InteretService::where('service_id', $id)->orderBy('created_at', 'DESC')->get();
        $libelle = Service::where('id', $id)->first()->value('libelle');
        $module = "Module Interet";
        $action = " a consulter la liste des interet du service : $libelle";
        Logs::saveLog($module, $action);
        return view('dashboard.services.interets.index', compact('InteretServices', 'id', 'libelle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function createInteret($id)
    {
        $module = "Module Interet";
        $action = " a affiche la page de creation d'interet  d'un service";
        Logs::saveLog($module, $action);
        return view('dashboard.services.interets.create', compact('id'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInteretServiceRequest $request)
    {
        try {
            DB::beginTransaction();
            $interet =  InteretService::create([
                'administrateur_id' => auth()->user()->administrateur->id,
                'service_id' => $request->id_service,
                'montant_debut' => $request->montant_debut,
                'montant_fin' => $request->montant_fin,
                'taux_interet' => $request->taux_interet,
                'status' => 1,
            ]);
            DB::commit();
            toast('interêt ajoutée avec succès !', 'success');
            // Rediriger l'utilisateur ou effectuer d'autres actions
            $module = "Module Interet";
            $action = " a enregistre un interet id : $interet->id";
            Logs::saveLog($module, $action);
            return redirect()->route('interetService.liste', ['id' => $request->id_service]);
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();
            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
            // Capturer toute autre exception (erreur 500)
            // Rediriger l'utilisateur ou effectuer d'autres actions
            $module = "Module Interet";
            $action = " une erreur s'est produite lors de l'ajout de l'interet". $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(InteretService $interetService)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InteretService $interetService)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInteretServiceRequest $request, InteretService $interetService)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InteretService $interetService)
    {
        //
    }


    public function recuperation($serviceId, $montant)
    {
        if (empty($serviceId) || empty($montant)) {
            return response()->json(['success' => false, 'message' => 'Service ID et montant sont requis.'], 400);
        }
        if (!is_numeric($montant)) {
            return response()->json(['success' => false, 'message' => 'Montant doit être un nombre.'], 400);
        }
        try {

            $interet = InteretService::where('service_id', $serviceId)
                ->where('montant_debut', '<=', $montant)
                ->where('montant_fin', '>=', $montant)
                ->value('taux_interet');

            if (!empty($interet)) {
                return response()->json(['success' => true, 'interet' => $interet]);
            } else {
                return response()->json(['success' => false, 'message' => 'Aucun taux d\'intérêt trouvé pour ce montant.']);
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération du taux d\'intérêt: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erreur interne du serveur.'], 500);
        }
    }
}
