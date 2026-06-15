<?php

namespace App\Http\Controllers;


use App\Models\Logs;
use App\Models\Taxe;
use App\Models\User;
use App\Models\Corps;
use App\Models\Grade;
use App\Models\Ville;
use App\Models\Compte;
use App\Models\Paiement;
use App\Models\Parametre;
use App\Models\TypePiece;
use App\Models\Cotisation;
use App\Models\Mutualiste;
use App\Models\Specialite;
use App\Models\CarteMembre;
use App\Models\Facturation;
use Illuminate\Http\Request;
use App\Models\DroitAdhesion;
use App\Models\FormeJuridique;
use Illuminate\Support\Carbon;
use App\Models\listeDesProduits;
use App\Models\PaiementInitiale;
use App\Models\STAuthTresorMoney;
use App\Imports\MutualistesImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Models\CotisationMutualiste;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\DemandeAccompagnement;
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

        $mutualistes = Mutualiste::where('status', '<>', 4)->orderBy('created_at', 'DESC')->get();
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
                // now()->addHours(24), // Définissez la durée de validité du lien
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
                'provider' => 'UNAMEPCI <notification@mail.tresormoney.ci>',
                "key_rsa" => '',
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
                    // Log::ajoutLOG($message);
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






            /// envoyer le sms de validation
            try {
                $message = envoyerMessageMutualiste($mutualiste, $lienDeValidation);

                $url = appelApiSMS();

                $headers = [
                    'Environnement' => 'bew', // Remplace par l'environnement approprié
                    'secretKey' => 'jfdiezaophrh90c(_kfjqlm', // Remplace par ta vraie clé secrète
                ];

                $data = [
                    "titre" => "Création de compte UNAMEPCI", // Titre du message
                    "destination" => $mutualiste->contact, // Numéro de téléphone
                    "email" => $mutualiste->email ?? null, // Facultatif
                    "texte" => $message,
                ];

                $retourAPI = Http::withHeaders($headers)->post($url, $data);

                $res = $retourAPI->json();


                if ($retourAPI->status() == 200) {
                    (int)$code = $res['status'];
                    if ($code != 200) {
                        $message = "Une erreur s'est produite " . $code . ", DETAIL: " . messageBrut($res['message']) . " ERR: Envoyer sms avec les accès";
                        // Log::ajoutLOG($message);
                        $module = "Envoyer du sms a la creation du compte Mutualiste";
                        $action = "Echec d'envoyer du sms  : $message";
                        Logs::saveLog($module, $action);
                    } else {
                        $module = "Envoyer du sms a la creation du compte Mutualiste";
                        $action = "Email envoyer avec success   : $mutualiste->nom , $mutualiste->prenom sur son email  $mutualiste->email";
                        Logs::saveLog($module, $action);
                    }
                } else {
                    Log::error("Erreur lors de l'envoi du sms. Statut API : " . $retourAPI->status());

                    $module = "Envoyer du sms a la creation du compte Mutualiste";
                    $action = "Erreur lors de l'envoi du sms. Statut API : " . $retourAPI->status();
                    Logs::saveLog($module, $action);
                }
            } catch (\Throwable $th) {
                //throw $th;
                Log::error("Erreur lors de l'envoi du sms. Statut API : " . $th->getMessage());
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


            // $etre_auteur = $request->etre_auteur ?? 0;
            // if ($etre_auteur == 1 || empty($request->nom_auteur)) {
            //     $valeur = $request->nom_relation ?? $data['nom_relation'];
            // } else {
            //     $valeur = $request->nom_auteur ?? $data['nom_relation'];
            //     $etre_auteur = 0;
            // }

            $mutualiste->update([
                'user_id' => $userId,
                'typeAdhesion' => $data['typeAdhesion'],
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'contact' => $contact_1,
                'contact_2' => $data['contact_2'],
                // 'fax' => $data['fax'],
                'adresse' => $data['adresse'],
                'civilite' => $data['civilite'],
                'date_naissance' => $data['date_naissance'],
                'lieu_naissance' => $data['lieu_naissance'],
                'nationalite' => $data['nationalite'],
                'situation_matrimoniale' => $data['situation_matrimoniale'],
                // 'nombre_charge' => $data['nombre_charge'],
                'date_adhesion_unamepci' => $data['date_adhesion_unamepci'],
                'type_piece_id' => $data['type_piece_id'],
                'numero_piece' => $data['numero_piece'],
                'date_etablissement_piece' => $data['date_etablissement_piece'],
                'date_expiration_piece' => $data['date_expiration_piece'],

                'lieu_etablissement_piece' => $data['lieu_etablissement_piece'],
                'ville_id' => $data['ville_id'],
                'numero_inscription_ONMCI' => $data['numero_inscription_ONMCI'],
                // 'pseudonyme_recon_ONMCI' => $data['pseudonyme_recon_ONMCI'],
                'raison_social_primaire' => $data['raison_social_primaire'],
                'specialite_id' => $data['specialite_id'],
                'fonction' => $data['fonction'],
                'date_debut_metier' => $data['date_debut_metier'],
                'nombre_annee_experience' => $data['nombre_annee_experience'],
                'nom_employeur_principale' => $data['nom_employeur_principale'],

                'niveau_intervention' => $data['niveau_intervention'],
                'precise_intervention' => $data['precise_intervention'],
                'ville_personnel_id' => $data['ville_personnel_id'],
                'commune_personnel' => $data['commune_personnel'],

                'statut_emploi' => $data['statut_emploi'],
                // 'domaine_activite' => $data['domaine_activite'],
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

                // 'etre_auteur' => $etre_auteur,
                // 'nom_auteur' => $valeur,
                // 'relation_tiers' => $data['relation_tiers'],
                // 'nom_relation' => $data['nom_relation'] ?? $request->nom_auteur,
                // 'raison_social_secondaire_freelance' => $data['raison_social_secondaire_freelance'],
                // 'fonction_occupe_freelance' => $data['fonction_occupe_freelance'],
                // 'type_contrat_freelance' => $data['type_contrat_freelance'],
                // 'telephone_freelance' => $data['telephone_freelance'],
                // 'fax_freelance' => $data['fax_freelance'],
                // 'localisation_freelance' => $data['localisation_freelance'],
                // 'adresse_postale_freelance' => $data['adresse_postale_freelance'],
                // 'domaine_activite_freelance' => $data['domaine_activite_freelance'],

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
            // $droitAdhesions = DroitAdhesion::all();
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
            $mutualiste = Mutualiste::findOrFail($id);

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
            // $mutualiste->nombre_charge = $request->nombre_charge;
            $mutualiste->date_adhesion_unamepci = $request->date_adhesion_unamepci;
            $mutualiste->email = htmlspecialchars($request->email);
            $mutualiste->contact = htmlspecialchars($request->contact);
            $mutualiste->contact_2 = htmlspecialchars($request->contact_2);

            // $mutualiste->fax = htmlspecialchars($request->fax);

            $mutualiste->ville_id = $request->ville_id;
            $mutualiste->adresse = htmlspecialchars($request->adresse);
            $mutualiste->type_piece_id = $request->type_piece_id;
            $mutualiste->numero_piece = htmlspecialchars($request->numero_piece);
            $mutualiste->date_etablissement_piece = $request->date_etablissement_piece;
            $mutualiste->date_expiration_piece = $request->date_expiration_piece;
            $mutualiste->lieu_etablissement_piece = htmlspecialchars($request->lieu_etablissement_piece);
            $mutualiste->numero_inscription_ONMCI = htmlspecialchars($request->numero_inscription_ONMCI);
            // $mutualiste->pseudonyme_recon_ONMCI = htmlspecialchars($request->pseudonyme_recon_ONMCI);
            $mutualiste->raison_social_primaire = htmlspecialchars($request->raison_social_primaire);
            $mutualiste->specialite_id = $request->specialite_id;
            $mutualiste->fonction = htmlspecialchars($request->fonction);
            $mutualiste->date_debut_metier = $request->date_debut_metier;
            $mutualiste->nombre_annee_experience = $request->nombre_annee_experience;
            $mutualiste->nom_employeur_principale = htmlspecialchars($request->nom_employeur_principale);
            $mutualiste->statut_emploi = htmlspecialchars($request->statut_emploi);
            // $mutualiste->domaine_activite = htmlspecialchars($request->domaine_activite);
            $mutualiste->date_recrutement = $request->date_recrutement;

            $mutualiste->niveau_intervention = $request->niveau_intervention;
            $mutualiste->precise_intervention = $request->precise_intervention;
            $mutualiste->commune_personnel = $request->commune_personnel;
            $mutualiste->ville_personnel_id = $request->ville_personnel_id;

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
            // $mutualiste->relation_tiers = $request->has('relation_tiers') ? 1 : 0;
            // $mutualiste->nom_relation = htmlspecialchars($request->nom_relation);
            // $mutualiste->etre_auteur = $request->has('etre_auteur') ? 1 : 0;
            // $mutualiste->nom_auteur = htmlspecialchars($request->nom_auteur);

            // Travail freelance
            // $mutualiste->raison_social_secondaire_freelance = htmlspecialchars($request->raison_social_secondaire_freelance);
            // $mutualiste->fonction_occupe_freelance = htmlspecialchars($request->fonction_occupe_freelance);
            // $mutualiste->type_contrat_freelance = htmlspecialchars($request->type_contrat_freelance);
            // $mutualiste->telephone_freelance = htmlspecialchars($request->telephone_freelance);
            // $mutualiste->fax_freelance = htmlspecialchars($request->fax_freelance);
            // $mutualiste->localisation_freelance = htmlspecialchars($request->localisation_freelance);
            // $mutualiste->adresse_postale_freelance = htmlspecialchars($request->adresse_postale_freelance);
            // $mutualiste->domaine_activite_freelance = htmlspecialchars($request->domaine_activite_freelance);

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

    // reenvoyer le lien d'inscription
    // public function resendLinkInscription($id)
    // {
    //     try {
    //         $mutualiste = Mutualiste::findOrFail($id);

    //         if (!$mutualiste) {
    //             toast('Mutualiste non trouvé', 'error');
    //             return redirect()->back();
    //         }

    //         $lienDeValidation = route('validation.inscription', ['code' => $mutualiste->code]) ?? $mutualiste->lien_email;
    //         $sujet = "Validation de votre  compte UNAMEPCI";
    //         $message = "  Bonjour, " . $mutualiste->prenom . ' ' . $mutualiste->nom . "<br>
    //                     Merci pour la première étape de votre inscription sur UNAMEPCI. <br> Veuillez cliquer sur le boutton ci-dessous pour finaliser votre inscription et valider votre compte. !<br>
    //                     <div style='margin-top:3px; margin-bottom:3px;  text-align:center;'>
    //                     <a href=" . $lienDeValidation . " class='bouton'> POURSUIVRE</a> <br>
    //                     </div>
    //                            Merci d'utiliser notre plateforme! <br>
    //                     Si vous rencontrez des problèmes avec votre compte, n'hésitez pas à nous contacter.
    //                             ";
    //         $url = appelApiEmail();
    //         $template = View::make('home.admin.paiements.paiementAdhesion', ['contenumess' => $message])->render();
    //         $data = [
    //             'provider' => 'UNAMEPCI <info@mail-taseti.com>',
    //             "key_rsa" => 're_2i7H3Ynf_KRVm9VwTsrwrfF8isCBYvyyE',
    //             "destination" => $mutualiste->email,
    //             "sujet" => $sujet,
    //             "message" => $template
    //         ];
    //         $retourAPI = Http::post($url, $data);
    //         $res = $retourAPI->json();
    //         if ($retourAPI->status() == 200) {
    //             (int)$code = $res['status'];
    //             if ($code != 200) {
    //                 $message = "Une erreur s'est produite " . $code . ", DETAIL: " . messageBrut($res['message']) . " ERR: Envoye Paiement adhesion";
    //                 // Log::ajoutLOG($message);
    //                 $module = "Envoyer de Mail a la creation Mutualiste";
    //                 $action = "Echec d'envoyer de mail  : $message";
    //                 Logs::saveLog($module, $action);
    //             } else {
    //                 $module = "Envoyer de Mail a la creation Mutualiste";
    //                 $action = "Email envoyer avec success   : $mutualiste->nom , $mutualiste->prenom sur son email  $mutualiste->email";
    //                 Logs::saveLog($module, $action);
    //             }
    //         } else {
    //             Log::error("Erreur lors de l'envoi de l'email. Statut API : " . $retourAPI->status());

    //             $module = "Envoyer de Mail a la creation Mutualiste";
    //             $action = "Erreur lors de l'envoi de l'email. Statut API : " . $retourAPI->status();
    //             Logs::saveLog($module, $action);
    //         }
    //         toast('Le lien d\'inscription a été renvoyé avec succès.', 'success');
    //         return redirect()->back();
    //     } catch (\Exception $e) {
    //         Log::error('Erreur lors du renvoi du lien d\'inscription: ' . $e->getMessage());
    //         toast('Une erreur s\'est produite, veuillez réessayer.', 'error');

    //         $module = "Envoyer de Mail a la creation Mutualiste";
    //         $action = "Erreur lors du renvoi du lien d\'inscription " . $e->getMessage();
    //         Logs::saveLog($module, $action);
    //         return redirect()->back();
    //     }
    // }


    // reenvoyer le lien d'inscription
    public function resendLinkInscription($id)
    {
        try {

            // Recherche du mutualiste
            $mutualiste = Mutualiste::findOrFail($id);

            if (!$mutualiste) {

                toast('Mutualiste non trouvé', 'error');

                Logs::saveLog(
                    "Envoyer de Mail a la creation Mutualiste",
                    "Mutualiste introuvable ID : " . $id
                );

                return redirect()->back();
            }

            // Vérification email
            if (empty($mutualiste->email)) {

                toast('Aucun email trouvé pour ce mutualiste', 'error');

                Logs::saveLog(
                    "Envoyer de Mail a la creation Mutualiste",
                    "Email vide pour le mutualiste ID : " . $mutualiste->id
                );

                return redirect()->back();
            }

            // Génération du lien
            $lienDeValidation = route(
                'validation.inscription',
                ['code' => $mutualiste->code]
            );

            if (empty($lienDeValidation)) {

                toast('Lien de validation introuvable', 'error');

                Logs::saveLog(
                    "Envoyer de Mail a la creation Mutualiste",
                    "Lien de validation vide pour le mutualiste ID : " . $mutualiste->id
                );

                return redirect()->back();
            }

            // Sujet
            $sujet = "Validation de votre compte UNAMEPCI";

            // Message HTML
            $message = "
            Bonjour {$mutualiste->prenom} {$mutualiste->nom}, <br><br>

            Merci pour la première étape de votre inscription sur UNAMEPCI. <br>

            Veuillez cliquer sur le bouton ci-dessous pour finaliser votre inscription et valider votre compte.<br><br>

            <div style='margin-top:3px; margin-bottom:3px; text-align:center;'>
                <a href='{$lienDeValidation}' class='bouton'>
                    POURSUIVRE
                </a>
            </div>

            <br>

            Merci d'utiliser notre plateforme ! <br>

            Si vous rencontrez des problèmes avec votre compte,
            n'hésitez pas à nous contacter.
        ";

            // Génération du template
            try {

                $template = View::make(
                    'home.admin.paiements.paiementAdhesion',
                    ['contenumess' => $message]
                )->render();
            } catch (\Exception $e) {

                Log::error("Erreur template email : " . $e->getMessage());

                toast('Erreur de génération du template email', 'error');

                Logs::saveLog(
                    "Envoyer de Mail a la creation Mutualiste",
                    "Erreur template : " . $e->getMessage()
                );

                return redirect()->back();
            }

            // URL API
            $url = appelApiEmail();

            if (empty($url)) {

                toast('Configuration API email introuvable', 'error');

                Logs::saveLog(
                    "Envoyer de Mail a la creation Mutualiste",
                    "URL API email vide"
                );

                return redirect()->back();
            }

            // Données API
            $data = [
                'provider' => 'UNAMEPCI <notification@mail.tresormoney.ci>',
                'key_rsa' => '',
                'destination' => $mutualiste->email,
                'sujet' => $sujet,
                'message' => $template
            ];

            // Appel API
            try {

                $retourAPI = Http::timeout(30)->post($url, $data);
            } catch (\Exception $e) {

                Log::error("Erreur connexion API Email : " . $e->getMessage());

                toast('Impossible de contacter le serveur email', 'error');

                Logs::saveLog(
                    "Envoyer de Mail a la creation Mutualiste",
                    "Erreur connexion API : " . $e->getMessage()
                );

                return redirect()->back();
            }

            // Vérification statut HTTP
            if (!$retourAPI->successful()) {

                Log::error(
                    "Erreur API Email HTTP : " .
                        $retourAPI->status()
                );

                toast('Erreur lors de l\'envoi de l\'email', 'error');

                Logs::saveLog(
                    "Envoyer de Mail a la creation Mutualiste",
                    "Erreur HTTP API : " . $retourAPI->status()
                );

                return redirect()->back();
            }

            // Réponse JSON
            $res = $retourAPI->json();

            if (!$res) {

                toast('Réponse API invalide', 'error');

                Logs::saveLog(
                    "Envoyer de Mail a la creation Mutualiste",
                    "Réponse API vide ou invalide"
                );

                return redirect()->back();
            }

            // Status API
            $code = $res['status'] ?? 500;

            // Message API
            $messageErreur = '';

            if (isset($res['message'])) {

                if (is_array($res['message'])) {

                    $messageErreur = messageBrut($res['message']);
                } else {

                    $messageErreur = $res['message'];
                }
            }

            // Vérification succès API
            if ((int)$code !== 200) {

                $messageLog = "Erreur API : "
                    . $code
                    . " DETAIL : "
                    . $messageErreur;

                Log::error($messageLog);

                Logs::saveLog(
                    "Envoyer de Mail a la creation Mutualiste",
                    $messageLog
                );

                toast('Erreur lors de l\'envoi du mail', 'error');

                return redirect()->back();
            }

            // Succès
            Logs::saveLog(
                "Envoyer de Mail a la creation Mutualiste",
                "Email envoyé avec succès à "
                    . $mutualiste->email
            );

            toast(
                'Le lien d\'inscription a été renvoyé avec succès.',
                'success'
            );

            return redirect()->back();
        } catch (\Exception $e) {

            Log::error(
                'Erreur lors du renvoi du lien d\'inscription : '
                    . $e->getMessage()
            );

            Logs::saveLog(
                "Envoyer de Mail a la creation Mutualiste",
                "Erreur générale : " . $e->getMessage()
            );

            toast(
                'Une erreur s\'est produite, veuillez réessayer.',
                'error'
            );

            return redirect()->back();
        }
    }



    public function importerListeMutualistes()
    {
        $taxeMontant = Taxe::where('id', 1)->value('montant') ?? 0;
        // dd($taxeMontant);
        $module = "Module Mutualiste ";
        $action = " a affiché la page pour importer des données ";
        Logs::saveLog($module, $action);

        return view('dashboard.mutualistes.importer', compact('taxeMontant'));
    }








    public function initier(Request $request)
    {
        // Validation (on retire 'montant' si vous voulez le calcul auto)
        $validator = Validator::make($request->all(), [
            // 'montant' => 'required|numeric|min:1000',  // Plus nécessaire si calcul auto
            'nombre_membres'     => 'required|integer|min:1',
            'description'        => 'nullable|string|max:255',
            'type_paiement'      => 'required|string|in:adhesion',
            'tresormoney_numero' => 'required|string|size:10',
            'fichier'            => 'required|mimes:xlsx,xls',
        ]);
        // dd($request->all());

        if ($validator->fails()) {
            toast("Veuillez charger un fichier valide svp!", "error");
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $isAjax = $request->ajax() || $request->wantsJson();

        try {
            DB::beginTransaction();

            // ---------- Lecture fichier ----------
            $file = $request->file('fichier');
            $rows = Excel::toArray([], $file);
            if (empty($rows) || empty($rows[0])) {
                throw new \Exception("Le fichier est vide ou illisible.");
            }
            $sheet = $rows[0];
            if (count($sheet) < 2) {
                throw new \Exception("Le fichier ne contient pas assez de lignes (en-tête + données).");
            }

            // Normalisation de l'en-tête
            $header = array_shift($sheet);
            $header = array_map(function ($cell) {
                return trim(mb_strtolower($cell));
            }, $header);

            // Alias des colonnes autorisées
            $requiredColumns = [
                'numero_matricule' => ['numero_matricule', 'matricule', 'num_matricule'],
                'noms'             => ['noms', 'nom', 'last_name', 'nom_famille'],
                'prenoms'          => ['prenoms', 'prenom', 'first_name'],
                'contact'          => ['contact', 'telephone', 'tel', 'phone', 'mobile'],
                'email'            => ['email', 'courriel', 'mail'],
            ];

            $colIndex = [];
            $missing = [];
            foreach ($requiredColumns as $field => $aliases) {
                $found = false;
                foreach ($aliases as $alias) {
                    $idx = array_search($alias, $header);
                    if ($idx !== false) {
                        $colIndex[$field] = $idx;
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $missing[] = $field . ' (alias : ' . implode(', ', $aliases) . ')';
                }
            }
            if (!empty($missing)) {
                throw new \Exception("Colonnes manquantes ou mal nommées dans le fichier : " . implode(', ', $missing));
            }

            $codePaiement = generateCode2('Ref');
            $mutualistesCrees = [];
            $lignesIgnorees = 0;
            $doublonsExistants = 0;

            // ---------- Parcours des lignes ----------
            foreach ($sheet as $rowIndex => $row) {
                $matricule = trim($row[$colIndex['numero_matricule']] ?? '');
                $nom       = trim($row[$colIndex['noms']] ?? '');
                $prenom    = trim($row[$colIndex['prenoms']] ?? '');
                $contact   = trim($row[$colIndex['contact']] ?? '');
                $email     = trim($row[$colIndex['email']] ?? '');

                if (empty($matricule) || empty($nom) || empty($prenom) || empty($contact) || empty($email)) {
                    $lignesIgnorees++;
                    continue;
                }

                $contactClean = cleanPhoneNumber($contact);

                // Vérifier doublon dans BDD
                $existant = Mutualiste::where('matricule', $matricule)
                    ->orWhere('contact', $contactClean)
                    ->exists();
                if ($existant) {
                    $doublonsExistants++;
                    continue;
                }

                // Création
                $mutualiste = Mutualiste::create([
                    'matricule' => $matricule,
                    'nom'       => $nom,
                    'prenom'    => $prenom,
                    'contact'   => $contactClean,
                    'email'     => $email,
                    'code'      => generateUniqueCode(10),
                    'codePlay'      => $codePaiement,
                    'status'    => 4,
                ]);
                $mutualistesCrees[] = $mutualiste->id;

                Compte::create([
                    'type_compte_id' => 2,
                    'mutualiste_id'  => $mutualiste->id,
                    'solde'          => 0,
                    'quota'          => 2000000,
                ]);

                $taxe = Taxe::where('id', 1)->first();
                DroitAdhesion::create([
                    'administrateur_id' => auth()->user()->administrateur->id,
                    'mutualiste_id'     => $mutualiste->id,
                    'type_paiement_id'  => 1,
                    'libelle'           => $taxe->libelle ?? "Droit d'adhésion",
                    'montant'           => $taxe->montant ?? 10000,
                    'status'            => 2,
                ]);

                CarteMembre::create([
                    'administrateur_id' => auth()->user()->administrateur->id,
                    'mutualiste_id'     => $mutualiste->id,
                    'type_paiement_id'  => 5,
                    'libelle'           => 'Taxe Carte Membre',
                    'status'            => 2,
                ]);





                /// envoyer de mail


                // $lienDeValidation = URL::signedRoute(
                //     'validation.inscription',
                //     ['code' => $mutualiste->code]
                // );
                // Mutualiste::where('id', $mutualiste->id)->update([
                //     'lien_email' => $lienDeValidation,
                // ]);
                // $sujet = "Validation de votre  compte UNAMEPCI";
                // $message = "  Bonjour, " . $mutualiste->prenom . ' ' . $mutualiste->nom . "<br>
                //         Merci pour la première étape de votre inscription sur UNAMEPCI. <br> Veuillez cliquer sur le boutton ci-dessous pour finaliser votre inscription et valider votre compte. !<br>
                //         <div style='margin-top:3px; margin-bottom:3px;  text-align:center;'>
                //         <a href=" . $lienDeValidation . " class='bouton'> POURSUIVRE</a> <br>
                //         </div>
                //                Merci d'utiliser notre plateforme! <br>
                //         Si vous rencontrez des problèmes avec votre compte, n'hésitez pas à nous contacter.
                //                 ";
                // $url = appelApiEmail();
                // $template = View::make('home.admin.paiements.paiementAdhesion', ['contenumess' => $message])->render();
                // $data = [
                //     'provider' => '',
                //     "key_rsa" => '',
                //     "destination" => $mutualiste->email,
                //     "sujet" => $sujet,
                //     "message" => $template
                // ];
                // $retourAPI = Http::post($url, $data);
                // $res = $retourAPI->json();
                // if ($retourAPI->status() == 200) {
                //     (int)$code = $res['status'];
                //     if ($code != 200) {
                //         $message = "Une erreur s'est produite " . $code . ", DETAIL: " . messageBrut($res['message']) . " ERR: Envoye Paiement adhesion";
                //         // Log::ajoutLOG($message);
                //         $module = "Envoyer de Mail a la creation Mutualiste";
                //         $action = "Echec d'envoyer de mail  : $message";
                //         Logs::saveLog($module, $action);
                //     } else {
                //         $module = "Envoyer de Mail a la creation Mutualiste";
                //         $action = "Email envoyer avec success   : $mutualiste->nom , $mutualiste->prenom sur son email  $mutualiste->email";
                //         Logs::saveLog($module, $action);
                //     }
                // } else {
                //     Log::error("Erreur lors de l'envoi de l'email. Statut API : " . $retourAPI->status());

                //     $module = "Envoyer de Mail a la creation Mutualiste";
                //     $action = "Erreur lors de l'envoi de l'email. Statut API : " . $retourAPI->status();
                //     Logs::saveLog($module, $action);
                // }
            }

            if (empty($mutualistesCrees)) {
                throw new \Exception("Aucun membre n'a pu être importé. Vérifiez les données (doublons ou champs vides).");
            }

            $nbMembres = count($mutualistesCrees) ?? $request->input('nombre_membres');

            $taxes = Taxe::where('id', 1)->first();

            $montantTotal = $nbMembres * ($taxes->montant ?? 10000); // 10 000 FCFA par membre

            // ---------- Paiement TresorMoney ----------
            $auth = new STAuthTresorMoney();
            $auth->Key    = env('KEY_AUTH_TREMO');
            $auth->Secret = env('SECRET_AUTH_TREMO');

            Logs::saveLog("Module Paiement", "debut authentification tresormoney");
            $responseReq = Http::post(env('URL_AUTHENTIF'), $auth);
            $retourauth  = json_decode($responseReq->body());
            Logs::saveLog("Module Paiement", "retour authentification: " . $responseReq->body());

            if ($responseReq->status() !== 200 || ($retourauth->code ?? 0) !== 200) {
                $msg = $retourauth->sMessage ?? 'Erreur d\'authentification';
                Logs::saveLog("Module Paiement", "Erreur auth: $msg");
                DB::rollBack();
                if ($isAjax) return response()->json(['success' => false, 'message' => $msg], 422);
                return view('dashboard.pageErreurs.index', ['code' => $retourauth->code ?? 500, 'mess' => "<p>$msg</p>"]);
            }

            // Préparation des produits
            $infosProduits = new listeDesProduits();
            $infosProduits->LibelleProduit       = "Droit d'adhesion (x$nbMembres)";
            $infosProduits->Montant              = $montantTotal;
            $infosProduits->nEstUnServicePrive   = 0;
            $infosProduits->TypeProduit          = 1;
            $infosProduits->Quantite             = 1;
            $infosProduits->IdProduit            = 0;
            $infosProduits->Reference_code_Produit = "";

            $us = auth()->user()->administrateur;

            $paiementinit = PaiementInitiale::create([
                'code_paiement'    => $codePaiement,
                'type_paiement_id' => 1,
                'montant_initial'  => $montantTotal,
                'contact_paiement' => cleanPhoneNumber($request->tresormoney_numero),
            ]);

            // Lien pivot (à adapter selon votre schéma)
            // foreach ($mutualistesCrees as $mutId) {
            //     DB::table('paiement_initial_mutualiste')->insert([
            //         'paiement_initial_id' => $paiementinit->id,
            //         'mutualiste_id'       => $mutId,
            //         'created_at'          => now(),
            //     ]);
            // }

            $infosbeneficiaire = [
                'Credentiel' => env('HUB_KEY_TREMO_BMI'),
                'produits'   => [$infosProduits]
            ];

            $infospaiement = [
                'Url_callback'   => urlCallbackLienAdministrateurMembre(),
                'Nom_usager'     => $us->nom ?? 'xxxxxxx',
                'Prenom_usager'  => $us->prenom ?? 'xxxxxxx',
                'code_paiement'  => $codePaiement,
                'Email'          => $us->email ?? 'administrateur@gmail.com',
                'Telephone'      => $paiementinit->contact_paiement,
                'Additif'        => $codePaiement,
                'Token'          => $retourauth->Token,
                'TypeOperation'  => 1,
                'TCredentiel'    => [$infosbeneficiaire],
            ];

            Logs::saveLog("Module Paiement", "debut initiation transaction: " . json_encode($infospaiement));
            $responseReq = Http::post(env('URL_INITIATE'), $infospaiement);
            $retourReq = json_decode($responseReq->body());
            Logs::saveLog("Module Paiement", "retour initiation: " . $responseReq->body());

            if ($responseReq->status() !== 200) {
                $msg = "Echec d'initiation transaction TresorMoney, impossible de joindre l'hôte.";
                Logs::saveLog("Module Paiement", $msg);
                DB::rollBack();
                if ($isAjax) return response()->json(['success' => false, 'message' => $msg], 500);
                return view('dashboard.pageErreurs.index', ['code' => $responseReq->status(), 'mess' => "<p>$msg</p>"]);
            }

            if (($retourReq->code ?? 0) !== 200) {
                $msg = $retourReq->cleretour ?? 'Erreur d\'initiation';
                Logs::saveLog("Module Paiement", "Erreur initiation: $msg");
                DB::rollBack();
                if ($isAjax) return response()->json(['success' => false, 'message' => $msg], 422);
                return view('dashboard.pageErreurs.index', ['code' => $retourReq->code ?? 500, 'mess' => "<p>$msg</p>"]);
            }

            $message = "L'opération a été initiée sur le numéro " . $paiementinit->contact_paiement . ". " . ($retourReq->cleretour ?? '');

            DB::commit();

            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'code_paiement' => $codePaiement,
                    'nb_membres_importes' => $nbMembres,
                    'redirect' => route('adhesionRelance', ['codePaiement' => $codePaiement, 'ind' => 1])
                ]);
            }

            return redirect()->route('adhesionRelance', ['codePaiement' => $codePaiement, 'ind' => 1])
                ->with('success', $message . " ($nbMembres membres importés)");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur paiement en ligne: ' . $e->getMessage());
            Logs::saveLog("Module Paiement", "Exception: " . $e->getMessage());

            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'Erreur : ' . $e->getMessage()], 500);
            }

            toast("Erreur lors du paiement en ligne : " . $e->getMessage(), "error");
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }


    public function resulPayRelance($codePaiement, $ind)
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
            return view('dashboard.mutualistes.replay', compact('paiementinit', 'mess', 'code',  'ind', 'codePaiement'));
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







    public function verifierDoublons(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fichier' => 'required|mimes:xlsx,xls',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Fichier invalide.',
            ], 422);
        }

        try {
            $data = Excel::toCollection(new MutualistesImport(), $request->file('fichier'));

            if ($data->isEmpty() || $data->first()->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le fichier est vide.',
                ], 422);
            }

            $rows = $data->first();
            $resultats = [
                'total' => 0,
                'nouveaux' => 0,
                'existants' => 0,
                'details' => [],
                'lignes_existantes' => []
            ];

            // 🔍 DEBUG : Voir la structure exacte
            if ($rows->isNotEmpty()) {
                $premiereLigne = $rows->first();
                Log::info('=== DEBUG API VERIFICATION ===');
                Log::info('Type de la ligne: ' . gettype($premiereLigne));
                if (is_object($premiereLigne)) {
                    Log::info('Classe: ' . get_class($premiereLigne));
                    Log::info('Méthodes disponibles: ' . implode(', ', get_class_methods($premiereLigne)));
                }
                Log::info('Valeurs brutes:', (array) $premiereLigne);
            }

            foreach ($rows as $lineNumber => $row) {
                $resultats['total']++;

                // Convertir en tableau proprement
                $rowArray = $this->rowToArray($row);

                // Récupérer les valeurs
                $matricule = $this->getValue($rowArray, ['numero_matricule', 'matricule']);
                $contact   = $this->getValue($rowArray, ['contact']);
                $email     = $this->getValue($rowArray, ['email']);
                $nom       = $this->getValue($rowArray, ['noms', 'nom']);
                $prenom    = $this->getValue($rowArray, ['prenoms', 'prenom']);

                // Si toujours null, essayer par index numérique
                if (empty($matricule) && empty($nom)) {
                    $values = array_values($rowArray);
                    $matricule = $values[0] ?? null;
                    $nom       = $values[1] ?? null;
                    $prenom    = $values[2] ?? null;
                    $contact   = $values[3] ?? null;
                    $email     = $values[4] ?? null;
                }

                // S'assurer que ce sont des strings
                $matricule = is_string($matricule) ? trim($matricule) : (string) $matricule;
                $nom = is_string($nom) ? trim($nom) : (string) $nom;
                $prenom = is_string($prenom) ? trim($prenom) : (string) $prenom;
                $contact = is_string($contact) ? trim($contact) : (string) $contact;
                $email = is_string($email) ? trim($email) : (string) $email;

                // Nettoyer le contact
                $contactClean = $this->cleanPhoneNumber($contact);

                // Vérifier si le matricule existe déjà
                $existeParMatricule = !empty($matricule) ? Mutualiste::where('matricule', $matricule)->exists() : false;

                // Vérifier si le contact existe déjà
                $existeParContact = !empty($contactClean) ? Mutualiste::where('contact', $contactClean)->exists() : false;

                // Vérifier si l'email existe déjà
                $existeParEmail = !empty($email) ? Mutualiste::where('email', $email)->exists() : false;

                $raisons = [];
                if ($existeParMatricule) $raisons[] = 'matricule';
                if ($existeParContact) $raisons[] = 'contact';
                if ($existeParEmail) $raisons[] = 'email';

                $fullName = trim($nom . ' ' . $prenom) ?: 'Nom inconnu';

                if (!empty($raisons)) {
                    $resultats['existants']++;
                    $resultats['lignes_existantes'][] = [
                        'ligne' => $lineNumber + 2,
                        'matricule' => $matricule ?: 'N/A',
                        'nom' => $fullName,
                        'contact' => $contact,
                        'email' => $email,
                        'raisons' => $raisons
                    ];
                } else {
                    $resultats['nouveaux']++;
                    $resultats['details'][] = [
                        'matricule' => $matricule ?: 'N/A',
                        'nom' => $fullName
                    ];
                }
            }

            Log::info("Résultat vérification - Total: {$resultats['total']}, Nouveaux: {$resultats['nouveaux']}, Existants: {$resultats['existants']}");

            return response()->json([
                'success' => true,
                'data' => $resultats
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la vérification: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la vérification : ' . $e->getMessage()
            ], 500);
        }
    }




    // public function verifierDoublons(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'fichier' => 'required|mimes:xlsx,xls',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Fichier invalide.',
    //         ], 422);
    //     }

    //     try {
    //         $data = Excel::toCollection(new MutualistesImport(), $request->file('fichier'));

    //         if ($data->isEmpty() || $data->first()->isEmpty()) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Le fichier est vide.',
    //             ], 422);
    //         }

    //         $rows = $data->first();

    //         // Récupérer le montant de la taxe depuis la base
    //         $taxe = Taxe::where('id', 1)->first();
    //         $taxeMontant = $taxe->montant ?? 10000;

    //         $resultats = [
    //             'total' => 0,
    //             'nouveaux' => 0,
    //             'existants' => 0,
    //             'taxe_montant' => $taxeMontant,
    //             'montant_total' => 0,
    //             'details_nouveaux' => [],
    //             'lignes_existantes' => []
    //         ];

    //         foreach ($rows as $lineNumber => $row) {
    //             // Convertir proprement en tableau simple
    //             $rowArray = [];
    //             foreach ((array) $row as $key => $value) {
    //                 // Nettoyer la clé
    //                 $cleanKey = str_replace("\0*\0", '', $key);
    //                 $cleanKey = str_replace("\0", '', $cleanKey);

    //                 // Extraire la valeur scalaire
    //                 if (is_object($value)) {
    //                     if (method_exists($value, '__toString')) {
    //                         $rowArray[$cleanKey] = (string) $value;
    //                     } elseif (method_exists($value, 'getValue')) {
    //                         $rowArray[$cleanKey] = (string) $value->getValue();
    //                     } else {
    //                         $rowArray[$cleanKey] = json_encode($value);
    //                     }
    //                 } elseif (is_array($value)) {
    //                     $rowArray[$cleanKey] = implode(', ', $value);
    //                 } else {
    //                     $rowArray[$cleanKey] = (string) $value;
    //                 }
    //             }

    //             $resultats['total']++;

    //             // Récupérer les valeurs
    //             $matricule = $rowArray['numero_matricule'] ?? $rowArray['matricule'] ?? '';
    //             $contact   = $rowArray['contact'] ?? '';
    //             $email     = $rowArray['email'] ?? '';
    //             $nom       = $rowArray['noms'] ?? $rowArray['nom'] ?? '';
    //             $prenom    = $rowArray['prenoms'] ?? $rowArray['prenom'] ?? '';

    //             // Nettoyer
    //             $matricule = trim($matricule);
    //             $contact = trim($contact);
    //             $email = trim($email);
    //             $nom = trim($nom);
    //             $prenom = trim($prenom);

    //             // Nettoyer le contact (enlever préfixe 225, espaces, etc.)
    //             $contactClean = preg_replace('/[^0-9]/', '', $contact);
    //             if (strlen($contactClean) > 10 && substr($contactClean, 0, 3) === '225') {
    //                 $contactClean = substr($contactClean, 3);
    //             }

    //             // Vérifier les doublons en base
    //             $raisons = [];

    //             if (!empty($matricule)) {
    //                 if (Mutualiste::where('matricule', $matricule)->exists()) {
    //                     $raisons[] = 'matricule';
    //                 }
    //             }

    //             if (!empty($contactClean)) {
    //                 if (Mutualiste::where('contact', $contactClean)->exists()) {
    //                     $raisons[] = 'contact';
    //                 }
    //             }

    //             if (!empty($email)) {
    //                 if (Mutualiste::where('email', $email)->exists()) {
    //                     $raisons[] = 'email';
    //                 }
    //             }

    //             $fullName = trim($nom . ' ' . $prenom) ?: 'Nom inconnu';

    //             if (!empty($raisons)) {
    //                 $resultats['existants']++;
    //                 $resultats['lignes_existantes'][] = [
    //                     'ligne' => $lineNumber + 2,
    //                     'matricule' => $matricule ?: 'N/A',
    //                     'nom' => $fullName,
    //                     'contact' => $contact,
    //                     'email' => $email,
    //                     'raisons' => $raisons
    //                 ];
    //             } else {
    //                 $resultats['nouveaux']++;
    //                 $resultats['details_nouveaux'][] = [
    //                     'matricule' => $matricule ?: 'N/A',
    //                     'nom' => $fullName
    //                 ];
    //             }
    //         }

    //         // Calculer le montant total (nouveaux * taxe_montant)
    //         $resultats['montant_total'] = $resultats['nouveaux'] * $taxeMontant;

    //         Log::info("Résultat vérification - Total: {$resultats['total']}, Nouveaux: {$resultats['nouveaux']}, Existants: {$resultats['existants']}");

    //         return response()->json([
    //             'success' => true,
    //             'data' => $resultats
    //         ]);
    //     } catch (\Exception $e) {
    //         Log::error('Erreur lors de la vérification: ' . $e->getMessage());
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Erreur : ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    /**
     * Convertit une ligne Excel en tableau propre
     */
    private function rowToArray($row)
    {
        if (is_array($row)) {
            $result = [];
            foreach ($row as $key => $value) {
                $result[$key] = $this->extractValue($value);
            }
            return $result;
        }

        if (is_object($row)) {
            // Essaye d'abord toArray()
            if (method_exists($row, 'toArray')) {
                $array = $row->toArray();
                $result = [];
                foreach ($array as $key => $value) {
                    $result[$key] = $this->extractValue($value);
                }
                return $result;
            }

            // Sinon, caste en array
            $array = (array) $row;
            $result = [];
            foreach ($array as $key => $value) {
                $key = str_replace("\0*\0", '', $key);
                $key = str_replace("\0", '', $key);
                $result[$key] = $this->extractValue($value);
            }
            return $result;
        }

        return (array) $row;
    }

    /**
     * Extrait la valeur scalaire d'un objet/cellule Excel
     */
    private function extractValue($value)
    {
        if (is_null($value)) return '';

        if (is_string($value) || is_numeric($value) || is_bool($value)) {
            return (string) $value;
        }

        if (is_object($value)) {
            // Si c'est un objet Cell de Maatwebsite
            if (method_exists($value, '__toString')) {
                return (string) $value;
            }
            if (method_exists($value, 'getValue')) {
                return (string) $value->getValue();
            }
            if (method_exists($value, 'value')) {
                return (string) $value->value();
            }

            // Dernier recours
            $json = json_encode($value);
            if ($json && $json !== 'null') {
                return $json;
            }

            return '';
        }

        if (is_array($value)) {
            return implode(', ', array_map([$this, 'extractValue'], $value));
        }

        return (string) $value;
    }

    /**
     * Récupère une valeur depuis un tableau avec plusieurs clés possibles
     */
    private function getValue($array, $keys)
    {
        foreach ($keys as $key) {
            if (isset($array[$key]) && !empty($array[$key])) {
                return $array[$key];
            }
        }
        return null;
    }

    private function cleanPhoneNumber($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($phone) > 10 && substr($phone, 0, 3) === '225') {
            $phone = substr($phone, 3);
        }
        return $phone;
    }
}
