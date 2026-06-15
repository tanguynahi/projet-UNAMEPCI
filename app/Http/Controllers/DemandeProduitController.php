<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use Illuminate\Http\Request;
use App\Models\ProduitProjet;
use App\Models\DemandeProduit;
use App\Models\DocumentProduit;
use App\Models\ProjetMutualiste;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\DocumentProduitMutualiste;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreDemandeProduitRequest;
use App\Http\Requests\UpdateDemandeProduitRequest;
use App\Http\Requests\ApprouverDemandeProduitRequest;

class DemandeProduitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $demandeproduits = DemandeProduit::orderBy('created_at', 'DESC')->get();
        $module = "Module Demande Produit";
        $action = "A consulte la liste des demande de produit";
        Logs::saveLog($module, $action);
        return view('dashboard.demandes.demandes_produits.index', compact(['demandeproduits']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $produit_projet_id = $request->produit_projet_id;
        $documentproduits = DocumentProduit::where('status', 1)
            ->where('produit_projet_id', $produit_projet_id)->get();
        $module = "Module Demande Produit";
        $action = "A affiche la page de creation d'un ajout de document de produit";
        Logs::saveLog($module, $action);
        return view('home.admin.demandes.create', compact('produit_projet_id', 'documentproduits'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDemandeProduitRequest $request) {}


    /**
     * Display the specified resource.
     */
    public function show(DemandeProduit $demandeproduit)
    {
        $module = "Module Demande Produit";
        $action = "A la page de detail d'une demande de produit id : $demandeproduit->id";
        Logs::saveLog($module, $action);
        return view('dashboard.demandes.demandes_produits.show', compact('demandeproduit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DemandeProduit $demandeproduit)
    {
        // return view('dashboard.demandes.demandes_produits.edit', compact('demandeProduit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDemandeProduitRequest $request, DemandeProduit $demandeproduit)
    {
        //
    }

    public function approuverDemandeProduit(DemandeProduit $demandeproduit, ApprouverDemandeProduitRequest $request)
    {
        // dd($demandeproduit, $request->all());

        try {
            DB::beginTransaction();

            $demandeproduit->status = 1;
            $demandeproduit->administrateur_id = auth()->user()->administrateur->id;
            $demandeproduit->commentaire = $request->commentaire_approbation;
            $demandeproduit->save();

            if ($demandeproduit) {

                $projetmutualiste = ProjetMutualiste::create([
                    'administrateur_id' => auth()->user()->administrateur->id,
                    'mutualiste_id' => $demandeproduit->mutualiste_id,
                    'demande_produit_id' => $demandeproduit->id,
                    'produit_projet_id' => $demandeproduit->produit_projet_id,
                    'lien_photo' => $demandeproduit->produitProjet->lien_photo,
                    'libelle' => $demandeproduit->produitProjet->libelle,
                    'montant_produit' => $demandeproduit->montant,
                    'total_apayer' => $request->total_apayer,
                    'date' => $request->date,
                    'commentaire' => $request->commentaire_approbation,
                    //    'status' => 1,
                ]);

                // dd($projetmutualiste);
            } else {
                DB::rollBack();
                toast('Demande refusée, Veuillez réessayer', 'error');
                $module = "Module Demande Produit";
                $action = "une demande a ete refuser ";
                Logs::saveLog($module, $action);
                return redirect()->back();
            }

            // dd($demandeproduit);

            DB::commit(); // Valider la demande de souscription

            toast('Demande approuvée avec succes!', 'success');
            $module = "Module Demande Produit";
            $action = "A approuve la demande de produit d'un mutualite id projet mutualiste : $projetmutualiste->id'";
            Logs::saveLog($module, $action);
            return redirect()->route('demandeproduits.show', $demandeproduit);
        } catch (\Throwable $e) {

            DB::rollBack(); // Annuler l'approbation de la demande

            // Loguer l'erreur
            Log::error('Erreur lors de la validation de la demande : ' . $e->getMessage());

            toast('Une erreur s\'est produite, Veuillez réessayer', 'error');
            $module = "Module Demande Produit";
            $action = "une erreur s'est produit lors de l'approuvement d'une demande de produit pour un mutualiste";
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }

    public function rejeterDemandeProduit(Request $request, DemandeProduit $demandeproduit)
    {
        // Définir les règles de validation
        $rules = [
            'commentaire' => 'required|string|min:3',
        ];

        // Définir les messages de validation personnalisés
        $messages = [
            'commentaire.required' => 'Le motif du rejet est requis.',
            'commentaire.string' => 'Le motif du rejet doit être une chaîne de caractères.',
            'commentaire.min' => 'Le motif du rejet doit avoir au moins 3 caractères.',
        ];

        // Appliquer la validation
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            // Rediriger avec les erreurs de validation et les données de la session
            toast('Veuillez renseigner le motif de rejet svp!', 'error');

            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $demandeproduit->status = 3;

            $demandeproduit->commentaire = $request->commentaire;
            $demandeproduit->save();

            DB::commit(); // Rejetée la demande de souscription

            toast('Demande rejetée avec succes!', 'success');
            $module = "Module Demande Produit";
            $action = "a refuser la demande de produit ";
            Logs::saveLog($module, $action);

            return redirect()->route('demandeproduits.show', $demandeproduit);
        } catch (\Throwable $e) {

            DB::rollBack(); // Rejetée l'approbation de la demande

            // Loguer l'erreur
            Log::error('Erreur lors du rejet de la demande : ' . $e->getMessage());
            $module = "Module Demande Produit";
            $action = "une erreur s'est produite lors du rejet de la demande de produit (demande de projet) " . $e->getMessage();
            Logs::saveLog($module, $action);

            toast('Une erreur s\'est produite, Veuillez réessayer', 'error');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DemandeProduit $demandeproduit)
    {
        try {
            DB::beginTransaction();

            $message = "";
            # code...
            $demandeproduit->status = 2;
            $demandeproduit->save();
            $message = "Demande de souscription Supprimé avec succès !";

            $demandeproduit->delete();
            $module = "Module Demande Produit";
            $action = "une demande de projet a ete supprimer  ";
            Logs::saveLog($module, $action);

            DB::commit();

            toast($message, 'success');

            return redirect() > route('demandeproduits.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)
            Log::error('Erreur interne du serveur: ' . $e->getMessage());
            $module = "Module Demande Produit";
            $action = "Une erreur serveur s'est produite lors de la suppression d'une demande de produite  ";
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }

    public function enregistrerDemande(Request $request)
    {
        // dd($request);
        try {
            DB::beginTransaction();
            $produit_projet_id = $request->produit_projet_id;
            $produit = ProduitProjet::whereId($produit_projet_id)->first();
            // dd($produit);
            // enregistrement de la demande avec le statut en attente
            $demandeproduit = DemandeProduit::create([
                'mutualiste_id' => auth()->user()->mutualiste->id,
                'produit_projet_id' => $produit_projet_id,
                'montant' => $produit->cout,
            ]);
            if ($request->hasFile('lien_document')) {
                // Bouclez sur les fichiers téléchargés et leurs IDs correspondants
                foreach ($request->file('lien_document') as $index => $file) {
                    // Récupérez l'ID du document produit correspondant (à partir de la vue)
                    $document_produit_id = $request->input('document_produit_id')[$index];
                    // dd($document_produit_id);
                    // Vérifiez si l'entrée existe dans la table 'document_produits'
                    $existingDocument = DocumentProduit::where('produit_projet_id', $produit_projet_id)
                        ->where('id', $document_produit_id)
                        ->first();
                    if ($existingDocument) {
                        // Générez un nom de fichier unique avec l'extension d'origine
                        $file_name = md5(uniqid()) . '_' . $document_produit_id . '.' . $file->extension();
                        // Vérifiez si le fichier existe déjà
                        while (Storage::exists('documents-produit-mutualiste/' . $file_name)) {
                            // Générez un nouveau nom de fichier unique
                            $file_name = md5(uniqid()) . '_' . $document_produit_id . '.' . $file->extension();
                        }
                        // Stockez le fichier dans le répertoire 'documents-produit'
                        $file->storeAs('documents-produit-mutualiste/', $file_name);
                        $lien_document = 'src-files/documents-produit-mutualiste/' . $file_name;
                        DocumentProduitMutualiste::create([
                            'demande_produit_id' => $demandeproduit->id,
                            'produit_projet_id' => $demandeproduit->produit_projet_id,
                            'type_document_id' => $existingDocument->typeDocument->id,
                            'lien_document' => $lien_document
                        ]);
                    } else {
                        // Optionnel : gérer le cas où l'entrée n'existe pas (loguer une erreur, par exemple)
                        Log::error('Document produit non trouvé pour la mise à jour avec produit_projet_id: ' . $produit_projet_id . ' et document_produit_id: ' . $document_produit_id);
                        return redirect()->back();
                    }
                }
            }

            DB::commit(); // Valider la transaction

            toast('Document(s) mis à jour avec succès !', 'success');
            $module = "Module Demande Produit";
            $action = "a ajouter les document pour une demande de produit ";
            Logs::saveLog($module, $action);
            return redirect()->route('liste.demandeproduit');
        } catch (\Throwable $e) {
            DB::rollBack(); // Annuler la transaction

            // Loguer l'erreur
            Log::error('Erreur lors de la mise à jour des documents : ' . $e->getMessage());

            toast('Une erreur s\'est produite, Veuillez réessayer', 'error');
            $module = "Module Demande Produit";
            $action = "Une erreur s'est produit lors de l'ajout des document d'une demande de produit ";
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }

    public function listeDemande(Request $request)
    {
        // liste des produits selon un mutualiste
        $mutualiste = auth()->user()->mutualiste;
        $demandeproduits = DemandeProduit::where('mutualiste_id', $mutualiste->id)->get();
        // dd($demandeproduits);
        $module = "Module Demande Produit";
        $action = "le mutualiste id $mutualiste->id , $mutualiste->nom , $mutualiste->prenom a consulte la liste de ses demande de produits ";
        Logs::saveLog($module, $action);
        return view('home.admin.demandes.index', compact('demandeproduits'));
    }


    // creation d'une demande de produit sur l'interface mutualiste ( je creer cette function pour ajouter un middleware d'adhesion sur sa route)
    // public function creation(Request $request)
    // {
    //     $produit_projet_id = $request->produit_projet_id;
    //     $documentproduits = DocumentProduit::where('produit_projet_id', $produit_projet_id)->get();
    //     return view('home.admin.demandes.create', compact('produit_projet_id', 'documentproduits'));
    // }
}
