<?php

namespace App\Http\Controllers\Home;

// use Barryvdh\DomPDF\PDF;
use PDF;
use App\Models\Logs;
use App\Models\User;
// use Barryvdh\DomPDF\Facade as PDF;
use App\Models\Corps;
use App\Models\Grade;
use App\Models\Ville;
use App\Models\Projet;
use App\Models\Paiement;
use App\Models\TypePiece;
use App\Models\Mutualiste;
use App\Models\Facturation;
use Illuminate\Http\Request;
use App\Models\DroitAdhesion;
use App\Models\ProduitProjet;
use App\Models\Administrateur;
use App\Models\PaiementInitiale;
use App\Http\Controllers\Controller;
use App\Models\CotisationMutualiste;
use Illuminate\Support\Facades\Auth;
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
        $corps = Corps::all();
        $grades = Grade::all();
        $typePieces = TypePiece::all();
        $villes = Ville::orderBy('libelle', 'ASC')->get();
        $droit_adhesions = DroitAdhesion::where('mutualiste_id',$mutualiste->id)->first();
        $module = "Module Espace Mutualiste ";
        $action = "a consulte son profil";
        Logs::saveLog($module, $action);
        return view('home.admin.profils.parametre', compact('corps', 'grades', 'typePieces', 'villes', 'droit_adhesions','contCoti','mutualiste'));
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
        return view('home.admin.projets_admin.projet', compact('droit_adhesions', 'projets','contCoti'));
    }
    public function historique_index()
    {
        $mutualiste = auth()->user()->mutualiste;
        // $droit_adhesions = DroitAdhesion::where('mutualiste_id', Auth::guard()->user()->mutualiste->id)->get();
        $paiements = PaiementInitiale::where('mutualiste_id', $mutualiste->id)->get();
        $facturations = Facturation::where('mutualiste_id', $mutualiste->id)->get();
        $cotisationMutualistes = CotisationMutualiste::where('mutualiste_id',$mutualiste->id)->get();
        $accompagnements = DemandeAccompagnement::where('mutualiste_id',$mutualiste->id)->get();
        $module = "Module Espace Mutualiste ";
        $action = "a consulte  l'historiques";
        Logs::saveLog($module, $action);
        return view('home.admin.historiques.index', compact('paiements', 'facturations','cotisationMutualistes','accompagnements'));
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
            return view('home.admin.paiements.resultat_paiement', compact('paiementinit', 'mess', 'code','contCoti'));
        } else {
            $code = 404;
            $mess = "<h3 class='sub-title'>Page Introuvable</h3><br>
            <p>Une erreur est survenue, veuillez réessayer plus tard. #EDOCTNEME</p>";
            $module = "Module Espace Mutualiste ";
            $action = "Une erreur s'est produit sur la page resultat paiement car le paiement n'existe pas ";
            Logs::saveLog($module, $action);
            return view ('home.admin.errorpage.index', compact('code','mess'));
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

}
