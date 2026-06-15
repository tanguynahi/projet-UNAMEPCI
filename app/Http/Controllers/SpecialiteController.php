<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\Specialite;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorespecialiteRequest;
use App\Http\Requests\UpdatespecialiteRequest;

class SpecialiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $specialites = Specialite::all();
        return view('dashboard.specialites.index', compact('specialites'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('dashboard.specialites.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorespecialiteRequest $request)
    {
        //
        try {
            DB::beginTransaction();
            $specialite = new Specialite();
            $specialite->libelle = $request->libelle;
            $specialite->description = $request->description;
            $specialite->status = 1;
            $specialite->save();
            DB::commit();
            toast('Specialite  ajouté avec succès !', 'success');
            return redirect()->route('specialites.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();
            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module Specialite  ";
            $action = "une erreur s'est produite lors de l'ajout d'une Specialite  " . $e->getMessage();
            Logs::saveLog($module, $action);

            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Specialite $specialite)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $specialite = Specialite::findOrFail($id);
        return view('dashboard.specialites.edit', compact('specialite'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatespecialiteRequest $request,  $id)
    {
        //
           try {
            DB::beginTransaction();
            $specialite =  Specialite::findOrFail($id);
            $specialite->libelle = $request->libelle;
            $specialite->description = $request->description;
            $specialite->status = 1;
            $specialite->save();
            DB::commit();
            toast('Specialite  mise a jour avec succès !', 'success');
            return redirect()->route('specialites.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();
            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module Specialite  ";
            $action = "une erreur s'est produite lors de la mise a jour d'une Specialite  " . $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Specialite $specialite)
    {
        //
    }
}
