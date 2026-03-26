<?php

namespace App\Http\Controllers;


use App\Models\Logs;
use App\Models\Taxe;
use App\Models\User;
use App\Models\Corps;
use App\Models\Grade;
use App\Models\Ville;
use App\Models\Compte;
use App\Models\Parametre;
use App\Models\TypePiece;
use App\Models\Cotisation;
use App\Models\Mutualiste;
use App\Models\Specialite;
use App\Models\CarteMembre;
use Illuminate\Http\Request;
use App\Models\DroitAdhesion;
use App\Models\FormeJuridique;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use App\Models\CotisationMutualiste;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use Illuminate\Auth\Events\Validated;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreMutualisteRequest;
use App\Http\Requests\UpdateMutualisteRequest;
use App\Http\Requests\MutualisteEspaceUpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Notifications\MailDeValidationDeCompteMutualiste;
use App\Http\Requests\FinaliserInscriptionMutualisteRequest;

class MutualisteController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {

        $mutualistes = Mutualiste::orderBy('created_at', 'DESC')->get();
        // dd($nom);
        $villes = Ville::orderBy('libelle', 'ASC')->get();

        $module = "Module Mutualiste ";
        $action = " a consulté la liste des mutualistes ";
        Logs::saveLog($module, $action);
        return view('dashboard.mutualistes.index', compact('mutualistes', 'villes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $villes = Ville::orderBy('libelle', 'ASC')->get();
        $specialites = Specialite::where('status', 1)->get();
        $module = "Module Mutualiste ";
        $action = " a affiché la page de création d'un mutualiste ";
        Logs::saveLog($module, $action);
        return view('dashboard.mutualistes.create', compact('villes', 'specialites'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMutualisteRequest $request)
    {
        // dd($request->all());

        try {
            DB::beginTransaction();
            // Récupérer les données validées
            $data = $request->validated();

            // Créer un nouveau mutualiste et insérer les données dans la table mutualistes
            $codeP = generateUniqueCode(10);
            // dd($request->all(),$codeP) ;
            $droit_adhesion = $data['droit_adhesion'] ?? $request->droit_adhesion;
            // $carte_membre = $data['carte_membre'] ?? $request->carte_membre;
            $cotisation_annuelle = $data['cotisation_annuelle'] ?? $request->cotisation_annuelle;



            $mutualiste = Mutualiste::create([
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'email' => $data['email'],
                'matricule' => $data['matricule'],
                'contact' => $data['contact'],
                'ville_id' => $data['ville_id'],
                'specialite_id' => $data['specialite_id'],
                'fonction' => $data['fonctions'],
                'typeAdhesion' => 'revision',
                'code' => $codeP,
            ]);
            $module = "Module Mutualiste ";
            $action = " a créé un mutualiste : $mutualiste->nom , $mutualiste->prenom";
            Logs::saveLog($module, $action);
            $verifiercompte = Compte::whereMutualisteId($mutualiste->id)->first();

            // verififier si le mutualiste a deja un compte
            if ($verifiercompte > 0) {
                toast('Impossible de créer ce compte mutualiste', 'error');
                // Rediriger l'utilisateur ou effectuer d'autres actions
                // $response->code = 401;
                $module = "Module Mutualiste Creation compte ";
                $action = " Impossible de créer ce compte mutualiste pour : $mutualiste->nom , $mutualiste->prenom";
                Logs::saveLog($module, $action);
                return redirect()->route('mutualistes.index');
            }

            $compte = Compte::create([
                'type_compte_id' => 2,
                'mutualiste_id' => $mutualiste->id,
                'solde' => 0,
                'quota' => 2000000,
            ]);
            if (!empty($compte)) {
                $module = "Module Mutualiste Creation compte ";
                $action = " a créé le compte mutualiste pour : $mutualiste->nom , $mutualiste->prenom";
                Logs::saveLog($module, $action);
            } else {
                $module = "Module Mutualiste Creation compte ";
                $action = " la creation de  compte mutualiste pour : $mutualiste->nom , $mutualiste->prenom a echoue";
                Logs::saveLog($module, $action);
            }

            $taxeAdhesion = Taxe::findOrFail(1);
            $droitAdhesion = new DroitAdhesion();
            $droitAdhesion->administrateur_id = auth()->user()->administrateur->id;
            $droitAdhesion->mutualiste_id = $mutualiste->id;
            $droitAdhesion->type_paiement_id = 1;
            $droitAdhesion->libelle = $taxeAdhesion->libelle;
            $droitAdhesion->montant = $taxeAdhesion->montant;
            if ($droit_adhesion == 0) {
                $droitAdhesion->status = 2;
            } else {
                $droitAdhesion->admin_pay = auth()->user()->administrateur->id;
                $droitAdhesion->status = 1;
            }
            $droitAdhesion->save();

            if (!empty($droitAdhesion)) {
                $module = "Module Mutualiste Creation compte ";
                $action = " a attribut un droite d'adhesion au  mutualiste  : $mutualiste->nom , $mutualiste->prenom";
                Logs::saveLog($module, $action);
            } else {
                $module = "Module Mutualiste Creation compte ";
                $action = " l' attribut un droite d'adhesion au  mutualiste  : $mutualiste->nom , $mutualiste->prenom a echoue";
                Logs::saveLog($module, $action);
            }
            // carte membre
            // $taxeCarteMembre = Taxe::findOrFail(2);
            $carteMembre = new CarteMembre();
            $carteMembre->administrateur_id = auth()->user()->administrateur->id;
            $carteMembre->mutualiste_id = $mutualiste->id;
            $carteMembre->type_paiement_id = 5;
            $carteMembre->libelle = 'Taxe Carte Membre';
            // $carteMembre->montant = $taxeCarteMembre->montant;
            if ($droit_adhesion == 0) {
                $carteMembre->status = 2;
            } else {
                $carteMembre->admin_pay = auth()->user()->administrateur->id;
                $carteMembre->status = 1;
            }
            $carteMembre->save();
            if (!empty($carteMembre)) {
                $module = "Module Mutualiste Creation compte ";
                $action = " a attribut un redevance de carte membre  au  mutualiste  : $mutualiste->nom , $mutualiste->prenom";
                Logs::saveLog($module, $action);
            } else {
                $module = "Module Mutualiste Creation compte ";
                $action = " l' attribut une redevence de carte membre au  mutualiste  : $mutualiste->nom , $mutualiste->prenom a echoue";
                Logs::saveLog($module, $action);
            }

            // cotisations Annuelle
            $coti = Cotisation::findOrFail(1);
            $cotisation = new CotisationMutualiste();
            $cotisation->administrateur_id = auth()->user()->administrateur->id;
            $cotisation->mutualiste_id = $mutualiste->id;
            $cotisation->cotisation_id = $coti->id ?? 1;
            $cotisation->type_paiement_id = 2;
            $cotisation->montant = $coti->montant_a_payer ?? 120000;
            $cotisation->montant_initial = $coti->montant_a_payer ?? 120000;
            $cotisation->frequence_paiement = $coti->frequence_paiement;
            $cotisation->date_debut = $coti->date_debut;
            $cotisation->date_fin = $coti->date_fin;
            if ($cotisation_annuelle == 1) {
                $cotisation->montant_paye = $coti->montant_a_payer;
                $cotisation->admin_pay = auth()->user()->administrateur->id;
                $cotisation->status = 1;
            }
            $cotisation->save();


            // Générer le lien de validation
            $lienDeValidation = URL::temporarySignedRoute(
                'validation.inscription',
                now()->addHours(24), // Définissez la durée de validité du lien
                ['code' => $mutualiste->code]
            );
            Mutualiste::where('id', $mutualiste->id)->update([
                'lien_email' => $lienDeValidation,
            ]);
            $sujet = "Validation de votre  compte UNAMEPCI";
            $message = "  Bonjour, " . $mutualiste->prenom . ' ' . $mutualiste->nom . "<br>
                        Merci pour la première étape de votre inscription sur UNAMEPCI. <br> Veuillez cliquer sur le boutton ci-dessous pour finaliser votre inscription et valider votre compte. !<br>
                        <div style='margin-top:3px; margin-bottom:3px;  text-align:center;'>
                        <a href=" . $lienDeValidation . " class='bouton'> POURSUIVRE</a> <br>
                        </div>
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
                    Log::ajoutLOG($message);
                    $module = "Envoyer de Mail a la creation Mutualiste";
                    $action = "Echec d'envoyer de mail  : $message";
                    Logs::saveLog($module, $action);
                } else {
                    $module = "Envoyer de Mail a la creation Mutualiste";
                    $action = "Email envoyer avec success   : $mutualiste->nom , $mutualiste->prenom sur son email  $mutualiste->email";
                    Logs::saveLog($module, $action);
                }
            } else {
                Log::error("Erreur lors de l'envoi de l'email. Statut API : " . $retourAPI->status());

                $module = "Envoyer de Mail a la creation Mutualiste";
                $action = "Erreur lors de l'envoi de l'email. Statut API : " . $retourAPI->status();
                Logs::saveLog($module, $action);
            }
            DB::commit();
            // Envoyer un message de succès
            // Alert::success('Succès', 'Mutualiste ajouté avec succès.');
            toast('Mutualiste ajouté avec succès', 'success');

            // Rediriger l'utilisateur ou effectuer d'autres actions
            return redirect()->route('mutualistes.index');
            // return redirect()->route('mutualistes.index')->with('success',"Mutualiste ajouté avec succès.");
        } catch (\Exception $e) {
            DB::rollback();

            // Envoyer un message d'erreur
            // Alert::error('Erreur', 'Une erreur s\'est produite lors de l\'ajout du mutualiste.');
            toast('Une erreur s\'est produite, veuillez réessayer. ', 'error');
            $module = "Module Mutualiste ";
            $action = " Une erreur s'est produite lors de l'enregistrement d'une mutualiste  " . $e->getMessage();
            Logs::saveLog($module, $action);

            // Enregistrer l'erreur dans les journaux
            logger()->error($e);

            // Rediriger l'utilisateur ou effectuer d'autres actions
            return redirect()->back();
            // return redirect()->back()->with('error',"Une erreur s'est produite, veuillez réessayer.".$e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $mutualiste = Mutualiste::findOrFail($id);
        return view('dashboard.mutualistes.show', compact('mutualiste'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mutualiste $mutualiste) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMutualisteRequest $request, Mutualiste $mutualiste) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mutualiste $mutualiste)
    {
        try {

            DB::beginTransaction();
            if ($mutualiste->lien_photo) {
                delete_file($mutualiste->lien_photo);
            }
            if ($mutualiste->user) {
                $mutualiste->user->delete();
            }
            $mutualiste->delete();

            DB::commit();
            $module = "Module Mutualiste";
            $action = "Mutualiste Supprimé avec succès : $mutualiste->nom ";
            Logs::saveLog($module, $action);

            toast('Mutualiste Supprimé avec succès', 'success');
            return redirect()->back();
        } catch (\Exception $e) {
            //throw $th;
            // dd($th);
            DB::rollback();

            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
            // Capturer toute autre exception (erreur 500)
            Log::error('Erreur interne du serveur: ' . $e->getMessage());

            return redirect()->back();
        }
    }

    public function finaliserInscriptionMutualiste(FinaliserInscriptionMutualisteRequest $request, Mutualiste $mutualiste)
    {
        try {
            // dd($request->all(), $mutualiste);
            DB::beginTransaction();
            // Récupérer les données validées
            $module = "Debut d'inscription mutualiste";
            $action = "Le mutualiste a commence son identifications";
            Logs::saveLog($module, $action);

            $data = $request->validated();
            // Créer l'utilisateur
            $user = User::create([
                'email' => $mutualiste->email,
                'password' => Hash::make($data['password'])
            ]);
            if ($user) {
                $module = "Creation d'acces Mutualiste";
                $action = "Le mutualiste a creer ses acces";
                Logs::saveLog($module, $action);
            } else {
                $module = "Creation d'acces Mutualiste";
                $action = "Creation d'acces du mutualiste a echoue";
                Logs::saveLog($module, $action);
            }

            // assign role to user
            $user->assignRole('mutualiste');

            $lien_photo = null;
            $photo_couverture = null;

            $signature = null;
            $photo_identite_1 = null;
            $document_autorisation_ouverture = null;
            $document_carte_inscript_ONMCI = null;
            $pieces_joints_verso = null;
            $pieces_joints_recto = null;

            // if ($request->hasFile("photo_identite_1")) {
            //     // $photo_identite_1 = null;
            //     $file = $request->file("photo_identite_1");

            //     if ($file->isValid()) {
            //         $folder = "DOCSLABELIS";
            //         $photo_identite_1 = createFichiers("$folder/", $file, $file->extension());
            //     }
            // }


            if ($request->hasFile('signature')) {
                $file_name = Carbon::now()->timestamp . '.' . $request->signature->extension();
                $request->signature->storeAs('images-mutualistes/signature/', $file_name);
                $signature = 'src-files/images-mutualistes/signature/' . $file_name;
            }
            if ($request->hasFile('lien_photo')) {
                $file_name = Carbon::now()->timestamp . '.' . $request->lien_photo->extension();
                $request->lien_photo->storeAs('images-mutualistes/', $file_name);
                $lien_photo = 'src-files/images-mutualistes/' . $file_name;
            }
            if ($request->hasFile('photo_couverture')) {
                $cover = Carbon::now()->timestamp . '.' . $request->photo_couverture->extension();
                $request->photo_couverture->storeAs('images-mutualistes/couverture/', $cover);
                $photo_couverture = 'src-files/images-mutualistes/couverture/' . $cover;
            }



            // if ($request->hasFile('photo_identite_1')) {
            //     $cover = Carbon::now()->timestamp . '.' . $request->photo_identite_1->extension();
            //     $request->photo_identite_1->storeAs('images-mutualistes/photo_identite_1/', $cover);
            //     $photo_identite_1 = 'src-files/images-mutualistes/photo_identite_1/' . $cover;
            // }
            // if ($request->hasFile('document_autorisation_ouverture')) {
            //     $cover = Carbon::now()->timestamp . '.' . $request->document_autorisation_ouverture->extension();
            //     $request->document_autorisation_ouverture->storeAs('images-mutualistes/document_autorisation_ouverture/', $cover);
            //     $document_autorisation_ouverture = 'src-files/images-mutualistes/document_autorisation_ouverture/' . $cover;
            // }
            // if ($request->hasFile('document_carte_inscript_ONMCI')) {
            //     $cover = Carbon::now()->timestamp . '.' . $request->document_carte_inscript_ONMCI->extension();
            //     $request->document_carte_inscript_ONMCI->storeAs('images-mutualistes/document_carte_inscript_ONMCI/', $cover);
            //     $document_carte_inscript_ONMCI = 'src-files/images-mutualistes/document_carte_inscript_ONMCI/' . $cover;
            // }




            if ($request->hasFile("document_autorisation_ouverture")) {
                $file = $request->file("document_autorisation_ouverture");
                if ($file->isValid()) {
                    $folder = nonDossierCloud();
                    $document_autorisation_ouverture = createFichiers("$folder/", $file, $file->extension());
                }
            }
            if ($request->hasFile("document_carte_inscript_ONMCI")) {
                $file = $request->file("document_carte_inscript_ONMCI");
                if ($file->isValid()) {
                    $folder = nonDossierCloud();
                    $document_carte_inscript_ONMCI = createFichiers("$folder/", $file, $file->extension());
                }
            }
            if ($request->hasFile("photo_identite_1")) {
                $file = $request->file("photo_identite_1");
                if ($file->isValid()) {
                    $folder = nonDossierCloud();
                    $photo_identite_1 = createFichiers("$folder/", $file, $file->extension());
                }
            }

            if ($request->hasFile('pieces_joints_verso')) {
                $cover = Carbon::now()->timestamp . '.' . $request->pieces_joints_verso->extension();
                $request->pieces_joints_verso->storeAs('images-mutualistes/pieces_joints_verso/', $cover);
                $pieces_joints_verso = 'src-files/images-mutualistes/pieces_joints_verso/' . $cover;
            }
            if ($request->hasFile('pieces_joints_recto')) {
                $cover = Carbon::now()->timestamp . '.' . $request->pieces_joints_recto->extension();
                $request->pieces_joints_recto->storeAs('images-mutualistes/pieces_joints_recto/', $cover);
                $pieces_joints_recto = 'src-files/images-mutualistes/pieces_joints_recto/' . $cover;
            }
            $userId = $user->id;
            $contact_1 = $mutualiste->contact ?? $data['contact'];


            $etre_auteur = $request->etre_auteur ?? 0;
            if ($etre_auteur == 1 || empty($request->nom_auteur)) {
                $valeur = $request->nom_relation ?? $data['nom_relation'];
            } else {
                $valeur = $request->nom_auteur ?? $data['nom_relation'];
                $etre_auteur = 0;
            }

            $mutualiste->update([
                'user_id' => $userId,
                'typeAdhesion' => $data['typeAdhesion'],
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'contact' => $contact_1,
                'contact_2' => $data['contact_2'],
                'fax' => $data['fax'],
                'adresse' => $data['adresse'],
                'civilite' => $data['civilite'],
                'date_naissance' => $data['date_naissance'],
                'lieu_naissance' => $data['lieu_naissance'],
                'nationalite' => $data['nationalite'],
                'situation_matrimoniale' => $data['situation_matrimoniale'],
                'nombre_charge' => $data['nombre_charge'],
                'date_adhesion_unamepci' => $data['date_adhesion_unamepci'],
                'type_piece_id' => $data['type_piece_id'],
                'numero_piece' => $data['numero_piece'],
                'date_etablissement_piece' => $data['date_etablissement_piece'],
                'lieu_etablissement_piece' => $data['lieu_etablissement_piece'],
                'ville_id' => $data['ville_id'],
                'numero_inscription_ONMCI' => $data['numero_inscription_ONMCI'],
                'pseudonyme_recon_ONMCI' => $data['pseudonyme_recon_ONMCI'],
                'raison_social_primaire' => $data['raison_social_primaire'],
                'specialite_id' => $data['specialite_id'],
                'fonction' => $data['fonction'],
                'date_debut_metier' => $data['date_debut_metier'],
                'nombre_annee_experience' => $data['nombre_annee_experience'],
                'nom_employeur_principale' => $data['nom_employeur_principale'],
                'statut_emploi' => $data['statut_emploi'],
                'domaine_activite' => $data['domaine_activite'],
                'date_recrutement' => $data['date_recrutement'],
                'sigle' => $data['sigle'],
                'date_creation' => $data['date_creation'],
                'numero_autorisation' => $data['numero_autorisation'],
                'num_immatriculation' => $data['num_immatriculation'],
                'forme_juridique_id' => $data['forme_juridique_id'],
                'precise_forme_juridique' => $data['precise_forme_juridique'],
                'commune' => $data['commune'],
                'quartier' => $data['quartier'],
                'rue' => $data['rue'],
                'adresse_postale_entreprise' => $data['adresse_postale_entreprise'],
                'localisation_entreprise' => $data['localisation_entreprise'],
                'email_entreprise' => $data['email_entreprise'],
                'telephone_entreprise' => $data['telephone_entreprise'],
                'fax_entreprise' => $data['fax_entreprise'],
                'etre_auteur' => $etre_auteur,
                'nom_auteur' => $valeur,
                'relation_tiers' => $data['relation_tiers'],
                'nom_relation' => $data['nom_relation'] ?? $request->nom_auteur,
                'raison_social_secondaire_freelance' => $data['raison_social_secondaire_freelance'],
                'fonction_occupe_freelance' => $data['fonction_occupe_freelance'],
                'type_contrat_freelance' => $data['type_contrat_freelance'],
                'telephone_freelance' => $data['telephone_freelance'],
                'fax_freelance' => $data['fax_freelance'],
                'localisation_freelance' => $data['localisation_freelance'],
                'adresse_postale_freelance' => $data['adresse_postale_freelance'],
                'domaine_activite_freelance' => $data['domaine_activite_freelance'],

                'lien_photo' => $lien_photo,
                'photo_couverture' => $photo_couverture,
                'photo_identite_1' => $photo_identite_1,
                'document_autorisation_ouverture' => $document_autorisation_ouverture,
                'signature' => $signature,
                'pieces_joints_verso' => $pieces_joints_verso,
                'pieces_joints_recto' => $pieces_joints_recto,
                'document_carte_inscript_ONMCI' => $document_carte_inscript_ONMCI,
                // 'documents' => $documents,
                'status' => 1,
                // 'droite_adhesions_id'=>$data['droite_adhesion_id'],
            ]);
            if (!empty($mutualiste)) {
                $module = "Infos  Mutualiste";
                $action = "Info mutualiste effectuer avec succes Mutualiste :$mutualiste->nom , $mutualiste->prenom";
                Logs::saveLog($module, $action);
            } else {
                $module = "Infos  Mutualiste";
                $action = "Echec lors de l'ajout des information du  Mutualiste :$mutualiste->nom , $mutualiste->prenom";
                Logs::saveLog($module, $action);
            }

            // dd($mutualiste->compte);
            $compteMutualiste = Compte::where('mutualiste_id', $mutualiste->id)->first();
            if ($compteMutualiste) {
                $compteMutualiste->update([
                    'status' => 1,
                ]);
                $module = "Activation compte  Mutualiste";
                $action = "Le compte du  Mutualiste :$mutualiste->nom , $mutualiste->prenom a ete active avec succes";
                Logs::saveLog($module, $action);
            } else {
                toast('Une erreur s\'est produite, veuillez réessayer. ', 'error');
                $module = "Activation compte  Mutualiste";
                $action = "Une error s'est produite lors de l'activation du compte du  Mutualiste :$mutualiste->nom , $mutualiste->prenom  compte introuvable";
                Logs::saveLog($module, $action);
                return redirect()->back();
            }

            $mutualiste->save();

            DB::commit();
            // Envoyer un message de succès
            // Alert::success('Succès', 'Mutualiste ajouté avec succès.');
            toast('Votre compte Mutualiste ajouté avec succès', 'success');

            // Rediriger l'utilisateur ou effectuer d'autres actions
            return redirect()->route('connexion');
        } catch (\Exception $e) {
            DB::rollback();

            // Envoyer un message d'erreur
            // Alert::error('Erreur', 'Une erreur s\'est produite lors de l\'ajout du mutualiste.');
            toast('Une erreur s\'est produite, veuillez réessayer. ', 'error');

            // Capturer toute autre exception (erreur 500)
            $module = "Infos   Mutualiste";
            $action = "Une erreur s\'est produite, veuillez réessayer" . $e->getMessage();
            Logs::saveLog($module, $action);


            // Rediriger l'utilisateur ou effectuer d'autres actions
            return redirect()->back();
        }
    }


    public function validationInscriptionMutualite($code)
    {
        try {
            // Vérifiez si le mutualiste existe
            // $mutualiste = Mutualiste::findOrFail($code);
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

            // $corps = Corps::all();
            // $grades = Grade::all();
            // $typePieces = TypePiece::all();


            $parametre = Parametre::whereId(1)->first();
            $typePieces = TypePiece::where('status', 1)->get();
            $specialites = Specialite::where('status', 1)->get();
            $formeJuridiques = FormeJuridique::where('status', 1)->get();
            $villes = Ville::orderBy('libelle', 'ASC')->get();
            $droitAdhesions = DroitAdhesion::all();
            // Vérifiez si le mutualiste contient des données
            if ($mutualiste->exists()) {
                // Si le mutualiste existe et contient des données, redirigez-le vers le formulaire pour compléter les autres informations de compte
                $module = "Activation du lien d'inscription  Mutualiste";
                $action = "Le mutualiste : $mutualiste->nom a activer son lien d'inscriptions";
                Logs::saveLog($module, $action);
                return view('home.pages.inscription', compact('mutualiste', 'specialites', 'formeJuridiques', 'typePieces', 'villes'));
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
    // public function modificationMutualiste(MutualisteEspaceUpdateRequest $request, $id)
    // {
    //     // recuperation des information

    //     // dd($request->all());
    //     try {

    //         $mutualiste = Mutualiste::find($id);

    //         if (!$mutualiste) {
    //             toast("Une erreur s'est produite, Mutualiste introuvable.", 'error');
    //         } else {
    //             DB::beginTransaction(); //

    //             $files = $request->hasFile('lien_photo');
    //             $couverture = $request->hasFile('photo_couverture');
    //             $lien_photo = null;
    //             $photo_couverture = null;

    //             if ($files) {
    //                 if ($mutualiste->lien_photo) {

    //                     if (File::exists(public_path($mutualiste->lien_photo))) {
    //                         File::delete(public_path($mutualiste->lien_photo));
    //                     }
    //                 }
    //                 $file_name = Carbon::now()->timestamp . '.' . $request->file('lien_photo')->extension();
    //                 $request->lien_photo->storeAs('images-mutualistes/', $file_name);
    //                 $lien_photo = 'src-files/images-mutualistes/' . $file_name;
    //                 $mutualiste->lien_photo = $lien_photo;
    //             }
    //             if ($couverture) {
    //                 if ($mutualiste->photo_couverture) {

    //                     if (File::exists(public_path($mutualiste->photo_couverture))) {
    //                         File::delete(public_path($mutualiste->photo_couverture));
    //                     }
    //                 }

    //                 $file_name = Carbon::now()->timestamp . '.' . $request->file('photo_couverture')->extension();
    //                 $request->photo_couverture->storeAs('images-mutualistes/couverture/', $file_name);
    //                 $photo_couverture = 'src-files/images-mutualistes/couverture/' . $file_name;
    //                 // dd(  $photo_couverture);
    //                 $mutualiste->photo_couverture = $photo_couverture;
    //             }


    //             $mutualiste->ville_id = $request->ville_id;
    //             $mutualiste->type_piece_id = $request->type_piece_id;
    //             $mutualiste->nom = htmlspecialchars($request->nom);
    //             $mutualiste->prenom = htmlspecialchars($request->prenom);
    //             $mutualiste->genre = htmlspecialchars($request->genre);
    //             $mutualiste->date_naissance = htmlspecialchars($request->date_naissance);
    //             $mutualiste->lieu_naissance = htmlspecialchars($request->lieu_naissance);
    //             // $mutualiste->lien_photo = $lien_photo;
    //             $mutualiste->adresse = htmlspecialchars($request->adresse);
    //             $mutualiste->contact = htmlspecialchars($request->contact);
    //             $mutualiste->contact_2 = htmlspecialchars($request->contact_2);
    //             $mutualiste->unite = htmlspecialchars($request->unite);
    //             $mutualiste->numero_piece = htmlspecialchars($request->numero_piece);
    //             $mutualiste->date_etablissement_piece = htmlspecialchars($request->date_etablissement_piece);
    //             $mutualiste->lieu_etablissement_piece = htmlspecialchars($request->lieu_etablissement_piece);
    //             $mutualiste->save();
    //             DB::commit();
    //             toast('Vos Informations ont étées modifiées avec succès !', 'success');

    //             $module = "Espace Mutualiste";
    //             $action = "Le mutualiste :  $mutualiste->nom , $mutualiste->prenom  a  modifier ses informations";
    //             Logs::saveLog($module, $action);
    //             return redirect()->back();
    //         }
    //     } catch (\Throwable $e) {
    //         DB::rollback();

    //         toast("Une erreur s'est produite, veuillez réessayer.", 'error');
    //         Log::error('Erreur interne du serveur: ' . $e->getMessage());
    //         return redirect()->back();
    //     }
    // }

    public function modificationMutualiste(MutualisteEspaceUpdateRequest $request, $id)
    {
        try {
            $mutualiste = Mutualiste::find($id);

            if (!$mutualiste) {
                toast("Une erreur s'est produite, Mutualiste introuvable.", 'error');
                return redirect()->back();
            }

            DB::beginTransaction();

            // Liste des champs de fichiers à gérer
            $fileFields = [
                'lien_photo' => 'images-mutualistes/profil',
                'photo_couverture' => 'images-mutualistes/couverture',
                'pieces_joints_recto' => 'images-mutualistes/pieces',
                'pieces_joints_verso' => 'images-mutualistes/pieces',
            ];
            // 'document_carte_inscript_ONMCI' => 'images-mutualistes/documents',
            // 'document_autorisation_ouverture' => 'images-mutualistes/documents',
            // 'photo_identite_1' => 'images-mutualistes/identite'




            // pour les ficher du cloud
            if ($request->hasFile("document_autorisation_ouverture")) {
                $file = $request->file("document_autorisation_ouverture");
                if ($file->isValid()) {
                    $folder = nonDossierCloud();
                    $mutualiste->document_autorisation_ouverture = createFichiers("$folder/", $file, $file->extension());
                }
            }
            if ($request->hasFile("document_carte_inscript_ONMCI")) {
                $file = $request->file("document_carte_inscript_ONMCI");
                if ($file->isValid()) {
                    $folder = nonDossierCloud();
                    $mutualiste->document_carte_inscript_ONMCI = createFichiers("$folder/", $file, $file->extension());
                }
            }
            if ($request->hasFile("photo_identite_1")) {
                $file = $request->file("photo_identite_1");
                if ($file->isValid()) {
                    $folder = nonDossierCloud();
                    $mutualiste->photo_identite_1 = createFichiers("$folder/", $file, $file->extension());
                }
            }

            // Gestion de chaque fichier
            foreach ($fileFields as $field => $folder) {
                if ($request->hasFile($field)) {
                    // Supprimer l'ancien fichier s'il existe
                    if ($mutualiste->$field && File::exists(public_path($mutualiste->$field))) {
                        File::delete(public_path($mutualiste->$field));
                    }

                    // Générer un nom de fichier unique
                    $file_name = Carbon::now()->timestamp . '_' . uniqid() . '.' . $request->file($field)->getClientOriginalExtension();

                    // Stocker le fichier
                    $request->file($field)->storeAs($folder, $file_name);

                    // Sauvegarder le chemin
                    $mutualiste->$field = 'src-files/' . $folder . '/' . $file_name;
                }
            }

            // Mise à jour des champs texte
            $mutualiste->matricule = htmlspecialchars($request->matricule);
            $mutualiste->civilite = htmlspecialchars($request->civilite);
            $mutualiste->nom = htmlspecialchars($request->nom);
            $mutualiste->prenom = htmlspecialchars($request->prenom);
            $mutualiste->date_naissance = $request->date_naissance;
            $mutualiste->lieu_naissance = htmlspecialchars($request->lieu_naissance);
            $mutualiste->nationalite = htmlspecialchars($request->nationalite);
            $mutualiste->situation_matrimoniale = htmlspecialchars($request->situation_matrimoniale);
            $mutualiste->nombre_charge = $request->nombre_charge;
            $mutualiste->date_adhesion_unamepci = $request->date_adhesion_unamepci;
            $mutualiste->email = htmlspecialchars($request->email);
            $mutualiste->contact = htmlspecialchars($request->contact);
            $mutualiste->contact_2 = htmlspecialchars($request->contact_2);
            $mutualiste->fax = htmlspecialchars($request->fax);
            $mutualiste->ville_id = $request->ville_id;
            $mutualiste->adresse = htmlspecialchars($request->adresse);
            $mutualiste->type_piece_id = $request->type_piece_id;
            $mutualiste->numero_piece = htmlspecialchars($request->numero_piece);
            $mutualiste->date_etablissement_piece = $request->date_etablissement_piece;
            $mutualiste->lieu_etablissement_piece = htmlspecialchars($request->lieu_etablissement_piece);
            $mutualiste->numero_inscription_ONMCI = htmlspecialchars($request->numero_inscription_ONMCI);
            $mutualiste->pseudonyme_recon_ONMCI = htmlspecialchars($request->pseudonyme_recon_ONMCI);
            $mutualiste->raison_social_primaire = htmlspecialchars($request->raison_social_primaire);
            $mutualiste->specialite_id = $request->specialite_id;
            $mutualiste->fonction = htmlspecialchars($request->fonction);
            $mutualiste->date_debut_metier = $request->date_debut_metier;
            $mutualiste->nombre_annee_experience = $request->nombre_annee_experience;
            $mutualiste->nom_employeur_principale = htmlspecialchars($request->nom_employeur_principale);
            $mutualiste->statut_emploi = htmlspecialchars($request->statut_emploi);
            $mutualiste->domaine_activite = htmlspecialchars($request->domaine_activite);
            $mutualiste->date_recrutement = $request->date_recrutement;

            $mutualiste->sigle = htmlspecialchars($request->sigle);
            $mutualiste->date_creation = $request->date_creation;
            $mutualiste->numero_autorisation = htmlspecialchars($request->numero_autorisation);
            $mutualiste->num_immatriculation = htmlspecialchars($request->num_immatriculation);
            $mutualiste->forme_juridique_id = $request->forme_juridique_id;
            $mutualiste->precise_forme_juridique = htmlspecialchars($request->precise_forme_juridique);
            $mutualiste->commune = htmlspecialchars($request->commune);
            $mutualiste->quartier = htmlspecialchars($request->quartier);
            $mutualiste->rue = htmlspecialchars($request->rue);
            $mutualiste->adresse_postale_entreprise = htmlspecialchars($request->adresse_postale_entreprise);
            $mutualiste->localisation_entreprise = htmlspecialchars($request->localisation_entreprise);
            $mutualiste->email_entreprise = htmlspecialchars($request->email_entreprise);
            $mutualiste->telephone_entreprise = htmlspecialchars($request->telephone_entreprise);
            $mutualiste->fax_entreprise = htmlspecialchars($request->fax_entreprise);

            // Gestion des champs boolean (checkbox)
            $mutualiste->relation_tiers = $request->has('relation_tiers') ? 1 : 0;
            $mutualiste->nom_relation = htmlspecialchars($request->nom_relation);
            $mutualiste->etre_auteur = $request->has('etre_auteur') ? 1 : 0;
            $mutualiste->nom_auteur = htmlspecialchars($request->nom_auteur);

            // Travail freelance
            $mutualiste->raison_social_secondaire_freelance = htmlspecialchars($request->raison_social_secondaire_freelance);
            $mutualiste->fonction_occupe_freelance = htmlspecialchars($request->fonction_occupe_freelance);
            $mutualiste->type_contrat_freelance = htmlspecialchars($request->type_contrat_freelance);
            $mutualiste->telephone_freelance = htmlspecialchars($request->telephone_freelance);
            $mutualiste->fax_freelance = htmlspecialchars($request->fax_freelance);
            $mutualiste->localisation_freelance = htmlspecialchars($request->localisation_freelance);
            $mutualiste->adresse_postale_freelance = htmlspecialchars($request->adresse_postale_freelance);
            $mutualiste->domaine_activite_freelance = htmlspecialchars($request->domaine_activite_freelance);

            $mutualiste->save();
            DB::commit();

            toast('Vos Informations ont été modifiées avec succès !', 'success');

            // Logs
            $module = "Espace Mutualiste";
            $action = "Le mutualiste : $mutualiste->nom, $mutualiste->prenom a modifié ses informations";
            Logs::saveLog($module, $action);

            return redirect()->back();
        } catch (\Throwable $e) {
            DB::rollback();

            // Log de l'erreur
            Log::error('Erreur lors de la modification du mutualiste: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            return redirect()->back()->withInput();
        }
    }

    // fonction qui permet de modifier le mot de passe d'un utilisateur

    public function changePasswordMutualiste(Request $request)
    {
        // mes validation
        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        // Vérification du mot de passe actuel
        if (!Hash::check($request->input('password'), Auth::user()->password)) {
            return redirect()->back()->withErrors(['password' => 'Le mot de passe actuel est incorrect.']);
        }
        // Mise à jour du mot de passe
        $user = Auth::user();
        $user->password = Hash::make($request->input('new_password'));
        //   dd($user);
        $user->save();
        $module = "Espace Mutualiste";
        $action = "L'utilisateur : $user->id  a  modifier son mot de passe";
        Logs::saveLog($module, $action);
        toast('Vos Mot de passe a été modifié avec succès !', 'success');
        return redirect()->back();
    }
}
