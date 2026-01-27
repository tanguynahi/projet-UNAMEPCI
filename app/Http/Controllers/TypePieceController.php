<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\TypePiece;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreTypePieceRequest;
use App\Http\Requests\UpdateTypePieceRequest;

class TypePieceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $typepieces = TypePiece::orderBy('libelle', 'ASC')->withTrashed()->get();
        $module = "Module Type Pieces ";
        $action = "a consulte la liste des type de Pieces";
        Logs::saveLog($module, $action);
        return view('dashboard.typespiece.index', compact('typepieces'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $module = "Module Type Pieces ";
        $action = "a affiche la page de creation d'un type de piece ";
        Logs::saveLog($module, $action);
        return view('dashboard.typespiece.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTypePieceRequest $request)
    {
        try {
            DB::beginTransaction();

            $types =  TypePiece::create([
                'libelle' => $request->libelle,
                'description' => $request->description ?? ''
            ]);
            $module = "Module Type Pieces ";
            $action = "a ajouter un type de piece ayant pour id = $types->id";
            Logs::saveLog($module, $action);

            DB::commit();

            toast('Type de pièce ajouté avec succès !', 'success');

            // Rediriger l'utilisateur ou effectuer d'autres actions
            return redirect()->route('typepieces.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();
            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
            $module = "Module Type Pieces ";
            $action = "Une erreur s'est produite lors de l'ajout d'un type de piece". $e->getMessage();
            Logs::saveLog($module, $action);

            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TypePiece $typepiece)
    {
        $module = "Module Type Pieces ";
        $action = "a consulte les details du type de piece ayant pour id = $typepiece->id";
        Logs::saveLog($module, $action);
        return view('dashboard.typespiece.show', compact('typepiece'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TypePiece $typepiece)
    {
        $module = "Module Type Pieces ";
        $action = "a consulte la page d'edition du type de piece ayant pour id = $typepiece->id";
        Logs::saveLog($module, $action);
        return view('dashboard.typespiece.edit', compact('typepiece'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTypePieceRequest $request, TypePiece $typepiece)
    {
        try {
            DB::beginTransaction();

            // update typePiece libelle if libelle has changed
            if ($typepiece->libelle !== $request->libelle) {
                $libelleExists = TypePiece::where('libelle', $request->libelle)->withTrashed()->exists();

                if ($libelleExists) {
                    DB::rollBack();
                    toast("Ce Type de pièce '$request->libelle' existe déjà, mise à jour annulée.", 'error');
                    return redirect()->back();
                }

                $typepiece->libelle = $request->libelle;
            }

            if ($typepiece->description !== $request->description) {
                $typepiece->description = $request->description;
            }

            $typepiece->save();
            DB::commit();
            $module = "Module Type Pieces ";
            $action = "a modifie le type de piece ayant pour id = $typepiece->id";
            Logs::saveLog($module, $action);
            toast('Type de pièce modifié avec succès !', 'success');

            return redirect()->route('typepieces.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module Type Pieces ";
            $action = "une erreur s'est produite lors de la modification d'un type de pieces ". $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function restaureTypePiece($id)
    {
        try {
            DB::beginTransaction();

            $TypePieceRestaure = TypePiece::withTrashed()->findOrFail($id);

            $TypePieceRestaure->status = 1;
            $TypePieceRestaure->restore();
            $message = "Type de pièce restauré avec succès !";
            // $ville->save();

            DB::commit();

            toast($message, 'success');
            $module = "Module Type Pieces ";
            $action = "a restaure le type de piece ayant pour id = $TypePieceRestaure->id ";
            Logs::saveLog($module, $action);
            return redirect()->route('typepieces.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module Type Pieces ";
            $action = "une erreur s'est produite lors de la restauration d'un type de pieces ". $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TypePiece $typepiece)
    {
        try {
            DB::beginTransaction();

            $message = "";
            # code...
            $typepiece->status = 2;
            $typepiece->save();
            $message = "Type de pièce Supprimé avec succès !";
            $typepiece->delete();


            DB::commit();

            toast($message, 'success');
            $module = "Module Type Pieces ";
            $action = "a supprime  le type de piece ayant pour id = $typepiece->id ";
            Logs::saveLog($module, $action);
            return redirect()->route('typepieces.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module Type Pieces ";
            $action = "une erreur s'est produite lors de la suppression d'un type de pieces ". $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }
}
