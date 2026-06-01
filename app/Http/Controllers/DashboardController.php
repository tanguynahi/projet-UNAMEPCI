<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\Service;
use App\Models\Paiement;
use App\Models\Mutualiste;
use App\Models\CarteMembre;
use App\Models\Facturation;
use App\Models\Inscription;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\DroitAdhesion;
use App\Models\Administrateur;
use App\Models\DocumentPaiement;
use App\Models\listeDesProduits;
use App\Models\PaiementInitiale;
use App\Models\ProjetMutualiste;
use App\Models\STAuthTresorMoney;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\CotisationMutualiste;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use App\Models\DemandeAccompagnement;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('role:super-administrateur');
    //     // $this->middleware('permission:manage city')->only(['create','store','update','destroy']);
    // }

    public function index()
    {
        // // Vérifier le rôle de l'utilisateur connecté
        // if (Auth::check()) {
        //     if (Auth::user()->hasRole('super-administrateur') || Auth::user()->hasRole('administrateur')) {
        //         // Utilisateur a le bon rôle, afficher la vue
        //         return view('dashboard.index');
        //     } else {
        //         // Rediriger l'utilisateur avec le rôle mutualiste
        //         return redirect()->route('espace.accueil');
        //     }
        // } else {
        //     // L'utilisateur n'est pas connecté, rediriger vers la page de connexion
        //     return redirect()->route('connexion');
        // }
        $module = "Module Tableau de bord administrateur";
        $action = "a consulte son tableau de bord ";
        Logs::saveLog($module, $action);
        $nombrAdmin = Administrateur::whereHas('user.roles', function ($query) {
            // $query->where('name', 'super-administrateur');
            $query->where('name', 'administrateur');
        })
            ->orderBy('created_at', 'DESC')
            ->count();
        $nombrMutualiste = Mutualiste::where('status', 1)->count();
        $montantTotal = PaiementInitiale::where('status', 1)->sum('montant_initial');
        $mntAdhesion = PaiementInitiale::where('status', 1)->where('type_paiement_id', 1)->sum('montant_initial');
        $mntCotisation = PaiementInitiale::where('status', 1)->where('type_paiement_id', 2)->sum('montant_initial');
        $mntPret = PaiementInitiale::where('status', 1)->where('type_paiement_id', 3)->sum('montant_initial');
        $mntProjet = PaiementInitiale::where('status', 1)->where('type_paiement_id', 4)->sum('montant_initial');
        // $mntCarte = PaiementInitiale::where('status', 1)->where('type_paiement_id', 5)->sum('montant_initial');
        $inscrires = Inscription::where('status', 2)->count();
        return view('dashboard.index', compact('mntAdhesion', 'mntAdhesion',  'mntCotisation', 'mntPret', 'mntProjet', 'montantTotal', 'nombrMutualiste', 'nombrAdmin', 'inscrires'));
    }

    // public function statistiques()
    // {
    //     $module = "Module Tableau de bord administrateur";
    //     $action = "a consulte la page des statistique d'un administrateur";
    //     Logs::saveLog($module, $action);
    //     $mutualistes = Mutualiste::where('status', 1)->get();
    //     $montantTotal = PaiementInitiale::where('status', 1)->sum('montant_initial');
    //     $montants = PaiementInitiale::where('status', 1)->get();
    //     return view('dashboard.stats', compact('mutualistes', 'montantTotal', 'montants'));
    // }

    public function statistiques()
    {
        $module = "Module Tableau de bord administrateur";
        $action = "a consulté la page des statistiques d'un administrateur";
        Logs::saveLog($module, $action);

        $mutualistes = Mutualiste::where('status', 1)->get();
        // dd($mutualistes);s

        // Totaux globaux
        $montantTotal = PaiementInitiale::where('status', 1)->sum('montant_initial');
        $mntAdhesion  = PaiementInitiale::where('status', 1)->where('type_paiement_id', 1)->sum('montant_initial');
        $mntCotisation = PaiementInitiale::where('status', 1)->where('type_paiement_id', 2)->sum('montant_initial');
        $mntPret      = PaiementInitiale::where('status', 1)->where('type_paiement_id', 3)->sum('montant_initial');
        $mntProjet    = PaiementInitiale::where('status', 1)->where('type_paiement_id', 4)->sum('montant_initial');
        $mntCarte    = PaiementInitiale::where('status', 1)->where('type_paiement_id', 5)->sum('montant_initial');

        // ------ Données pour le graphique (paiements par mois) ------
        $paiementsParMois = PaiementInitiale::where('status', 1)
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as mois, SUM(montant_initial) as total')
            ->groupBy('mois')
            ->orderBy('mois', 'asc')
            ->get();

        $labels = $paiementsParMois->pluck('mois');     // ex: ["2025-01", "2025-02"]
        $data   = $paiementsParMois->pluck('total');    // montants correspondants

        return view('dashboard.stats', compact(
            'mutualistes',
            'montantTotal',
            'mntAdhesion',
            'mntCotisation',
            'mntPret',
            'mntProjet',
            'labels',
            'mntCarte',
            'data'
        ));
    }


    public function getstatistiquesPaiement($mutualisteId)
    {
        $paiements = PaiementInitiale::where('mutualiste_id', $mutualisteId)
            ->where('status', 1)
            ->with('typePaiement') // suppose une relation typePaiement
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($paiements->map(function ($p) {
            return [
                'id' => $p->id,
                'date' => $p->created_at->format('d/m/Y'),
                'type_paiement' => $p->typePaiement ? $p->typePaiement->libelle : 'Inconnu',
                'montant' => $p->montant_initial,
                'status' => $p->status
            ];
        }));
    }

    // paiments par type de paiements (cotisations, projets, accompagnement/prets)

    public function PaiementsProjets()
    {
        $typePaiementId = 1;
    }

    public function PaiementsCotisations()
    {
        $typePaiementId = 2;
    }

    public function PaiementsAccompagnements()
    {
        $typePaiementId = 3;
    }

    public function PaiementsDroitAdhesion()
    {
        $typePaiementId = 4;
    }

    public function caissePaiement()
    {
        // $paiements = PaiementInitiale::
        // where('p_cash',1)
        // ->where('status',1)

        // ->orderBy('created_at', 'DESC')
        //     ->get();
        // dd($paiements);
        $paiements = PaiementInitiale::where(function ($query) {
            $query->where('p_cash', 1)
                ->orWhere(function ($query) {
                    $query->where('status', 1);
                });
        })
            ->orderBy('created_at', 'DESC')
            ->get();

        $module = "Module Paiement";
        $action = "a consulte la liste des paiements";
        Logs::saveLog($module, $action);
        return view('dashboard.caisses.index', compact('paiements'));
    }
    public function detailPaiementCaisse($id)
    {
        $paiement = PaiementInitiale::where('id', $id)->first();
        // dd($paiement->type_paiement_id);
        $documentPaiements = DocumentPaiement::where('paiement_initiale_id', $id)->get();
        if ($paiement->type_paiement_id == 4) {
            $produit = ProjetMutualiste::where('id', $paiement->produit_id)->first();
            $libelle = '';
        } elseif ($paiement->type_paiement_id == 3) {
            $produit = [];
            $accompa = DemandeAccompagnement::where('id', $paiement->correspondance_id)->value('service_id');
            if (!empty($accompa)) {
                $libelle = Service::where('id', $accompa)->value('libelle');
            }
            //    dd($libelle);
        } else {
            $produit = [];
            $libelle = '';
        }
        $module = "Module Paiement";
        $action = "a affiche la page detail du paiement initiale id : ";
        Logs::saveLog($module, $action);
        return view('dashboard.caisses.show', compact('paiement', 'documentPaiements', 'produit', 'libelle'));
    }

    public function accepterPaiement($id)
    {
        DB::beginTransaction();
        $paiement = PaiementInitiale::where('id', $id)->first();
        if ($paiement) {
            $paiement->status = 1;
            $paiement->save();
            // enregistrement dans la table paiement*
            $newpaiement = new Paiement();
            $newpaiement->reference = $paiement->reference;
            $newpaiement->code_paiement = $paiement->code_paiement;
            $newpaiement->mutualiste_id = $paiement->mutualiste_id;
            $newpaiement->type_paiement_id = $paiement->type_paiement_id;
            $newpaiement->correspondance_id = $paiement->correspondance_id;
            $newpaiement->montant_initial = $paiement->montant_initial;
            $newpaiement->p_cash = $paiement->p_cash;
            $newpaiement->montant_total = $paiement->montant_initial;
            $newpaiement->moyen_paiement = $paiement->moyen_paiement ?? "Cash";
            $newpaiement->contact_paiement = $paiement->contact_paiement ?? "0000000000";
            $newpaiement->produit_id = $paiement->produit_id;
            $newpaiement->date_paiement_final = $paiement->date_paiement_final;
            $newpaiement->heure_paiement_final = $paiement->heure_paiement_final;
            $newpaiement->status = 1;
            $newpaiement->save();
            //  Paiement::create([
            //     'reference' => $paiement->reference,
            //     'code_paiement' => $paiement->code_paiement,
            //     'mutualiste_id' => $paiement->mutualiste_id,
            //     'type_paiement_id' => $paiement->type_paiement_id,
            //     'correspondance_id' => $paiement->correspondance_id,
            //     'montant_initial' => $paiement->montant_initial,
            //     'p_cash' => $paiement->p_cash,
            //     'montant_total' => $paiement->montant_total,
            //     'moyen_paiement' => $paiement->moyen_paiement,
            //     'contact_paiement' => $paiement->contact_paiement,
            //     'produit_id' => $paiement->produit_id,
            //     'date_paiement_final' => $paiement->date_paiement_final,
            //     'heure_paiement_final' => $paiement->heure_paiement_final,
            //     'status' => 1,
            // ]);

            if ($paiement->type_paiement_id == 4) {
                // projet
                $facture = Facturation::where('id', $paiement->correspondance_id)->first();
                // 1: immediat 2: journalier 3:hebdomadaire 4:mensuelle 5: annuelle 6: aperiodique
                // dd($facture->periode_id ,$paiement->montant_initial);
                if ($facture->periode_id == 1 || $facture->periode_id == 6) {
                    $facture->update([
                        'status' => 3, // payer
                        'total_payer' => $facture->total_payer + $paiement->montant_initial,
                        'reste_apayer' => $facture->reste_apayer - $paiement->montant_initial,
                    ]);
                } else if ($facture->periode_id == 2 || $facture->periode_id == 3 || $facture->periode_id == 4 || $facture->periode_id == 5) {
                    if ($paiement->montant_initial >= $facture->montant_periodique) {
                        $facture->update([
                            'status' => 3, // payer
                            'total_payer' => $facture->total_payer + $paiement->montant_initial,
                            'reste_apayer' => $facture->reste_apayer - $paiement->montant_initial,
                        ]);
                    }
                }
                $module = "Module Paiement";
                $action = "a valide le paiement initial ID:  $paiement->id , consernant la facturation : id = $facture->id ";
                Logs::saveLog($module, $action);
            } elseif ($paiement->type_paiement_id == 3) {
                $demandeAccompagnement = DemandeAccompagnement::where('id', $paiement->correspondance_id)->first();
                if (!empty($demandeAccompagnement)) {
                    $demandeAccompagnement->update([
                        'payer' => $demandeAccompagnement->payer + $paiement->montant_initial,
                    ]);
                }
                $module = "Module Paiement";
                $action = "a valide le paiement initial ID:  $paiement->id , consernant la demande d'accompagnements  : id = $demandeAccompagnement->id ";
                Logs::saveLog($module, $action);
            }
            DB::commit();
            toast('Paiement Accepter avec succes', 'success');
            return redirect()->back();
        }
    }

    public function refuserPaiement($id)
    {
        $paiement = PaiementInitiale::where('id', $id)->first();
        if (!empty($paiement)) {
            $paiement->status = 3;
            $paiement->save();
            toast('Paiement Refuse  avec succes', 'success');
            $module = "Module Paiement";
            $action = "a refuser le paiement initial ID:  $paiement->id  ";
            Logs::saveLog($module, $action);
        } else {
            toast("une Erreur s'est produite paiement Introuvable", 'error');
        }
        return redirect()->back();
    }
    public function documentPaiement($id)
    {
        $documentPaiements = DocumentPaiement::where('paiement_initiale_id', $id)->get();
        $module = "Module Paiement Document";
        $action = "A consulte les documents d'un paiement id document paiement : $id ";
        Logs::saveLog($module, $action);
        return view('dashboard.caisses.galeries', compact('documentPaiements'));
    }


    public function ajouterPaiement()
    {
        $module = "Module Paiement";
        $action = "a affiche la page d'ajout d'un paiement";
        Logs::saveLog($module, $action);
        // $mutualistes = Mutualiste::where('status', 1)->get();
        $mutualistes = Mutualiste::all();
        $projets  = ProjetMutualiste::where('status', 1)->get();
        $services = Service::where('status', 1)->get();
        $projetsMutualistes = ProjetMutualiste::where('status', 1)->get();
        return view('dashboard.caisses.create', compact('mutualistes', 'projets', 'projetsMutualistes', 'services'));
    }


    public function getProjetsMutualiste($mutualiste_id)
    {
        $projets = ProjetMutualiste::where('mutualiste_id', $mutualiste_id)
            ->where('status', 1)
            ->with('produitProjet') // relation vers produit_projets pour avoir le libelle
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'libelle' => $p->libelle ?? ($p->produitProjet->libelle ?? 'Projet'),
                    'montant' => $p->montant_produit ?? $p->total_apayer ?? 0,
                    'total_apayer' => $p->total_apayer ?? 0,
                    'date' => $p->date,
                ];
            });

        return response()->json($projets);
    }

    public function getCotisationsMutualiste($mutualiste_id)
    {
        $cotisations = CotisationMutualiste::where('mutualiste_id', $mutualiste_id)
            ->where('status', 2) // statut actif/en attente
            ->with('cotisation') // relation vers la table cotisations pour avoir le libelle
            ->get()
            ->map(function ($cm) {
                return [
                    'id' => $cm->id,
                    'libelle' => $cm->cotisation->libelle ?? 'Cotisation',
                    'montant' => $cm->montant_initial ?? 0,
                    'montant_paye' => $cm->montant_paye ?? 0,
                    'reste' => ($cm->montant_initial ?? 0) - ($cm->montant_paye ?? 0),
                    'frequence' => $cm->frequence_paiement,
                    'date_debut' => $cm->date_debut,
                    'date_fin' => $cm->date_fin,
                ];
            });

        return response()->json($cotisations);
    }
    public function getAdhesionsMutualiste($mutualiste_id)
    {
        $adhesion = DroitAdhesion::where('mutualiste_id', $mutualiste_id)
            ->where('status', 2) // statut actif/en attente
            ->first();

        if (!$adhesion) {
            return response()->json([]);
        }

        return response()->json([
            [
                'id' => $adhesion->id,
                'libelle' => 'Droit d\'adhésion',
                'montant' => $adhesion->montant ?? 10000,
            ]
        ]);
    }



    public function servicesAccompagnementMutualiste($mutualisteId)
    {
        $demandes = DemandeAccompagnement::with('service')
            ->where('mutualiste_id', $mutualisteId)
            ->where('status', 1) // Uniquement les demandes approuvées/en cours
            ->whereRaw('(payer IS NULL OR payer < montant_apayer)') // Pas encore totalement payé
            ->get()
            ->map(function ($demande) {
                $reste = $demande->montant_apayer - ($demande->payer ?? 0);
                return [
                    'id' => $demande->id,
                    'libelle' => $demande->service->libelle . ' (#' . $demande->id . ')',
                    'service_libelle' => $demande->service->libelle,
                    'montant_voulue' => $demande->montant_voulue,
                    'montant_apayer' => $demande->montant_apayer,
                    'payer' => $demande->payer ?? 0,
                    'reste' => $reste,
                    'contact_tresormoney' => $demande->contact_tresormoney,
                    'commentaire' => $demande->commentaire,
                ];
            });

        return response()->json($demandes);
    }





    public function enregistrePaiement(Request $request)
    {



        $module = "Module Paiement";
        $action = "a enregistré un paiement , " . json_encode($request->all());
        Logs::saveLog($module, $action);

        // Vérifier si c'est une requête AJAX (paiement en ligne)
        $isAjax = $request->ajax() || $request->wantsJson();

        // Validation de base
        $validated = $request->validate([
            'mutualiste_id' => 'required|exists:mutualistes,id',
            'type_paiement' => 'required|in:adhesion,cotisation,produit',
            'moyen_paiement' => 'required|in:especes,cheque,virement,en_ligne',
            'montant' => 'required|numeric|min:0',
            'reference' => 'nullable|string|max:255',
            // 'date_paiement' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        try {
            $typePaiementMap = [
                'adhesion' => 1,
                'cotisation' => 2,
                'produit' => 3,
            ];

            $data = [
                'mutualiste_id' => $request->mutualiste_id,
                'type_paiement_id' => $typePaiementMap[$request->type_paiement],
                'moyen_paiement' => $request->moyen_paiement,
                'montant_initial' => $request->montant,
                'reference' => $request->reference ?? 'CAISSE-' . strtoupper(Str::random(8)),
                // 'date_paiement_final' => $request->date_paiement,
                'notes' => $request->notes,
                'status' => 1,
                'p_cash' => 1,
                'code_paiement' => 'PAY-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
                'user_id' => auth()->id(),
            ];

            // Traitement selon le type de paiement
            if ($request->type_paiement === 'produit') {
                $data['produit_id'] = $request->produit_id;
                $data['correspondance_id'] = $request->produit_id;
                if ($request->produit_type === 'service') {
                    $service = Service::findOrFail($request->produit_id);
                    if ($service) {
                        $data['correspondance_id'] = $service->id;
                    }
                }
            }

            if ($request->type_paiement === 'cotisation') {
                $data['cotisation_mutualiste_id'] = $request->cotisation_mutualiste_id;
            }


            // Traitement selon le moyen de paiement
            switch ($request->moyen_paiement) {
                case 'cheque':
                    DB::beginTransaction();
                    $banque = '';
                    if ($request->cheque_banque == 'autres') {
                        $banque = $request->cheque_banque_autre;
                    } else {
                        $banque = $request->cheque_banque;
                    }

                    $mutualiste = Mutualiste::where('id', $request->mutualiste_id)->first();
                    if (!$mutualiste) {
                        throw new \Exception("Mutualiste introuvable.");
                    }
                    $type_paiement = $request->type_paiement;
                    $Montant = $request->montant;

                    switch ($type_paiement) {
                        case 'adhesion':
                            // Comme dans l'ancien code qui fonctionne
                            $codePaiement = generateCode2('Ref');

                            $paiementinit = new PaiementInitiale();
                            $paiementinit->code_paiement = $codePaiement;
                            $paiementinit->reference =  $request->reference;
                            $paiementinit->cheque_numero =  $request->cheque_numero;
                            $paiementinit->banque_autre =  $banque;
                            $paiementinit->mutualiste_id = $mutualiste->id;
                            $paiementinit->type_paiement_id = 1;
                            $paiementinit->correspondance_id = $mutualiste->droitAdhesion->id;
                            $paiementinit->montant_initial = $Montant ?? $request->montant;
                            $paiementinit->date_paiement_initial = $request->cheque_date ?? $request->date_paiement ?? now() ?? '';
                            $paiementinit->moyen_paiement = "cheque";
                            $paiementinit->status = 1; //
                            $paiementinit->save();

                            // enregistre dans la table paiement

                            $paiement = new Paiement();
                            $paiement->montant_total = $request->montant ?? $paiementinit->montant_initial;
                            $paiement->moyen_paiement = "cheque";
                            $paiement->contact_paiement =  $mutualiste->contact ?? $mutualiste->contact_2 ??  "0000000000";

                            $paiement->code_paiement = $codePaiement;
                            $paiement->date_paiement_final =  $request->cheque_date ??  $paiementinit->date_paiement_initial ?? '';
                            $paiement->reference =  $request->reference;
                            $paiement->cheque_numero =  $request->cheque_numero;
                            $paiement->banque_autre =  $banque;
                            $paiement->type_paiement_id = $paiementinit->type_paiement_id;
                            $paiement->mutualiste_id = $paiementinit->mutualiste_id;
                            $paiement->montant_initial = $paiementinit->montant_initial;
                            $paiement->correspondance_id = $paiementinit->correspondance_id;
                            $paiement->status = 1;
                            $paiement->save();

                            if ($request->hasFile('documents')) {
                                foreach ($request->file('documents') as $index => $file) {
                                    $file_name = md5(uniqid()) . $index . '.' . $file->extension();
                                    if (Storage::exists('images-docPaiementPret/' . $file_name)) {
                                        $file_name = md5(uniqid()) . $index . '.' . $file->extension();
                                    }
                                    $file->storeAs('images-docPaiementPret/', $file_name);
                                    $lien_images_projet = 'src-files/images-docPaiementPret/' . $file_name;

                                    DocumentPaiement::create([
                                        'pret_id' => $request->idDemandeaccompa,
                                        'paiement_initiale_id' => $paiementinit->id,
                                        'lien_photo' => $lien_images_projet,
                                    ]);
                                }
                            }




                            $mutualisteId = $paiement->mutualiste_id ?? $paiementinit->mutualiste_id ?? $mutualiste->id;
                            $droit_adhesion = DroitAdhesion::where('mutualiste_id', $mutualisteId)->first();
                            if (!empty($droit_adhesion)) {
                                $nouveauMontant = $droit_adhesion->montant - $Montant;

                                if ($nouveauMontant <= 0) {
                                    $droit_adhesion->montant = 0;
                                    $droit_adhesion->status = 1;
                                } else {
                                    $droit_adhesion->montant = $nouveauMontant;
                                    $droit_adhesion->status = 2;
                                }
                                $droit_adhesion->save();
                            } else {
                                $Chaine = "\droit d'adhesion pas defini pour ce mutualiste error '";
                                $module = " paiement Retour API";
                                $action = "$Chaine";
                                Logs::saveLog($module, $action);
                            }
                            if ($droit_adhesion->status == 1) {
                                $sujet = "Paiement de droit d'adhésion sur votre compte UNAMEPCI";
                                $message = "
                                    Bonjour M/Mme/Mlle:, " . $mutualiste->prenom . ' ' . $mutualiste->nom . "<br>
                                    C'est officiel, votre paiement d'adhésion a été confirmé ! 🎉 Bienvenue chez Union Nationale des Medecins Prives de Côte d'Ivoire (UNAMEPCI) !<br>
                                    Nous sommes super excités de vous avoir avec nous. Votre adhésion vous ouvre les portes à un monde de nouvelles opportunités, d'événements passionnants et de nombreuses ressources.<br>
                                    Prenez le temps d'explorer ce qui vous attend et faites-en le maximum !<br>
                                    Votre soutien signifie beaucoup pour nous, et nous sommes impatients de voir tout ce que vous accomplirez avec nous.<br>
                                    Bienvenue à bord, et profitons de cette aventure ensemble !<br>
                                    Merci d'utiliser notre plateforme! <br>
                                    Si vous rencontrez des problèmes avec votre compte, n'hésitez pas à nous contacter.
                                 ";
                                $url = appelApiEmail();
                                $template = View::make('home.admin.paiements.paiementAdhesion', ['contenumess' => $message])->render();
                                $data = [
                                    'provider' => 'UNAMEPCI <info@mail-taseti.com>',
                                    "key_rsa" => 're_2i7H3Ynf_KRVm9VwTsrwrfF8isCBYvyyE',
                                    "destination" => $mutualiste->email,
                                    "sujet" => $sujet,
                                    "message" => $template
                                ];
                                $retourAPI = Http::post($url, $data);
                                $res = $retourAPI->json();

                                if ($retourAPI->status() == 200) {
                                    (int)$code = $res['status'];
                                    if ($code != 200) {
                                        $message = "Une erreur s'est produite " . $code . ", DETAIL: " . messageBrut($res['message']) . " ERR: Envoye Paiement adhesion";
                                        Log::error($message);
                                    }
                                } else {
                                    Log::error("Erreur lors de l'envoi de l'email. Statut API : " . $retourAPI->status());
                                }
                            } else {
                                $sujet = "Paiement éffectuer pour le droit d'adhésion sur votre compte UNAMEPCI";
                                $message = "
                                        Bonjour M/Mme/Mlle: " . $mutualiste->prenom . ' ' . $mutualiste->nom . "<br>
                                        C'est officiel, Vous avez effectué un paiement pour le droit d'adhésion ! 🎉<br>
                                        Bienvenue chez Union Nationale des Medecins Prives de Côte d'Ivoire (UNAMEPCI) !<br>
                                        Nous sommes super excités de vous avoir avec nous. Votre adhésion vous ouvre les portes à un monde de nouvelles opportunités, d'événements passionnants et de nombreuses ressources.<br>
                                        Prenez le temps d'explorer ce qui vous attend et faites-en le maximum !<br>
                                        Montant restant : " . formatMontant($droit_adhesion->montant ?? 0) . "<br>
                                        Votre soutien signifie beaucoup pour nous, et nous sommes impatients de voir tout ce que vous accomplirez avec nous.<br>
                                        Bienvenue à bord, et profitons de cette aventure ensemble !<br>
                                        Merci d'utiliser notre plateforme !<br>
                                        Si vous rencontrez des problèmes avec votre compte, n'hésitez pas à nous contacter.
                                        ";
                                $url = appelApiEmail();
                                $template = View::make('home.admin.paiements.paiementAdhesion', ['contenumess' => $message])->render();
                                $data = [
                                    'provider' => 'UNAMEPCI <info@mail-taseti.com>',
                                    "key_rsa" => 're_2i7H3Ynf_KRVm9VwTsrwrfF8isCBYvyyE',
                                    "destination" => $mutualiste->email,
                                    "sujet" => $sujet,
                                    "message" => $template
                                ];
                                $retourAPI = Http::post($url, $data);
                                $res = $retourAPI->json();

                                if ($retourAPI->status() == 200) {
                                    (int)$code = $res['status'];
                                    if ($code != 200) {
                                        $message = "Une erreur s'est produite " . $code . ", DETAIL: " . messageBrut($res['message']) . " ERR: Envoye Paiement adhesion";
                                        Log::error($message);
                                    }
                                } else {
                                    Log::error("Erreur lors de l'envoi de l'email. Statut API : " . $retourAPI->status());
                                }
                            }
                            // carte membre inclus
                            $carteMembre = CarteMembre::where('mutualiste_id', $mutualisteId)->first();
                            if (!empty($carteMembre)) {
                                $carteMembre->status = 1;
                                $carteMembre->genere = 2;
                                $carteMembre->save();

                                if ($carteMembre->status == 1) {
                                    $sujet = "Confirmation de paiement – Carte Membre UNAMEPCI";

                                    $message = "
                                    Bonjour " . $mutualiste->prenom . " " . $mutualiste->nom . ",<br><br>

                                    Félicitations 🎉 !
                                    Nous vous informons que le paiement de votre **carte de membre UNAMEPCI** a été effectué avec succès.<br><br>
                                    Votre adhésion est désormais **active** et vous bénéficiez pleinement des services et avantages offerts par le **Union Nationale des Medecins Prives de Côte d'Ivoire**.<br><br>
                                    Nous vous remercions pour votre confiance et sommes ravis de vous compter parmi nos membres.<br><br>
                                    Si vous avez besoin d’assistance ou d’informations complémentaires, notre équipe reste à votre disposition.<br><br>
                                    Cordialement,<br>
                                    <strong>L’équipe UNAMEPCI</strong>
                                    ";

                                    $url = appelApiEmail();
                                    $template = View::make('home.admin.paiements.paiementAdhesion', ['contenumess' => $message])->render();
                                    $data = [
                                        'provider' => 'UNAMEPCI <info@mail-taseti.com>',
                                        "key_rsa" => 're_2i7H3Ynf_KRVm9VwTsrwrfF8isCBYvyyE',
                                        "destination" => $mutualiste->email,
                                        "sujet" => $sujet,
                                        "message" => $template
                                    ];
                                    $retourAPI = Http::post($url, $data);
                                    $res = $retourAPI->json();

                                    if ($retourAPI->status() == 200) {
                                        (int)$code = $res['status'];
                                        if ($code != 200) {
                                            $message = "Une erreur s'est produite " . $code . ", DETAIL: " . messageBrut($res['message']) . " ERR: Envoye Paiement adhesion";
                                            Log::error($message);
                                        }
                                    } else {
                                        Log::error("Erreur lors de l'envoi de l'email. Statut API : " . $retourAPI->status());
                                    }
                                }
                            } else {
                                $Chaine = " id carte membre  incorrecter";
                                $module = " paiement ";
                                $action = "$Chaine";
                                Logs::saveLog($module, $action);
                            }
                            break;

                        case 'cotisation':
                            // COPIÉ COLLÉ DE L'ANCIEN CODE QUI FONCTIONNE
                            $cotisationMutualiste = CotisationMutualiste::where('id', $request->cotisation_mutualiste_id)->first();

                            $codePaiement = generateCode2('Cot');

                            $paiementinit = new PaiementInitiale();
                            $paiementinit->code_paiement = $codePaiement;
                            $paiementinit->reference =  $request->reference;
                            $paiementinit->cheque_numero =  $request->cheque_numero;
                            $paiementinit->banque_autre =  $banque;
                            $paiementinit->mutualiste_id = $mutualiste->id;
                            $paiementinit->type_paiement_id = 2;
                            $paiementinit->correspondance_id = $cotisationMutualiste->id;
                            $paiementinit->montant_initial = $request->montant ?? $cotisationMutualiste->montant;
                            $paiementinit->date_paiement_initial =  $request->cheque_date ?? $request->date_paiement ?? now() ?? '';
                            $paiementinit->moyen_paiement = "cheque";
                            $paiementinit->status = 1; //
                            $paiementinit->save();


                            $paiement = new Paiement();
                            $paiement->contact_paiement =  $mutualiste->contact ?? $mutualiste->contact_2 ??  "0000000000";
                            $paiement->montant_total = $request->montant ?? $paiementinit->montant_initial;
                            $paiement->moyen_paiement =  "cheque";
                            $paiement->code_paiement = $codePaiement;
                            $paiement->date_paiement_final =  $request->date_paiement ??  $paiementinit->date_paiement_initial ?? '';
                            $paiement->reference =  $request->reference;
                            $paiement->cheque_numero =  $request->cheque_numero;
                            $paiement->banque_autre =  $banque;
                            $paiement->type_paiement_id = $paiementinit->type_paiement_id;
                            $paiement->mutualiste_id = $paiementinit->mutualiste_id;
                            $paiement->montant_initial = $paiementinit->montant_initial;
                            $paiement->correspondance_id = $paiementinit->correspondance_id;
                            $paiement->status = 1;
                            $paiement->save();

                            if ($request->hasFile('documents')) {
                                foreach ($request->file('documents') as $index => $file) {
                                    $file_name = md5(uniqid()) . $index . '.' . $file->extension();
                                    if (Storage::exists('images-docPaiementPret/' . $file_name)) {
                                        $file_name = md5(uniqid()) . $index . '.' . $file->extension();
                                    }
                                    $file->storeAs('images-docPaiementPret/', $file_name);
                                    $lien_images_projet = 'src-files/images-docPaiementPret/' . $file_name;

                                    DocumentPaiement::create([
                                        'pret_id' => $request->idDemandeaccompa,
                                        'paiement_initiale_id' => $paiementinit->id,
                                        'lien_photo' => $lien_images_projet,
                                    ]);
                                }
                            }
                            if (!empty($cotisationMutualiste)) {
                                if ($cotisationMutualiste->frequence_paiement === 'Annuelle') {

                                    // Mettre à jour les montants
                                    $cotisationMutualiste->montant_paye += $Montant;
                                    $cotisationMutualiste->montant -= $Montant;

                                    // Empêcher un solde négatif
                                    if ($cotisationMutualiste->montant < 0) {
                                        $cotisationMutualiste->montant = 0;
                                    }

                                    // Si tout est payé → statut validé
                                    if ($cotisationMutualiste->montant == 0) {
                                        $cotisationMutualiste->status = 1;
                                    }

                                    $cotisationMutualiste->save();
                                } else {
                                    // Autres fréquences (mensuelle, trimestrielle…)
                                    $cotisationMutualiste->update([
                                        'status' => 1
                                    ]);
                                }

                                $Chaine = " enregistre cotisations id $cotisationMutualiste->id ";
                                $module = " paiement ";
                                $action = "$Chaine";
                                Logs::saveLog($module, $action);
                            } else {
                                $Chaine = " id cotisation incorrecter";
                                $module = " paiement ";
                                $action = "$Chaine";
                                Logs::saveLog($module, $action);
                            }




                            break;

                        case 'produit':
                            if ($request->categorie_produit == "projets") {
                                // Comme dans l'ancien code projets
                                $demandeP = Facturation::findOrFail($request->produit_id);


                                $codePaiement = generateCode2('Ref');

                                $paiementinit = new PaiementInitiale();
                                $paiementinit->code_paiement = $codePaiement;
                                $paiementinit->reference =  $request->reference;
                                $paiementinit->cheque_numero =  $request->cheque_numero;
                                $paiementinit->banque_autre =  $banque;
                                $paiementinit->mutualiste_id = $mutualiste->id;
                                $paiementinit->type_paiement_id = 4;
                                $paiementinit->correspondance_id = $request->facturationID;
                                $paiementinit->montant_initial = $request->montant;
                                $paiementinit->produit_id = $request->produit_id;
                                $paiementinit->date_paiement_initial = $request->cheque_date ?? $request->date_paiement ?? now() ?? '';
                                $paiementinit->moyen_paiement = "cheque";
                                $paiementinit->status = 1; //
                                $paiementinit->save();


                                $paiement = new Paiement();
                                $paiement->contact_paiement =  $mutualiste->contact ?? $mutualiste->contact_2 ??  "0000000000";
                                $paiement->montant_total = $request->montant ?? $paiementinit->montant_initial;
                                $paiement->moyen_paiement =  "cheque";
                                $paiement->code_paiement = $codePaiement;
                                $paiement->date_paiement_final =  $request->cheque_date ??  $paiementinit->date_paiement_initial ?? '';
                                $paiement->reference =  $request->reference;
                                $paiement->cheque_numero =  $request->cheque_numero;
                                $paiement->banque_autre =  $banque;
                                $paiement->type_paiement_id = $paiementinit->type_paiement_id;
                                $paiement->mutualiste_id = $paiementinit->mutualiste_id;
                                $paiement->montant_initial = $paiementinit->montant_initial;
                                $paiement->correspondance_id = $paiementinit->correspondance_id;
                                $paiement->status = 1;
                                $paiement->save();


                                if ($request->hasFile('documents')) {
                                    foreach ($request->file('documents') as $index => $file) {
                                        $file_name = md5(uniqid()) . $index . '.' . $file->extension();
                                        if (Storage::exists('images-docPaiementPret/' . $file_name)) {
                                            $file_name = md5(uniqid()) . $index . '.' . $file->extension();
                                        }
                                        $file->storeAs('images-docPaiementPret/', $file_name);
                                        $lien_images_projet = 'src-files/images-docPaiementPret/' . $file_name;

                                        DocumentPaiement::create([
                                            'pret_id' => $request->idDemandeaccompa,
                                            'paiement_initiale_id' => $paiementinit->id,
                                            'lien_photo' => $lien_images_projet,
                                        ]);
                                    }
                                }
                                if (!empty($demandeP)) {

                                    $demandeP->total_payer = $paiement->montant_initial;
                                    $demandeP->total_apayer = $demandeP->total_apayer - $paiement->montant_initial;
                                    $demandeP->status = 2;
                                    $demandeP->save();
                                    $Chaine = " facturation   solde id: $demandeP->id  ";
                                    $module = " paiement ";
                                    $action = "$Chaine";
                                    Logs::saveLog($module, $action);
                                } else {
                                    $Chaine = " id projet incorrecter";
                                    $module = " paiement ";
                                    $action = "$Chaine";
                                    Logs::saveLog($module, $action);
                                }
                            } else {
                                // Services
                                $demandeP = DemandeAccompagnement::findOrFail($request->produit_id);


                                $codePaiement = generateCode2('Rem');

                                $paiementinit = new PaiementInitiale();
                                $paiementinit->code_paiement = $codePaiement;
                                $paiementinit->reference =  $request->reference;
                                $paiementinit->cheque_numero =  $request->cheque_numero;
                                $paiementinit->banque_autre =  $banque;
                                $paiementinit->mutualiste_id = $mutualiste->id;
                                $paiementinit->type_paiement_id = 3;
                                $paiementinit->correspondance_id = $request->produit_id;
                                $paiementinit->montant_initial = $request->montant;
                                $paiementinit->date_paiement_initial = $request->cheque_date ?? $request->date_paiement ?? now() ?? '';
                                $paiementinit->moyen_paiement = "cheque";
                                $paiementinit->status = 1; //
                                $paiementinit->save();


                                $paiement = new Paiement();
                                $paiement->contact_paiement =  $mutualiste->contact ?? $mutualiste->contact_2 ??  "0000000000";
                                $paiement->montant_total = $request->montant ?? $paiementinit->montant_initial;
                                $paiement->moyen_paiement =  "cheque";
                                $paiement->code_paiement = $codePaiement;
                                $paiement->date_paiement_final =  $request->cheque_date ??  $paiementinit->date_paiement_initial ?? '';
                                $paiement->reference =  $request->reference;
                                $paiement->cheque_numero =  $request->cheque_numero;
                                $paiement->banque_autre =  $banque;
                                $paiement->type_paiement_id = $paiementinit->type_paiement_id;
                                $paiement->mutualiste_id = $paiementinit->mutualiste_id;
                                $paiement->montant_initial = $paiementinit->montant_initial;
                                $paiement->correspondance_id = $paiementinit->correspondance_id;
                                $paiement->status = 1;
                                $paiement->save();


                                if ($request->hasFile('documents')) {
                                    foreach ($request->file('documents') as $index => $file) {
                                        $file_name = md5(uniqid()) . $index . '.' . $file->extension();
                                        if (Storage::exists('images-docPaiementPret/' . $file_name)) {
                                            $file_name = md5(uniqid()) . $index . '.' . $file->extension();
                                        }
                                        $file->storeAs('images-docPaiementPret/', $file_name);
                                        $lien_images_projet = 'src-files/images-docPaiementPret/' . $file_name;

                                        DocumentPaiement::create([
                                            'pret_id' => $request->idDemandeaccompa,
                                            'paiement_initiale_id' => $paiementinit->id,
                                            'lien_photo' => $lien_images_projet,
                                        ]);
                                    }
                                }

                                if (!empty($demandeP)) {
                                    $demandeP->payer = $demandeP->payer + $paiement->montant_initial;
                                    $demandeP->save();
                                    $Chaine = " pret solde $demandeP->id  ";
                                    $module = " paiement ";
                                    $action = "$Chaine";
                                    Logs::saveLog($module, $action);
                                } else {
                                    $Chaine = " id pret incorrecter";
                                    $module = " paiement ";
                                    $action = "$Chaine";
                                    Logs::saveLog($module, $action);
                                }
                            }

                            break;

                        default:
                            throw new \Exception("Type de paiement inconnu");
                    }
                    DB::commit();
                    break;

                case 'virement':
                    DB::beginTransaction();
                    // ... ton code existant pour virement ...
                    $banque = '';
                    if ($request->virement_banque == 'autres') {
                        $banque = $request->virement_banque_autre;
                    } else {
                        $banque = $request->virement_banque;
                    }



                    $mutualiste = Mutualiste::where('id', $request->mutualiste_id)->first();
                    if (!$mutualiste) {
                        throw new \Exception("Mutualiste introuvable.");
                    }

                    // dd($request->all());
                    $type_paiement = $request->type_paiement;
                    $Montant = $request->montant;

                    switch ($type_paiement) {
                        case 'adhesion':

                            // Comme dans l'ancien code qui fonctionne
                            $codePaiement = generateCode2('Ref');

                            $paiementinit = new PaiementInitiale();
                            $paiementinit->code_paiement = $codePaiement;
                            $paiementinit->reference =  $request->reference;
                            $paiementinit->cheque_numero =  $request->virement_reference;
                            $paiementinit->banque_autre =  $banque;
                            $paiementinit->mutualiste_id = $mutualiste->id;
                            $paiementinit->type_paiement_id = 1;
                            $paiementinit->correspondance_id = $mutualiste->droitAdhesion->id;
                            $paiementinit->montant_initial = $Montant ?? $request->montant;
                            $paiementinit->date_paiement_initial = $request->virement_date ?? $request->date_paiement ?? now() ?? '';
                            $paiementinit->moyen_paiement = "virement";
                            $paiementinit->status = 1; //
                            $paiementinit->save();

                            // enregistre dans la table paiement

                            $paiement = new Paiement();
                            $paiement->montant_total = $request->montant ?? $paiementinit->montant_initial;
                            $paiement->moyen_paiement = "virement";
                            $paiement->contact_paiement =  $mutualiste->contact ?? $mutualiste->contact_2 ??  "0000000000";

                            $paiement->code_paiement = $codePaiement;
                            $paiement->date_paiement_final =  $request->virement_date ??  $paiementinit->date_paiement_initial ?? '';
                            $paiement->reference =  $request->reference;
                            $paiement->cheque_numero =  $request->virement_reference;
                            $paiement->banque_autre =  $banque;
                            $paiement->type_paiement_id = $paiementinit->type_paiement_id;
                            $paiement->mutualiste_id = $paiementinit->mutualiste_id;
                            $paiement->montant_initial = $paiementinit->montant_initial;
                            $paiement->correspondance_id = $paiementinit->correspondance_id;
                            $paiement->status = 1;
                            $paiement->save();

                            if ($request->hasFile('documents')) {
                                foreach ($request->file('documents') as $index => $file) {
                                    $file_name = md5(uniqid()) . $index . '.' . $file->extension();
                                    if (Storage::exists('images-docPaiementPret/' . $file_name)) {
                                        $file_name = md5(uniqid()) . $index . '.' . $file->extension();
                                    }
                                    $file->storeAs('images-docPaiementPret/', $file_name);
                                    $lien_images_projet = 'src-files/images-docPaiementPret/' . $file_name;

                                    DocumentPaiement::create([
                                        'pret_id' => $request->idDemandeaccompa,
                                        'paiement_initiale_id' => $paiementinit->id,
                                        'lien_photo' => $lien_images_projet,
                                    ]);
                                }
                            }




                            $mutualisteId = $paiement->mutualiste_id ?? $paiementinit->mutualiste_id ?? $mutualiste->id;
                            $droit_adhesion = DroitAdhesion::where('mutualiste_id', $mutualisteId)->first();
                            if (!empty($droit_adhesion)) {
                                $nouveauMontant = $droit_adhesion->montant - $Montant;

                                if ($nouveauMontant <= 0) {
                                    $droit_adhesion->montant = 0;
                                    $droit_adhesion->status = 1;
                                } else {
                                    $droit_adhesion->montant = $nouveauMontant;
                                    $droit_adhesion->status = 2;
                                }
                                $droit_adhesion->save();
                            } else {
                                $Chaine = "\droit d'adhesion pas defini pour ce mutualiste error '";
                                $module = " paiement Retour API";
                                $action = "$Chaine";
                                Logs::saveLog($module, $action);
                            }
                            if ($droit_adhesion->status == 1) {
                                $sujet = "Paiement de droit d'adhésion sur votre compte UNAMEPCI";
                                $message = "
                                    Bonjour M/Mme/Mlle:, " . $mutualiste->prenom . ' ' . $mutualiste->nom . "<br>
                                    C'est officiel, votre paiement d'adhésion a été confirmé ! 🎉 Bienvenue chez Union Nationale des Medecins Prives de Côte d'Ivoire (UNAMEPCI) !<br>
                                    Nous sommes super excités de vous avoir avec nous. Votre adhésion vous ouvre les portes à un monde de nouvelles opportunités, d'événements passionnants et de nombreuses ressources.<br>
                                    Prenez le temps d'explorer ce qui vous attend et faites-en le maximum !<br>
                                    Votre soutien signifie beaucoup pour nous, et nous sommes impatients de voir tout ce que vous accomplirez avec nous.<br>
                                    Bienvenue à bord, et profitons de cette aventure ensemble !<br>
                                    Merci d'utiliser notre plateforme! <br>
                                    Si vous rencontrez des problèmes avec votre compte, n'hésitez pas à nous contacter.
                                 ";
                                $url = appelApiEmail();
                                $template = View::make('home.admin.paiements.paiementAdhesion', ['contenumess' => $message])->render();
                                $data = [
                                    'provider' => 'UNAMEPCI <info@mail-taseti.com>',
                                    "key_rsa" => 're_2i7H3Ynf_KRVm9VwTsrwrfF8isCBYvyyE',
                                    "destination" => $mutualiste->email,
                                    "sujet" => $sujet,
                                    "message" => $template
                                ];
                                $retourAPI = Http::post($url, $data);
                                $res = $retourAPI->json();

                                if ($retourAPI->status() == 200) {
                                    (int)$code = $res['status'];
                                    if ($code != 200) {
                                        $message = "Une erreur s'est produite " . $code . ", DETAIL: " . messageBrut($res['message']) . " ERR: Envoye Paiement adhesion";
                                        Log::error($message);
                                    }
                                } else {
                                    Log::error("Erreur lors de l'envoi de l'email. Statut API : " . $retourAPI->status());
                                }
                            } else {
                                $sujet = "Paiement éffectuer pour le droit d'adhésion sur votre compte UNAMEPCI";
                                $message = "
                                        Bonjour M/Mme/Mlle: " . $mutualiste->prenom . ' ' . $mutualiste->nom . "<br>
                                        C'est officiel, Vous avez effectué un paiement pour le droit d'adhésion ! 🎉<br>
                                        Bienvenue chez Union Nationale des Medecins Prives de Côte d'Ivoire (UNAMEPCI) !<br>
                                        Nous sommes super excités de vous avoir avec nous. Votre adhésion vous ouvre les portes à un monde de nouvelles opportunités, d'événements passionnants et de nombreuses ressources.<br>
                                        Prenez le temps d'explorer ce qui vous attend et faites-en le maximum !<br>
                                        Montant restant : " . formatMontant($droit_adhesion->montant ?? 0) . "<br>
                                        Votre soutien signifie beaucoup pour nous, et nous sommes impatients de voir tout ce que vous accomplirez avec nous.<br>
                                        Bienvenue à bord, et profitons de cette aventure ensemble !<br>
                                        Merci d'utiliser notre plateforme !<br>
                                        Si vous rencontrez des problèmes avec votre compte, n'hésitez pas à nous contacter.
                                        ";
                                $url = appelApiEmail();
                                $template = View::make('home.admin.paiements.paiementAdhesion', ['contenumess' => $message])->render();
                                $data = [
                                    'provider' => 'UNAMEPCI <info@mail-taseti.com>',
                                    "key_rsa" => 're_2i7H3Ynf_KRVm9VwTsrwrfF8isCBYvyyE',
                                    "destination" => $mutualiste->email,
                                    "sujet" => $sujet,
                                    "message" => $template
                                ];
                                $retourAPI = Http::post($url, $data);
                                $res = $retourAPI->json();

                                if ($retourAPI->status() == 200) {
                                    (int)$code = $res['status'];
                                    if ($code != 200) {
                                        $message = "Une erreur s'est produite " . $code . ", DETAIL: " . messageBrut($res['message']) . " ERR: Envoye Paiement adhesion";
                                        Log::error($message);
                                    }
                                } else {
                                    Log::error("Erreur lors de l'envoi de l'email. Statut API : " . $retourAPI->status());
                                }
                            }
                            // carte membre inclus
                            $carteMembre = CarteMembre::where('mutualiste_id', $mutualisteId)->first();
                            if (!empty($carteMembre)) {
                                $carteMembre->status = 1;
                                $carteMembre->genere = 2;
                                $carteMembre->save();

                                if ($carteMembre->status == 1) {
                                    $sujet = "Confirmation de paiement – Carte Membre UNAMEPCI";

                                    $message = "
                                    Bonjour " . $mutualiste->prenom . " " . $mutualiste->nom . ",<br><br>

                                    Félicitations 🎉 !
                                    Nous vous informons que le paiement de votre **carte de membre UNAMEPCI** a été effectué avec succès.<br><br>
                                    Votre adhésion est désormais **active** et vous bénéficiez pleinement des services et avantages offerts par le **Union Nationale des Medecins Prives de Côte d'Ivoire**.<br><br>
                                    Nous vous remercions pour votre confiance et sommes ravis de vous compter parmi nos membres.<br><br>
                                    Si vous avez besoin d’assistance ou d’informations complémentaires, notre équipe reste à votre disposition.<br><br>
                                    Cordialement,<br>
                                    <strong>L’équipe UNAMEPCI</strong>
                                    ";

                                    $url = appelApiEmail();
                                    $template = View::make('home.admin.paiements.paiementAdhesion', ['contenumess' => $message])->render();
                                    $data = [
                                        'provider' => 'UNAMEPCI <info@mail-taseti.com>',
                                        "key_rsa" => 're_2i7H3Ynf_KRVm9VwTsrwrfF8isCBYvyyE',
                                        "destination" => $mutualiste->email,
                                        "sujet" => $sujet,
                                        "message" => $template
                                    ];
                                    $retourAPI = Http::post($url, $data);
                                    $res = $retourAPI->json();

                                    if ($retourAPI->status() == 200) {
                                        (int)$code = $res['status'];
                                        if ($code != 200) {
                                            $message = "Une erreur s'est produite " . $code . ", DETAIL: " . messageBrut($res['message']) . " ERR: Envoye Paiement adhesion";
                                            Log::error($message);
                                        }
                                    } else {
                                        Log::error("Erreur lors de l'envoi de l'email. Statut API : " . $retourAPI->status());
                                    }
                                }
                            } else {
                                $Chaine = " id carte membre  incorrecter";
                                $module = " paiement ";
                                $action = "$Chaine";
                                Logs::saveLog($module, $action);
                            }
                            break;

                        case 'cotisation':
                            // COPIÉ COLLÉ DE L'ANCIEN CODE QUI FONCTIONNE
                            $cotisationMutualiste = CotisationMutualiste::where('id', $request->cotisation_mutualiste_id)->first();

                            $codePaiement = generateCode2('Cot');

                            $paiementinit = new PaiementInitiale();
                            $paiementinit->code_paiement = $codePaiement;
                            $paiementinit->reference =  $request->reference;
                            $paiementinit->cheque_numero =  $request->virement_reference;
                            $paiementinit->banque_autre =  $banque;
                            $paiementinit->mutualiste_id = $mutualiste->id;
                            $paiementinit->type_paiement_id = 2;
                            $paiementinit->correspondance_id = $cotisationMutualiste->id;
                            $paiementinit->montant_initial = $request->montant ?? $cotisationMutualiste->montant;
                            $paiementinit->date_paiement_initial =  $request->virement_date ?? $request->date_paiement ?? now() ?? '';
                            $paiementinit->moyen_paiement = "virement";
                            $paiementinit->status = 1; //
                            $paiementinit->save();


                            $paiement = new Paiement();
                            $paiement->contact_paiement =  $mutualiste->contact ?? $mutualiste->contact_2 ??  "0000000000";
                            $paiement->montant_total = $request->montant ?? $paiementinit->montant_initial;
                            $paiement->moyen_paiement =  "virement";
                            $paiement->code_paiement = $codePaiement;
                            $paiement->date_paiement_final =  $request->date_paiement ??  $paiementinit->date_paiement_initial ?? '';
                            $paiement->reference =  $request->reference;
                            $paiement->cheque_numero =  $request->virement_reference;
                            $paiement->banque_autre =  $banque;
                            $paiement->type_paiement_id = $paiementinit->type_paiement_id;
                            $paiement->mutualiste_id = $paiementinit->mutualiste_id;
                            $paiement->montant_initial = $paiementinit->montant_initial;
                            $paiement->correspondance_id = $paiementinit->correspondance_id;
                            $paiement->status = 1;
                            $paiement->save();

                            if ($request->hasFile('documents')) {
                                foreach ($request->file('documents') as $index => $file) {
                                    $file_name = md5(uniqid()) . $index . '.' . $file->extension();
                                    if (Storage::exists('images-docPaiementPret/' . $file_name)) {
                                        $file_name = md5(uniqid()) . $index . '.' . $file->extension();
                                    }
                                    $file->storeAs('images-docPaiementPret/', $file_name);
                                    $lien_images_projet = 'src-files/images-docPaiementPret/' . $file_name;

                                    DocumentPaiement::create([
                                        'pret_id' => $request->idDemandeaccompa,
                                        'paiement_initiale_id' => $paiementinit->id,
                                        'lien_photo' => $lien_images_projet,
                                    ]);
                                }
                            }
                            if (!empty($cotisationMutualiste)) {
                                if ($cotisationMutualiste->frequence_paiement === 'Annuelle') {

                                    // Mettre à jour les montants
                                    $cotisationMutualiste->montant_paye += $Montant;
                                    $cotisationMutualiste->montant -= $Montant;

                                    // Empêcher un solde négatif
                                    if ($cotisationMutualiste->montant < 0) {
                                        $cotisationMutualiste->montant = 0;
                                    }

                                    // Si tout est payé → statut validé
                                    if ($cotisationMutualiste->montant == 0) {
                                        $cotisationMutualiste->status = 1;
                                    }

                                    $cotisationMutualiste->save();
                                } else {
                                    // Autres fréquences (mensuelle, trimestrielle…)
                                    $cotisationMutualiste->update([
                                        'status' => 1
                                    ]);
                                }

                                $Chaine = " enregistre cotisations id $cotisationMutualiste->id ";
                                $module = " paiement ";
                                $action = "$Chaine";
                                Logs::saveLog($module, $action);
                            } else {
                                $Chaine = " id cotisation incorrecter";
                                $module = " paiement ";
                                $action = "$Chaine";
                                Logs::saveLog($module, $action);
                            }




                            break;

                        case 'produit':
                            if ($request->categorie_produit == "projets") {
                                // Comme dans l'ancien code projets
                                $demandeP = Facturation::findOrFail($request->produit_id);


                                $codePaiement = generateCode2('Ref');

                                $paiementinit = new PaiementInitiale();
                                $paiementinit->code_paiement = $codePaiement;
                                $paiementinit->reference =  $request->reference;
                                $paiementinit->cheque_numero =  $request->virement_reference;
                                $paiementinit->banque_autre =  $banque;
                                $paiementinit->mutualiste_id = $mutualiste->id;
                                $paiementinit->type_paiement_id = 4;
                                $paiementinit->correspondance_id = $request->facturationID;
                                $paiementinit->montant_initial = $request->montant;
                                $paiementinit->produit_id = $request->produit_id;
                                $paiementinit->date_paiement_initial = $request->virement_date ?? $request->date_paiement ?? now() ?? '';
                                $paiementinit->moyen_paiement = "virement";
                                $paiementinit->status = 1; //
                                $paiementinit->save();


                                $paiement = new Paiement();
                                $paiement->contact_paiement =  $mutualiste->contact ?? $mutualiste->contact_2 ??  "0000000000";
                                $paiement->montant_total = $request->montant ?? $paiementinit->montant_initial;
                                $paiement->moyen_paiement =  "virement";
                                $paiement->code_paiement = $codePaiement;
                                $paiement->date_paiement_final =  $request->virement_date ??  $paiementinit->date_paiement_initial ?? '';
                                $paiement->reference =  $request->reference;
                                $paiement->cheque_numero =  $request->virement_reference;
                                $paiement->banque_autre =  $banque;
                                $paiement->type_paiement_id = $paiementinit->type_paiement_id;
                                $paiement->mutualiste_id = $paiementinit->mutualiste_id;
                                $paiement->montant_initial = $paiementinit->montant_initial;
                                $paiement->correspondance_id = $paiementinit->correspondance_id;
                                $paiement->status = 1;
                                $paiement->save();


                                if ($request->hasFile('documents')) {
                                    foreach ($request->file('documents') as $index => $file) {
                                        $file_name = md5(uniqid()) . $index . '.' . $file->extension();
                                        if (Storage::exists('images-docPaiementPret/' . $file_name)) {
                                            $file_name = md5(uniqid()) . $index . '.' . $file->extension();
                                        }
                                        $file->storeAs('images-docPaiementPret/', $file_name);
                                        $lien_images_projet = 'src-files/images-docPaiementPret/' . $file_name;

                                        DocumentPaiement::create([
                                            'pret_id' => $request->idDemandeaccompa,
                                            'paiement_initiale_id' => $paiementinit->id,
                                            'lien_photo' => $lien_images_projet,
                                        ]);
                                    }
                                }
                                if (!empty($demandeP)) {

                                    $demandeP->total_payer = $paiement->montant_initial;
                                    $demandeP->total_apayer = $demandeP->total_apayer - $paiement->montant_initial;
                                    $demandeP->status = 2;
                                    $demandeP->save();
                                    $Chaine = " facturation   solde id: $demandeP->id  ";
                                    $module = " paiement ";
                                    $action = "$Chaine";
                                    Logs::saveLog($module, $action);
                                } else {
                                    $Chaine = " id projet incorrecter";
                                    $module = " paiement ";
                                    $action = "$Chaine";
                                    Logs::saveLog($module, $action);
                                }
                            } else {
                                // Services
                                $demandeP = DemandeAccompagnement::findOrFail($request->produit_id);


                                $codePaiement = generateCode2('Rem');

                                $paiementinit = new PaiementInitiale();
                                $paiementinit->code_paiement = $codePaiement;
                                $paiementinit->reference =  $request->reference;
                                $paiementinit->cheque_numero =  $request->virement_reference;
                                $paiementinit->banque_autre =  $banque;
                                $paiementinit->mutualiste_id = $mutualiste->id;
                                $paiementinit->type_paiement_id = 3;
                                $paiementinit->correspondance_id = $request->produit_id;
                                $paiementinit->montant_initial = $request->montant;
                                $paiementinit->date_paiement_initial = $request->virement_date ?? $request->date_paiement ?? now() ?? '';
                                $paiementinit->moyen_paiement = "virement";
                                $paiementinit->status = 1; //
                                $paiementinit->save();


                                $paiement = new Paiement();
                                $paiement->contact_paiement =  $mutualiste->contact ?? $mutualiste->contact_2 ??  "0000000000";
                                $paiement->montant_total = $request->montant ?? $paiementinit->montant_initial;
                                $paiement->moyen_paiement =  "virement";
                                $paiement->code_paiement = $codePaiement;
                                $paiement->date_paiement_final =  $request->virement_date ??  $paiementinit->date_paiement_initial ?? '';
                                $paiement->reference =  $request->reference;
                                $paiement->cheque_numero =  $request->virement_reference;
                                $paiement->banque_autre =  $banque;
                                $paiement->type_paiement_id = $paiementinit->type_paiement_id;
                                $paiement->mutualiste_id = $paiementinit->mutualiste_id;
                                $paiement->montant_initial = $paiementinit->montant_initial;
                                $paiement->correspondance_id = $paiementinit->correspondance_id;
                                $paiement->status = 1;
                                $paiement->save();


                                if ($request->hasFile('documents')) {
                                    foreach ($request->file('documents') as $index => $file) {
                                        $file_name = md5(uniqid()) . $index . '.' . $file->extension();
                                        if (Storage::exists('images-docPaiementPret/' . $file_name)) {
                                            $file_name = md5(uniqid()) . $index . '.' . $file->extension();
                                        }
                                        $file->storeAs('images-docPaiementPret/', $file_name);
                                        $lien_images_projet = 'src-files/images-docPaiementPret/' . $file_name;

                                        DocumentPaiement::create([
                                            'pret_id' => $request->idDemandeaccompa,
                                            'paiement_initiale_id' => $paiementinit->id,
                                            'lien_photo' => $lien_images_projet,
                                        ]);
                                    }
                                }

                                if (!empty($demandeP)) {
                                    $demandeP->payer = $demandeP->payer + $paiement->montant_initial;
                                    $demandeP->save();
                                    $Chaine = " virement solde $demandeP->id  ";
                                    $module = " paiement ";
                                    $action = "$Chaine";
                                    Logs::saveLog($module, $action);
                                } else {
                                    $Chaine = " id virement incorrecter";
                                    $module = " paiement ";
                                    $action = "$Chaine";
                                    Logs::saveLog($module, $action);
                                }
                            }

                            break;

                        default:
                            throw new \Exception("Type de paiement inconnu");
                    }

                    DB::commit();
                    break;

                case 'en_ligne':
                    try {
                        DB::beginTransaction();
                        $mutualiste = Mutualiste::where('id', $request->mutualiste_id)->first();
                        if (!$mutualiste) {
                            throw new \Exception("Mutualiste introuvable.");
                        }

                        $type_paiement = $request->type_paiement;

                        // Authentification TresorMoney (commune à tous les cas)
                        $auth = new STAuthTresorMoney();
                        $auth->Key = env('KEY_AUTH_TREMO');
                        $auth->Secret = env('SECRET_AUTH_TREMO');
                        $bufSend = json_encode($auth);

                        Logs::saveLog("Module Paiement", "debut authentification tresormoney");

                        $responseReq = Http::post(env('URL_AUTHENTIF'), $auth);
                        $Contenu = $responseReq->body();
                        $retourauth = json_decode($responseReq->body());

                        Logs::saveLog("Module Paiement", "retour authentification: $Contenu");

                        if ($responseReq->status() !== 200 || $retourauth->code !== 200) {
                            $msg = $retourauth->sMessage ?? 'Erreur d\'authentification';
                            Logs::saveLog("Module Paiement", "Erreur auth: $msg");

                            if ($isAjax) {
                                return response()->json(['success' => false, 'message' => $msg], 422);
                            }

                            $code = $retourauth->code ?? 500;
                            $mess = "<p>Erreur: $msg</p>";
                            return view('dashboard.pageErreurs.index', compact('code', 'mess'));
                        }

                        // Traitement selon le type
                        switch ($type_paiement) {
                            case 'adhesion':
                                // Comme dans l'ancien code qui fonctionne
                                $infosProduits = new listeDesProduits();
                                $infosProduits['LibelleProduit'] = "Droit d'adhesion";
                                $infosProduits['Montant'] = $request->montant;
                                $infosProduits['nEstUnServicePrive'] = 0;
                                $infosProduits['TypeProduit'] = 1;
                                $infosProduits['Quantite'] = 1;
                                $infosProduits['IdProduit'] = 0;
                                $infosProduits['Reference_code_Produit'] = "";

                                $codePaiement = generateCode2('Ref');

                                $paiementinit = new PaiementInitiale();
                                $paiementinit->code_paiement = $codePaiement;
                                $paiementinit->mutualiste_id = $mutualiste->id;
                                $paiementinit->type_paiement_id = 1;
                                $paiementinit->correspondance_id = $mutualiste->droitAdhesion->id;
                                $paiementinit->montant_initial = $request->montant;
                                $paiementinit->contact_paiement = $request->tresormoney_numero;
                                $paiementinit->save();

                                $infosbeneficiaire['Credentiel'] = env('HUB_KEY_TREMO_BMI');
                                $infosbeneficiaire['produits'][] = $infosProduits;

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
                                break;

                            case 'cotisation':
                                // COPIÉ COLLÉ DE L'ANCIEN CODE QUI FONCTIONNE
                                $cotisationMutualiste = CotisationMutualiste::where('id', $request->cotisation_mutualiste_id)->first();

                                $infosProduits = new listeDesProduits();
                                $infosProduits['LibelleProduit'] = $cotisationMutualiste->cotisation->libelle ?? 'Cotisation';
                                $infosProduits['Montant'] = $request->montant;
                                $infosProduits['nEstUnServicePrive'] = 0;
                                $infosProduits['TypeProduit'] = 1;
                                $infosProduits['Quantite'] = 1;
                                $infosProduits['IdProduit'] = 0;
                                $infosProduits['Reference_code_Produit'] = "";

                                $codePaiement = generateCode2('Cot');

                                $paiementinit = new PaiementInitiale();
                                $paiementinit->code_paiement = $codePaiement;
                                $paiementinit->mutualiste_id = $mutualiste->id;
                                $paiementinit->type_paiement_id = 2;
                                $paiementinit->correspondance_id = $cotisationMutualiste->id;
                                $paiementinit->montant_initial = $request->montant ?? $cotisationMutualiste->montant;
                                $paiementinit->contact_paiement = $request->tresormoney_numero;
                                $paiementinit->save();

                                $infosbeneficiaire['Credentiel'] = env('HUB_KEY_TREMO');
                                $infosbeneficiaire['produits'][] = $infosProduits;

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
                                break;

                            case 'produit':
                                if ($request->categorie_produit == "projets") {
                                    // Comme dans l'ancien code projets
                                    $demandeP = Facturation::findOrFail($request->produit_id);
                                    $infosProduits = new listeDesProduits();
                                    $infosProduits['LibelleProduit'] = "redevance :" . $demandeP->redevance->libelle . " pour le Produit : ." . $demandeP->produitProjet->libelle;
                                    $infosProduits['Montant'] = $request->montant;
                                    $infosProduits['nEstUnServicePrive'] = 0;
                                    $infosProduits['TypeProduit'] = 1;
                                    $infosProduits['Quantite'] = 1;
                                    $infosProduits['IdProduit'] = 0;
                                    $infosProduits['Reference_code_Produit'] = "";

                                    $codePaiement = generateCode2('Ref');

                                    $paiementinit = new PaiementInitiale();
                                    $paiementinit->code_paiement = $codePaiement;
                                    $paiementinit->mutualiste_id = $mutualiste->id;
                                    $paiementinit->type_paiement_id = 4;
                                    $paiementinit->correspondance_id = $request->facturationID;
                                    $paiementinit->montant_initial = $request->montant;
                                    $paiementinit->produit_id = $request->produit_id;
                                    $paiementinit->contact_paiement = $request->tresormoney_numero;
                                    $paiementinit->save();

                                    $infosbeneficiaire['Credentiel'] = env('HUB_KEY_TREMO');
                                    $infosbeneficiaire['produits'][] = $infosProduits;
                                } else {
                                    // Services
                                    $demandeP = DemandeAccompagnement::findOrFail($request->produit_id);
                                    $infosProduits = new listeDesProduits();
                                    $infosProduits['LibelleProduit'] = "Pret : ." . $demandeP->service->libelle;
                                    $infosProduits['Montant'] = $request->montant;
                                    $infosProduits['nEstUnServicePrive'] = 0;
                                    $infosProduits['TypeProduit'] = 1;
                                    $infosProduits['Quantite'] = 1;
                                    $infosProduits['IdProduit'] = 0;
                                    $infosProduits['Reference_code_Produit'] = "";

                                    $codePaiement = generateCode2('Rem');

                                    $paiementinit = new PaiementInitiale();
                                    $paiementinit->code_paiement = $codePaiement;
                                    $paiementinit->mutualiste_id = $mutualiste->id;
                                    $paiementinit->type_paiement_id = 3;
                                    $paiementinit->correspondance_id = $request->produit_id;
                                    $paiementinit->montant_initial = $request->montant;
                                    $paiementinit->contact_paiement = $request->tresormoney_numero;
                                    $paiementinit->save();

                                    $infosbeneficiaire['Credentiel'] = env('HUB_KEY_TREMO');
                                    $infosbeneficiaire['produits'][] = $infosProduits;
                                }

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
                                break;

                            default:
                                throw new \Exception("Type de paiement inconnu");
                        }

                        // Initiation du paiement (commun à tous)
                        $bufSend = json_encode($infospaiement);
                        Logs::saveLog("Module Paiement", "debut initiation transaction: $bufSend");

                        $responseReq = Http::post(env('URL_INITIATE'), $infospaiement);
                        $Contenu = $responseReq->body();

                        Logs::saveLog("Module Paiement", "retour initiation: $Contenu");

                        $retourReq = json_decode($responseReq->body());

                        if ($responseReq->status() !== 200) {
                            $msg = "echec d initiation transaction tresormoney, impossible de joindre l hote.";
                            Logs::saveLog("Module Paiement", $msg);

                            if ($isAjax) {
                                return response()->json(['success' => false, 'message' => $msg], 500);
                            }

                            $code = $responseReq->status();
                            $mess = "<p>$msg</p>";
                            return view('dashboard.pageErreurs.index', compact('code', 'mess'));
                        }

                        $debutmess = "L operation a ete initiee sur le numero " . $paiementinit->contact_paiement . ". ";

                        if ($retourReq->code !== 200) {
                            $msg = $retourReq->cleretour ?? 'Erreur d\'initiation';
                            Logs::saveLog("Module Paiement", "Erreur initiation: $msg");

                            if ($isAjax) {
                                return response()->json(['success' => false, 'message' => $msg], 422);
                            }

                            $code = $retourReq->code;
                            $mess = "<p>$msg</p>";
                            return view('dashboard.pageErreurs.index', compact('code', 'mess'));
                        }

                        // SUCCÈS
                        $message = $debutmess . "\n " . $retourReq->cleretour;

                        // Sauvegarder dans paiements
                        // $data['code_paiement'] = $codePaiement;
                        // $data['status'] = 2; // En attente
                        // $data['correspondance_id'] = $paiementinit->correspondance_id;
                        // $data['produit_id'] = $paiementinit->produit_id ?? null;
                        // $paiement = Paiement::create($data);

                        $message = $debutmess . "\n " . $retourReq->cleretour;
                        DB::commit();
                        if ($isAjax) {
                            return response()->json([
                                'success' => true,
                                'message' => $message,
                                'code_paiement' => $codePaiement,
                                'redirect' => route('removePlay', ['codePaiement' => $codePaiement, 'ind' => 1])
                            ]);
                        }

                        return redirect()->route('removePlay', ['codePaiement' => $codePaiement, 'ind' => 1])
                            ->with('success', $message);
                    } catch (\Exception $e) {
                        Log::error('Erreur paiement en ligne: ' . $e->getMessage());
                        Logs::saveLog("Module Paiement", "Exception: " . $e->getMessage());

                        if ($isAjax) {
                            return response()->json([
                                'success' => false,
                                'message' => 'Erreur : ' . $e->getMessage()
                            ], 500);
                        }

                        return redirect()->back()
                            ->withInput()
                            ->with('error', 'Erreur lors du paiement en ligne : ' . $e->getMessage());
                    }
                    break;
            }




            return redirect()->route('paiements.caisse')
                ->with('success', 'Paiement enregistré avec succès ');
        } catch (\Exception $e) {
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur : ' . $e->getMessage()
                ], 500);
            }


            $module = " erreur paiement ";
            $action = "Exception lors de l'enregistrement du paiement : " . $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de l\'enregistrement : ' . $e->getMessage());
        }
    }









    public function resulPay($codePaiement, $ind)
    {
        $paiementinit = PaiementInitiale::where('code_paiement', $codePaiement)->first();
        // $contCoti = NbreCotisation();

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
            $module = "Module Espace administrateur ";
            $action = "a consulte  la page resultat paiement et voici le code du paiement : $code";
            Logs::saveLog($module, $action);
            return view('dashboard.caisses.replay', compact('paiementinit', 'mess', 'code',  'ind', 'codePaiement'));
        } else {
            $code = 404;
            $mess = "<h3 class='sub-title'>Page Introuvable</h3><br>
            <p>Une erreur est survenue, veuillez réessayer plus tard. #EDOCTNEME</p>";
            $module = "Module Espace administrateur ";
            $action = "Une erreur s'est produit sur la page resultat paiement car le paiement n'existe pas code Paiement : $codePaiement ";

            Logs::saveLog($module, $action);
            return view('dashboard.pageErreurs.index', compact('code', 'mess'));
        }
    }
}
