<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\Paiement;
use App\Models\Facturation;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Request;
use App\Models\DroitAdhesion;
use App\Models\PaiementInitiale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StorePaiementRequest;
use App\Http\Requests\UpdatePaiementRequest;
use App\Notifications\MutualisteNotification;
// use App\Notifications\MutualisteNotification;

class PaiementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

    }

    public $clError = [];

    function TestRetourAPI($param1 = []): bool
    {
        if (count($param1) > 0) {
            if (array_key_exists("codehttp", $param1)) {
                $this->clError = $param1;
                return true;
            }
        }
        return false;
    }
    public static function dmToastVersIHM($errorContent): string
    {
        $msg = ' ';
        if (array_key_exists("fault", $errorContent)) {
            if (isset($errorContent['fault']['faultstring'])) {
                $msg = str_replace(array("\r\n", "\n", "\r", "\t"), ' ', $errorContent['fault']['faultstring']);
            }
            if (isset($errorContent['fault']['detail'])) {
                $msg .= str_replace(array("\r\n", "\n", "\r", "\t"), ' ', $errorContent['fault']['detail']);
            }
        }
        return $msg;
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaiementRequest $request)
    {

        try {
            DB::beginTransaction();
            $data = [
                'code_paiement' => 'reference',
                'credential_id' => "wojjayw8st",
                'nom_usager' => auth()->user()->mutualiste->nom,
                'prenom_usager' => auth()->user()->mutualiste->prenom,
                'telephone' => auth()->user()->mutualiste->contact,
                'email' => auth()->user()->mutualiste->email,
                'libelle_article' => auth()->user()->mutualiste->droitAdhesion->libelle,
                'quantite' => 1,
                'montant' => auth()->user()->mutualiste->paiement->montant,
                'lib_order' => auth()->user()->mutualiste->droitAdhesion->libelle,
                'url_logo' =>  asset('assets/home/images/logo/logo.png'),
                'pay_fees' => 1,
                'url_retour' => url('/accueil'),
                'url_callback' => url('api/paiements/callback'),
            ];

            Paiement::create($data);
            DB::commit();
            $response = Http::post('https://rest-airtime.paysecurehub.com/payhub-ws/build-away', $data);
            $resJSON = $response->json();
            // dd($resJSON);

            $this->clError = [];
            if ($this->TestRetourAPI($resJSON) == true) {
                return back()->with('error', $this->dmToastVersIHM($this->clError));
                // return redirect()->route('accueil');
            }

            if (isset($resJSON['url']) && $resJSON['url'] != '') {
                return redirect()->away($resJSON['url']);
            } else {
                return back()->with('error', "Echec d'authentification pour accès à la page demandée !");
                // return redirect()->route('accueil');
            }
        } catch (\Throwable $e) {
            DB::rollback();
            Log::error('Erreur interne du serveur: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur s\'est produite, veuillez réessayer.');
            // return redirect()->route('accueil');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Paiement $paiement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Paiement $paiement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaiementRequest $request, Paiement $paiement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Paiement $paiement)
    {
        //
    }

    // paiement droit d'adhesion
    public function paiementAdhesion()
    {
        //  dd(auth()->user()->mutualiste->droitAdhesion);
        try {
            DB::beginTransaction();
            // initialisation du code de paiement avec une valeur unique
            $codePaiement = generateCode2('Ref');
            // creation d'un nouveau element dans la table PaiementInitiale (debut)
            $paiementinit = new PaiementInitiale();
            $paiementinit->code_paiement = $codePaiement;
            $paiementinit->mutualiste_id = auth()->user()->mutualiste->id;
            $paiementinit->type_paiement_id = 1;
            $paiementinit->correspondance_id = auth()->user()->mutualiste->droitAdhesion->id;
            $paiementinit->montant_initial = auth()->user()->mutualiste->droitAdhesion->montant;
            $paiementinit->save();
            // fin


            $data = [
                'code_paiement' => $codePaiement,
                'nom_usager' => auth()->user()->mutualiste->nom,
                'prenom_usager' => auth()->user()->mutualiste->prenom,
                'telephone' => auth()->user()->mutualiste->contact,
                'email' => auth()->user()->mutualiste->email,
                'libelle_article' => auth()->user()->mutualiste->droitAdhesion->libelle,
                'quantite' => 1,
                'montant' => auth()->user()->mutualiste->droitAdhesion->montant,
                'lib_order' => auth()->user()->mutualiste->droitAdhesion->libelle,
                'pay_fees' => 1,
                'Url_Retour' => urlRetour() . $codePaiement,
                'Url_Callback' => urlCallback(),
                // 'Url_Retour' => route('resultat.paiement',['codePaiement'=>$codePaiement]),
                // 'Url_Callback' => route('paiements.newCallBack'),
            ];
            // dd('ici');
            // // Paiement::create($data);
            // // initialisation des route d'envoie via la plateforme de paiement

            $reponse = Http::withHeaders(['MerchantId' => CREDENSHEL(), 'ApiKey' => cleApi()])
                ->post(urlPaiement(), $data);
            $ResJSON = $reponse->json();
            $code = $ResJSON['code'];
            if ($reponse->status() === 200) {
                if ($code === 200) {
                    DB::commit();

                    if (!empty($ResJSON['url'])) {
                        $module = "Module paiement";
                        $action = " Un mutualiste est passe sur l'hub de paiement concernant un paiement de droit d'adhesion";
                        Logs::saveLog($module, $action);
                        return redirect()->away($ResJSON['url']);
                    } else {
                        toast('Echec d\'authentification à la page demandée !', 'error');
                        $module = "Module paiement";
                        $action = " Une erreur s'est produit lors du passage sur le hub de paiement consernant le droit d'adhesion";
                        Logs::saveLog($module, $action);
                        return back();
                    }
                } else {
                    $message = messageBrut($ResJSON['message']);
                    $module = "Module paiement";
                    $action = " $message";
                    Logs::saveLog($module, $action);
                    toast($message, 'error');
                    return back();
                }
                // verification
            } else {
                $message = 'Une erreur inattendue s\'est produite, verifier que vous avez accès à internet, ' .
                    'puis reéssayer. erreur ' . $reponse->status();
                toast($message, 'error');
                $module = "Module paiement";
                $action = " $message";
                Logs::saveLog($module, $action);
            }
        } catch (\Throwable $e) {
            // dd($e->getMessage().'test');
            DB::rollback();
            $module = "Module paiement";
            $action = " Une erreur s'est produite sur le serveur lors de passage sur l'hub de paiement" . $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back()->with('error', 'Une erreur s\'est produite, veuillez réessayer.');
            // return redirect()->route('accueil');
        }
    }

    // paiement du facturation concernant un produit
    public function paiementProduitFacturation($id)
    {
        $facturations = Facturation::findOrFail($id);
        try {
            DB::beginTransaction();
            // initialisation du code de paiement avec une valeur unique
            $codePaiement = generateCode2('Pro');
            // creation d'un nouveau element dans la table PaiementInitiale (debut)
            $paiementinit = new PaiementInitiale();
            $paiementinit->code_paiement = $codePaiement;
            $paiementinit->mutualiste_id = auth()->user()->mutualiste->id;
            $paiementinit->type_paiement_id = 4;
            $paiementinit->correspondance_id = $facturations->id;
            $paiementinit->montant_initial =  $facturations->total_apayer;
            $paiementinit->save();


            // fin
            // DB::commit();
            // dd($paiementinit);
            // traitement appel a l'api
            // initialisation dans le champs de la bd de la plateforme de paiement
            // $data = [
            //     'code_paiement' => $codePaiement,
            //     'credential_id' => "wojjayw8st", // code unique donnee pour mes acces a la plateforme
            //     'nom_usager' => auth()->user()->mutualiste->nom,
            //     'prenom_usager' => auth()->user()->mutualiste->prenom,
            //     'telephone' => auth()->user()->mutualiste->contact,
            //     'email' => auth()->user()->mutualiste->email,
            //     'libelle_article' => auth()->user()->mutualiste->facturations->projetMutualiste->libelle,
            //     'quantite' => 1,
            //     'montant' => auth()->user()->mutualiste->facturations->total_apayer,
            //     'lib_order' => auth()->user()->mutualiste->facturations->produitProjet->libelle,
            //     'Url_Logo' =>  asset('assets/home/images/logo/logo.png'),
            //     'pay_fees' => 1,
            //     'Url_Retour' => 'https://127.0.0.1:8000/accueil',
            //     'Url_Callback' => 'https://127.0.0.1:8000/api/paiements/newCallBack',
            // ];
            // // // Paiement::create($data);
            // // // initialisation des route d'envoie via la plateforme de paiement
            // $reponse = Http::post('https://rest-airtime.paysecurehub.com/api/payhub-ws/build-away', $data);
            // $ResJSON = $reponse->json();
            // if ($reponse->status()===200) {
            //     if ($ResJSON['code']===200){
            //         // Redirection sur le hub de paiement
            //         if (!empty($ResJSON['url'])){
            //             return redirect()->away($ResJSON['url']);
            //         }else{
            //             $mess = "Echec d'authentification pour acceder à la page demandée !";
            //             toast($mess,'success');
            //             return redirect()->back();
            //         }
            //     }else{
            //         $mess = $ResJSON['message'];
            //         toast($mess,'error');
            //         return redirect()->back();
            //     }
            // }else{
            //     $mess = 'Une erreur inattendue s\'est produite, verifier que vous ' .
            //     'avez accès à internet, puis reéssayer. erreur ' . $reponse->status().', impossible de joindre l\'hôte !';
            //     toast($mess,'error');
            //     return redirect()->back();
            // }

            if ($paiementinit &&  $paiementinit->code_paiement === $codePaiement) {
                $paiement = new Paiement();
                $paiement->reference = "reference";
                $paiement->code_paiement = $codePaiement;
                $paiement->mutualiste_id = $paiementinit->mutualiste_id;
                $paiement->type_paiement_id = $paiementinit->type_paiement_id;
                $paiement->correspondance_id = $facturations->id;
                $paiement->montant_initial = $paiementinit->montant_initial;
                $paiement->montant_total = $facturations->total_apayer;
                $paiement->moyen_paiement = "TresorMoney";
                $paiement->contact_paiement = auth()->user()->mutualiste->contact;
                // $paiement->status = 1;
                $paiement->save();
                // dd($paiementinit,$paiementinit->code_paiement,$codePaiement, $paiement);
            }
            toast('Paiement effectuer  avec succès !', 'success');
            $facturationsT = Facturation::whereId($facturations->id)->first();
            $facturationsT->update([
                'reste_apayer' => 0,
                'total_payer' => $facturationsT->total_apayer,
            ]);
            // dd($facturationsT);
            // dd($facturationsT->reste_apayer);
            DB::commit();
            return redirect()->back();
        } catch (\Throwable $e) {
            DB::rollback();
            Log::error('Erreur interne du serveur: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur s\'est produite, veuillez réessayer.');
        }
    }




    // paiement droit d'adhesion
    public function paiementCarteMembre()
    {
        //  dd(auth()->user()->mutualiste->carteMembre);
        try {
            DB::beginTransaction();
            // initialisation du code de paiement avec une valeur unique
            $codePaiement = generateCode2('Car');
            // creation d'un nouveau element dans la table PaiementInitiale (debut)
            $paiementinit = new PaiementInitiale();
            $paiementinit->code_paiement = $codePaiement;
            $paiementinit->mutualiste_id = auth()->user()->mutualiste->id;
            $paiementinit->type_paiement_id = 5;
            $paiementinit->correspondance_id = auth()->user()->mutualiste->carteMembre->id;
            $paiementinit->montant_initial = auth()->user()->mutualiste->carteMembre->montant;
            $paiementinit->save();
            // fin


            $data = [
                'code_paiement' => $codePaiement,
                'nom_usager' => auth()->user()->mutualiste->nom,
                'prenom_usager' => auth()->user()->mutualiste->prenom,
                'telephone' => auth()->user()->mutualiste->contact,
                'email' => auth()->user()->mutualiste->email,
                'libelle_article' => auth()->user()->mutualiste->carteMembre->libelle,
                'quantite' => 1,
                'montant' => auth()->user()->mutualiste->carteMembre->montant,
                'lib_order' => auth()->user()->mutualiste->carteMembre->libelle,
                'pay_fees' => 1,
                'Url_Retour' => urlRetour() . $codePaiement,
                'Url_Callback' => urlCallback(),
                // 'Url_Retour' => route('resultat.paiement',['codePaiement'=>$codePaiement]),
                // 'Url_Callback' => route('paiements.newCallBack'),
            ];
            // dd('ici');
            // // Paiement::create($data);
            // // initialisation des route d'envoie via la plateforme de paiement

            $reponse = Http::withHeaders(['MerchantId' => CREDENSHEL(), 'ApiKey' => cleApi()])
                ->post(urlPaiement(), $data);
            $ResJSON = $reponse->json();
            $code = $ResJSON['code'];
            if ($reponse->status() === 200) {
                if ($code === 200) {
                    DB::commit();

                    if (!empty($ResJSON['url'])) {
                        $module = "Module paiement";
                        $action = " Un mutualiste est passe sur l'hub de paiement concernant un paiement de carte Membre";
                        Logs::saveLog($module, $action);
                        return redirect()->away($ResJSON['url']);
                    } else {
                        toast('Echec d\'authentification à la page demandée !', 'error');
                        $module = "Module paiement";
                        $action = " Une erreur s'est produit lors du passage sur le hub de paiement consernant la carte Membre";
                        Logs::saveLog($module, $action);
                        return back();
                    }
                } else {
                    $message = messageBrut($ResJSON['message']);
                    $module = "Module paiement";
                    $action = " $message";
                    Logs::saveLog($module, $action);
                    toast($message, 'error');
                    return back();
                }
                // verification
            } else {
                $message = 'Une erreur inattendue s\'est produite, verifier que vous avez accès à internet, ' .
                    'puis reéssayer. erreur ' . $reponse->status();
                toast($message, 'error');
                $module = "Module paiement";
                $action = " $message";
                Logs::saveLog($module, $action);
            }
        } catch (\Throwable $e) {
            // dd($e->getMessage().'test');
            DB::rollback();
            $module = "Module paiement";
            $action = " Une erreur s'est produite sur le serveur lors de passage sur l'hub de paiement" . $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back()->with('error', 'Une erreur s\'est produite, veuillez réessayer.');
            // return redirect()->route('accueil');
        }
    }




    // public function paiementInscription(Request $request)
    // {

    //     //  dd($request->input());
    //     try {
    //         $obj = session()->get('paybox');
    //         $codePaiement = $obj['transUID'];
    //         $artisan = $obj['infos'];
    //         // dd($obj);
    //         $id = '';
    //         foreach ($obj['factures'] as $fact) {
    //             if ($fact['ID_GROUPES'] < 4) {
    //                 if ($request->has($fact['ID_LIGNESFACTURE'])) {
    //                     $id .= $request->input($fact['ID_LIGNESFACTURE']) . '.';
    //                 }
    //             }
    //         }
    //         foreach ($obj['factures'] as $fact) {
    //             if ($fact['ID_GROUPES'] > 3) {
    //                 if ($request->has('choix' . $fact['ID_LIGNESFACTURE'])) {
    //                     $id .= $request->input($fact['ID_LIGNESFACTURE']) . '.';
    //                 }
    //             }
    //         }
    //         // dd($id);
    //         $len = strlen($id) - 1;
    //         $id = substr($id, 0, $len);
    //         $cod = $codePaiement . '-' . $id;
    //         (int) $montant = $request->montant_initial;
    //         $data = [
    //             'code_paiement' => $cod,
    //             'credential_id' => '28kahrg-q0',
    //             'nom_usager' => $artisan['NOM'],
    //             'prenom_usager' => $artisan['PRENOMS'],
    //             'telephone' => $artisan['LOGIN'],
    //             'email' => '',
    //             'libelle_article' => $obj['factures'][0]['LIB_TAXE'],
    //             'quantite' => 1,
    //             'montant' => $montant,
    //             'lib_order' => $codePaiement,
    //             'Url_Logo' => '',
    //             'pay_fees' => 1,
    //             'Url_Retour' => "https://artisanconnect.ci/state-payment/$codePaiement",
    //             // 'Url_Retour' => "https://site-cnmci.paysecurehub.com/state-payment/$codePaiement",
    //             // 'Url_Callback' => 'https://cnmci.paysecurehub.com/api/cnmci-ws/callbackPAYMENT',
    //             // 'Url_Callback' => 'http://rest-ws.artisanconnecte.net/api/cnmci-ws/callbackPAYMENT',
    //             'Url_Callback' => 'https://cnmci.paysecurehub.com/api/cnmci-ws/callbackPAYMENT',

    //         ];
    //         $reponse = Http::post('http://rest-airtime.paysecurehub.com/api/payhub-ws/build-away', $data);
    //         $ResJSON = $reponse->json();
    //         $code = $ResJSON['code'];
    //         if ($reponse->status() === 200) {
    //             if ($code === 200) {
    //                 if (!empty($ResJSON['url'])) {
    //                     return redirect()->away($ResJSON['url']);
    //                 } else {
    //                     toast('Echec d\'authentification à la page demandée !', 'error');
    //                     return back();
    //                 }
    //             } else {
    //                 $message = messageBrut($ResJSON['message']);
    //                 toast($message, 'error');
    //                 return back();
    //             }
    //         } else {
    //             $message = 'Une erreur inattendue s\'est produite, verifier que vous avez accès à internet, ' .
    //                 'puis reéssayer. erreur ' . $reponse->status();
    //             toast($message, 'error');
    //         }
    //     } catch (\Exception $e) {
    //         $message = 'Une erreur inattendue s\'est produite: ' . $e->getMessage();
    //         toast($message, 'error');
    //         return back();
    //     }
    // }
}
