<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Logs;
use App\Models\Message;
use App\Models\Parametre;
use App\Models\Mutualiste;
use App\Models\CarteMembre;
use App\Models\Conversation;
use App\Http\Requests\StoreCarteMembreRequest;
use App\Http\Requests\UpdateCarteMembreRequest;

class CarteMembreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $module = "Module Carte Membres ";
        $action = " a consulté la liste des Carte Membres ";
        Logs::saveLog($module, $action);
        $carteMembres = CarteMembre::orderBy('created_at', 'ASC')->whereIn('status', [1,2])->whereIn('genere', [1,3,2])->get();
        // dd($carteMembres);
        return view('dashboard.cartesMembres.index', compact('carteMembres'));
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
    public function store(StoreCarteMembreRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $CarteMembre = CarteMembre::findOrFail($id);
        if ($CarteMembre->genere == 2) {
            $CarteMembre->genere = 1; // Générée
            $CarteMembre->date_delivre = Carbon::now();
            $CarteMembre->date_expiration = Carbon::now()->addYear();
            $CarteMembre->save();

            // envoyer message a l'utilisateurs
            $message = new Message();
            $message->mutualiste_id = $CarteMembre->mutualiste_id;
            $message->sujet = 'Votre carte UNAMEPCI est prête';
            $message->email = $CarteMembre->mutualiste->email;
            $message->statut = 1;
            $message->save();
            // conversation
            $conversation = new Conversation();
            $conversation->message_id = $message->id;
            $conversation->message = 'Nous avons le plaisir de vous informer que votre carte de membre UNAMEPCI est désormais disponible.
                Votre carte physique est actuellement en cours de préparation et vous sera remise très prochainement.
                En attendant, vous pouvez dès à présent consulter votre carte virtuelle depuis votre espace personnel.
                Nous vous invitons également à vérifier vos informations personnelles (nom, prénoms, photo, matricule, etc.).
                Si vous constatez une erreur, merci de la corriger directement dans votre profil ou de contacter l’administrateur afin que la mise à jour soit effectuée avant l’impression définitive de votre carte.
                Cette vérification est très importante pour garantir l’exactitude des informations figurant sur votre carte.

                Cordialement,
                L’équipe UNAMEPCI';
            $conversation->statut = 2;
            $conversation->recepteur = 1;
            $conversation->save();
        }

        $mutualiste = Mutualiste::findOrFail($CarteMembre->mutualiste_id);
        $parametre = Parametre::findOrFail(1);
        return view('dashboard.cartesMembres.show', compact('mutualiste', 'CarteMembre','parametre'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CarteMembre $carteMembre)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCarteMembreRequest $request, CarteMembre $carteMembre)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CarteMembre $carteMembre)
    {
        //
    }
}
