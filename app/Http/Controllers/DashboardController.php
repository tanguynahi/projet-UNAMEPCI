<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\Service;
use App\Models\Paiement;
// use Illuminate\Http\Request;
use App\Models\Mutualiste;
use App\Models\Facturation;
use App\Models\Administrateur;
use App\Models\DocumentPaiement;
use App\Models\PaiementInitiale;
use App\Models\ProjetMutualiste;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\DemandeAccompagnement;

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
        $nombrMutualiste = Mutualiste::where('status',1)->count();
        $montantTotal = PaiementInitiale::where('status', 1)->sum('montant_initial');
        $mntAdhesion = PaiementInitiale::where('status', 1)->where('type_paiement_id', 1)->sum('montant_initial');
        $mntCotisation = PaiementInitiale::where('status', 1)->where('type_paiement_id', 2)->sum('montant_initial');
        $mntPret = PaiementInitiale::where('status', 1)->where('type_paiement_id', 3)->sum('montant_initial');
        $mntProjet = PaiementInitiale::where('status', 1)->where('type_paiement_id', 4)->sum('montant_initial');
        // $mntCarte = PaiementInitiale::where('status', 1)->where('type_paiement_id', 5)->sum('montant_initial');

        return view('dashboard.index', compact('mntAdhesion', 'mntAdhesion',  'mntCotisation', 'mntPret', 'mntProjet','montantTotal','nombrMutualiste','nombrAdmin'));
    }

    public function statistiques()
    {
        $module = "Module Tableau de bord administrateur";
        $action = "a consulte la page des statistique d'un administrateur";
        Logs::saveLog($module, $action);
        return view('dashboard.stats');
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
}
