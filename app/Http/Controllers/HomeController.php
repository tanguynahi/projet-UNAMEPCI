<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\User;
use App\Models\Slide;
use App\Models\Ville;
use App\Models\Projet;
use App\Models\Actualite;
use App\Models\Parametre;
use App\Models\TypePiece;
use App\Models\Mutualiste;
use App\Models\Specialite;
use App\Models\Inscription;
use Illuminate\Http\Request;
use App\Mail\ContactFormMail;
use App\Models\ProduitProjet;
use App\Models\FormeJuridique;
use App\Models\PaiementInitiale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\CreationAccesInscriptionRequest;
use App\Http\Requests\StoreinscriptionUtilisateurRequest;

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
        return view('home.admin.paiements.recu_payement', compact('paiement', 'code'));
    }


    // inscription
    public function inscrit()
    {
        $parametre = Parametre::whereId(1)->first();
        $typePieces = TypePiece::where('status', 1)->get();
        $specialites = Specialite::where('status', 1)->get();
        $formeJuridiques = FormeJuridique::where('status', 1)->get();
        $villes = Ville::where('status', 1)->get(); // Ajoutez cette ligne
        return view('home.pages.inscriptions.index', compact('parametre', 'typePieces', 'formeJuridiques', 'specialites', 'villes'));
    }
    public function traitementInscript(StoreinscriptionUtilisateurRequest $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            $avatar = null;
            $photo_couverture = null;
            $document_autorisation_ouverture = null;
            $document_carte_inscript_ONMCI = null;
            $photo_identite_1 = null;
            $pieces_joints_recto = null;
            $pieces_joints_verso = null;
            $valeur = '';

            // if ($request->hasFile("avatar")) {
            //     // $avatar = null;
            //     $file = $request->file("avatar");

            //     if ($file->isValid()) {
            //         $folder = "DOCSLABELIS";
            //         $avatar = createFichiers("$folder/", $file, $file->extension());
            //     }
            // }

            if ($request->hasFile('avatar')) {
                $file_name = md5(uniqid()) . '.' . $request->file('avatar')->extension();
                $request->file('avatar')->storeAs('avatar-inscription/', $file_name);
                $avatar = 'src-files/avatar-inscription/' . $file_name;
            }
            if ($request->hasFile('photo_couverture')) {
                $file_name = md5(uniqid()) . '.' . $request->file('photo_couverture')->extension();
                $request->file('photo_couverture')->storeAs('photo_couverture-inscription/', $file_name);
                $photo_couverture = 'src-files/photo_couverture-inscription/' . $file_name;
            }
            if ($request->hasFile('document_autorisation_ouverture')) {
                $file_name = md5(uniqid()) . '.' . $request->file('document_autorisation_ouverture')->extension();
                $request->file('document_autorisation_ouverture')->storeAs('document_autorisation_ouverture-inscription/', $file_name);
                $document_autorisation_ouverture = 'src-files/document_autorisation_ouverture-inscription/' . $file_name;
            }
            if ($request->hasFile('document_carte_inscript_ONMCI')) {
                $file_name = md5(uniqid()) . '.' . $request->file('document_carte_inscript_ONMCI')->extension();
                $request->file('document_carte_inscript_ONMCI')->storeAs('document_carte_inscript_ONMCI-inscription/', $file_name);
                $document_carte_inscript_ONMCI = 'src-files/document_carte_inscript_ONMCI-inscription/' . $file_name;
            }
            if ($request->hasFile('photo_identite_1')) {
                $file_name = md5(uniqid()) . '.' . $request->file('photo_identite_1')->extension();
                $request->file('photo_identite_1')->storeAs('photo_identite_1-inscription/', $file_name);
                $photo_identite_1 = 'src-files/photo_identite_1-inscription/' . $file_name;
            }
            if ($request->hasFile('pieces_joints_recto')) {
                $file_name = md5(uniqid()) . '.' . $request->file('pieces_joints_recto')->extension();
                $request->file('pieces_joints_recto')->storeAs('pieces_joints_recto-inscription/', $file_name);
                $pieces_joints_recto = 'src-files/pieces_joints_recto-inscription/' . $file_name;
            }
            if ($request->hasFile('pieces_joints_verso')) {
                $file_name = md5(uniqid()) . '.' . $request->file('pieces_joints_verso')->extension();
                $request->file('pieces_joints_verso')->storeAs('pieces_joints_verso-inscription/', $file_name);
                $pieces_joints_verso = 'src-files/pieces_joints_verso-inscription/' . $file_name;
            }

            $etre_auteur = $request->etre_auteur;
            $email = $request->email;
            if ($etre_auteur == 1 || empty($request->nom_auteur)) {
                $valeur = $request->nom_relation;
            } else {
                $valeur = $request->nom_auteur;
            }



            // $table->text('lien_email')->nullable();
            $code = genereCodeInscription();

            $inscription = new Inscription();
            $inscription->typeAdhesion = $request->typeAdhesion;
            $inscription->nom = $request->nom;
            $inscription->prenom = $request->prenom;
            $inscription->contact = $request->contact;
            $inscription->contact_2 = $request->contact_2;
            $inscription->fax = $request->fax;
            $inscription->email = $email ?? $request->email;
            $inscription->adresse = $request->adresse;
            $inscription->civilite = $request->civilite;
            $inscription->date_naissance = $request->date_naissance;
            $inscription->lieu_naissance = $request->lieu_naissance;
            $inscription->nationalite = $request->nationalite;
            $inscription->situation_matrimoniale = $request->situation_matrimoniale;
            $inscription->nombre_charge = $request->nombre_charge;
            $inscription->date_adhesion_unamepci = $request->date_adhesion_unamepci;
            $inscription->type_piece_id = $request->type_piece_id;
            $inscription->numero_piece = $request->numero_piece;
            $inscription->date_etablissement_piece = $request->date_etablissement_piece;
            $inscription->lieu_etablissement_piece = $request->lieu_etablissement_piece;
            // les fichier
            $inscription->pieces_joints_recto = $pieces_joints_recto;
            $inscription->pieces_joints_verso = $pieces_joints_verso;

            $inscription->numero_inscription_ONMCI = $request->numero_inscription_ONMCI;
            $inscription->pseudonyme_recon_ONMCI = $request->pseudonyme_recon_ONMCI;
            $inscription->matricule = $request->matricule;
            $inscription->raison_social_primaire = $request->raison_social_primaire;
            $inscription->specialite_id = $request->specialite_id;
            $inscription->fonction = $request->fonction;
            $inscription->date_debut_metier = $request->date_debut_metier;
            $inscription->nombre_annee_experience = $request->nombre_annee_experience;
            $inscription->nom_employeur_principale = $request->nom_employeur_principale;
            $inscription->statut_emploi = $request->statut_emploi;
            $inscription->domaine_activite = $request->domaine_activite;
            $inscription->date_recrutement = $request->date_recrutement;
            $inscription->montant_cotis_annuel = $request->montant_cotis_annuel;
            $inscription->sigle = $request->sigle;
            $inscription->date_creation = $request->date_creation;
            $inscription->numero_autorisation = $request->numero_autorisation;
            $inscription->num_immatriculation = $request->num_immatriculation;
            $inscription->forme_juridique_id = $request->forme_juridique_id;
            $inscription->precise_forme_juridique = $request->precise_forme_juridique;
            $inscription->ville_id = $request->ville_id;
            $inscription->commune = $request->commune;
            $inscription->quartier = $request->quartier;
            $inscription->rue = $request->rue;
            $inscription->adresse_postale_entreprise = $request->adresse_postale_entreprise;
            $inscription->localisation_entreprise = $request->localisation_entreprise;
            $inscription->email_entreprise = $request->email_entreprise;
            $inscription->telephone_entreprise = $request->telephone_entreprise;
            $inscription->fax_entreprise = $request->fax_entreprise;
            $inscription->fax_entreprise = $request->fax_entreprise;
            $inscription->relation_tiers = $request->relation_tiers;
            $inscription->nom_relation = $request->nom_relation;
            $inscription->etre_auteur = $etre_auteur;
            $inscription->nom_auteur = $valeur;
            $inscription->raison_social_secondaire_freelance = $request->raison_social_secondaire_freelance;
            $inscription->fonction_occupe_freelance = $request->fonction_occupe_freelance;
            $inscription->type_contrat_freelance = $request->type_contrat_freelance;
            $inscription->telephone_freelance = $request->telephone_freelance;
            $inscription->fax_freelance = $request->fax_freelance;
            $inscription->localisation_freelance = $request->localisation_freelance;
            $inscription->adresse_postale_freelance = $request->adresse_postale_freelance;
            $inscription->domaine_activite_freelance = $request->domaine_activite_freelance;
            $inscription->avatar = $avatar;
            $inscription->photo_couverture = $photo_couverture;
            $inscription->document_carte_inscript_ONMCI = $document_carte_inscript_ONMCI;
            $inscription->document_autorisation_ouverture = $document_autorisation_ouverture;
            $inscription->photo_identite_1 = $photo_identite_1;
            $inscription->code = $code;
            $inscription->status = 2;
            $inscription->save();

            DB::commit();
            toast('Vos Inscription ont été effectuées avec succès !', 'success');
            $module = "module inscription";
            $action = "Le mutualiste :  $inscription->nom , $inscription->prenom  a  fais son inscription";
            Logs::saveLog($module, $action);
            return redirect()->route('resultatInscription', ['code' => $inscription->code]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module Inscriptions ";
            $action = " Une erreur s'est produite lors de l'enregistrement d'une inscription  " . $th->getMessage();
            Logs::saveLog($module, $action);

            Log::error('Erreur interne du serveur: ' . $th->getMessage());
            return redirect()->back()->withInput();
        }
    }
    public function resultatInscript($code)
    {
        $identification = Inscription::where('code', $code)->where('status', 2)->first();
        if (empty($identification)) {
            $module = ' Inscription deja approuve';
            $action = 'L\'inscription ayant code = ' . $code . 'existe deja ou es introuvable dans la base de donnee';
            Logs::saveLog($module, $action);
            return redirect()->route('accueil')->with('error', 'Liens expirer');
        }
        return view('home.pages.inscriptions.show', compact('identification'));
    }



    public function validationMutualiteApresInscription($code)
    {
        try {

            $mutualiste = Mutualiste::where('code', $code)->first();

            // dd($mutualiste,$code);
            if (empty($mutualiste)) {
                $module = "Activation du lien d'inscription  Mutualiste";
                $action = "Mutualiste non trouvé";
                Logs::saveLog($module, $action);
                // return response()->json(['message' => 'Mutualiste non trouvé'], 404);
                toast('Mutualiste non trouvé', 'error');
                return  redirect()->route('accueil');
            } elseif (!empty($mutualiste->user_id) || empty($mutualiste)) {
                $code = 200;
                $mess = "Desole Monsieur Vous avez deja un compte liens Expiré";
                $module = "Activation du lien d'inscription  Mutualiste";
                $action = "Desole Monsieur Vous avez deja un compte liens Expiré";
                Logs::saveLog($module, $action);
                return view('home.pages.pageError.index', compact('code', 'mess', 'mutualistes'));
            }

            // Vérifiez si le mutualiste contient des données
            if ($mutualiste->exists()) {
                // Si le mutualiste existe et contient des données, redirigez-le vers le formulaire pour compléter les autres informations de compte
                $module = "Activation du lien d'inscription  Mutualiste";
                $action = "Le mutualiste : $mutualiste->nom a activer son lien d'inscriptions";
                Logs::saveLog($module, $action);
                return view('home.pages.inscriptions.acces', compact('mutualiste'));
            } else {
                // Si le mutualiste n'existe pas ou ne contient pas de données, affichez un message d'erreur
                toast('Vous n\'y est pas autorisé', 'error');
                $module = "Activation du lien d'inscription  Mutualiste";
                $action = "Vous n\'y est pas autorisé lien truqué";
                Logs::saveLog($module, $action);
                return redirect()->route('accueil');
            }
        } catch (\Exception $e) {
            // Si une erreur se produit, affichez un message d'erreur
            toast('Une erreur s\'est produite, veuillez réessayer.', 'error');
            Log::error('Erreur interne du serveur: ' . $e->getMessage());

            $module = "Activation du lien d'inscription  Mutualiste";
            $action = "Erreur interne du serveur:" . $e->getMessage();
            Logs::saveLog($module, $action);

            return redirect()->route('accueil');
        }
    }

    public function creatAccesInscrip(CreationAccesInscriptionRequest $request, $id)
    {
        // dd($request->all(), $id);
        try {
            DB::beginTransaction();
            $mutualiste = Mutualiste::findOrFail($id);
            if (empty($mutualiste)) {
                toast('Impossible de créer acces mutualiste', 'error');
                $module = "Module Mutualiste Creation acces ";
                $action = " Impossible de créer acces mutualiste pour : $mutualiste->nom , $mutualiste->prenom";
                Logs::saveLog($module, $action);
                return redirect()->route('accueil');
            }
            $user = new User();
            $user->email = $mutualiste->email;
            $user->password = Hash::make($request->password);
            $user->save();
            $user->assignRole('mutualiste');
            $mutualiste->user_id = $user->id;
            $mutualiste->save();
            DB::commit();
            toast('Mutualiste a creer ses acces  avec succès', 'success');
            return redirect()->route('connexion');

        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module Inscriptions ";
            $action = " Une erreur s'est produite lors de l'enregistrement des acces  " . $th->getMessage();
            Logs::saveLog($module, $action);

            Log::error('Erreur interne du serveur: ' . $th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
