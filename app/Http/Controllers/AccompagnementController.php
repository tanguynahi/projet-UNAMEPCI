<?php

namespace App\Http\Controllers;

use App\Models\DroitAdhesion;
use App\Models\Accompagnement;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreAccompagnementRequest;
use App\Http\Requests\UpdateAccompagnementRequest;
use App\Http\Controllers\Controller;

class AccompagnementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // afficher la page d'acceuil de accompagnement
        // $data['accompagnements'] = Accompagnement::all();

    // return view('home.admin.accompagnement');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        // return view('home.admin.accompagnements.formulaire-accompagement');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccompagnementRequest $request)
    {
        //

    }

    /**
     * Display the specified resource.
     */
    public function show(Accompagnement $accompagnement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Accompagnement $accompagnement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccompagnementRequest $request, Accompagnement $accompagnement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Accompagnement $accompagnement)
    {
        //
    }


}
