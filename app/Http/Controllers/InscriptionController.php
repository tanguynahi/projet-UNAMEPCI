<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\Taxe;
use App\Models\Compte;
use App\Models\Cotisation;
use App\Models\Mutualiste;
use App\Models\CarteMembre;
use App\Models\Inscription;
use Illuminate\Http\Request;
use App\Models\DroitAdhesion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use App\Models\CotisationMutualiste;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use App\Http\Requests\StoreinscriptionRequest;
use App\Http\Requests\UpdateinscriptionRequest;

class InscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $module = "Module Inscriptions ";
        $action = " a consulté la liste des Inscriptions ";
        Logs::saveLog($module, $action);
        $inscriptions = Inscription::orderBy('created_at', 'ASC')->withTrashed()->get();
        return view('dashboard.inscriptions.index', compact('inscriptions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreinscriptionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $inscription = Inscription::findOrFail($id);
        return view('dashboard.inscriptions.show', compact('inscription'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inscription $inscription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateinscriptionRequest $request, Inscription $inscription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inscription $inscription)
    {
        //
    }
    public function approuver($id)
    {
        // dd($id);
        try {
            DB::beginTransaction();
            // inscription
            $inscription = Inscription::findOrFail($id);
            $inscription->administrateur_id = auth()->user()->administrateur->id;
            $inscription->status = 1;
            $inscription->save();
            $codeP = generateUniqueCode(10);
            // mutualiste
            $mutualiste = new Mutualiste();
            $mutualiste->typeAdhesion = $inscription->typeAdhesion;
            $mutualiste->nom = $inscription->nom;
            $mutualiste->prenom = $inscription->prenom;
            $mutualiste->contact = $inscription->contact;
            // $mutualiste->contact_2 = $inscription->contact_2 ?? null;77



            $mutualiste->date_expiration_piece = $inscription->date_expiration_piece; // ajouter
            $mutualiste->niveau_intervention = $inscription->niveau_intervention; // ajouter
            $mutualiste->precise_intervention = $inscription->precise_intervention; // ajouter
            $mutualiste->ville_personnel_id = $inscription->ville_personnel_id; // ajouter
            $mutualiste->commune_personnel = $inscription->commune_personnel; // ajouter



            // $mutualiste->fax = $inscription->fax;
            $mutualiste->email = $inscription->email;
            $mutualiste->adresse = $inscription->adresse;
            $mutualiste->civilite = $inscription->civilite;
            $mutualiste->date_naissance = $inscription->date_naissance;
            $mutualiste->lieu_naissance = $inscription->lieu_naissance;
            $mutualiste->nationalite = $inscription->nationalite;
            $mutualiste->situation_matrimoniale = $inscription->situation_matrimoniale;
            // $mutualiste->nombre_charge = $inscription->nombre_charge;
            $mutualiste->date_adhesion_unamepci = $inscription->date_adhesion_unamepci;
            $mutualiste->type_piece_id = $inscription->type_piece_id;
            $mutualiste->numero_piece = $inscription->numero_piece;
            $mutualiste->date_etablissement_piece = $inscription->date_etablissement_piece;
            $mutualiste->lieu_etablissement_piece = $inscription->lieu_etablissement_piece;
            $mutualiste->pieces_joints_recto = $inscription->pieces_joints_recto;
            $mutualiste->pieces_joints_verso = $inscription->pieces_joints_verso;
            $mutualiste->numero_inscription_ONMCI = $inscription->numero_inscription_ONMCI;
            // $mutualiste->pseudonyme_recon_ONMCI = $inscription->pseudonyme_recon_ONMCI;
            $mutualiste->matricule = $inscription->matricule;
            $mutualiste->raison_social_primaire = $inscription->raison_social_primaire;
            $mutualiste->specialite_id = $inscription->specialite_id;
            $mutualiste->fonction = $inscription->fonction;
            $mutualiste->date_debut_metier = $inscription->date_debut_metier;
            $mutualiste->nombre_annee_experience = $inscription->nombre_annee_experience;
            $mutualiste->nom_employeur_principale = $inscription->nom_employeur_principale;
            $mutualiste->statut_emploi = $inscription->statut_emploi;
            // $mutualiste->domaine_activite = $inscription->domaine_activite;
            $mutualiste->date_recrutement = $inscription->date_recrutement;
            $mutualiste->sigle = $inscription->sigle;
            $mutualiste->date_creation = $inscription->date_creation;
            $mutualiste->numero_autorisation = $inscription->numero_autorisation;
            $mutualiste->num_immatriculation = $inscription->num_immatriculation;
            $mutualiste->forme_juridique_id = $inscription->forme_juridique_id;
            $mutualiste->precise_forme_juridique = $inscription->precise_forme_juridique;
            $mutualiste->ville_id = $inscription->ville_id;
            $mutualiste->commune = $inscription->commune;
            $mutualiste->quartier = $inscription->quartier;
            $mutualiste->rue = $inscription->rue;
            $mutualiste->adresse_postale_entreprise = $inscription->adresse_postale_entreprise;
            $mutualiste->localisation_entreprise = $inscription->localisation_entreprise;
            $mutualiste->email_entreprise = $inscription->email_entreprise;
            $mutualiste->telephone_entreprise = $inscription->telephone_entreprise;
            $mutualiste->fax_entreprise = $inscription->fax_entreprise;


            // $mutualiste->relation_tiers = $inscription->relation_tiers;
            // $mutualiste->nom_relation = $inscription->nom_relation;
            // $mutualiste->etre_auteur = $inscription->etre_auteur;
            // $mutualiste->nom_auteur = $inscription->nom_auteur;

            // $mutualiste->raison_social_secondaire_freelance = $inscription->raison_social_secondaire_freelance;
            // $mutualiste->fonction_occupe_freelance = $inscription->fonction_occupe_freelance;
            // $mutualiste->type_contrat_freelance = $inscription->type_contrat_freelance;
            // $mutualiste->telephone_freelance = $inscription->telephone_freelance;
            // $mutualiste->fax_freelance = $inscription->fax_freelance;
            // $mutualiste->localisation_freelance = $inscription->localisation_freelance;
            // $mutualiste->adresse_postale_freelance = $inscription->adresse_postale_freelance;
            // $mutualiste->domaine_activite_freelance = $inscription->domaine_activite_freelance;

            $mutualiste->lien_photo = $inscription->avatar;
            $mutualiste->signature = $inscription->signature;
            $mutualiste->photo_couverture = $inscription->photo_couverture;
            $mutualiste->document_carte_inscript_ONMCI = $inscription->document_carte_inscript_ONMCI;
            $mutualiste->document_autorisation_ouverture = $inscription->document_autorisation_ouverture;
            $mutualiste->photo_identite_1 = $inscription->photo_identite_1;
            $mutualiste->code = $codeP;
            $mutualiste->save();


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
                // return redirect()->route('mutualistes.index');
            } else {
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
            }
            // droit d'adhesion
            $taxeAdhesion =  Taxe::findOrFail(1); // montant droit adhesion
            $droitAdhesion = DroitAdhesion::create([
                'administrateur_id' => auth()->user()->administrateur->id,
                'mutualiste_id' => $mutualiste->id,
                'type_paiement_id' => 1,
                'libelle' => $taxeAdhesion->libelle ?? "Droit d'adhésion",
                'montant' => $taxeAdhesion->montant ?? 10000,
            ]);
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
            // $taxeCarte = Taxe::findOrFail(2);
            $carteMembre = new CarteMembre();
            $carteMembre->administrateur_id = auth()->user()->administrateur->id;
            $carteMembre->mutualiste_id = $mutualiste->id;
            $carteMembre->type_paiement_id = 5;
            $carteMembre->libelle =  'Taxe Carte Membre';
            // $carteMembre->montant = $taxeCarte->montant ?? 5000;
            $carteMembre->save();

            // cotisation  annuelle
            $coti = Cotisation::findOrFail(1);
            $cotisationMutualiste = new CotisationMutualiste();
            $cotisationMutualiste->administrateur_id = auth()->user()->administrateur->id;
            $cotisationMutualiste->mutualiste_id = $mutualiste->id;
            $cotisationMutualiste->cotisation_id = $coti->id;
            $cotisationMutualiste->type_paiement_id = 2;
            $cotisationMutualiste->montant = $coti->montant_a_payer ?? 120000;
            // $cotisationMutualiste->montant_initial = $coti->montant_a_payer ?? 120000;
            $cotisationMutualiste->frequence_paiement = $coti->frequence_paiement;
            $cotisationMutualiste->date_debut = $coti->date_debut;
            $cotisationMutualiste->date_fin = $coti->date_fin;
            $cotisationMutualiste->save();

            // Générer le lien de validation
            $lienDeValidation = URL::temporarySignedRoute(
                'creatAccesNewInscrits',
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

            // envoyer via sms

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
            toast('Inscription a été Valider avec succès !', 'success');
            return redirect()->route('inscriptions.index');
        } catch (\Throwable $th) {
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
    public function rejeter(Request $request, $id)
    {
        // dd($request->all(), $id);

        try {
            DB::beginTransaction();
            // inscription
            $inscription = Inscription::findOrFail($id);
            $inscription->administrateur_id = auth()->user()->administrateur->id;
            $inscription->commentaire = $request->commentaire; // Rejetée
            $inscription->status = 3; // Rejetée
            $inscription->save();
            // Générer le lien de validation

            $sujet = "Notification concernant votre demande UNAMEPCI ";

            $message = "
                Bonjour {$inscription->prenom} {$inscription->nom}, <br><br>

                Nous vous informons que votre demande d'inscription sur la plateforme <strong>UNAMEPCI </strong> n’a malheureusement pas été validée et a été <strong>rejetée</strong> après examen de votre dossier. <br><br>

                <strong>Motif du rejet :</strong> <br>
                {$inscription->commentaire} <br><br>

                Nous vous invitons à corriger les éléments mentionnés ci-dessus et à effectuer une nouvelle demande si nécessaire. <br><br>

                Pour toute information complémentaire ou assistance, n’hésitez pas à contacter notre support. <br><br>

                Merci de votre compréhension. <br><br>

                Cordialement, <br>
                <strong>L’équipe UNAMEPCI </strong>
                ";
            $url = appelApiEmail();
            $template = View::make('home.admin.paiements.paiementAdhesion', ['contenumess' => $message])->render();
            $data = [
                'provider' => 'UNAMEPCI <info@mail-taseti.com>',
                "key_rsa" => 're_2i7H3Ynf_KRVm9VwTsrwrfF8isCBYvyyE',
                "destination" => $inscription->email,
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
                    $module = "Envoyer de Mail a  rejeter de  inscription";
                    $action = "Echec d'envoyer de mail  : $message";
                    Logs::saveLog($module, $action);
                } else {
                    $module = "Envoyer de Mail a  rejeter de  inscription";
                    $action = "Email envoyer avec success   : $inscription->nom , $inscription->prenom sur son email  $inscription->email";
                    Logs::saveLog($module, $action);
                }
            } else {
                Log::error("Erreur lors de l'envoi de l'email. Statut API : " . $retourAPI->status());

                $module = "Envoyer de Mail a  rejeter de  inscription";
                $action = "Erreur lors de l'envoi de l'email. Statut API : " . $retourAPI->status();
                Logs::saveLog($module, $action);
            }

            // envoyer via sms rejeter

            try {
                $message = envoyerMessageInscriptRejet($inscription);

                $url = appelApiSMS();

                $headers = [
                    'Environnement' => 'bew', // Remplace par l'environnement approprié
                    'secretKey' => 'jfdiezaophrh90c(_kfjqlm', // Remplace par ta vraie clé secrète
                ];

                $data = [
                    "titre" => "Rejeter de compte UNAMEPCI",
                    "destination" => $inscription->contact, // Numéro de téléphone
                    "email" => $inscription->email ?? null, // Facultatif
                    "texte" => $message,
                ];

                $retourAPI = Http::withHeaders($headers)->post($url, $data);

                $res = $retourAPI->json();


                if ($retourAPI->status() == 200) {
                    (int)$code = $res['status'];
                    if ($code != 200) {
                        $message = "Une erreur s'est produite " . $code . ", DETAIL: " . messageBrut($res['message']) . " ERR: Envoyer sms avec les accès";
                        // Log::ajoutLOG($message);
                        $module = "Envoyer du sms a  rejeter de  inscription";
                        $action = "Echec d'envoyer du sms  : $message";
                        Logs::saveLog($module, $action);
                    } else {
                        $module = "Envoyer du sms a  rejeter de  inscription";
                        $action = "Email envoyer avec success   : $inscription->nom , $inscription->prenom sur son email  $inscription->email";
                        Logs::saveLog($module, $action);
                    }
                } else {
                    Log::error("Erreur lors de l'envoi du sms. Statut API : " . $retourAPI->status());

                    $module = "Envoyer du sms a la rejeter de  inscription";
                    $action = "Erreur lors de l'envoi du sms. Statut API : " . $retourAPI->status();
                    Logs::saveLog($module, $action);
                }
            } catch (\Throwable $th) {
                //throw $th;
                Log::error("Erreur lors de l'envoi du sms. Statut API : " . $th->getMessage());
            }
            DB::commit();
            toast(' Inscription a été rejeter avec succès !', 'success');
            return redirect()->route('inscriptions.show', ['id' => $inscription->id]);
        } catch (\Throwable $th) {
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
}
