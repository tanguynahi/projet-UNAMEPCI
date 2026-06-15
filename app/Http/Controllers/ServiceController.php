<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $services = Service::orderBy('libelle', 'ASC')->get();
        // dd($services);
        $module = "Module Service ";
        $action = "a consulte la liste des services";
        Logs::saveLog($module, $action);
        return view('dashboard.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $module = "Module Service ";
        $action = "a affiche la page de creation d'un service";
        Logs::saveLog($module, $action);
        return view('dashboard.services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request)
    {
        //

        try {
            DB::beginTransaction();
            $service = Service::create([
                'libelle' => $request->libelle,
                'description' => $request->description,
                'montant_maximum' => $request->montant_maximum,
                'status' => 1,
            ]);
            DB::commit();

            toast('Service ajoutée avec succès !', 'success');
            // Rediriger l'utilisateur ou effectuer d'autres actions
            $module = "Module Service ";
            $action = "a ajouter le service ayant l'id : $service->id et le libelle : $service->libelle";
            Logs::saveLog($module, $action);
            return redirect()->route('services.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();
            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
            // Capturer toute autre exception (erreur 500)
            Log::error('Erreur interne du serveur: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        //
    }

    public function desactiver($id)
    {
        try {
            DB::beginTransaction();
            $service = Service::findOrFail($id);
            $service->status = 2; // Désactiver le service
            $service->save();
            DB::commit();

            toast('Service désactivé avec succès !', 'success');
            // Rediriger l'utilisateur ou effectuer d'autres actions
            $module = "Module Service ";
            $action = "a désactivé le service ayant l'id : $service->id et le libelle : $service->libelle";
            Logs::saveLog($module, $action);
            return redirect()->route('services.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();
            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
            // Capturer toute autre exception (erreur 500)
            Log::error('Erreur interne du serveur: ' . $e->getMessage());
            return redirect()->back();
        }
    }
    public function restaurer($id)
    {
        try {
            DB::beginTransaction();
            $service = Service::findOrFail($id);
            $service->status = 1; // Activer le service
            $service->save();
            DB::commit();

            toast('Service activé avec succès !', 'success');
            // Rediriger l'utilisateur ou effectuer d'autres actions
            $module = "Module Service ";
            $action = "a activé le service ayant l'id : $service->id et le libelle : $service->libelle";
            Logs::saveLog($module, $action);
            return redirect()->route('services.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();
            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
            // Capturer toute autre exception (erreur 500)
            Log::error('Erreur interne du serveur: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
