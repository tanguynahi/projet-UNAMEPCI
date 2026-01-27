<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Logs;
use App\Models\Cotisation;
use App\Models\Mutualiste;
use Illuminate\Http\Request;
use App\Models\DroitAdhesion;
use App\Models\PaiementInitiale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\CotisationMutualiste;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\StoreCotisationMutualisteRequest;
use App\Http\Requests\UpdateCotisationMutualisteRequest;

class CotisationMutualisteController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    // les status  1 solde a l'instant , 2 en attente ,3 supprimer ,4 solde ne s'affiche pas chez le mutualiste
    public function index()
    {
        //
        // $cotisationMutualistes = CotisationMutualiste::all();
        $idAdmin = auth()->user()->administrateur;
        if (etreAdmin() == true) {
            // si il est super admin
            $cotisationMutualistes = CotisationMutualiste::where('administrateur_id', '!=', null)
                ->orderBy('created_at', 'DESC')
                ->get();
        } else {
            $cotisationMutualistes = CotisationMutualiste::where('administrateur_id', $idAdmin->id)
                ->orderBy('created_at', 'DESC')
                ->get();
        }
        $module = "Module Cotisation Mutualiste";
        $action = "A consultés la liste d'attribution d'une cotisation mutualiste ";
        Logs::saveLog($module, $action);
        return view('dashboard.cotisation_mutualistes.index', compact('cotisationMutualistes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cotisations = Cotisation::orderBy('libelle', 'ASC')->withTrashed()->get();
        $mutualistes = Mutualiste::orderBy('nom', 'ASC')->withTrashed()->get();
        $module = "Module Cotisation Mutualiste";
        $action = "A affiche la page d'attribution d'une cotisation a un mutualiste ";
        Logs::saveLog($module, $action);
        return view('dashboard.cotisation_mutualistes.create', compact('cotisations', 'mutualistes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCotisationMutualisteRequest $request)
    {
        //
        // dd($request->all());
        try {
            DB::beginTransaction();

            $cotisationExists = CotisationMutualiste::where('mutualiste_id', $request->mutualiste_id)
                ->where('cotisation_id', $request->cotisation_id)
                ->where('administrateur_id', '!=', null)
                ->exists();

            if ($cotisationExists) {
                DB::rollBack();
                toast("Cette cotisation  existe déjà pour cette mutualiste, ajout annulé.", 'error');
                return redirect()->back();
            }
            $cotisation = Cotisation::where('id', $request->cotisation_id)->first();

            $mutualisteCotisation = CotisationMutualiste::create([
                'administrateur_id' => auth()->user()->administrateur->id,
                'mutualiste_id' => $request->mutualiste_id,
                'cotisation_id' => $request->cotisation_id,
                'type_paiement_id' => 2,
                'montant' => $cotisation->montant_a_payer,
                'frequence_paiement' => $cotisation->frequence_paiement,
                'date_debut' => $cotisation->date_debut,
                'date_fin' => $cotisation->date_fin,
                'status' => 2,
            ]);
            DB::commit();
            toast('Cotisation Attribuée avec succès !', 'success');
            // Rediriger l'utilisateur ou effectuer d'autres actions
            $module = "Module Cotisation Mutualiste";
            $action = "A attribut la cotisation id :" . $mutualisteCotisation->cotisation->libelle . "au mutualiste : " . $mutualisteCotisation->mutualiste->nom . " " . $mutualisteCotisation->mutualiste->prenom;
            Logs::saveLog($module, $action);

            return redirect()->route('cotisationmutualistes.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();
            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
            // Capturer toute autre exception (erreur 500)
            Log::error('Erreur interne du serveur: ' . $e->getMessage());
            $module = "Module Cotisation Mutualiste";
            $action = "une erreur s'est produite lors de l'attribution d'une cotisation a un mutualiste" . $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CotisationMutualiste $cotisationMutualiste)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CotisationMutualiste $cotisationMutualiste)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCotisationMutualisteRequest $request, CotisationMutualiste $cotisationMutualiste)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CotisationMutualiste $cotisationMutualiste)
    {
        //
    }

    // ligne de paiement des cotisations sur l'espaces adminstrateurs
    public function ligneDetailPaiement($idCoti, $idMutual)
    {
        // dd($idMutual,$idCoti);

        $PaiementCotisations = CotisationMutualiste::where('cotisation_id', $idCoti)
            ->where('mutualiste_id', $idMutual)
            ->whereIn('status', [4])
            ->get();
        // dd($PaiementCotisations);
        $libelleCotisation = Cotisation::where('id', $idCoti)->value('libelle');
        $mutualiste = Mutualiste::where('id', $idMutual)->first();
        $module = "Module Cotisation Mutualiste";
        $action = "A consulter les ligne de paiement de la cotisation : $libelleCotisation  du mutualiste :$mutualiste->nom , $mutualiste->prenom ";
        Logs::saveLog($module, $action);
        return view('dashboard.cotisation_mutualistes.show', compact('libelleCotisation', 'mutualiste', 'PaiementCotisations'));
    }



    // chez le mutualiste
    public function listeCotisationMutualiste()
    {

        $contCoti = NbreCotisation();
        $mutualiste = auth()->user()->mutualiste;
        $cotisations = CotisationMutualiste::where('mutualiste_id', $mutualiste->id)
            ->whereIn('status', [1, 2, 5])
            ->get();
        $dateActuel = Carbon::now()->format('Y-m-d');
        $module = "Module Cotisation Mutualiste";
        $action = "Le mutualiste $mutualiste->nom , $mutualiste->prenom a consulte la liste de ses cotisations";
        Logs::saveLog($module, $action);
        return view('home.admin.cotisations.index', compact('cotisations', 'contCoti', 'dateActuel'));
    }

    public function paiementCotisations($id)
    {

        $cotisationMutualiste = CotisationMutualiste::where('id', $id)->first();
        try {
            DB::beginTransaction();
            // initialisation du code de paiement avec une valeur unique
            $codePaiement = generateCode2('Cot');
            // creation d'un nouveau element dans la table PaiementInitiale (debut)
            $paiementinit = new PaiementInitiale();
            $paiementinit->code_paiement = $codePaiement;
            $paiementinit->mutualiste_id = auth()->user()->mutualiste->id;
            $paiementinit->type_paiement_id = 2;
            $paiementinit->correspondance_id = $cotisationMutualiste->id; // id facturations
            $paiementinit->montant_initial = $cotisationMutualiste->montant;

            $paiementinit->save();
            // fin
            DB::commit(); // verification

            $data = [
                'code_paiement' => $codePaiement,
                'nom_usager' => auth()->user()->mutualiste->nom,
                'prenom_usager' => auth()->user()->mutualiste->prenom,
                'telephone' => auth()->user()->mutualiste->contact,
                'email' => auth()->user()->mutualiste->email,
                'libelle_article' => $cotisationMutualiste->cotisation->libelle,
                'quantite' => 1,
                'montant' => $cotisationMutualiste->montant,
                'lib_order' => $cotisationMutualiste->cotisation->libelle,
                'pay_fees' => 1,
                'Url_Retour' => urlRetour() . $codePaiement,
                'Url_Callback' => urlCallback(),
            ];
            // dd('test');
            $reponse = Http::withHeaders(['MerchantId' => CREDENSHEL(), 'ApiKey' => cleApi()])
                ->post('http://rest-airtime.paysecurehub.com/api/payhub-ws/build-away', $data);

            // $reponse = Http::withHeaders(['MerchantId' => CREDENSHEL(), 'ApiKey' => cleApi()])
            //     ->post('http://rest-airtime.paysecurehub.com/api/payhub-ws/build-away', $data);

            $ResJSON = $reponse->json();
            $code = $ResJSON['code'];
            if ($reponse->status() === 200) {
                if ($code === 200) {
                    if (!empty($ResJSON['url'])) {
                        return redirect()->away($ResJSON['url']);
                    } else {
                        toast('Echec d\'authentification à la page demandée !', 'error');
                        $module = "Module Cotisation Mutualiste";
                        $action = "Une erreur s'est produit lors du passage du paiement d'une cotisation sur hub de paiement";
                        Logs::saveLog($module, $action);
                        return back();
                    }
                } else {
                    $message = messageBrut($ResJSON['message']);
                    toast($message, 'error');
                    return back();
                }
            } else {
                $message = 'Une erreur inattendue s\'est produite, verifier que vous avez accès à internet, ' .
                    'puis reéssayer. erreur ' . $reponse->status();
                toast($message, 'error');
                $module = "Module Cotisation Mutualiste";
                $action = "Une erreur inatendue s'est produite lors du passage sur l'hub de paiement pour le paiement d'une cotisation";
                Logs::saveLog($module, $action);
            }
        } catch (\Throwable $e) {
            // dd($e->getMessage().'test');
            DB::rollback();
            Log::error('Erreur interne du serveur: ' . $e->getMessage());
            $module = "Module Cotisation Mutualiste";
            $action = "Une erreur serveur s'est produite lors du passage sur l'hub de paiement pour le paiement d'une cotisation";
            Logs::saveLog($module, $action);
            return redirect()->back()->with('error', 'Une erreur s\'est produite, veuillez réessayer.');
        }
    }

    public function detailCotisaMutualiste($id)
    {
        $mutualisteId = auth()->user()->mutualiste->id;
        $PaiementCotisations = CotisationMutualiste::where('cotisation_id', $id)
            ->where('mutualiste_id', $mutualisteId)
            ->whereIn('status', [4])
            ->get();
        $libelleCotisation = Cotisation::where('id', $id)->value('libelle');
        $module = "Module Cotisation Mutualiste";
        $action = "a afficher les detail de paiement de la cotisation : $libelleCotisation";
        Logs::saveLog($module, $action);
        return view('home.admin.cotisations.show', compact('PaiementCotisations', 'libelleCotisation'));
    }
}
