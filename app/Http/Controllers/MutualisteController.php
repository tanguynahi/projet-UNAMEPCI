<?php

namespace App\Http\Controllers;


use App\Models\Logs;
use App\Models\User;
use App\Models\Corps;
use App\Models\Grade;
use App\Models\Ville;
use App\Models\Compte;
use App\Models\TypePiece;
use App\Models\Mutualiste;
use Illuminate\Http\Request;
use App\Models\DroitAdhesion;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
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
        $module = "Module Mutualiste ";
        $action = " a affiché la page de création d'un mutualiste ";
        Logs::saveLog($module, $action);
        return view('dashboard.mutualistes.create', compact('villes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMutualisteRequest $request)
    {
        // dd($request->all());:

        try {
            DB::beginTransaction();
            // Récupérer les données validées
            $data = $request->validated();

            // Créer un nouveau mutualiste et insérer les données dans la table mutualistes
            $codeP = generateUniqueCode(10);
            // dd($request->all(),$codeP) ;
            $mutualiste = Mutualiste::create([
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'email' => $data['email'],
                'matricule' => $data['matricule'],
                'contact' => $data['contact'],
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
            $droitAdhesion = DroitAdhesion::create([
                'administrateur_id' => auth()->user()->administrateur->id,
                'mutualiste_id' => $mutualiste->id,
                'type_paiement_id' => 1,
                'libelle' => "Droit d'adhésion",
                'montant' => 15000,
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

            // Générer le lien de validation
            $lienDeValidation = URL::temporarySignedRoute(
                'validation.inscription',
                now()->addHours(24), // Définissez la durée de validité du lien
                ['code' => $mutualiste->code]
            );
            Mutualiste::where('id', $mutualiste->id)->update([
                'lien_email' => $lienDeValidation,
            ]);
            $sujet = "Validation de votre  compte MUTUALPAY";
            $message = "  Bonjour, " . $mutualiste->prenom . ' ' . $mutualiste->nom . "<br>
                        Merci pour la première étape de votre inscription sur MUTUALPAY. <br> Veuillez cliquer sur le boutton ci-dessous pour finaliser votre inscription et valider votre compte. !<br>
                        <div style='margin-top:3px; margin-bottom:3px;  text-align:center;'>
                        <a href=" . $lienDeValidation . " class='bouton'> POURSUIVRE</a> <br>
                        </div>
                               Merci d'utiliser notre plateforme! <br>
                        Si vous rencontrez des problèmes avec votre compte, n'hésitez pas à nous contacter.
                                ";
            $url = appelApiEmail();
            $template = View::make('home.admin.paiements.paiementAdhesion', ['contenumess' => $message])->render();
            $data = [
                'provider' => 'MUTUALPAY <info@mail-taseti.com>',
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
    public function show(Mutualiste $mutualiste)
    {
        //
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

            // dd($request->all());

            $documents = [];
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $document) {
                    $file_name = Carbon::now()->timestamp . '_' . uniqid() . '.' . $document->extension();
                    $document->storeAs('images-mutualistes/', $file_name);
                    $documents[] = 'src-files/images-mutualistes/' . $file_name;
                }
            }
            // dd( $documents);


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
            $userId = $user->id;
            $contact_1 = $mutualiste->contact ?? $data['contact'];

            $mutualiste->update([
                'user_id' => $userId,
                'corp_id' => $data['corp_id'],
                'grade_id' => $data['grade_id'],
                'ville_id' => $data['ville_id'],
                'type_piece_id' => $data['type_piece_id'],
                'numero_piece' => $data['numero_piece'],
                'date_etablissement_piece' => $data['date_etablissement_piece'],
                'lieu_etablissement_piece' => $data['lieu_etablissement_piece'],
                'unite' => $data['unite'],
                'contact' => $contact_1,
                'contact_2' => $data['contact_2'],
                'adresse' => $data['adresse'],
                'genre' => $data['genre'],
                'date_naissance' => $data['date_naissance'],
                'lieu_naissance' => $data['lieu_naissance'],
                'lien_photo' => $lien_photo,
                'photo_couverture' => $photo_couverture,
                'documents' => $documents,
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
            $compteMutualiste = Compte::whereMutualisteId($mutualiste->id)->first();
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

            $corps = Corps::all();
            $grades = Grade::all();
            $typePieces = TypePiece::all();
            $villes = Ville::orderBy('libelle', 'ASC')->get();
            $droitAdhesions = DroitAdhesion::all();
            // Vérifiez si le mutualiste contient des données
            if ($mutualiste->exists()) {
                // Si le mutualiste existe et contient des données, redirigez-le vers le formulaire pour compléter les autres informations de compte
                $module = "Activation du lien d'inscription  Mutualiste";
                $action = "Le mutualiste : $mutualiste->nom a activer son lien d'inscriptions";
                Logs::saveLog($module, $action);
                return view('home.pages.inscription', compact('mutualiste', 'corps', 'grades', 'typePieces', 'villes'));
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
    public function modificationMutualiste(Request $request, $id)
    {
        // recuperation des information

        $validator = Validator::make($request->all(), [
            'corp_id' => 'required',
            'grade_id' => 'required',
            'ville_id' => 'required',
            'type_piece_id' => 'required',
            'numero_piece' => 'required',
            'date_etablissement_piece' => 'required',
            'lieu_etablissement_piece' => 'required',
            'unite' => 'required',
            'contact' => 'required',
            'contact_2' => 'nullable',
            'adresse' => 'required',
            'genre' => 'required',
            'date_naissance' => 'required',
            'lieu_naissance' => 'required',
            'lien_photo' => 'nullable',
            'nom' => 'required',
            'prenom' => 'required',
            'photo_couverture' => 'nullable',
            // 'status' => 2,
        ]);
        if ($validator->fails()) {
            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            return back()->withErrors($validator->errors())->withInput($request->input());
        }
        try {

            $mutualiste = Mutualiste::find($id);

            if (!$mutualiste) {
                toast("Une erreur s'est produite, Mutualiste introuvable.", 'error');
            } else {
                DB::beginTransaction(); //

                $files = $request->hasFile('lien_photo');
                $couverture = $request->hasFile('photo_couverture');
                $lien_photo = null;
                $photo_couverture = null;
                $documentsA = $request->hasFile('documents');
                $documents = [];
                if ($files) {
                    if ($mutualiste->lien_photo) {

                        if (File::exists(public_path($mutualiste->lien_photo))) {
                            File::delete(public_path($mutualiste->lien_photo));
                        }
                    }
                    $file_name = Carbon::now()->timestamp . '.' . $request->file('lien_photo')->extension();
                    $request->lien_photo->storeAs('images-mutualistes/', $file_name);
                    $lien_photo = 'src-files/images-mutualistes/' . $file_name;
                    $mutualiste->lien_photo = $lien_photo;
                }
                if ($couverture) {
                    if ($mutualiste->photo_couverture) {

                        if (File::exists(public_path($mutualiste->photo_couverture))) {
                            File::delete(public_path($mutualiste->photo_couverture));
                        }
                    }

                    $file_name = Carbon::now()->timestamp . '.' . $request->file('photo_couverture')->extension();
                    $request->photo_couverture->storeAs('images-mutualistes/couverture/', $file_name);
                    $photo_couverture = 'src-files/images-mutualistes/couverture/' . $file_name;
                    // dd(  $photo_couverture);
                    $mutualiste->photo_couverture = $photo_couverture;
                }
                if ($documentsA) {
                    if ($mutualiste->documents) {
                        $existingDocuments = json_decode($mutualiste->documents);
                        foreach ($existingDocuments as $existingDocument) {
                            if (File::exists(public_path($existingDocument))) {
                                File::delete(public_path($existingDocument));
                            }
                        }
                    }
                    foreach ($request->file('documents') as $document) {
                        $file_name = Carbon::now()->timestamp . '_' . uniqid() . '.' . $document->extension();
                        $document->storeAs('images-mutualistes/', $file_name);
                        $documents[] = 'src-files/images-mutualistes/' . $file_name;
                    }
                    $mutualiste->documents = $documents;
                }
                $mutualiste->corp_id = $request->corp_id;
                $mutualiste->grade_id = $request->grade_id;
                $mutualiste->ville_id = $request->ville_id;
                $mutualiste->type_piece_id = $request->type_piece_id;
                $mutualiste->nom = htmlspecialchars($request->nom);
                $mutualiste->prenom = htmlspecialchars($request->prenom);
                $mutualiste->genre = htmlspecialchars($request->genre);
                $mutualiste->date_naissance = htmlspecialchars($request->date_naissance);
                $mutualiste->lieu_naissance = htmlspecialchars($request->lieu_naissance);
                // $mutualiste->lien_photo = $lien_photo;
                $mutualiste->adresse = htmlspecialchars($request->adresse);
                $mutualiste->contact = htmlspecialchars($request->contact);
                $mutualiste->contact_2 = htmlspecialchars($request->contact_2);
                $mutualiste->unite = htmlspecialchars($request->unite);
                $mutualiste->numero_piece = htmlspecialchars($request->numero_piece);
                $mutualiste->date_etablissement_piece = htmlspecialchars($request->date_etablissement_piece);
                $mutualiste->lieu_etablissement_piece = htmlspecialchars($request->lieu_etablissement_piece);
                $mutualiste->save();
                DB::commit();
                toast('Vos Informations ont étées modifiées avec succès !', 'success');

                $module = "Espace Mutualiste";
                $action = "Le mutualiste :  $mutualiste->nom , $mutualiste->prenom  a  modifier ses informations";
                Logs::saveLog($module, $action);
                return redirect()->back();
            }
        } catch (\Throwable $e) {
            DB::rollback();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            Log::error('Erreur interne du serveur: ' . $e->getMessage());
            return redirect()->back();
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
