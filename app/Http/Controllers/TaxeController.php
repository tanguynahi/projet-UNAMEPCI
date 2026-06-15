<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\Taxe;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaxeRequest;
use App\Http\Requests\UpdateTaxeRequest;

class TaxeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $taxes = Taxe::orderBy('libelle', 'ASC')->get();
        $module = "Module Taxe ";
        $action = "a consulte la liste des taxes";
        Logs::saveLog($module, $action);
        return view('dashboard.taxes.index', compact('taxes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaxeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Taxe $taxe)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $taxe = Taxe::findOrFail($id);
        return view('dashboard.taxes.edit', compact('taxe'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaxeRequest $request,  $id)
    {
        //
        // dd($request->all());
        try {
            DB::beginTransaction();
            $taxe = Taxe::findOrFail($id);
            $taxe->libelle = $request->libelle;
            $taxe->montant = $request->montant;
            $taxe->description = $request->description;
            $taxe->save();
            DB::commit();
            toast('Taxe ajouté avec succès !', 'success');
            return redirect()->route('taxes.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();
            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module Taxe ";
            $action = "une erreur s'est produite lors de l'ajout d'un taxe " . $e->getMessage();
            Logs::saveLog($module, $action);

            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Taxe $taxe)
    {
        //
    }
}
