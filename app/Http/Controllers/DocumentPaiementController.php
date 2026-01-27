<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use Illuminate\Http\Request;
use App\Models\DocumentPaiement;
use App\Models\PaiementInitiale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreDocumentPaiementRequest;
use App\Http\Requests\UpdateDocumentPaiementRequest;

class DocumentPaiementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreDocumentPaiementRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentPaiement $documentPaiement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentPaiement $documentPaiement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentPaiementRequest $request, DocumentPaiement $documentPaiement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentPaiement $documentPaiement)
    {
        //
    }


    public function remplissageInfoPaie(Request $request)
    {
        try {
            DB::beginTransaction();
            $paiementInital = PaiementInitiale::create([
                'mutualiste_id' => auth()->user()->mutualiste->id,
                'reference' => $request->reference,
                'p_cash' => 1,
                'type_paiement_id' => 4,
                'correspondance_id' => $request->facturationID, // id du produit du mutualiste (id de la facturations)
                'produit_id' => $request->projetID, // id du produit du mutualiste (id de la facturations)
                'montant_initial' => $request->montant,
                'moyen_paiement' => $request->modepaiement,
                'date_paiement_initial' => $request->date,
                'status' => 2,
            ]);

            if ($request->hasFile('document')) {
                foreach ($request->file('document') as $index => $file) {
                    $file_name = md5(uniqid()) . $index . '.' . $file->extension();
                    if (Storage::exists('images-docPaiement/' . $file_name)) {
                        $file_name = md5(uniqid()) . $index . '.' . $file->extension();
                    }
                    $file->storeAs('images-docPaiement/', $file_name);
                    $lien_images_projet = 'src-files/images-docPaiement/' . $file_name;

                    DocumentPaiement::create([
                        'facturation_id' => $request->facturationID,
                        'paiement_initiale_id' => $paiementInital->id,
                        'lien_photo' => $lien_images_projet,
                    ]);
                }
            }

            DB::commit();
            $module = "Module Document paiement";
            $action = "a enregistre un paiement manuel consernant un projet ";
            Logs::saveLog($module, $action);
            toast('Bien ajouté avec succès !', 'success');
            return redirect()->route('produitacquis.paiement', ['projetMutualiste' => $request->projetID]);
        } catch (\Exception $e) {
            DB::rollBack();
            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
           
            $module = "Module Document paiement";
            $action = "Une erreur s'est produite lors de l'enregistrement d'un paiement manuel pour un projet ";
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }
}
