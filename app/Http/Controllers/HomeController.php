<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\Slide;
use App\Models\Projet;
use App\Models\Actualite;
use App\Models\Parametre;
use Illuminate\Http\Request;
use App\Mail\ContactFormMail;
use App\Models\ProduitProjet;
use App\Models\PaiementInitiale;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()

    {
        $actualites = Actualite::orderBy('created_at', 'desc')->paginate(3);
        $projets = Projet::orderBy('created_at', 'desc')->get();
        $slides = Slide::whereCategorie('Image Carousel')->orderBy('created_at', 'ASC')->get();
        $partenaires = Slide::whereCategorie('Image Partenaire')->orderBy('created_at', 'ASC')->get();
        $annonces = Slide::whereCategorie('Image Annonce')->orderBy('created_at', 'desc')->get();
       
        return view('home.index', compact('actualites', 'projets', 'slides', 'partenaires', 'annonces'));
    }
    public function connexionMutualiste()
    {
        return view('auth.home_auth.login_home');
    }
    public function presentation()
    {
        $parametre = Parametre::whereId(1)->first();
        // dd($parametre);
        return view('home.pages.presentations', compact('parametre'));
    }
    /*
      --------------------------------------------------------------------
                        la section de projet & Produit debut
      --------------------------------------------------------------------
*/
    public function projets()
    {
        $projets = Projet::orderBy('created_at', 'desc')->paginate(3);

        return view('home.pages.projets.index', compact('projets'));
    }
    public function detail_projet(Projet $projet)
    {
        $produitprojets = ProduitProjet::where('projet_id', $projet->id)->get();
        return view('home.pages.projets.show', compact(['projet', 'produitprojets']));
    }
    public function detailProduitMutualiste(ProduitProjet $produitprojet)
    {
        return view('home.admin.projets_admin.produits.show', compact('produitprojet'));
    }
    /*
      --------------------------------------------------------------------
                        la section de actualite debut
      --------------------------------------------------------------------
*/

    public function actualite()
    {
        $actualites = Actualite::orderBy('created_at', 'desc')->paginate(3);
        return view('home.pages.actualites.index', compact('actualites'));
    }
    public function detailsActualites(Actualite $actualite)
    {
        return view('home.pages.actualites.show', compact('actualite'));
    }
    /*
      ------------------
      la section de actualite fin
    ------------------------
*/
    public function contact()
    {
        $parametre = Parametre::whereId(1)->first();
        return view('home.pages.contacts', compact('parametre'));
    }
    public function inscription()
    {
        return view('home.pages.inscription');
    }
    // fonction qui permet de traiter les information du formulaire de contact
    public function traitementContact(Request $request)
    {
        // dd('test');
        $validated = $request->validate([
            'contact_name' => 'required',
            'contact_email' => 'required',
            'objet' => 'required',
            'contact_message' => 'required',
        ]);
        $administrateur = auth()->user()->mutualiste;
        Mail::to('dorianenahi@gmail.com')->send(new ContactFormMail($validated));
        return back();
    }
    // recu de paiement
    public function generatePDF($id)
    {
        $paiement = PaiementInitiale::where('id', $id)->first();
        $code = route('telecharger.recu', ['id' => $id]);
        return view('home.admin.paiements.recu_payement', compact('paiement','code'));
    }
}
