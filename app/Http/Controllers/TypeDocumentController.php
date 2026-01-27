<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\TypeDocument;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreTypeDocumentRequest;
use App\Http\Requests\UpdateTypeDocumentRequest;

class TypeDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $typedocuments = TypeDocument::orderBy('libelle', 'ASC')->withTrashed()->get();
        $module = "Module Type Document ";
        $action = "a consulte la liste des Type de document";
        Logs::saveLog($module, $action);
        return view('dashboard.typesdocument.index', compact('typedocuments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $module = "Module Type Document ";
        $action = "a affiche la page de creation d'un type de document ";
        Logs::saveLog($module, $action);
        return view('dashboard.typesdocument.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTypeDocumentRequest $request)
    {
        try {
            DB::beginTransaction();

            $typedocument =  TypeDocument::create([
                'libelle' => $request->libelle,
                'description' => $request->description ?? ''
            ]);

            DB::commit();

            toast('Type de document ajouté avec succès !', 'success');

            // Rediriger l'utilisateur ou effectuer d'autres actions
            $module = "Module Type Document ";
            $action = "a ajouter un type de document ayant pour id : $typedocument->id et pour libelle : $typedocument->libelle ";
            Logs::saveLog($module, $action);
            return redirect()->route('typedocuments.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();


            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module Type Document ";
            $action = " une erreur s'est produite lors de la creation d'un type de document" . $e->getMessage();
            Logs::saveLog($module, $action);

            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TypeDocument $typedocument)
    {
        $module = "Module Type Document ";
        $action = " a affiche la page de detail du type de document ayant pour id : $typedocument->id";
        Logs::saveLog($module, $action);
        return view('dashboard.typesdocument.show', compact('typedocument'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TypeDocument $typedocument)
    {
        $module = "Module Type Document ";
        $action = " a affiche la page d'edition du type de document ayant pour id : $typedocument->id";
        Logs::saveLog($module, $action);
        return view('dashboard.typesdocument.edit', compact('typedocument'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTypeDocumentRequest $request, TypeDocument $typedocument)
    {
        try {
            DB::beginTransaction();

            // Vérifier si le libelle a changé et s'il existe déjà
            if ($typedocument->libelle !== $request->libelle) {
                $libelleExists = TypeDocument::where('libelle', $request->libelle)->withTrashed()->exists();

                if ($libelleExists) {
                    DB::rollBack();
                    toast("Ce Type de document '$request->libelle' existe déjà, mise à jour annulée.", 'error');
                    return redirect()->back();
                }

                $typedocument->libelle = $request->libelle;
            }

            // Mettre à jour la description si elle a changé
            if ($typedocument->description !== $request->description) {
                $typedocument->description = $request->description;
            }

            $typedocument->save();
            DB::commit();
            $module = "Module Type Document ";
            $action = " a modifier le  type de document ayant pour id : $typedocument->id";
            Logs::saveLog($module, $action);
            toast('Type de document modifié avec succès !', 'success');
            return redirect()->route('typedocuments.index');
        } catch (\Throwable $e) {
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            $module = "Module Type Document ";
            $action = " une erreur s'est produite lors de la mise a jour d'un type de document" . $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }


    /**
     * Update the specified resource from storage.
     */
    public function restaureTypeDocument($id)
    {
        try {
            DB::beginTransaction();

            $TypeDocumentRestaure = TypeDocument::withTrashed()->findOrFail($id);

            $TypeDocumentRestaure->status = 1;
            $TypeDocumentRestaure->restore();
            $message = "Type de document restauré avec succès !";
            // $ville->save();

            DB::commit();

            toast($message, 'success');
            $module = "Module Type Document ";
            $action = " a restaure le  type de document ayant pour id : $TypeDocumentRestaure->id";
            Logs::saveLog($module, $action);
            return redirect()->route('typedocuments.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module Type Document ";
            $action = " une erreur s'est produite lors de la restauration d'un type de document" . $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TypeDocument $typedocument)
    {
        try {
            DB::beginTransaction();

            $message = "";
            # code...
            $typedocument->status = 2;
            $typedocument->save();
            $message = "Type de document Supprimé avec succès !";
            $typedocument->delete();


            DB::commit();
            $module = "Module Type Document ";
            $action = " a restaure le  type de document ayant pour id : $typedocument->id";
            Logs::saveLog($module, $action);
            toast($message, 'success');

            return redirect()->route('typedocuments.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module Type Document ";
            $action = " une erreur s'est produite lors de la suppression d'un type de document" . $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }
}
