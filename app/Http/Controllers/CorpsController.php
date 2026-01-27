<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\Corps;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreCorpsRequest;
use App\Http\Requests\UpdateCorpsRequest;

class CorpsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $corpsArmee = Corps::orderBy('libelle', 'ASC')->withTrashed()->get();
        $module = "Module Corps d'armée";
        $action = "a consulte la liste des corps d'armée";
        Logs::saveLog($module, $action);
        return view('dashboard.corps.index', compact('corpsArmee'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $module = "Module Corps d'armée";
        $action = "a affiché la page de création d'un corps d'armée ";
        Logs::saveLog($module, $action);
        return view('dashboard.corps.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCorpsRequest $request)
    {
        try {
            DB::beginTransaction();

            Corps::create([
                'libelle' => $request->libelle,
                'description' => $request->description ?? ''
            ]);

            DB::commit();
            $module = "Module Corps d'armée";
            $action = "a ajouter un corps d'armee : $request->libelle ";
            Logs::saveLog($module, $action);
            toast('Corps ajouté avec succès !', 'success');

            // Rediriger l'utilisateur ou effectuer d'autres actions
            return redirect()->route('corps.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();


            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
            // Capturer toute autre exception (erreur 500)
            Log::error('Erreur interne du serveur: ' . $e->getMessage());
            $module = "Module Corps d'armée";
            $action = "une error c'est produite lors de l'ajouter d'un corps d'arméé " . $e->getMessage();
            Logs::saveLog($module, $action);

            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Corps $corps)
    {
        $module = "Module Corps d'armée";
        $action = "a consulte les information du corps d'armes :$corps->libelle ";
        Logs::saveLog($module, $action);
        return view('dashboard.corps.show', compact('corps'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Corps $corps)
    {
        $module = "Module Corps d'armée";
        $action = "a affiché la page d'edition  du corps d'armes :$corps->libelle ";
        Logs::saveLog($module, $action);
        return view('dashboard.corps.edit', compact('corps'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCorpsRequest $request, Corps $corps)
    {
        try {
            DB::beginTransaction();

            // update typePiece libelle if libelle has changed
            if ($corps->libelle !== $request->libelle) {
                $libelleExists = Corps::where('libelle', $request->libelle)->withTrashed()->exists();

                if ($libelleExists) {
                    DB::rollBack();
                    toast("Ce Corps '$request->libelle' existe déjà, mise à jour annulée.", 'error');
                    return redirect()->back();
                }

                $corps->libelle = $request->libelle;
            }

            if ($corps->description !== $request->description) {
                $corps->description = $request->description;
            }

            $corps->save();
            DB::commit();

            toast('Corps modifié avec succès !', 'success');

            $module = "Module Corps d'armée";
            $action = "a modifier un corps d'armée :$corps->libelle ";
            Logs::saveLog($module, $action);

            return redirect()->route('corps.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)
            Log::error('Erreur interne du serveur: ' . $e->getMessage());

            $module = "Module Corps d'armée";
            $action = "une erreur s'est produite lors de la modification d'un corps d'armée". $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource from storage.
     */
    public function restaureCorps($id)
    {
        try {
            DB::beginTransaction();

            $CorpsRestaure = Corps::withTrashed()->findOrFail($id);

            $CorpsRestaure->status = 1;
            $CorpsRestaure->restore();
            $message = "Corps restauré avec succès !";
            // $ville->save();
            $module = "Module Corps d'armée";
            $action = "a restauré le corps d'armée : $CorpsRestaure->libelle";
            Logs::saveLog($module, $action);
            DB::commit();

            toast($message, 'success');

            return redirect()->route('corps.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)
            Log::error('Erreur interne du serveur: ' . $e->getMessage());
            $module = "Module Corps d'armée";
            $action = "une erreur s'est produite lors de la restauration d'un corps d'armée";
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Corps $corps)
    {
        try {
            DB::beginTransaction();

            $message = "";
            # code...
            $corps->status = 2;
            $corps->save();
            $message = "Corps Supprimé avec succès !";
            $module = "Module Corps d'armée";
            $action = "A supprimer un corps d'armée";
            Logs::saveLog($module, $action);
            $corps->delete();


            DB::commit();

            toast($message, 'success');

            return redirect()->route('corps.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)
            Log::error('Erreur interne du serveur: ' . $e->getMessage());
            $module = "Module Corps d'armée";
            $action = "Une erreur s'est produite lors de la suppression d'un corps d'armée";
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }
}
