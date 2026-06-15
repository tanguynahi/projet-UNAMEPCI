<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\Compte;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompteRequest;
use App\Http\Requests\UpdateCompteRequest;

class CompteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comptes = Compte::whereTypeCompteId(2)->orderBy('created_at', 'DESC')->withTrashed()->get();
        $module = "Module Compte ";
        $action = "a consulté la liste des comptés";
        Logs::saveLog($module, $action);
        return view('dashboard.comptes.index',compact('comptes'));
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
    public function store(StoreCompteRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Compte $compte)
    {

        $module = "Module Compte ";
        $action = "a consulté le details d'un compte id: $compte->id ";
        Logs::saveLog($module, $action);
        return view('dashboard.comptes.show',compact('compte'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Compte $compte)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompteRequest $request, Compte $compte)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Compte $compte)
    {
        //
    }
}
