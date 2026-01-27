<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\Direction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreDirectionRequest;
use App\Http\Requests\UpdateDirectionRequest;

class DirectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $directions = Direction::orderBy('libelle', 'ASC')->withTrashed()->get();
        $module = "Module Direction";
        $action = "a consulte la liste des directions ";
        Logs::saveLog($module, $action);
        return view('dashboard.directions.index', compact('directions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $module = "Module Direction";
        $action = "a affiche la page de creation d'une direction";
        Logs::saveLog($module, $action);
        return view('dashboard.directions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDirectionRequest $request)
    {
        try {
            DB::beginTransaction();

            Direction::create([
                'libelle' => $request->libelle,
                'description' => $request->description ?? ''
            ]);

            DB::commit();

            toast('Direction ajoutée avec succès !', 'success');

            // Rediriger l'utilisateur ou effectuer d'autres actions
            $module = "Module Direction";
            $action = "a enregistre la direction  :$request->libelle";
            Logs::saveLog($module, $action);
            return redirect()->route('directions.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();


            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
            // Capturer toute autre exception (erreur 500)
            Log::error('Erreur interne du serveur: ' . $e->getMessage());
            $module = "Module Direction";
            $action = "une erreur serveur s'est produite lors de l'enregistrement d'une direction" . $e->getMessage();
            Logs::saveLog($module, $action);


            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Direction $direction)
    {
        $module = "Module Direction";
        $action = "a affiche la page de detail de la direction : $direction->id , $direction->libelle";
        Logs::saveLog($module, $action);
        return view('dashboard.directions.show', compact('direction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Direction $direction)
    {
        $module = "Module Direction";
        $action = "a affiche la page d'edition de la direction : $direction->id , $direction->libelle";
        Logs::saveLog($module, $action);
        return view('dashboard.directions.edit', compact('direction'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDirectionRequest $request, Direction $direction)
    {
        try {
            DB::beginTransaction();

            // update ville label if label has changed
            if ($direction->libelle !== $request->libelle) {
                $libelleExists = Direction::where('libelle', $request->libelle)->withTrashed()->exists();

                if ($libelleExists) {
                    DB::rollBack();
                    toast("Cette direction '$request->libelle' existe déjà, mise à jour annulée.", 'error');
                    return redirect()->back();
                }


                $direction->libelle = $request->libelle;
            }

            if ($direction->description !== $request->description) {
                $direction->description = $request->description;
            }

            $direction->save();
            DB::commit();
            $module = "Module Direction";
            $action = "a modifier une direction";
            Logs::saveLog($module, $action);
            toast('Direction modifiée avec succès !', 'success');

            return redirect()->route('directions.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)
            Log::error('Erreur interne du serveur: ' . $e->getMessage());
            $module = "Module Direction";
            $action = "Un erreur s'estn produit lors de la modification des information d'une direction";
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource from storage.
     */
    public function restaureDirection($id)
    {
        try {
            DB::beginTransaction();

            $DirectionRestaure = Direction::withTrashed()->findOrFail($id);

            $DirectionRestaure->status = 1;
            $DirectionRestaure->restore();
            $message = "Direction restaurée avec succès !";
            // $ville->save();

            DB::commit();
            $module = "Module Direction";
            $action = "a restaure la direction ayant l'id $DirectionRestaure->id";
            Logs::saveLog($module, $action);
            toast($message, 'success');

            return redirect()->route('directions.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)
            Log::error('Erreur interne du serveur: ' . $e->getMessage());
            $module = "Module Direction";
            $action = "une erreur serveur s'est produite lors de la restauration d'une direction";
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Direction $direction)
    {
        try {
            DB::beginTransaction();

            $message = "";
            # code...
            $direction->status = 2;
            $direction->save();
            $message = "Direction Supprimée avec succès !";
            $direction->delete();


            DB::commit();
            $module = "Module Direction";
            $action = "a supprimer la direction ayant l'id : $direction->id";
            Logs::saveLog($module, $action);
            toast($message, 'success');

            return redirect()->route('directions.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)
            Log::error('Erreur interne du serveur: ' . $e->getMessage());
            $module = "Module Direction";
            $action = "une erreur serveur s'est produite lors de la suppression de la direction";
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }
}
