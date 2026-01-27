<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\Grade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreGradeRequest;
use App\Http\Requests\UpdateGradeRequest;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grades = Grade::orderBy('libelle', 'ASC')->withTrashed()->get();
        $module = "Module Grade";
        $action = "a consulte la liste des grades";
        Logs::saveLog($module, $action);
        return view('dashboard.grades.index', compact('grades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $module = "Module Grade";
        $action = "a affiche la page de creation d'un grade";
        Logs::saveLog($module, $action);
        return view('dashboard.grades.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGradeRequest $request)
    {
        try {
            DB::beginTransaction();

            Grade::create([
                'libelle' => $request->libelle,
                'description' => $request->description ?? ''
            ]);

            DB::commit();

            toast('Grade ajouté avec succès !', 'success');
            $module = "Module Grade";
            $action = "a ajouter le grade: $request->libelle ";
            Logs::saveLog($module, $action);
            // Rediriger l'utilisateur ou effectuer d'autres actions
            return redirect()->route('grades.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();
            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module Grade";
            $action = "une erreur s'est produite lors de la creation d'un grade ". $e->getMessage();
            Logs::saveLog($module, $action);

            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Grade $grade)
    {
        $module = "Module Grade";
        $action = "a affiche la page de detail du grade : $grade->libelle ";
        Logs::saveLog($module, $action);
        return view('dashboard.grades.show', compact('grade'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grade $grade)
    {
        $module = "Module Grade";
        $action = "a affiche la page de d'edition du grade : $grade->libelle ";
        Logs::saveLog($module, $action);
        return view('dashboard.grades.edit', compact('grade'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGradeRequest $request, Grade $grade)
    {
        try {
            DB::beginTransaction();

            // update Gradelibelle if libelle has changed
            if ($grade->libelle !== $request->libelle) {
                $libelleExists = Grade::where('libelle', $request->libelle)->withTrashed()->exists();

                if ($libelleExists) {
                    DB::rollBack();
                    toast("Ce Grade '$request->libelle' existe déjà, mise à jour annulée.", 'error');
                    return redirect()->back();
                }

                $grade->libelle = $request->libelle;
            }

            if ($grade->description !== $request->description) {
                $grade->description = $request->description;
            }

            $grade->save();
            DB::commit();

            toast('Grade modifié avec succès !', 'success');
            $module = "Module Grade";
            $action = "a affiche a modifier le grade : $grade->libelle ";
            Logs::saveLog($module, $action);
            return redirect()->route('grades.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)
            Log::error('Erreur interne du serveur: ' . $e->getMessage());
            return redirect()->back();
        }
    }


    /**
     * Update the specified resource from storage.
     */
    public function restaureGrade($id)
    {
        try {
            DB::beginTransaction();

            $GradeRestaure = Grade::withTrashed()->findOrFail($id);

            $GradeRestaure->status = 1;
            $GradeRestaure->restore();
            $message = "Grade restauré avec succès !";
            // $ville->save();

            DB::commit();

            toast($message, 'success');
            $module = "Module Grade";
            $action = "a restaure le grade ayant l'id : $GradeRestaure->id ";
            Logs::saveLog($module, $action);
            return redirect()->route('grades.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module Grade";
            $action = "une erreur serveur s'est produite lors de la restauration d'un grade ". $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grade $grade)
    {
        try {
            DB::beginTransaction();

            $message = "";
            # code...
            $grade->status = 2;
            $grade->save();
            $message = "Grade Supprimé avec succès !";
            $grade->delete();

            $module = "Module Grade";
            $action = "Le grade ayant l'id : $grade->id a ete supprimée avec succes";
            Logs::saveLog($module, $action);
            DB::commit();

            toast($message, 'success');

            return redirect()->route('grades.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)

            $module = "Module Grade";
            $action = "une erreur serveur s'est produite lors de la suppression d'un grade ". $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }
}
