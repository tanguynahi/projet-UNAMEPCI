<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\Compte;
use App\Models\Service;
use App\Models\Parametre;
use Illuminate\Http\Request;
use App\Models\DroitAdhesion;
use App\Mail\AlerteMontantEleve;
use App\Models\DocumentPaiement;
use App\Models\PaiementInitiale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
// use Illuminate\Support\Facades\Request;
use App\Models\DemandeAccompagnement;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreDemandeAccompagnementRequest;
use App\Http\Requests\UpdateDemandeAccompagnementRequest;

class DemandeAccompagnementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // $demandeaccompagnements = DemandeAccompagnement::orderBy('created_at', 'DESC')->get();
        // $demandeaccompagnements = DB::table('demande_accompagnements')
        // ->join('services', 'demande_accompagnements.service_id', '=', 'services.id')
        // ->select('demande_accompagnements.*', 'services.libelle as service_libelle')
        // ->orderBy('demande_accompagnements.created_at', 'DESC')
        // ->get()
        // ->groupBy('service_id');

        // $demandeaccompagnements = DB::table('demande_accompagnements')
        // ->join('services', 'demande_accompagnements.service_id', '=', 'services.id')
        // ->select('demande_accompagnements.*', 'services.libelle as service_libelle')
        // ->orderBy('demande_accompagnements.created_at', 'DESC')
        // ->get()
        // ->groupBy('service_libelle');

        $demandeaccompagnements = DemandeAccompagnement::with(['mutualiste', 'mutualiste.grade', 'service'])
            ->orderBy('created_at', 'DESC')
            ->get()
            ->groupBy('service.libelle');

        $module = "Module Demande Accompagnement";
        $action = "A consulte la liste des demande d'accompagnements ";
        Logs::saveLog($module, $action);
        return view('dashboard.demandes.demandes_prets.index', compact('demandeaccompagnements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

        $services = Service::orderBy('libelle', 'asc')->get();
        $module = "Module Demande Accompagnement";
        $action = "A affiche la page demande d'accompagements  ";
        Logs::saveLog($module, $action);
        return view('home.admin.accompagnements.formulaire-accompagement', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDemandeAccompagnementRequest $request)
    {
        try {
            $mutualiste = auth()->user()->mutualiste;
            $parametre = Parametre::whereId(1)->first();
            DB::beginTransaction();
            $service = Service::where('id', $request->service_id)->first();
            // dd($service->montant_maximum);
            if ($service && $request->montant_voulue > $service->montant_maximum) {
                toast('Vous ne pouvez pas demande plus de ' . $service->montant_maximun . ' pour ce service', 'error');
                // return redirect()->back();
                return redirect()->back()->with([
                    'status' => 'error',
                    'message' => 'Vous ne pouvez pas demander plus de ' . $service->montant_maximum . ' pour ce service'
                ]);
            }
            $demandeAccompagnement = new DemandeAccompagnement();
            $demandeAccompagnement->mutualiste_id = $mutualiste->id;
            $demandeAccompagnement->service_id = $request->service_id;
            $demandeAccompagnement->contact_tresormoney = htmlspecialchars($request->contact_tresormoney);
            $demandeAccompagnement->montant_voulue = htmlspecialchars($request->montant_voulue);
            $demandeAccompagnement->montant_apayer = htmlspecialchars($request->montant_apayer);
            $demandeAccompagnement->commentaire = htmlspecialchars($request->commentaire);
            $demandeAccompagnement->save();
            if (!empty($demandeAccompagnement)) {
                $module = "Module Demande Accompagnement";
                $action = "Le mutualiste : $mutualiste->nom a enregistre une demande d'accompagnements id : $demandeAccompagnement->id  ";
                Logs::saveLog($module, $action);
            }

            $transition = $demandeAccompagnement->montant_voulue;
            // compte FPM le compte a debit lors d'une demande d'accompagnement
            $comptesFPM = Compte::where('id', 1)->first(); // compte FPM le compte a debit lors d'une demande d'accompagnement
            // compte transitoire le compte a credit lors d'une demande d'accompagnement
            $compteTRA = Compte::where('id', 2)->first();  // compte transitoire le compte a credit lors d'une demande d'accompagnement
            // la condition si le montant demande est superieur au solde du compte Mutualplay
            if ($request->montant_voulue > $comptesFPM->solde) {
                // DB::rollBack();;
                toast('Le service est momentanément indisponible. Veuillez réessayer plus tard.', 'error');
                // pour envoyer un sms a l'administrateur pour insufissante de solde dans le compte mutualplay

                $module = "Module Demande Accompagnement";
                $action = "montant compte FPM insuffissante au montant demande pas le mutualiste : $mutualiste->nom , $mutualiste->prenom , montant demande : $request->montant_voulue FCFA ";
                Logs::saveLog($module, $action);

                $sujet = "Insuffissance de prêt sur votre compte MUTUALPAY";
                $message = "
                    Bonjour, Cher Administrateur <br>
                    Nous vous informons qu'un mutualiste "
                    . $mutualiste->prenom . ' ' . $mutualiste->nom . "<br>
                   a récemment soumis une demande de prêt. Le montant demandé s'élève à  <br>" .  formatMontant($demandeAccompagnement->montant_voulue)  . " <br>  ce qui dépasse le solde disponible sur le compte de la mutuelle. <br>
                   Nous vous prions de bien vouloir examiner cette demande et d'évaluer la situation. Merci d'utiliser notre plateforme pour gérer ce cas <br>
                   Si vous avez des questions ou si vous avez besoin d'assistance supplémentaire, n'hésitez pas à nous contacter.
                ";

                $url = appelApiEmail();
                // $url = "https://mailtremo.paysecurehub.com/api/sendemail";
                $template = View::make('home.admin.paiements.paiementAdhesion', ['contenumess' => $message])->render();
                $email = '';
                if (empty($parametre->email_1) && empty($parametre->email_2)) {
                    $email = 'tanguymannahi@gmail.com';
                } elseif (!empty($parametre->email_1)) {
                    $email = $parametre->email_1;
                } else {
                    $email = $parametre->email_2;
                }
                $data = [
                    'provider' => 'MUTUALPAY <info@mail-taseti.com>',
                    "key_rsa" => 're_2i7H3Ynf_KRVm9VwTsrwrfF8isCBYvyyE',
                    "destination" => $email,
                    "sujet" => $sujet,
                    "message" => $template
                ];
                $retAPI = Http::post($url, $data);
                $res = $retAPI->json();
                if ($retAPI->status() == 200) {
                    (int)$code = $res['status'];
                    if ($code != 200) {
                        $message = "Une erreur s'est produite " . $code . ", DETAIL: " . messageBrut($res['message']) . " ERR: Envoye Paiement adhesion";
                        Log::error($message);
                    }
                    $module = "Module Demande Accompagnement";
                    $action = "Email envoyer a l'administrateur sur le mail : $email consernant l'insuffissance de montant du compte FPM ";
                    Logs::saveLog($module, $action);
                } else {
                    Log::error("Erreur lors de l'envoi de l'email. Statut API : " . $retAPI->status());
                    $module = "Module Demande Accompagnement";
                    $action = "Une error s'est produites lors de l'envoyer de mail consernant l'insuffissante de fond dans le compte FPM " . $retAPI->status();
                    Logs::saveLog($module, $action);
                }
                return redirect()->route('error.pret');
            }

            if ($compteTRA) {
                // $compteTRA->solde = $compteTRA->solde + $transition;
                $compteTRA->solde += $transition;
                $compteTRA->save();
            }

            if ($comptesFPM) {
                // $comptesFPM->solde = $comptesFPM->solde - $transition;
                $comptesFPM->solde -= $transition;
                $comptesFPM->save();
            }
            DB::commit();
            toast('Votre demande d\'accompagnement a été effectuée avec succès !', 'success');
            // return redirect()->route('liste.demandeaccompagnement')->with('success_message', 'Formulaire soumis avec succès!');
            $module = "Module Demande Accompagnement";
            $action = "la demande d'accompagnement id : $demandeAccompagnement->id a ete effectuer avec succes ";
            Logs::saveLog($module, $action);

            return redirect()->route('liste.demandeaccompagnement')->with([
                'status' => 'success',
                'message' => 'Votre demande d\'accompagnement a été effectuée avec succès !'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
            Log::error('Erreur interne du serveur: ' . $e->getMessage());
            $module = "Module Demande Accompagnement";
            $action = "Une erreur s'est produite lors de la demande d'accompagnement " . $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back()->with([
                'status' => 'error',
                'message' => 'Une erreur s\'est produite, Veuillez réessayer.'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(DemandeAccompagnement $demandeAccompagnement) {}
    public function detail($id)
    {
        $demandeAccompagnement = DemandeAccompagnement::where('id', $id)->first();
        $module = "Module Demande Accompagnement";
        $action = "A affiché la page de detail d'un demande d'accompagnement id : $demandeAccompagnement->id";
        Logs::saveLog($module, $action);
        return view('dashboard.demandes.demandes_prets.show', compact('demandeAccompagnement'));
    }
    public function accepterDemande($id)
    {
        try {
            DB::beginTransaction();
            $demandeAccompagnement = DemandeAccompagnement::where('id', $id)->first();
            if (empty($demandeAccompagnement)) {
                toast("Une erreur s'est produite : ID inexistant", 'error');
                $module = "Module Demande Accompagnement";
                $action = "une Erreur s'est produit lors de la recuperation des information d'une demande d'accompagnement ce id : $id est vide dans ma table demande accompagnement";
                Logs::saveLog($module, $action);
                return redirect()->back();
            }
            // compte transitoire
            $compteTRA = Compte::where('id', 2)->first();
            $comptMutualiste = Compte::where('mutualiste_id', $demandeAccompagnement->mutualiste_id)->first();
            if ($compteTRA) {
                $compteTRA->solde -= $demandeAccompagnement->montant_voulue;
                $compteTRA->save();
            }
            if ($comptMutualiste) {
                $comptMutualiste->solde += $demandeAccompagnement->montant_voulue;
                $comptMutualiste->save();
            }
            $demandeAccompagnement->update([
                'administrateur_id' => auth()->user()->administrateur->id,
                'status' => 1,
            ]);
            DB::commit();
            toast('Votre demande d\'accompagnement a été acceptée avec succès !', 'success');
            $module = "Module Demande Accompagnement";
            $action = "La demande d'accompagnement ayant id $demandeAccompagnement->id a été accepter avec succés";
            Logs::saveLog($module, $action);

            // return redirect()->route('liste.demandeaccompagnement')->with('success_message', 'Formulaire soumis avec succès!');
            return redirect()->route('demandeAccompagnement.detail', ['id' => $id]);
        } catch (\Throwable $e) {
            DB::rollBack();
            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
            Log::error('Erreur interne du serveur: ' . $e->getMessage());
            $module = "Module Demande Accompagnement";
            $action = "une erreur serveur s'est produite lors de l'acceptation d'une demande d'accompagnement";
            Logs::saveLog($module, $action);
            return redirect()->back()->with([
                'status' => 'error',
                'message' => 'Une erreur s\'est produite, Veuillez réessayer.'
            ]);
        }
    }

    public function refusDemandePret(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $demandeAccompagnement = DemandeAccompagnement::where('id', $id)->first();
            if (empty($demandeAccompagnement)) {
                toast("Une erreur s'est produite : ID inexistant", 'error');
                $module = "Module Demande Accompagnement";
                $action = "Une erreur s'est produite lors de la recuperation de la demande d'accompagnement ce id : $id est vide dans la table demandeAccompagnement";
                Logs::saveLog($module, $action);
                return redirect()->back();
            }
            // compte transitoire
            $compteTRA = Compte::where('id', 2)->first();
            // compte FPM
            $comptesFPM = Compte::where('id', 1)->first();
            if ($compteTRA) {
                $compteTRA->solde -= $demandeAccompagnement->montant_voulue;
                $compteTRA->save();
            }
            if ($comptesFPM) {
                $comptesFPM->solde += $demandeAccompagnement->montant_voulue;
                $comptesFPM->save();
            }
            $demandeAccompagnement->update([
                'administrateur_id' => auth()->user()->administrateur->id,
                'status' => 3,
                'rejet' => $request->rejet,
            ]);
            DB::commit();
            toast('Votre demande d\'accompagnement a été refusée avec succès !', 'success');
            $module = "Module Demande Accompagnement";
            $action = "la demande d'accompagnement ayant id : $demandeAccompagnement->id a ete refuse avec succes";
            Logs::saveLog($module, $action);
            return redirect()->route('demandeAccompagnement.detail', ['id' => $id]);
        } catch (\Throwable $e) {
            DB::rollBack();
            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
            Log::error('Erreur interne du serveur: ' . $e->getMessage());
            $module = "Module Demande Accompagnement";
            $action = "Une erreur s'est produite lors de refus d'une demande d'accompagnement";
            Logs::saveLog($module, $action);
            return redirect()->back()->with([
                'status' => 'error',
                'message' => 'Une erreur s\'est produite, Veuillez réessayer.'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DemandeAccompagnement $demandeAccompagnement) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDemandeAccompagnementRequest $request, DemandeAccompagnement $demandeAccompagnement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DemandeAccompagnement $demandeAccompagnement)
    {
        //
    }


    public function listeDemandeAccompagnement()
    {
        $mutualiste = auth()->user()->mutualiste;
        $demandeaccompagnements = DemandeAccompagnement::where('mutualiste_id', $mutualiste->id)
            ->orderBy('created_at', 'desc')
            ->get();
        // dd($demandeaccompagnements);
        $module = "Module Demande Accompagnement";
        $action = "le mutualiste : $mutualiste->nom , $mutualiste->prenom , a l'id : $mutualiste->id a consulte la liste de ses demande d'accompagnements";
        Logs::saveLog($module, $action);
        return view('home.admin.accompagnements.accompagnement', compact('demandeaccompagnements'));
    }
    public function accompagnementAttente(DemandeAccompagnement $demandeAccompagnement)
    {

        return view('home.admin.accompagnements.accompagnement_attente', compact('demandeAccompagnement'));
    }
    public function editDemandeMutualiste($id)
    {
        $demandeaccompagnement = DemandeAccompagnement::findOrFail($id);
        // dd($demandeaccompagnement);
        $services = Service::orderBy('libelle', 'asc')->get();
        $module = "Module Demande Accompagnement";
        $action = "a affiche la page de detail d'une demande d'accompagnement id : $demandeaccompagnement->id";
        Logs::saveLog($module, $action);
        return view('home.admin.accompagnements.edit', compact('demandeaccompagnement', 'services'));
    }
    public function miseAJourDemande(Request $request, $id)
    {
        $validated = $request->validate([
            "service_id" => 'required|integer|exists:services,id',
            "contact_tresormoney" => 'required|string|min:10|max:10',
            "montant_voulue" => 'required|integer',
            "montant_apayer" => 'required|integer',
            "commentaire" => 'nullable|string',
        ]);
        $demandeaccompagnement = DemandeAccompagnement::findOrFail($id);
        // dd($demandeaccompagnement);
        if ($demandeaccompagnement && $demandeaccompagnement->status == 2) {
            $demandeaccompagnement->update([
                'service_id' => $validated['service_id'],
                'contact_tresormoney' => $validated['contact_tresormoney'],
                'montant_voulue' => $validated['montant_voulue'],
                'montant_apayer' => $validated['montant_apayer'],
                'commentaire' => $validated['commentaire'],
            ]);

            $message = "Votre demande a été modifier avec succès !";
            toast($message, 'success');
            return redirect()->route('liste.demandeaccompagnement');
        } else {
            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            return redirect()->route('liste.demandeaccompagnement');
        }
    }
    // verifier  les detail de ma demande
    public function detaildemandeAccompagnement($id)
    {
        $demandeaccompagnement = DemandeAccompagnement::findOrFail($id);
        $paiements = PaiementInitiale::where('type_paiement_id', 3)
            ->where('correspondance_id', $id)
            ->orderBy('created_at', 'DESC')
            ->get();
        $module = "Module Demande Accompagnement";
        $action = "a affiche la page de detail d'une demande d'accompagnement id : $demandeaccompagnement->id";
        Logs::saveLog($module, $action);
        return view('home.admin.accompagnements.show', compact('demandeaccompagnement', 'paiements'));
    }
    public function pageErrorpret()
    {
        $code = 504;
        return view('home.admin.errorpage.errormontant', compact('code'));
    }
    // remboursement dette (aller sur hub)
    public function remboursHub(Request $request)
    {
        try {
            // dd($request->all());
            DB::beginTransaction();
            // initialisation du code de paiement avec une valeur unique
            $codePaiement = generateCode2('Rem');
            // creation d'un nouveau element dans la table PaiementInitiale (debut)
            $paiementinit = new PaiementInitiale();
            $paiementinit->code_paiement = $codePaiement;
            $paiementinit->mutualiste_id = auth()->user()->mutualiste->id;
            $paiementinit->type_paiement_id = 3; // pret
            $paiementinit->correspondance_id = $request->idDemandeaccompa; // id
            $paiementinit->montant_initial = $request->montant;

            $paiementinit->save();
            // fin
            DB::commit(); // verification
            $data = [
                'code_paiement' => $codePaiement,
                'nom_usager' => auth()->user()->mutualiste->nom,
                'prenom_usager' => auth()->user()->mutualiste->prenom,
                'telephone' => auth()->user()->mutualiste->contact,
                'email' => auth()->user()->mutualiste->email,
                'libelle_article' => $request->libelleRed,
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
                        return redirect()->away($ResJSON['url']);
                    } else {
                        toast('Echec d\'authentification à la page demandée !', 'error');
                        return back();
                        $module = "Module Demande Accompagnement";
                        $action = "une erreur s'est produite lors de l'appel de l'api pour le paiement d'une demande d'accompagnement";
                        Logs::saveLog($module, $action);
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
            }
        } catch (\Throwable $e) {
            // dd($e->getMessage().'test');
            DB::rollback();
            Log::error('Erreur interne du serveur: ' . $e->getMessage());
            $module = "Module Demande Accompagnement";
            $action = "Une erreur serveur s'est produite lors de l'appel de l'api" . $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back()->with('error', 'Une erreur s\'est produite, veuillez réessayer.');
        }
    }
    // enregistrement Remboursement (dette)
    public function rembourDetteEnregiste(Request $request)
    {
        try {
            DB::beginTransaction();
            $paiementInital = PaiementInitiale::create([
                'mutualiste_id' => auth()->user()->mutualiste->id,
                'reference' => $request->reference,
                'p_cash' => 1,
                'type_paiement_id' => 3, // pret
                'correspondance_id' => $request->idDemandeaccompa, // id de la demande d'accompagnement
                'montant_initial' => $request->montant,
                'moyen_paiement' => $request->modepaiement,
                'date_paiement_initial' => $request->date,
                'status' => 2,
            ]);

            if ($request->hasFile('document')) {
                foreach ($request->file('document') as $index => $file) {
                    $file_name = md5(uniqid()) . $index . '.' . $file->extension();
                    if (Storage::exists('images-docPaiementPret/' . $file_name)) {
                        $file_name = md5(uniqid()) . $index . '.' . $file->extension();
                    }
                    $file->storeAs('images-docPaiementPret/', $file_name);
                    $lien_images_projet = 'src-files/images-docPaiementPret/' . $file_name;

                    DocumentPaiement::create([
                        'pret_id' => $request->idDemandeaccompa,
                        'paiement_initiale_id' => $paiementInital->id,
                        'lien_photo' => $lien_images_projet,
                    ]);
                }
            }
            DB::commit();
            toast('Bien ajouté avec succès !', 'success');
            $module = "Module Demande Accompagnement";
            $action = "a ajouter une paiement manuelle consernant le paiement d'une demande d'accompagnement id : $request->idDemandeaccompa";
            Logs::saveLog($module, $action);
            return redirect()->route('detail.demandeaccompagnement', ['id' => $request->idDemandeaccompa]);
        } catch (\Exception $e) {
            DB::rollBack();
            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
            Log::error('Erreur interne du serveur: ' . $e->getMessage());
            return redirect()->back();
        }
    }
    // les images
    public function galeriImagePret($id)
    {
        $documentPaiements = DocumentPaiement::where('paiement_initiale_id', $id)->get();
        $module = "Module Demande Accompagnement";
        $action = "A consulte la liste des document justificatif d'un paiement id document paiement : $documentPaiements->id";
        Logs::saveLog($module, $action);
        return view('home.admin.accompagnements.images', compact('documentPaiements'));
    }
}
