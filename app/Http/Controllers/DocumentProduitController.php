<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\Equipe;
use App\Models\TypeDocument;
use Illuminate\Http\Request;
use App\Models\ProduitProjet;
use App\Models\DocumentProduit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\StoreDocumentProduitRequest;
use App\Http\Requests\UpdateDocumentProduitRequest;

class DocumentProduitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $produit_id = $request->produit_id;
        $documentproduits = DocumentProduit::whereProduitProjetId($produit_id)->orderBy('created_at', 'desc')->withTrashed()->get();

        $produit = ProduitProjet::whereId($produit_id)->first();
        $module = "Module Document produit";
        $action = "a consulte la liste des document d'un produit ";
        Logs::saveLog($module, $action);
        return view('dashboard.projets.produits_projet.documents_produit.index', compact(['documentproduits', 'produit']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $typeDocuments = TypeDocument::orderBy('libelle', 'ASC')->get();

        $produit_id = $request->produit_id;
        $module = "Module Document produit";
        $action = "a affiche la page de creation d'un type de document ";
        Logs::saveLog($module, $action);
        return view('dashboard.projets.produits_projet.documents_produit.create', compact(['produit_id', 'typeDocuments']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentProduitRequest $request)
    {
        try {
            DB::beginTransaction();

            $produit_projet_id = $request->produit_projet_id;

            foreach ($request->type_document_id as $documentId) {
                // Vérifier si une entrée avec le même produit_projet_id et type_document_id existe déjà
                $existingDocument = DocumentProduit::where('produit_projet_id', $produit_projet_id)
                    ->where('type_document_id', $documentId)
                    ->first();

                // Si une entrée existe déjà, afficher un message d'erreur
                if ($existingDocument) {
                    DB::rollBack(); // Annuler la transaction
                    // return response()->json([
                    //     'message' => 'Une entrée avec le même type de document existe déjà.',
                    //     'status' => 'error',
                    // ], 409); // Code HTTP 409 pour "Conflict"
                    toast('Une entrée avec ' . $existingDocument->typeDocument->libelle . ' pour ce produit existe déjà. !', 'error');
                    // Rediriger l'utilisateur ou effectuer d'autres actions
                    return redirect()->back();
                }

                // Créer une nouvelle entrée
                DocumentProduit::create([
                    'administrateur_id' => auth()->user()->administrateur->id,
                    'produit_projet_id' => $produit_projet_id,
                    'type_document_id' => $documentId,
                ]);
            }

            DB::commit(); // Valider la transaction

            toast('Document(s) ajouté(s) avec succès !', 'success');
            // Rediriger l'utilisateur ou effectuer d'autres actions
            $module = "Module Document produit";
            $action = "a ajouter un document pour un produit ";
            Logs::saveLog($module, $action);
            return redirect()->route('documentproduits.index', ['produit_id' => $produit_projet_id]);
        } catch (\Throwable $e) {
            DB::rollBack(); // Annuler la transaction

            // Loguer l'erreur
            Log::error('Erreur lors de l\'ajout des documents : ' . $e->getMessage());

            toast('Une erreur s\'est produite, Veuillez réessayer', 'error');
            // Rediriger l'utilisateur ou effectuer d'autres actions
            // return redirect()->route('documentproduits.index', ['produit_id' => $produit_projet_id]);
            return redirect()->back();
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(DocumentProduit $documentproduit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentProduit $documentproduit)
    {
        $typeDocuments = TypeDocument::orderBy('libelle', 'ASC')->get();

        // $produit_id = $documentproduit->produit_projet_id;
        $module = "Module Document produit";
        $action = "a consulte la page d'edition de document des produits ";
        Logs::saveLog($module, $action);
        return view('dashboard.projets.produits_projet.documents_produit.edit', compact(['documentproduit', 'typeDocuments']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentProduitRequest $request, DocumentProduit $documentproduit)
    {
        try {
            DB::beginTransaction();

            $produit_projet_id = $documentproduit->produit_projet_id;

            // Vérifier s'il existe déjà un document produit avec le même produit_projet_id et type_document_id
            $existingDocumentProduit = DocumentProduit::withTrashed()
                ->where('produit_projet_id', $produit_projet_id)
                ->where('type_document_id', $request->type_document_id)
                ->first();

            if ($existingDocumentProduit && $existingDocumentProduit->id !== $documentproduit->id) {
                DB::rollBack(); // Annuler la transaction
                toast('Une entrée avec le même type de document "' . $existingDocumentProduit->typeDocument->libelle . '" existe déjà!', 'error');
                return redirect()->back();
            }

            // Mettre à jour les informations du document produit
            $documentproduit->administrateur_id = auth()->user()->administrateur->id;
            $documentproduit->type_document_id = $request->type_document_id;

            $documentproduit->save();

            DB::commit();

            toast('Type de document modifié avec succès!', 'success');
            $module = "Module Document produit";
            $action = "a modifier un type de document pour un projet ";
            Logs::saveLog($module, $action);
            return redirect()->route('documentproduits.index', ['produit_id' => $produit_projet_id]);
        } catch (\Throwable $e) {
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            $module = "Module Document produit";
            $action = "une erreur s'est produit lors de la mise a jour d'un document d'un produit" . $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }


    public function restaureDocumentProduit($id)
    {
        try {
            DB::beginTransaction();


            $documentRestaure = DocumentProduit::withTrashed()->findOrFail($id);

            $produit_projet_id = $documentRestaure->produit_projet_id;

            $documentRestaure->status = 1;
            $documentRestaure->restore();
            $message = "Type de document restauré avec succès !";

            DB::commit();

            toast($message, 'success');
            $module = "Module Document produit";
            $action = "a restaure un document produit";
            Logs::saveLog($module, $action);
            return redirect()->route('documentproduits.index', ['produit_id' => $produit_projet_id]);
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)
            Log::error('Erreur interne du serveur: ' . $e->getMessage());
            $module = "Module Document produit";
            $action = "une erreur s'est produit lors de la restauration  d'un document d'un produit". $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentProduit $documentproduit)
    {
        try {
            DB::beginTransaction();

            $produit_projet_id = $documentproduit->produit_projet_id;

            $message = "";
            # code...
            $documentproduit->status = 2;
            $documentproduit->save();
            $message = "Type de document Supprimé avec succès !";
            $documentproduit->delete();

            DB::commit();

            toast($message, 'success');
            $module = "Module Document produit";
            $action = "a supprimer un document produit";
            Logs::saveLog($module, $action);
            return redirect()->route('documentproduits.index', ['produit_id' => $produit_projet_id]);
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module Document produit";
            $action = "une erreur s'est produite lors la suppression d'un document produit";
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }
}
