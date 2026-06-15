<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\Mutualiste;
use App\Models\Facturation;
use Illuminate\Http\Request;
use App\Models\ProduitProjet;
use App\Models\DemandeProduit;
use App\Models\ProduitPaiement;
use App\Models\DocumentPaiement;
use App\Models\PaiementInitiale;
use App\Models\ProjetMutualiste;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\StoreProjetMutualisteRequest;
use App\Http\Requests\UpdateProjetMutualisteRequest;

class ProjetMutualisteController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:super-administrateur|administrateur')->only(['index', 'create', 'store', 'show', 'edit', 'update']);
        $this->middleware('role:super-administrateur')->only(['destroy', 'restaureProjetMutualiste']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projetmutualistes = ProjetMutualiste::where('status', 1)->orderBy('created_at', 'DESC')->get();
        $projetmutualistesSuprimees = ProjetMutualiste::where('status', 2)->orderBy('created_at', 'DESC')->get();
        $module = "Module  projet mutualiste";
        $action = "a consulte la liste des projet des mutualiste ";
        Logs::saveLog($module, $action);
        return view('dashboard.projets_mutualistes.index', compact('projetmutualistes', 'projetmutualistesSuprimees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mutualistes = Mutualiste::join('droit_adhesions', 'mutualistes.id', '=', 'droit_adhesions.mutualiste_id')
            ->where('mutualistes.status', 1)
            ->where('droit_adhesions.status', 1)
            ->orderBy('mutualistes.nom', 'ASC')
            ->select('mutualistes.*')
            ->get();
        $module = "Module  projet mutualiste";
        $action = "a affiche la page de creation de projet mutualiste";
        Logs::saveLog($module, $action);
        return view('dashboard.projets_mutualistes.create', compact('mutualistes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjetMutualisteRequest $request)
    {
        try {
            DB::beginTransaction();
            $demandeproduit = DemandeProduit::where('status', 1)
                ->where('produit_projet_id', $request->produit_projet_id)->first();
            if (empty($demandeproduit)) {
                toast('aucune donnée disponible !', 'error');
                return redirect()->back();
            }
            $projetMutualiste = ProjetMutualiste::create([
                'administrateur_id' => auth()->user()->administrateur->id,
                'mutualiste_id' => $request->mutualiste_id,
                'demande_produit_id' => $demandeproduit->id,
                'produit_projet_id' => $request->produit_projet_id,
                'lien_photo' => $demandeproduit->produitProjet->lien_photo,
                'libelle' => $demandeproduit->produitProjet->libelle,
                'montant_produit' => $request->montant_produit,
                'total_apayer' => $request->total_apayer,
                'date' => $request->date,
                'commentaire' => $request->commentaire,
            ]);
            DB::commit();
            toast('Bien ajouté avec succès !', 'success');
            $module = "Module  projet mutualiste";
            $action = "a approuve la demande du projet ayant d'id: $demandeproduit->id , au projet du mutualiste ayant id : $projetMutualiste->id , au mutualiste :." . $projetMutualiste->mutualiste->nom . "" . $projetMutualiste->mutualiste->prenoms . "id:$projetMutualiste->mutualiste_id";
            Logs::saveLog($module, $action);
            return redirect()->route('projetmutualistes.index');
        } catch (\Exception $e) {

            DB::rollBack();

            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module  projet mutualiste";
            $action = "Erreur serveur une erreur s'est produit lors de l'approuvement de la demande d'un produit pour un mutualiste " . $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProjetMutualiste $projetmutualiste)
    {
        $module = "Module  projet mutualiste";
        $action = "A affiche la page de detail d'un projet mutualiste ayant id : $projetmutualiste->id ";
        Logs::saveLog($module, $action);
        return view('dashboard.projets_mutualistes.show', compact('projetmutualiste'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProjetMutualiste $projetmutualiste)
    {
        $mutualistes = Mutualiste::whereStatus(1)->orderBy('nom', 'ASC')->get();
        $module = "Module  projet mutualiste";
        $action = "A affiche la page de d'edition d'un projet mutualiste ayant id : $projetmutualiste->id ";
        Logs::saveLog($module, $action);
        return view('dashboard.projets_mutualistes.edit', compact('projetmutualiste', 'mutualistes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjetMutualisteRequest $request, ProjetMutualiste $projetmutualiste)
    {
        try {
            DB::beginTransaction();

            $demandeproduit = DemandeProduit::where('produit_projet_id', $request->produit_projet_id)->first();

            if (empty($demandeproduit)) {
                toast('aucune donnée disponible !', 'error');
                return redirect()->back();
            }

            $projetmutualiste->update([
                'administrateur_id' => auth()->user()->administrateur->id,
                'mutualiste_id' => $request->mutualiste_id,
                'demande_produit_id' => $demandeproduit->id,
                'produit_projet_id' => $request->produit_projet_id,
                'lien_photo' => $demandeproduit->produitProjet->lien_photo,
                'libelle' => $demandeproduit->produitProjet->libelle,
                'montant_produit' => $request->montant_produit,
                'total_apayer' => $request->total_apayer,
                'date' => $request->date,
                'commentaire' => $request->commentaire,
            ]);

            DB::commit();


            toast('Bien acquis Modifié avec succès !', 'success');
            $module = "Module  projet mutualiste";
            $action = "A modifier le projet mutualiste ayant id: $projetmutualiste->id ";
            Logs::saveLog($module, $action);
            return redirect()->route('projetmutualistes.index');
        } catch (\Exception $e) {

            DB::rollBack();

            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module  projet mutualiste";
            $action = "Erreur serveur une erreur s'est produit lors de la mise a jour de la demande d'un produit pour un mutualiste " . $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }

    public function getProjetMutualistes($mutualisteId)
    {
        try {
            //code...
            $mutualiste = Mutualiste::find($mutualisteId);
            $projetmutualistes = DemandeProduit::where('mutualiste_id', $mutualiste->id)
                ->whereStatus(1)
                ->with('produitProjet')
                ->get();

            $projets = $projetmutualistes->map(function ($projetmutualiste) {
                return [
                    'demande_produit_id' =>  $projetmutualiste->id,
                    'produit_projet_id' => $projetmutualiste->produit_projet_id,
                    'projet' => $projetmutualiste->produitProjet->projet->libelle,
                    'lien_photo' => $projetmutualiste->produitProjet->lien_photo,
                    'libelle' => $projetmutualiste->produitProjet->libelle,
                    'montant' => $projetmutualiste->montant,
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

    public function getProjetDetails($produitProjetId)
    {
        try {
            $projetmutualiste = DemandeProduit::where('produit_projet_id', $produitProjetId)
                ->whereStatus(1)
                ->first();

            if ($projetmutualiste) {
                return response()->json(['montant' => $projetmutualiste->montant]);
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


    public function restaureProjetMutualiste($id)
    {
        try {
            DB::beginTransaction();

            $projetMutualisteRestaure = ProjetMutualiste::withTrashed()->findOrFail($id);

            $projetMutualisteRestaure->status = 1;
            $projetMutualisteRestaure->restore();
            $message = "Bien acquis restauré avec succès !";

            DB::commit();

            toast($message, 'success');
            $module = "Module  projet mutualiste";
            $action = "Il a restaurer  le projetMutualiste ayant l'id : $id";
            Logs::saveLog($module, $action);

            return redirect()->route('projetmutualistes.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module  projet mutualiste";
            $action = "Erreur serveur une erreur s'est produit lors de la restauration de la demande d'un produit pour un mutualiste " . $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProjetMutualiste $projetmutualiste)
    {
        try {
            DB::beginTransaction();

            $message = "";
            # code...
            $projetmutualiste->status = 2;
            $projetmutualiste->save();
            $message = "Bien Supprimé avec succès !";
            $projetmutualiste->delete();

            DB::commit();
            $module = "Module  projet mutualiste";
            $action = "Il a supprimer le produit ayant l'id: $projetmutualiste->id";
            Logs::saveLog($module, $action);

            toast($message, 'success');

            return redirect()->route('projetmutualistes.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module  projet mutualiste";
            $action = "Erreur serveur une erreur s'est produit lors de la suppression de la demande d'un produit pour un mutualiste " . $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }


    public function detailProjet($id)
    {
        $mutualiste = auth()->user()->mutualiste;
        $projetMutualiste = ProjetMutualiste::where('demande_produit_id', $id)->where('mutualiste_id', $mutualiste->id)->first();
        session()->put('identifiantProd', $id);
        $module = "Module  projet mutualiste (chez mutualiste)";
        $action = "il a affiche la page de detail du projetMutualiste ayant l'id: $id";
        Logs::saveLog($module, $action);
        return view('home.admin.projets_admin.produits.produits_acquis.index', compact('projetMutualiste'));
    }

    // liste des projet acquis du mutualiste
    public function listesProdAcquis()
    {
        $mutualiste = auth()->user()->mutualiste;
        $projetMutualistes = ProjetMutualiste::where('mutualiste_id', $mutualiste->id)->get();
        $module = "Module  projet mutualiste (chez mutualiste)";
        $action = "a consulte la liste des projet mutualiste";
        Logs::saveLog($module, $action);
        return view('home.admin.projets_admin.produits.produits_acquis.liste_projetMutualiste', compact('projetMutualistes'));
    }



    // detail des produits acquis du mutualiste connecter
    public function detailMutualisteProduitAcquis(ProjetMutualiste $projetMutualiste)
    {
        $facturations = Facturation::where('projet_mutualiste_id', $projetMutualiste->id)
            ->whereIn('status', [1, 4, 3])
            ->get();
        // dd($facturations);
        $sommes = 0;
        $reste = 0;
        foreach ($facturations as $facturation) {
            $sommes +=  $facturation->reste_apayer;
            $reste +=  $facturation->total_payer;
        }
        $retour = session()->get('identifiantProd');
        $module = "Module  projet mutualiste (chez mutualiste)";
        $action = "a affiche les detail des redevance du projet mutualiste ayant id :$projetMutualiste->id";
        Logs::saveLog($module, $action);
        return view('home.admin.projets_admin.produits.produits_acquis.show', compact('projetMutualiste', 'facturations', 'sommes', 'retour', 'reste'));
    }
    // detail des produits acquis sous paiements
    public function paiementProduitAcquis(ProjetMutualiste $projetMutualiste)
    {
        // $facturations = Facturation::findOrFail($id); recuperation de la faturaction
        $facturations = Facturation::where('projet_mutualiste_id', $projetMutualiste->id)
            ->whereIn('status', [1, 3, 4])->get();
        $sommes = 0;
        $reste = 0;
        foreach ($facturations as $facturation) {
            $sommes +=  $facturation->total_apayer;
            $reste +=  $facturation->total_payer;
            // dd($facturation->id);

        }
        // dd($reste);
        $retour = session()->get('identifiantProd');
        $mutualiste = auth()->user()->mutualiste;
        $lignePaiements = PaiementInitiale::where('mutualiste_id', $mutualiste->id)->where('produit_id', $projetMutualiste->id)->where('type_paiement_id', 4)->get();
        // dd($lignePaiements);
        $module = "Module  projet mutualiste (chez mutualiste)";
        $action = "a affiche les listes des paiement du projet mutualiste ayant id :$projetMutualiste->id";
        Logs::saveLog($module, $action);
        return view('home.admin.projets_admin.produits.produits_acquis.voir_paiement', compact('projetMutualiste', 'facturations', 'sommes', 'retour', 'lignePaiements', 'reste'));
    }


    public function fichePaiement(Request $request)
    {
        // dd($request->all());
        $idProduit = $request->idprod;
        $idfacct = $request->idfact;
        $produitMutualiste = ProjetMutualiste::where('id', $idProduit)->first();
        $facturation = Facturation::where('projet_mutualiste_id', $idProduit)->where('id', $idfacct)->first();
        $module = "Module  projet mutualiste (chez mutualiste)";
        $action = "a affiche les detail  liste des paiement du produit  mutualiste ayant id :$produitMutualiste->id";
        Logs::saveLog($module, $action);
        return view('home.admin.projets_admin.produits.produits_acquis.view_detail_paie', compact('produitMutualiste', 'facturation'));
    }


    public function hubPaiemPro(Request $request)
    {
        // dd($request->all(), 'hubPaiementProd');
        try {
            DB::beginTransaction();
            // initialisation du code de paiement avec une valeur unique
            $codePaiement = generateCode2('Ref');
            // creation d'un nouveau element dans la table PaiementInitiale (debut)
            $paiementinit = new PaiementInitiale();
            $paiementinit->code_paiement = $codePaiement;
            $paiementinit->mutualiste_id = auth()->user()->mutualiste->id;
            $paiementinit->type_paiement_id = 4;
            $paiementinit->correspondance_id = $request->facturationID; // id facturations
            $paiementinit->montant_initial = $request->montant;
            $paiementinit->produit_id = $request->projetID;

            $paiementinit->save();
            // fin
            DB::commit(); // verification
            $data = [
                'code_paiement' => $codePaiement,
                'nom_usager' => auth()->user()->mutualiste->nom,
                'prenom_usager' => auth()->user()->mutualiste->prenom,
                'telephone' => auth()->user()->mutualiste->contact,
                'email' => auth()->user()->mutualiste->email,
                'libelle_article' => $request->redevance,
                'quantite' => 1,
                'montant' => $request->montant,
                'lib_order' => $request->libPro,
                'pay_fees' => 1,
                'Url_Retour' => urlRetour() . $codePaiement,
                'Url_Callback' => urlCallback(),
                // 'Url_Retour' => route('resultat.paiement',['codePaiement'=>$codePaiement]),
                // 'Url_Callback' => route('paiements.newCallBack'),
            ];
            // // Paiement::create($data);
            // // initialisation des route d'envoie via la plateforme de paiement

            $reponse = Http::withHeaders(['MerchantId' => CREDENSHEL(), 'ApiKey' => cleApi()])
                ->post('http://rest-airtime.paysecurehub.com/api/payhub-ws/build-away', $data);
            $ResJSON = $reponse->json();
            $code = $ResJSON['code'];
            if ($reponse->status() === 200) {
                if ($code === 200) {
                    if (!empty($ResJSON['url'])) {
                        $module = "Module  projet mutualiste (chez mutualiste)";
                        $action = "a acceder a l hub de paiement pour le paiement d'un projet mutualiste avec le paiement initail ayant l'id :$paiementinit->id";
                        Logs::saveLog($module, $action);
                        return redirect()->away($ResJSON['url']);
                    } else {
                        toast('Echec d\'authentification à la page demandée !', 'error');
                        $module = "Module  projet mutualiste (chez mutualiste)";
                        $action = "a pas pu accede  a l hub de paiement pour le paiement d'un projet mutualiste avec le paiement initail ayant l'id :$paiementinit->id";
                        Logs::saveLog($module, $action);
                        return back();
                    }
                } else {
                    $message = messageBrut($ResJSON['message']);
                    toast($message, 'error');
                    $module = "Module  projet mutualiste (chez mutualiste)";
                    $action = "une erreur  c'est produit lors de l'appel de hub : $message";
                    Logs::saveLog($module, $action);
                    return back();
                }
            } else {
                $message = 'Une erreur inattendue s\'est produite, verifier que vous avez accès à internet, ' .
                    'puis reéssayer. erreur ' . $reponse->status();
                toast($message, 'error');
                $module = "Module  projet mutualiste (chez mutualiste)";
                $action = "une erreur  c'est produit lors de l'appel de hub : $message";
                Logs::saveLog($module, $action);
            }
        } catch (\Throwable $e) {
            // dd($e->getMessage().'test');
            DB::rollback();
            $module = "Module  projet mutualiste (chez mutualiste)";
            $action = "une erreur serveur s'est produite lors de l'appel de hub :" . $e->getMessage();
            Logs::saveLog($module, $action);

            return redirect()->back()->with('error', 'Une erreur s\'est produite, veuillez réessayer.');
            // return   redirect()->route('accueil');

        }
    }

    public function viewGalleriPaiemnt($id)
    {
        $documentPaiements = DocumentPaiement::where('paiement_initiale_id', $id)->get();
        $module = "Module  projet mutualiste (chez mutualiste)";
        $action = "a affiche les document justification d'un paiement id documentPaiement : $documentPaiements->id";
        Logs::saveLog($module, $action);
        return view('home.admin.projets_admin.produits.produits_acquis.detail_doc_factPaiem', compact('documentPaiements'));
    }
    // voir les detail d'un paiements
    public function showPaiement($id)
    {
        $lignePaiements = PaiementInitiale::where('id', $id)->first();
        $facturation = Facturation::where('id', $lignePaiements->correspondance_id)->first();
        $produitMutualiste = ProjetMutualiste::where('id', $facturation->projet_mutualiste_id)->first();
        $module = "Module  projet mutualiste (chez mutualiste)";
        $action = "a affiche la page de detail d'un paiement pour un projet mutualiste id du paiement: $id";
        Logs::saveLog($module, $action);
        return view('home.admin.projets_admin.produits.produits_acquis.lisShow', compact('lignePaiements', 'produitMutualiste', 'facturation'));
    }
}
