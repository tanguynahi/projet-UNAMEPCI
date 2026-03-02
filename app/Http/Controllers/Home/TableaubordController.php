<?php

namespace App\Http\Controllers\Home;

// use Barryvdh\DomPDF\PDF;
use PDF;
use App\Models\Logs;
use App\Models\User;
use App\Models\Corps;
use App\Models\Grade;
// use Barryvdh\DomPDF\Facade as PDF;
use App\Models\Ville;
use App\Models\Projet;
use App\Models\Paiement;
use App\Models\TypePiece;
use App\Models\Mutualiste;
use App\Models\Specialite;
use App\Models\CarteMembre;
use App\Models\Facturation;
use Illuminate\Http\Request;
use App\Models\DroitAdhesion;
use App\Models\ProduitProjet;
use App\Models\Administrateur;
use App\Models\FormeJuridique;
use App\Models\listeDesProduits;
use App\Models\PaiementInitiale;
use App\Models\STAuthTresorMoney;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\CotisationMutualiste;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Http;
use App\Models\DemandeAccompagnement;
use Illuminate\Support\Facades\Session;
use App\Notifications\MutualisteNotification;

class TableaubordController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $mutualiste = auth()->user()->mutualiste;
        $notifications = $mutualiste->notifications;
        $droit_adhesions =  DroitAdhesion::where('mutualiste_id', $mutualiste->id)->first();
        $contCoti = CotisationMutualiste::where('mutualiste_id', $mutualiste->id)
            ->where('status', 2)
            ->count();
        session()->put('NbreCotis', $contCoti);
        if ($droit_adhesions && $droit_adhesions->status != 1) {
            $message = 'Veuillez vous acquitter de votre adhesion\nMutualPlay afin de profiter de tous \nles services de la plateforme';
            Session::flash('notification_pour_mutualiste', $message);
        }
        $module = "Module Espace Mutualiste ";
        $action = "a consulte son tableau de bord";
        Logs::saveLog($module, $action);
        return view('home.admin.accueil', compact('contCoti', 'droit_adhesions', 'notifications')); //$data
    }

    public function profil(Mutualiste $mutualist)
    {
        $mutualiste = auth()->user()->mutualiste;
        $contCoti = NbreCotisation();
        // $corps = Corps::all();
        // $grades = Grade::all();
        $specialites = Specialite::where('status', 1)->get();
        $formeJuridiques = FormeJuridique::where('status', 1)->get();
        $typePieces = TypePiece::where('status', 1)->get();
        $villes = Ville::orderBy('libelle', 'ASC')->get();
        $droit_adhesions = DroitAdhesion::where('mutualiste_id', $mutualiste->id)->first();
        $module = "Module Espace Mutualiste ";
        $action = "a consulte son profil";
        Logs::saveLog($module, $action);
        return view('home.admin.profils.parametre', compact('formeJuridiques', 'specialites', 'typePieces', 'villes', 'droit_adhesions', 'contCoti', 'mutualiste'));
    }


    public function projet()
    {
        $contCoti = NbreCotisation();
        // dd($contCoti);
        $projets = Projet::orderBy('created_at', 'desc')->get();
        $droit_adhesions = DroitAdhesion::where('mutualiste_id', Auth::guard()->user()->mutualiste->id)->get();
        $module = "Module Espace Mutualiste ";
        $action = "a consulte la liste des projets";
        Logs::saveLog($module, $action);
        return view('home.admin.projets_admin.projet', compact('droit_adhesions', 'projets', 'contCoti'));
    }
    public function historique_index()
    {
        $mutualiste = auth()->user()->mutualiste;
        // $droit_adhesions = DroitAdhesion::where('mutualiste_id', Auth::guard()->user()->mutualiste->id)->get();
        $paiements = PaiementInitiale::where('mutualiste_id', $mutualiste->id)->get();
        $facturations = Facturation::where('mutualiste_id', $mutualiste->id)->get();
        $cotisationMutualistes = CotisationMutualiste::where('mutualiste_id', $mutualiste->id)->get();
        $accompagnements = DemandeAccompagnement::where('mutualiste_id', $mutualiste->id)->get();
        $module = "Module Espace Mutualiste ";
        $action = "a consulte  l'historiques";
        Logs::saveLog($module, $action);
        return view('home.admin.historiques.index', compact('paiements', 'facturations', 'cotisationMutualistes', 'accompagnements'));
    }

    // pour generer recu de payement de maniere automatique

    // resultat Paiement
    public function resultatPaiement($codePaiement)
    {
        $paiementinit = PaiementInitiale::where('code_paiement', $codePaiement)->first();
        $contCoti = NbreCotisation();
        if (!empty($paiementinit)) {
            if ($paiementinit->status == 1) {
                $code = 200;
                $mess = 'Paiement éffectué avec succès';
            } else {
                $code = 201;
                $mess = 'Paiement échoué';
            }
            $module = "Module Espace Mutualiste ";
            $action = "a consulte  la page resultat paiement et voici le code du paiement : $code";
            Logs::saveLog($module, $action);
            return view('home.admin.paiements.resultat_paiement', compact('paiementinit', 'mess', 'code', 'contCoti'));
        } else {
            $code = 404;
            $mess = "<h3 class='sub-title'>Page Introuvable</h3><br>
            <p>Une erreur est survenue, veuillez réessayer plus tard. #EDOCTNEME</p>";
            $module = "Module Espace Mutualiste ";
            $action = "Une erreur s'est produit sur la page resultat paiement car le paiement n'existe pas ";
            Logs::saveLog($module, $action);
            return view('home.admin.errorpage.index', compact('code', 'mess'));
        }
    }
    // ma boutiques conversion de monetaire
    public function boutiques()
    {
        $module = "Module Espace Mutualiste ";
        $action = "a consulte la boutique ";
        Logs::saveLog($module, $action);
        return view('home.admin.boutiques.index');
    }

    public function carteMembreImpayer()
    {
        $mutualiste = auth()->user()->mutualiste;
        $carteMembre = CarteMembre::where('mutualiste_id', $mutualiste->id)->first();
        if (empty($carteMembre)) {
            $module = "Module Espace Mutualiste Carte Membre ";
            $action = "ce mutualiste ayant id $mutualiste->id n'a pas de carte membre creer ";
            Logs::saveLog($module, $action);
            toast('Une erreur s\'est produit, Veuillez réessayer. carte membre introuvable', 'error');
            return redirect()->route('espace.accueil');
        }
        if ($carteMembre->status == 2) {
            return view('home.admin.carteMembres.index', compact('carteMembre'));
        } else {
            if ($carteMembre->genere == 1) {
                return view('home.admin.carteMembres.genere', compact('mutualiste', 'carteMembre'));
            } else {
                return view('home.admin.carteMembres.attente', compact('mutualiste'));
            }
        }
    }




    // paiement via tremo uniquement

    // public function paiementEspace(Request $request, $id)
    // {

    //     // dd($request->all(), $id);
    //     switch ($id) {
    //         case 1:
    //             // paiement d'adhesions

    //             $montant = $request->montant ?? auth()->user()->mutualiste->droitAdhesion->montant;
    //             $libelle = auth()->user()->mutualiste->droitAdhesion->libelle;
    //             $idCorrespondant = auth()->user()->mutualiste->droitAdhesion->id;
    //             break;

    //         case 2:
    //             // Paiement de cotisation

    //             $cotisationMutualiste = CotisationMutualiste::where('id', $request->idCotisation)->first();
    //             $idCorrespondant = $cotisationMutualiste->id;
    //             $libelle = $cotisationMutualiste->cotisation->libelle;
    //             $montant = $request->montant;
    //             // dd($request->all(), $id, $libelle);

    //             break;

    //         case 3:
    //             // Paiement de Prét

    //             $demandePret = DemandeAccompagnement::find($request->idDemandeaccompa);
    //             $montant = $request->montant;
    //             $libelle = $request->libelleRed;
    //             $idCorrespondant = $demandePret->id;

    //             //  dd($request->all(), $id,$demandePret);

    //             break;
    //         case 4:
    //             // Paiement de Projet
    //             // echo "ID est égal à 3.";
    //             $montant = $request->montant;
    //             $libelle = $request->redevance . ' du produit : ' . $request->libPro;
    //             $idCorrespondant = $request->projetID;
    //             $facturationID = $request->facturationID;
    //             //         if (!is_null($facturationID)) {
    //             //          $params['facturationID'] = $facturationID;
    //             //   }
    //             break;
    //         case 5:
    //             // carte membres
    //             $carteMembre = CarteMembre::findOrFail($request->idCarte);
    //             $montant = $carteMembre->montant ?? $request->montant;
    //             $libelle = $carteMembre->libelle ?? $request->libelle;
    //             $idCorrespondant = $carteMembre->id;
    //             break;

    //         default:
    //             // Code à exécuter si aucun des cas ci-dessus ne correspond
    //             echo "ID inconnu.";
    //             break;
    //     }
    //     $mutualiste = auth()->user()->mutualiste;
    //     $module = "Module Espace Paiement du  Mutualiste ";
    //     $action = "a consulte la page de paiement d'un mutuliste Etape1 ";
    //     Logs::saveLog($module, $action);
    //     // dd($mutualiste);
    //     //         $params = [
    //     //     'libelle' => $libelle,
    //     //     'montant' => $montant,
    //     //     'id' => $id,
    //     //     'idCorrespondant' => $idCorrespondant,
    //     // ];


    //     return redirect()->route('hubPayPag', [
    //         'libelle' => $libelle,
    //         'montant' => $montant,
    //         'id' => $id,
    //         'idCorrespondant' => $idCorrespondant,
    //         'facturationID' => $facturationID ?? 0, // ou null si la route accepte
    //     ]);
    //     // return view('home.admin.paiements.index', compact('mutualiste', 'libelle', 'montant', 'id', 'idCorrespondant'));
    // }

    public function paiementEspace(Request $request, $id)
    {
        DB::beginTransaction();

        // dd($request->all(),$id);

        try {

            switch ($id) {

                case 1:
                    // Paiement d'adhésion $request->montant ??
                    $montant =  auth()->user()->mutualiste->droitAdhesion->montant;
                    $libelle = auth()->user()->mutualiste->droitAdhesion->libelle;
                    $idCorrespondant = auth()->user()->mutualiste->droitAdhesion->id;
                    break;

                case 2:
                    // Paiement de cotisation
                    $cotisationMutualiste = CotisationMutualiste::findOrFail($request->idCotisation);

                    $idCorrespondant = $cotisationMutualiste->id;
                    $libelle = $cotisationMutualiste->cotisation->libelle;
                    $montant = $request->montant;
                    break;

                case 3:
                    // Paiement de prêt
                    $demandePret = DemandeAccompagnement::findOrFail($request->idDemandeaccompa);

                    $montant = $request->montant;
                    $libelle = $request->libelleRed;
                    $idCorrespondant = $demandePret->id;
                    break;

                case 4:
                    // Paiement de projet
                    $montant = $request->montant;
                    $libelle = $request->redevance . ' du produit : ' . $request->libPro;
                    $idCorrespondant = $request->projetID;
                    $facturationID = $request->facturationID ?? 0;
                    break;

                case 5:
                    // Carte membre
                    $carteMembre = CarteMembre::findOrFail($request->idCarte);

                    $montant = $carteMembre->montant ?? $request->montant;
                    $libelle = $carteMembre->libelle ?? $request->libelle;
                    $idCorrespondant = $carteMembre->id;
                    break;

                default:
                    throw new \Exception("ID de paiement inconnu.");
            }

            $mutualiste = auth()->user()->mutualiste;

            $module = "Module Espace Paiement du Mutualiste";
            $action = "a consulté la page de paiement d'un mutualiste Etape1";
            Logs::saveLog($module, $action);

            DB::commit();

            return redirect()->route('hubPayPag', [
                'libelle' => $libelle,
                'montant' => $montant,
                'id' => $id,
                'idCorrespondant' => $idCorrespondant,
                'facturationID' => $facturationID ?? 0,
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Erreur paiementEspace : ' . $e->getMessage());
            $module = "Module Espace Paiement du Mutualiste";
            $action = "Une erreur est survenue lors du traitement du paiement" . $e->getMessage();
            Logs::saveLog($module, $action);

            return back()->with('error', 'Une erreur est survenue lors du traitement du paiement.');
        }
    }



    public function PayPage($libelle, $montant, $id, $idCorrespondant, $facturationID = null)
    {

        $mutualiste = auth()->user()->mutualiste;
        $module = "Module Espace Paiement du  Mutualiste ";
        $action = "a consulte la page de paiement d'un mutuliste Etape 2 ";
        Logs::saveLog($module, $action);
        // dd($mutualiste);
        return view('home.admin.paiements.index', compact('mutualiste', 'libelle', 'montant', 'id', 'idCorrespondant', 'facturationID'));
    }

    public function traitementAppelApiPaiement(Request $request, $id)
    {
        //  $demandeP = Facturation::find($request->facturationID);
        //  $demandeP->produitProjet->libelle;
        // $mutualiste = auth()->user()->mutualiste;

        // DB::beginTransaction();



        try {

            $mutualiste = auth()->user()->mutualiste;

            if (!$mutualiste) {
                throw new \Exception("Mutualiste introuvable.");
            }
            switch ($id) {
                case 1:
                            // dd($request->all(),$id);
                    // paiement d'adhesions
                    // Les informations d'authentification
                    $auth = new STAuthTresorMoney();
                    $auth->Key = env('KEY_AUTH_TREMO');
                    $auth->Secret = env('SECRET_AUTH_TREMO');
                    $bufSend = json_encode($auth);

                    // $ChaineLOG .= "\n** debut authentification tresormoney, parametres: $bufSend";
                    $module = "Module Espace Mutualiste Paiement";
                    $action = "\n** debut authentification tresormoney, parametres: $bufSend";

                    Logs::saveLog($module, $action);
                    // Effectue la requête HTTP POST
                    $responseReq = Http::post(env('URL_AUTHENTIF'), $auth);

                    // Récupère le contenu de la réponse
                    $Contenu = $responseReq->body();
                    $retourauth = json_decode($responseReq->body());
                    // $ChaineLOG .= "\n** retour authentification tresormoney, reponse: $Contenu";
                    $module = "Module Espace Mutualiste Paiement";
                    $action = "\n** retour authentification tresormoney, reponse: $Contenu";

                    Logs::saveLog($module, $action);
                    if ($responseReq->status() === 200) {

                        if ($retourauth->code !== 200) {
                            $response['code'] = $retourauth->code;
                            $response['message'][] = $retourauth->sMessage . ' ERREUR' . $retourauth->code;
                            $code = $retourauth->code;
                            $mess = "<h3 class='sub-title'>Page Introuvable</h3><br>
                            <p>Une erreur est survenue, veuillez réessayer plus tard. # $retourauth->sMessage erreur $retourauth->code</p>";
                            $module = "Module Espace Mutualiste Paiement";
                            $action = "$retourauth->sMessage \n envoyee: $bufSend \n recue: $Contenu";

                            Logs::saveLog($module, $action);
                            return view('home.admin.errorpage.index', compact('code', 'mess'));
                        } else {
                            $infosProduits = new listeDesProduits();
                            $infosProduits['LibelleProduit'] = "Droit d'adhesion";
                            $infosProduits['Montant'] =  $request->montant;
                            $infosProduits['nEstUnServicePrive'] = 0;
                            $infosProduits['TypeProduit'] = 1;
                            $infosProduits['Quantite'] = 1;
                            $infosProduits['IdProduit'] = 0;
                            $infosProduits['Reference_code_Produit'] = "";


                            $codePaiement = generateCode2('Ref');
                            // creation d'un nouveau element dans la table PaiementInitiale (debut)
                            $paiementinit = new PaiementInitiale();
                            $paiementinit->code_paiement = $codePaiement;
                            $paiementinit->mutualiste_id = $mutualiste->id;
                            $paiementinit->type_paiement_id = 1;
                            $paiementinit->correspondance_id = $mutualiste->droitAdhesion->id;
                            $paiementinit->montant_initial = $request->montant;
                            $paiementinit->contact_paiement = $request->numero;
                            $paiementinit->save();


                            // $infosbeneficiaire = new STBeneficiaire();
                            $infosbeneficiaire['Credentiel'] = env('HUB_KEY_TREMO'); //$obj->CREDENTIAL;
                            $infosbeneficiaire['produits'][] = $infosProduits;

                            // env('CALL_BACK_TREMO') urlCallbackLien
                            // $infospaiement = new paramaAEnvoyer();
                            $infospaiement['Url_callback'] = urlCallbackLien();
                            $infospaiement['Nom_usager'] = $mutualiste->nom;
                            $infospaiement['Prenom_usager'] = $mutualiste->prenom ?? "xxxxxxx";
                            $infospaiement['code_paiement'] = $codePaiement;
                            $infospaiement['Email'] = $mutualiste->email;
                            $infospaiement['Telephone'] = $paiementinit->contact_paiement;
                            $infospaiement['Additif'] = $codePaiement;
                            $infospaiement['Token'] = $retourauth->Token;
                            $infospaiement['TypeOperation'] = 1;
                            $infospaiement['TCredentiel'][] = $infosbeneficiaire;

                            $bufSend = json_encode($infospaiement);
                            $module = "Module Espace Paiement du  Mutualiste Tremo";
                            $action = "\n** debut initiation transaction tremo, parametres :  $bufSend ";
                            Logs::saveLog($module, $action);

                            // Effectue la requête HTTP POST
                            $responseReq = Http::post(env('URL_INITIATE'), $infospaiement);
                            // Récupère le contenu de la réponse
                            $Contenu = $responseReq->body();

                            $module = "Module Espace Paiement du  Mutualiste Tremo";
                            $action = "\n** retour initiation transaction tremo, reponse: $Contenu";
                            Logs::saveLog($module, $action);

                            $retourReq = json_decode($responseReq->body());

                            if ($responseReq->status() === 200) {
                                $id = $paiementinit->id;
                                $response['idPay'] = $id;
                                $response['code'] = $retourReq->code;
                                $debutmess = "L operation a ete initiee sur le numero " . $paiementinit->contact_paiement . ". ";

                                if ($retourReq->code !== 200) {
                                    $response['message'][] = $retourReq->cleretour;

                                    $code = $retourReq->code;
                                    $mess = "<h3 class='sub-title'>Page Introuvable</h3><br>
                                    <p>Une erreur est survenue, veuillez réessayer plus tard. # $retourReq->cleretour  55</p>";
                                    $module = "Module Espace Mutualiste Paiement";
                                    $action = "$retourReq->cleretour \n code: $code";
                                    Logs::saveLog($module, $action);
                                    return view('home.admin.errorpage.index', compact('code', 'mess'));
                                } else {
                                    $response['message'] = $debutmess . "\n " . $retourReq->cleretour;
                                    $ind = 1;
                                    return redirect()->back()
                                        ->with('mess', $response['message'])
                                        ->with('codeP', $codePaiement);
                                }
                                $module = "Module Espace Mutualiste Paiement";
                                $action = "\n** fin d initiation success transaction tremo $obj->CREDENTIAL, $numero, $ChaineLOG";
                                Logs::saveLog($module, $action);
                            } else {
                                $response['code'] = $responseReq->status();
                                $response['message'][] = "echec d initiation transaction tresormoney, impossible de joindre l hote.";
                                $code = $responseReq->status();
                                $mess = "<h3 class='sub-title'>Page Introuvable</h3><br>
                                    <p>Une erreur est survenue, veuillez réessayer plus tard. # echec d initiation transaction tresormoney, impossible de joindre l hote.  </p>";
                                $module = "Module Espace Mutualiste Paiement";
                                $action = "echec d initiation transaction tresormoney, impossible de joindre l hote.  \n code: $code";
                                Logs::saveLog($module, $action);
                                return view('home.admin.errorpage.index', compact('code', 'mess'));

                                // return $response;
                            }

                        }
                    } else {

                        $response['code'] = $responseReq->status();
                        $response['message'][] = "echec d authentification, impossible de joindre l hote.";

                        // $ChaineLOG .= "\n** erreur lors de l appel de l api d authentification tremo";
                        // $ChaineLOG .= " donnees envoyee: $bufSend, donnees recue: $Contenu";
                        // Logs::ajoutLOG('authentification transaction tremo', $obj->CREDENTIAL, $numero, $ChaineLOG, 'HTTP');

                        $code = $responseReq->status();
                        $mess = "<h3 class='sub-title'>Page Introuvable</h3><br>
                     <p>Une erreur est survenue, veuillez réessayer plus tard. #EDOCTNEME ADHESION</p>";
                        $module = "Module Espace Mutualiste Paiement";
                        $action = "echec d authentification, impossible de joindre l hote.";

                        Logs::saveLog($module, $action);
                        return view('home.admin.errorpage.index', compact('code', 'mess'));

                        // return $response;
                    }

                    break;

                case 2:
                    // dd($request->all(),$id);
                    $auth = new STAuthTresorMoney();
                    $auth->Key = env('KEY_AUTH_TREMO');
                    $auth->Secret = env('SECRET_AUTH_TREMO');
                    $bufSend = json_encode($auth);

                    // $ChaineLOG .= "\n** debut authentification tresormoney, parametres: $bufSend";
                    $module = "Module Espace Mutualiste Paiement";
                    $action = "\n** debut authentification tresormoney, parametres: $bufSend";

                    Logs::saveLog($module, $action);
                    // Effectue la requête HTTP POST
                    $responseReq = Http::post(env('URL_AUTHENTIF'), $auth);

                    // Récupère le contenu de la réponse
                    $Contenu = $responseReq->body();
                    $retourauth = json_decode($responseReq->body());
                    // $ChaineLOG .= "\n** retour authentification tresormoney, reponse: $Contenu";
                    $module = "Module Espace Mutualiste Paiement";
                    $action = "\n** retour authentification tresormoney, reponse: $Contenu";

                    Logs::saveLog($module, $action);
                    if ($responseReq->status() === 200) {

                        if ($retourauth->code !== 200) {
                            $response['code'] = $retourauth->code;
                            $response['message'][] = $retourauth->sMessage . ' ERREUR' . $retourauth->code;
                            $code = $retourauth->code;
                            $mess = "<h3 class='sub-title'>Page Introuvable</h3><br>
                        <p>Une erreur est survenue, veuillez réessayer plus tard. # $retourauth->sMessage erreur $retourauth->code</p>";
                            $module = "Module Espace Mutualiste Paiement";
                            $action = "$retourauth->sMessage \n envoyee: $bufSend \n recue: $Contenu";

                            Logs::saveLog($module, $action);
                            return view('home.admin.errorpage.index', compact('code', 'mess'));
                        } else {
                            $cotisationMutualiste = CotisationMutualiste::where('id', $request->idcorrespondant)->first();
                            $infosProduits = new listeDesProduits();
                            $infosProduits['LibelleProduit'] = $cotisationMutualiste->cotisation->libelle;
                            $infosProduits['Montant'] =  $request->montant;
                            $infosProduits['nEstUnServicePrive'] = 0;
                            $infosProduits['TypeProduit'] = 1;
                            $infosProduits['Quantite'] = 1;
                            $infosProduits['IdProduit'] = 0;
                            $infosProduits['Reference_code_Produit'] = "";

                            $codePaiement = generateCode2('Cot');
                            // creation d'un nouveau element dans la table PaiementInitiale (debut)
                            $paiementinit = new PaiementInitiale();
                            $paiementinit->code_paiement = $codePaiement;
                            $paiementinit->mutualiste_id = $mutualiste->id;
                            $paiementinit->type_paiement_id = 2;
                            $paiementinit->correspondance_id = $cotisationMutualiste->id; // id facturations
                            $paiementinit->montant_initial =  $request->montant ?? $cotisationMutualiste->montant  ;
                            $paiementinit->contact_paiement = $request->numero;

                            $paiementinit->save();
                            // $infosbeneficiaire = new STBeneficiaire();
                            $infosbeneficiaire['Credentiel'] = env('HUB_KEY_TREMO'); //$obj->CREDENTIAL;
                            $infosbeneficiaire['produits'][] = $infosProduits;

                            // env('CALL_BACK_TREMO') urlCallbackLien
                            // $infospaiement = new paramaAEnvoyer();
                            $infospaiement['Url_callback'] = urlCallbackLien();
                            $infospaiement['Nom_usager'] = $mutualiste->nom;
                            $infospaiement['Prenom_usager'] = $mutualiste->prenom ?? "xxxxxxx";
                            $infospaiement['code_paiement'] = $codePaiement;
                            $infospaiement['Email'] = $mutualiste->email;
                            $infospaiement['Telephone'] = $paiementinit->contact_paiement;
                            $infospaiement['Additif'] = $codePaiement;
                            $infospaiement['Token'] = $retourauth->Token;
                            $infospaiement['TypeOperation'] = 1;
                            $infospaiement['TCredentiel'][] = $infosbeneficiaire;

                            $bufSend = json_encode($infospaiement);
                            $module = "Module Espace Paiement du  Mutualiste Tremo";
                            $action = "\n** debut initiation transaction tremo, parametres :  $bufSend ";
                            Logs::saveLog($module, $action);

                            // Effectue la requête HTTP POST
                            $responseReq = Http::post(env('URL_INITIATE'), $infospaiement);
                            // Récupère le contenu de la réponse
                            $Contenu = $responseReq->body();

                            $module = "Module Espace Paiement du  Mutualiste Tremo Pour Cotisation";
                            $action = "\n** retour initiation transaction tremo, reponse: $Contenu";
                            Logs::saveLog($module, $action);

                            $retourReq = json_decode($responseReq->body());

                            if ($responseReq->status() === 200) {
                                $id = $paiementinit->id;
                                $response['idPay'] = $id;
                                $response['code'] = $retourReq->code;
                                $debutmess = "L operation a ete initiee sur le numero " . $paiementinit->contact_paiement . ". ";

                                if ($retourReq->code !== 200) {
                                    $response['message'][] = $retourReq->cleretour;
                                    $code = $retourReq->code;
                                    $mess = "<h3 class='sub-title'>Page Introuvable</h3><br>
                                    <p>Une erreur est survenue, veuillez réessayer plus tard. # $retourReq->cleretour </p>";
                                    $module = "Module Espace Mutualiste Paiement";
                                    $action = "$retourReq->cleretour \n code: $code";
                                    Logs::saveLog($module, $action);
                                    return view('home.admin.errorpage.index', compact('code', 'mess'));
                                } else {
                                    $response['message'] = $debutmess . "\n " . $retourReq->cleretour;
                                    $ind = 1;
                                    return redirect()->back()
                                        ->with('mess', $response['message'])
                                        ->with('codeP', $codePaiement);
                                }
                                $module = "Module Espace Mutualiste Paiement";
                                $action = "\n** fin d initiation success transaction tremo $obj->CREDENTIAL, $numero, $ChaineLOG";
                                Logs::saveLog($module, $action);
                            } else {
                                $response['code'] = $responseReq->status();
                                $response['message'][] = "echec d initiation transaction tresormoney, impossible de joindre l hote.";
                                $code = $responseReq->status();
                                $mess = "<h3 class='sub-title'>Page Introuvable</h3><br>
                                    <p>Une erreur est survenue, veuillez réessayer plus tard. # echec d initiation transaction tresormoney, impossible de joindre l hote.  </p>";
                                $module = "Module Espace Mutualiste Paiement";
                                $action = "echec d initiation transaction tresormoney, impossible de joindre l hote.  \n code: $code";
                                Logs::saveLog($module, $action);
                                return view('home.admin.errorpage.index', compact('code', 'mess'));
                            }
                        }
                    } else {
                        $response['code'] = $responseReq->status();
                        $response['message'][] = "echec d authentification, impossible de joindre l hote.";
                        $code = $responseReq->status();
                        $mess = "<h3 class='sub-title'>Page Introuvable</h3><br>
                     <p>Une erreur est survenue, veuillez réessayer plus tard. #EDOCTNEME</p>";
                        $module = "Module Espace Mutualiste Paiement";
                        $action = "echec d authentification, impossible de joindre l hote. 52";

                        Logs::saveLog($module, $action);
                        return view('home.admin.errorpage.index', compact('code', 'mess'));
                    }
                    break;

                case 3:
                    // Paiement de Prét
                    $auth = new STAuthTresorMoney();
                    $auth->Key = env('KEY_AUTH_TREMO');
                    $auth->Secret = env('SECRET_AUTH_TREMO');
                    $bufSend = json_encode($auth);
                    // $ChaineLOG .= "\n** debut authentification tresormoney, parametres: $bufSend";
                    $module = "Module Espace Mutualiste Paiement";
                    $action = "\n** debut authentification tresormoney, parametres: $bufSend";

                    Logs::saveLog($module, $action);
                    // Effectue la requête HTTP POST
                    $responseReq = Http::post(env('URL_AUTHENTIF'), $auth);

                    // Récupère le contenu de la réponse
                    $Contenu = $responseReq->body();
                    $retourauth = json_decode($responseReq->body());
                    // $ChaineLOG .= "\n** retour authentification tresormoney, reponse: $Contenu";
                    $module = "Module Espace Mutualiste Paiement Pret";
                    $action = "\n** retour authentification tresormoney, reponse: $Contenu";
                    Logs::saveLog($module, $action);
                    if ($responseReq->status() === 200) {

                        if ($retourauth->code !== 200) {
                            $response['code'] = $retourauth->code;
                            $response['message'][] = $retourauth->sMessage . ' ERREUR' . $retourauth->code;
                            $code = $retourauth->code;
                            $mess = "<h3 class='sub-title'>Page Introuvable</h3><br>
                        <p>Une erreur est survenue, veuillez réessayer plus tard. # $retourauth->sMessage erreur $retourauth->code</p>";
                            $module = "Module Espace Mutualiste Paiement";
                            $action = "$retourauth->sMessage \n envoyee: $bufSend \n recue: $Contenu";

                            Logs::saveLog($module, $action);
                            return view('home.admin.errorpage.index', compact('code', 'mess'));
                        } else {
                            $demandeP = DemandeAccompagnement::find($request->idcorrespondant);
                            $infosProduits = new listeDesProduits();
                            $infosProduits['LibelleProduit'] = "Pret : ." . $demandeP->service->libelle;
                            $infosProduits['Montant'] =  $request->montant;
                            $infosProduits['nEstUnServicePrive'] = 0;
                            $infosProduits['TypeProduit'] = 1;
                            $infosProduits['Quantite'] = 1;
                            $infosProduits['IdProduit'] = 0;
                            $infosProduits['Reference_code_Produit'] = "";
                            $codePaiement = generateCode2('Rem');
                            // creation d'un nouveau element dans la table PaiementInitiale (debut)
                            $paiementinit = new PaiementInitiale();
                            $paiementinit->code_paiement = $codePaiement;
                            $paiementinit->mutualiste_id = $mutualiste->id;
                            $paiementinit->type_paiement_id = 3; // pret
                            $paiementinit->correspondance_id = $request->idcorrespondant; // id
                            $paiementinit->montant_initial = $request->montant;
                            $paiementinit->contact_paiement = $request->numero;
                            $paiementinit->save();
                            // $infosbeneficiaire = new STBeneficiaire();
                            $infosbeneficiaire['Credentiel'] = env('HUB_KEY_TREMO'); //$obj->CREDENTIAL;
                            $infosbeneficiaire['produits'][] = $infosProduits;

                            // env('CALL_BACK_TREMO') urlCallbackLien
                            // $infospaiement = new paramaAEnvoyer();
                            $infospaiement['Url_callback'] = urlCallbackLien();
                            $infospaiement['Nom_usager'] = $mutualiste->nom;
                            $infospaiement['Prenom_usager'] = $mutualiste->prenom ?? "xxxxxxx";
                            $infospaiement['code_paiement'] = $codePaiement;
                            $infospaiement['Email'] = $mutualiste->email;
                            $infospaiement['Telephone'] = $paiementinit->contact_paiement;
                            $infospaiement['Additif'] = $codePaiement;
                            $infospaiement['Token'] = $retourauth->Token;
                            $infospaiement['TypeOperation'] = 1;
                            $infospaiement['TCredentiel'][] = $infosbeneficiaire;

                            $bufSend = json_encode($infospaiement);
                            $module = "Module Espace Paiement du  Mutualiste Tremo Pret";
                            $action = "\n** debut initiation transaction tremo, parametres :  $bufSend ";
                            Logs::saveLog($module, $action);

                            // Effectue la requête HTTP POST
                            $responseReq = Http::post(env('URL_INITIATE'), $infospaiement);
                            // Récupère le contenu de la réponse
                            $Contenu = $responseReq->body();

                            $module = "Module Espace Paiement du  Mutualiste Tremo Pour Pret";
                            $action = "\n** retour initiation transaction tremo, reponse: $Contenu";
                            Logs::saveLog($module, $action);

                            $retourReq = json_decode($responseReq->body());

                            if ($responseReq->status() === 200) {
                                $id = $paiementinit->id;
                                $response['idPay'] = $id;
                                $response['code'] = $retourReq->code;
                                $debutmess = "L operation a ete initiee sur le numero " . $paiementinit->contact_paiement . ". ";

                                if ($retourReq->code !== 200) {
                                    $response['message'][] = $retourReq->cleretour;
                                    $code = $retourReq->code;
                                    $mess = "<h3 class='sub-title'>Page Introuvable</h3><br>
                                    <p>Une erreur est survenue, veuillez réessayer plus tard. # $retourReq->cleretour </p>";
                                    $module = "Module Espace Mutualiste Paiement";
                                    $action = "$retourReq->cleretour \n code: $code";
                                    Logs::saveLog($module, $action);
                                    return view('home.admin.errorpage.index', compact('code', 'mess'));
                                } else {
                                    $response['message'] = $debutmess . "\n " . $retourReq->cleretour;
                                    $ind = 1;
                                    return redirect()->back()
                                        ->with('mess', $response['message'])
                                        ->with('codeP', $codePaiement);
                                }
                                $module = "Module Espace Mutualiste Paiement";
                                $action = "\n** fin d initiation success transaction tremo $obj->CREDENTIAL, $numero, $ChaineLOG";
                                Logs::saveLog($module, $action);
                            } else {
                                $response['code'] = $responseReq->status();
                                $response['message'][] = "echec d initiation transaction tresormoney, impossible de joindre l hote.";
                                $code = $responseReq->status();
                                $mess = "<h3 class='sub-title'>Page Introuvable</h3><br>
                                    <p>Une erreur est survenue, veuillez réessayer plus tard. # echec d initiation transaction tresormoney, impossible de joindre l hote.  </p>";
                                $module = "Module Espace Mutualiste Paiement";
                                $action = "echec d initiation transaction tresormoney, impossible de joindre l hote.  \n code: $code";
                                Logs::saveLog($module, $action);
                                return view('home.admin.errorpage.index', compact('code', 'mess'));
                            }
                        }
                    } else {
                        $response['code'] = $responseReq->status();
                        $response['message'][] = "echec d authentification, impossible de joindre l hote.";
                        $code = $responseReq->status();
                        $mess = "<h3 class='sub-title'>Page Introuvable</h3><br>
                     <p>Une erreur est survenue, veuillez réessayer plus tard. #EDOCTNEME</p>";
                        $module = "Module Espace Mutualiste Paiement";
                        $action = "echec d authentification, impossible de joindre l hote.";

                        Logs::saveLog($module, $action);
                        return view('home.admin.errorpage.index', compact('code', 'mess'));
                    }
                    break;
                case 4:

                    $auth = new STAuthTresorMoney();
                    $auth->Key = env('KEY_AUTH_TREMO');
                    $auth->Secret = env('SECRET_AUTH_TREMO');
                    $bufSend = json_encode($auth);
                    // $ChaineLOG .= "\n** debut authentification tresormoney, parametres: $bufSend";
                    $module = "Module Espace Mutualiste Paiement";
                    $action = "\n** debut authentification tresormoney, parametres: $bufSend";

                    Logs::saveLog($module, $action);
                    // Effectue la requête HTTP POST
                    $responseReq = Http::post(env('URL_AUTHENTIF'), $auth);

                    // Récupère le contenu de la réponse
                    $Contenu = $responseReq->body();
                    $retourauth = json_decode($responseReq->body());
                    // $ChaineLOG .= "\n** retour authentification tresormoney, reponse: $Contenu";
                    $module = "Module Espace Mutualiste Paiement Pret";
                    $action = "\n** retour authentification tresormoney, reponse: $Contenu";
                    Logs::saveLog($module, $action);
                    if ($responseReq->status() === 200) {

                        if ($retourauth->code !== 200) {
                            $response['code'] = $retourauth->code;
                            $response['message'][] = $retourauth->sMessage . ' ERREUR' . $retourauth->code;
                            $code = $retourauth->code;
                            $mess = "<h3 class='sub-title'>Page Introuvable</h3><br>
                        <p>Une erreur est survenue, veuillez réessayer plus tard. # $retourauth->sMessage erreur $retourauth->code</p>";
                            $module = "Module Espace Mutualiste Paiement";
                            $action = "$retourauth->sMessage \n envoyee: $bufSend \n recue: $Contenu";

                            Logs::saveLog($module, $action);
                            return view('home.admin.errorpage.index', compact('code', 'mess'));
                        } else {
                            $demandeP = Facturation::find($request->facturationID);
                            $infosProduits = new listeDesProduits();
                            $infosProduits['LibelleProduit'] = "redevance :" . $demandeP->redevance->libelle . " pour le Produit : ." . $demandeP->produitProjet->libelle;
                            $infosProduits['Montant'] =  $request->montant;
                            $infosProduits['nEstUnServicePrive'] = 0;
                            $infosProduits['TypeProduit'] = 1;
                            $infosProduits['Quantite'] = 1;
                            $infosProduits['IdProduit'] = 0;
                            $infosProduits['Reference_code_Produit'] = "";

                            $codePaiement = generateCode2('Ref');
                            // creation d'un nouveau element dans la table PaiementInitiale (debut)
                            $paiementinit = new PaiementInitiale();
                            $paiementinit->code_paiement = $codePaiement;
                            $paiementinit->mutualiste_id = $mutualiste->id;
                            $paiementinit->type_paiement_id = 4;
                            $paiementinit->correspondance_id = $request->facturationID; // id facturations
                            $paiementinit->montant_initial = $request->montant;
                            $paiementinit->produit_id = $request->idcorrespondant;

                            $paiementinit->save();

                            // $infosbeneficiaire = new STBeneficiaire();
                            $infosbeneficiaire['Credentiel'] = env('HUB_KEY_TREMO'); //$obj->CREDENTIAL;
                            $infosbeneficiaire['produits'][] = $infosProduits;

                            // env('CALL_BACK_TREMO') urlCallbackLien
                            // $infospaiement = new paramaAEnvoyer();
                            $infospaiement['Url_callback'] = urlCallbackLien();
                            $infospaiement['Nom_usager'] = $mutualiste->nom;
                            $infospaiement['Prenom_usager'] = $mutualiste->prenom ?? "xxxxxxx";
                            $infospaiement['code_paiement'] = $codePaiement;
                            $infospaiement['Email'] = $mutualiste->email;
                            $infospaiement['Telephone'] = $paiementinit->contact_paiement;
                            $infospaiement['Additif'] = $codePaiement;
                            $infospaiement['Token'] = $retourauth->Token;
                            $infospaiement['TypeOperation'] = 1;
                            $infospaiement['TCredentiel'][] = $infosbeneficiaire;

                            $bufSend = json_encode($infospaiement);
                            $module = "Module Espace Paiement du  Mutualiste Tremo Pret";
                            $action = "\n** debut initiation transaction tremo, parametres :  $bufSend ";
                            Logs::saveLog($module, $action);

                            // Effectue la requête HTTP POST
                            $responseReq = Http::post(env('URL_INITIATE'), $infospaiement);
                            // Récupère le contenu de la réponse
                            $Contenu = $responseReq->body();

                            $module = "Module Espace Paiement du  Mutualiste Tremo Pour Pret";
                            $action = "\n** retour initiation transaction tremo, reponse: $Contenu";
                            Logs::saveLog($module, $action);

                            $retourReq = json_decode($responseReq->body());

                            if ($responseReq->status() === 200) {
                                $id = $paiementinit->id;
                                $response['idPay'] = $id;
                                $response['code'] = $retourReq->code;
                                $debutmess = "L operation a ete initiee sur le numero " . $paiementinit->contact_paiement . ". ";

                                if ($retourReq->code !== 200) {
                                    $response['message'][] = $retourReq->cleretour;
                                    $code = $retourReq->code;
                                    $mess = "<h3 class='sub-title'>Page Introuvable</h3><br>
                                    <p>Une erreur est survenue, veuillez réessayer plus tard. # $retourReq->cleretour </p>";
                                    $module = "Module Espace Mutualiste Paiement";
                                    $action = "$retourReq->cleretour \n code: $code";
                                    Logs::saveLog($module, $action);
                                    return view('home.admin.errorpage.index', compact('code', 'mess'));
                                } else {
                                    $response['message'] = $debutmess . "\n " . $retourReq->cleretour;
                                    $ind = 1;
                                    return redirect()->back()
                                        ->with('mess', $response['message'])
                                        ->with('codeP', $codePaiement);
                                }
                                $module = "Module Espace Mutualiste Paiement";
                                $action = "\n** fin d initiation success transaction tremo $obj->CREDENTIAL, $numero, $ChaineLOG";
                                Logs::saveLog($module, $action);
                            } else {
                                $response['code'] = $responseReq->status();
                                $response['message'][] = "echec d initiation transaction tresormoney, impossible de joindre l hote.";
                                $code = $responseReq->status();
                                $mess = "<h3 class='sub-title'>Page Introuvable</h3><br>
                                    <p>Une erreur est survenue, veuillez réessayer plus tard. # echec d initiation transaction tresormoney, impossible de joindre l hote.  </p>";
                                $module = "Module Espace Mutualiste Paiement";
                                $action = "echec d initiation transaction tresormoney, impossible de joindre l hote.  \n code: $code";
                                Logs::saveLog($module, $action);
                                return view('home.admin.errorpage.index', compact('code', 'mess'));
                            }
                        }
                    } else {
                        $response['code'] = $responseReq->status();
                        $response['message'][] = "echec d authentification, impossible de joindre l hote.";
                        $code = $responseReq->status();
                        $mess = "<h3 class='sub-title'>Page Introuvable</h3><br>
                     <p>Une erreur est survenue, veuillez réessayer plus tard. #EDOCTNEME</p>";
                        $module = "Module Espace Mutualiste Paiement";
                        $action = "echec d authentification, impossible de joindre l hote.";

                        Logs::saveLog($module, $action);
                        return view('home.admin.errorpage.index', compact('code', 'mess'));
                    }


                    break;
                case 5:
                        //  dd($request->all() ,$id,'ss');
                    // Paiement de carte Membre
                    $auth = new STAuthTresorMoney();
                    $auth->Key = env('KEY_AUTH_TREMO');
                    $auth->Secret = env('SECRET_AUTH_TREMO');
                    $bufSend = json_encode($auth);
                    // $ChaineLOG .= "\n** debut authentification tresormoney, parametres: $bufSend";
                    $module = "Module Espace Mutualiste Paiement";
                    $action = "\n** debut authentification tresormoney, parametres: $bufSend";

                    Logs::saveLog($module, $action);
                    // Effectue la requête HTTP POST
                    $responseReq = Http::post(env('URL_AUTHENTIF'), $auth);

                    // Récupère le contenu de la réponse
                    $Contenu = $responseReq->body();
                    $retourauth = json_decode($responseReq->body());
                    // $ChaineLOG .= "\n** retour authentification tresormoney, reponse: $Contenu";
                    $module = "Module Espace Mutualiste Paiement Pret";
                    $action = "\n** retour authentification tresormoney, reponse: $Contenu";
                    Logs::saveLog($module, $action);
                    if ($responseReq->status() === 200) {

                        if ($retourauth->code !== 200) {
                            $response['code'] = $retourauth->code;
                            $response['message'][] = $retourauth->sMessage . ' ERREUR' . $retourauth->code;
                            $code = $retourauth->code;
                            $mess = "<h3 class='sub-title'>Page Introuvable</h3><br>
                            <p>Une erreur est survenue, veuillez réessayer plus tard. # $retourauth->sMessage erreur $retourauth->code</p>";
                            $module = "Module Espace Mutualiste Paiement";
                            $action = "$retourauth->sMessage \n envoyee: $bufSend \n recue: $Contenu";

                            Logs::saveLog($module, $action);
                            return view('home.admin.errorpage.index', compact('code', 'mess'));
                        } else {
                            // $demandeP = Facturation::find($request->facturationID);
                            $carteMembre =  CarteMembre::findOrFail($request->idcorrespondant);
                            $infosProduits = new listeDesProduits();
                            $infosProduits['LibelleProduit'] = "Carte Membre :" . $carteMembre->libelle;
                            $infosProduits['Montant'] =  $request->montant;
                            $infosProduits['nEstUnServicePrive'] = 0;
                            $infosProduits['TypeProduit'] = 1;
                            $infosProduits['Quantite'] = 1;
                            $infosProduits['IdProduit'] = 0;
                            $infosProduits['Reference_code_Produit'] = "";


                            $codePaiement = generateCode2('Ref');
                            // creation d'un nouveau element dans la table PaiementInitiale (debut)
                            $paiementinit = new PaiementInitiale();
                            $paiementinit->code_paiement = $codePaiement;
                            $paiementinit->mutualiste_id = $mutualiste->id;
                            $paiementinit->type_paiement_id = 5; // paiement de carteMembrex
                            $paiementinit->correspondance_id = $carteMembre->id; // id du carteMembre
                            $paiementinit->montant_initial = $carteMembre->montant ?? $request->montant;

                            $paiementinit->save();


                            // $infosbeneficiaire = new STBeneficiaire();
                            $infosbeneficiaire['Credentiel'] = env('HUB_KEY_TREMO'); //$obj->CREDENTIAL;
                            $infosbeneficiaire['produits'][] = $infosProduits;

                            // env('CALL_BACK_TREMO') urlCallbackLien
                            // $infospaiement = new paramaAEnvoyer();
                            $infospaiement['Url_callback'] = urlCallbackLien();
                            $infospaiement['Nom_usager'] = $mutualiste->nom;
                            $infospaiement['Prenom_usager'] = $mutualiste->prenom ?? "xxxxxxx";
                            $infospaiement['code_paiement'] = $codePaiement;
                            $infospaiement['Email'] = $mutualiste->email;
                            $infospaiement['Telephone'] = $paiementinit->contact_paiement;
                            $infospaiement['Additif'] = $codePaiement;
                            $infospaiement['Token'] = $retourauth->Token;
                            $infospaiement['TypeOperation'] = 1;
                            $infospaiement['TCredentiel'][] = $infosbeneficiaire;

                            $bufSend = json_encode($infospaiement);
                            $module = "Module Espace Paiement du  Mutualiste Tremo carteMembre";
                            $action = "\n** debut initiation transaction tremo, parametres :  $bufSend ";
                            Logs::saveLog($module, $action);

                            // Effectue la requête HTTP POST
                            $responseReq = Http::post(env('URL_INITIATE'), $infospaiement);
                            // Récupère le contenu de la réponse
                            $Contenu = $responseReq->body();

                            $module = "Module Espace Paiement du  Mutualiste Tremo Pour carteMembre";
                            $action = "\n** retour initiation transaction tremo, reponse: $Contenu";
                            Logs::saveLog($module, $action);

                            $retourReq = json_decode($responseReq->body());

                            if ($responseReq->status() === 200) {
                                $id = $paiementinit->id;
                                $response['idPay'] = $id;
                                $response['code'] = $retourReq->code;
                                $debutmess = "L operation a ete initiee sur le numero " . $paiementinit->contact_paiement . ". ";

                                if ($retourReq->code !== 200) {
                                    $response['message'][] = $retourReq->cleretour;
                                    $code = $retourReq->code;
                                    $mess = "<h3 class='sub-title'>Page Introuvable</h3><br>
                                    <p>Une erreur est survenue, veuillez réessayer plus tard. # $retourReq->cleretour </p>";
                                    $module = "Module Espace Mutualiste Paiement";
                                    $action = "$retourReq->cleretour \n api  code: $code ";
                                    Logs::saveLog($module, $action);
                                    return view('home.admin.errorpage.index', compact('code', 'mess'));
                                } else {
                                    $response['message'] = $debutmess . "\n " . $retourReq->cleretour;
                                    $ind = 1;
                                    return redirect()->back()
                                        ->with('mess', $response['message'])
                                        ->with('codeP', $codePaiement);
                                }
                                $module = "Module Espace Mutualiste Paiement";
                                $action = "\n** fin d initiation success transaction tremo $obj->CREDENTIAL, $numero, $ChaineLOG";
                                Logs::saveLog($module, $action);
                            } else {
                                $response['code'] = $responseReq->status();
                                $response['message'][] = "echec d initiation transaction tresormoney, impossible de joindre l hote.";
                                $code = $responseReq->status();
                                $mess = "<h3 class='sub-title'>Page Introuvable</h3><br>
                                    <p>Une erreur est survenue, veuillez réessayer plus tard. # echec d initiation transaction tresormoney, impossible de joindre l hote.  </p>";
                                $module = "Module Espace Mutualiste Paiement";
                                $action = "echec d initiation transaction tresormoney, impossible de joindre l hote.  \n code: $code";
                                Logs::saveLog($module, $action);
                                return view('home.admin.errorpage.index', compact('code', 'mess'));
                            }
                        }
                    } else {
                        $response['code'] = $responseReq->status();
                        $response['message'][] = "echec d authentification, impossible de joindre l hote.";
                        $code = $responseReq->status();
                        $mess = "<h3 class='sub-title'>Page Introuvable</h3><br>
                        <p>Une erreur est survenue, veuillez réessayer plus tard. #EDOCTNEME</p>";
                        $module = "Module Espace Mutualiste Paiement";
                        $action = "echec d authentification, impossible de joindre l hote.";

                        Logs::saveLog($module, $action);
                        return view('home.admin.errorpage.index', compact('code', 'mess'));
                    }


                    break;

                default:
                    // Code à exécuter si aucun des cas ci-dessus ne correspond
                    echo "ID inconnu.";
                    break;
            }
            // DB::commit();
            $module = "Module Espace Paiement du  Mutualiste ";
            $action = "a consulte le traitement de paiement d'un mutuliste ";
            Logs::saveLog($module, $action);
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Erreur traitementAppelApiPaiement : ' . $e->getMessage());
            $module = "Module Espace Paiement du  Mutualiste ";
            $action = "a consulte le traitement de paiement d'un mutuliste " . $e->getMessage();
            Logs::saveLog($module, $action);

            return back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
        // dd($mutualiste);
        // return view('home.admin.paiements.index', compact('mutualiste','libelle','montant','id'));
    }

    public function resulPayment($codePaiement, $ind)
    {
        $mutualiste = auth()->user()->mutualiste;
        $paiementinit = PaiementInitiale::where('code_paiement', $codePaiement)->first();
        $contCoti = NbreCotisation();
        // dd($codePaiement);
        if (!empty($paiementinit)) {
            if ($paiementinit->status == 1) {
                $code = 200;

                if ($ind > 10) {
                    $ind = 16;
                    $mess = 'La reponse de traitement de votre transaction a mit plus de temps que prévu, ' .
                        'mais elle a été valideé avec succès';
                } else {
                    $ind = 16;
                    $mess = 'Paiement éffectué avec succès';
                }
            } else {

                if ($ind < 15) {
                    $code = 203;
                    $comp = 15 - $ind;
                    $mess = "L operation a ete initiee sur le numero " . $paiementinit->contact_paiement . " La transaction a été initiée. Veuillez la valider sur le numéro en composant \n *760#, option 2 'Paiement-TresorPay' puis 2 'Valider un paiement' ou par l’application mobile TresorMoney dans un délais de $comp min ";


                    if ($ind == 14) {
                        $code = 201;
                        $mess = 'Votre transaction a mit plus de temps que prévu, ' .
                            'elle a donc été annulée. Si votre compte a été débité, nous vous prions' .
                            ' de contacter le support avec la reference: ' . $paiementinit->code_paiement;
                    }
                } else {
                    $code = 201;

                    $mess = ' Paiement échoué. Si votre compte a été débité, nous vous prions' .
                        ' de contacter le support avec la reference: ' . $paiementinit->code_paiement;
                }
            }
            $module = "Module Espace Mutualiste ";
            $action = "a consulte  la page resultat paiement et voici le code du paiement : $code";
            Logs::saveLog($module, $action);
            return view('home.admin.paiements.restPay', compact('paiementinit', 'mess', 'code', 'contCoti', 'mutualiste', 'ind', 'codePaiement'));
            // return view('home.admin.paiements.resultat_paiement', compact('paiementinit', 'mess', 'code', 'contCoti', 'mutualiste'));
        } else {
            $code = 404;
            $mess = "<h3 class='sub-title'>Page Introuvable</h3><br>
            <p>Une erreur est survenue, veuillez réessayer plus tard. #EDOCTNEME</p>";
            $module = "Module Espace Mutualiste ";
            $action = "Une erreur s'est produit sur la page resultat paiement car le paiement n'existe pas code Paiement : $codePaiement ";

            Logs::saveLog($module, $action);
            return view('home.admin.errorpage.index', compact('code', 'mess'));
        }
    }
}
