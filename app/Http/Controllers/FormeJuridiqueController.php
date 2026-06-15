<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\FormeJuridique;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreformeJuridiqueRequest;
use App\Http\Requests\UpdateformeJuridiqueRequest;

class FormeJuridiqueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $formejuridiques = FormeJuridique::orderBy('libelle', 'ASC')->get();
        $module = "Module Forme Juridiques ";
        $action = "a consulte la liste de Forme Juridique";
        Logs::saveLog($module, $action);
        return view('dashboard.formesJuridiques.index', compact('formejuridiques'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('dashboard.formesJuridiques.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreformeJuridiqueRequest $request)
    {
        //

        try {
            DB::beginTransaction();
            $formeJuridique = new FormeJuridique();
            $formeJuridique->libelle = $request->libelle;
            $formeJuridique->description = $request->description;
            $formeJuridique->status = 1;
            $formeJuridique->save();
            DB::commit();
            toast('Forme Juridique  ajouté avec succès !', 'success');
            return redirect()->route('formejuridiques.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();
            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module Forme Juridique  ";
            $action = "une erreur s'est produite lors de l'ajout d'une Forme Juridique  " . $e->getMessage();
            Logs::saveLog($module, $action);

            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(FormeJuridique $formeJuridique)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $formeJuridique = FormeJuridique::findOrFail($id);
        return view('dashboard.formesJuridiques.edit', compact('formeJuridique'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateformeJuridiqueRequest $request,  $id)
    {
        try {
            DB::beginTransaction();
            $formeJuridique = FormeJuridique::findOrFail($id);
            $formeJuridique->libelle = $request->libelle;
            $formeJuridique->description = $request->description;
            $formeJuridique->status = 1;
            $formeJuridique->save();
            DB::commit();
            toast('Forme Juridique  mise a jour avec succès !', 'success');
            return redirect()->route('formejuridiques.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();
            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module Forme Juridique  ";
            $action = "une erreur s'est produite lors de la modification d'une Forme Juridique  " . $e->getMessage();
            Logs::saveLog($module, $action);

            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FormeJuridique $formeJuridique)
    {
        //
    }
}
