<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\Actualite;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\StoreActualiteRequest;
use App\Http\Requests\UpdateActualiteRequest;
class ActualiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $module = "Module Actualite ";
        $action = " a consulté la liste des actualités ";
        Logs::saveLog($module, $action);
        $actualites = Actualite::orderBy('created_at', 'ASC')->withTrashed()->get();
        return view('dashboard.actualites.index', compact('actualites'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $module = "Module Actualite ";
        $action = " a affiché la page de création d'une actualité ";
        Logs::saveLog($module, $action);
        return view('dashboard.actualites.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreActualiteRequest $request)
    {
        try {
            // dd($request->all());
            DB::beginTransaction();
            if ($request->hasFile('lien_photo')) {
                // $file_name = Carbon::now()->timestamp . '.' . $request->file('lien_photo')->extension();
                $file_name = md5(uniqid()) . '.' . $request->file('lien_photo')->extension();
                $request->file('lien_photo')->storeAs('images-actualites/', $file_name);
                $lien_photo = 'src-files/images-actualites/' . $file_name;
            }
            $actualite = Actualite::create([
                'administrateur_id' => auth()->user()->administrateur->id,
                'libelle' => $request->libelle,
                'description' => $request->description ?? '',
                'lien_photo' => $lien_photo,
                'date_actualite' => $request->date_actualite,
            ]);
            DB::commit();
            toast('Actualité ajoutée avec succès !', 'success');
            // Rediriger l'utilisateur ou effectuer d'autres actions
            $module = "Module Actualite ";
            $action = " a ajouter l'actualites : $actualite->libelle ";
            Logs::saveLog($module, $action);

            return redirect()->route('actualite.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();
            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module Actualite ";
            $action = " Une erreur s'est produite lors de la creation d'une actualite ";
            Logs::saveLog($module, $action);

            Log::error('Erreur interne du serveur: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Actualite $actualite)
    {
        $module = "Module Actualite ";
        $action = " a affiché la page de detail d'une actualité ";
        Logs::saveLog($module, $action);
        return view('dashboard.actualites.show', compact('actualite'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Actualite $actualite)
    {
        $module = "Module Actualite ";
        $action = " a affiché la page d' edition d'une actualité ";
        Logs::saveLog($module, $action);
        return view('dashboard.actualites.edit', compact('actualite'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateActualiteRequest $request, Actualite $actualite)
    {
        try {
            DB::beginTransaction();

            $files = $request->hasFile('lien_photo');
            $lien_photo = null;

            $description = $request->description;

            if ($files && !empty($actualite->lien_photo)) {
                if (File::exists(public_path($actualite->lien_photo))) {
                    File::delete(public_path($actualite->lien_photo));
                }
                // $file_name = Carbon::now()->timestamp . '.' . $request->file('lien_photo')->extension();
                $file_name = md5(uniqid()) . '.' . $request->file('lien_photo')->extension();
                $request->lien_photo->storeAs('images-actualites/', $file_name);
                $lien_photo = 'src-files/images-actualites/' . $file_name;
                $actualite->lien_photo = $lien_photo;
            }

            $actualite->administrateur_id = auth()->user()->administrateur->id;
            // $description = $dom->saveHTML();

            // update qctuqlite label if label has changed
            if ($actualite->libelle !== $request->libelle) {
                $libelleExists = Actualite::where('libelle', $request->libelle)->withTrashed()->exists();

                if ($libelleExists) {
                    DB::rollBack();
                    toast("Cette actualité '$request->libelle' existe déjà, mise à jour annulée.", 'error');
                    return redirect()->back();
                }

                $actualite->libelle = $request->libelle;
            }

            if ($actualite->description !== $request->description) {
                $actualite->description = $description;
            }

            if ($actualite->date_actualite !== $request->date_actualite) {
                $actualite->date_actualite = $request->date_actualite;
            }

            $actualite->save();

            DB::commit();

            toast('Actualité modifiée avec succès !', 'success');
            $module = "Module Actualite ";
            $action = " Actualites a ete modifier avec succes ";
            Logs::saveLog($module, $action);

            return redirect()->route('actualite.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module Actualite ";
            $action = " Une erreur s'est produite lors de la mise a jour d'une actualite ";
            Logs::saveLog($module, $action);
            Log::error('Erreur interne du serveur: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource from storage.
     */
    public function restaureActualite($id)
    {
        try {
            DB::beginTransaction();

            $ActualiteRestaure = Actualite::withTrashed()->findOrFail($id);

            $ActualiteRestaure->status = 1;
            $ActualiteRestaure->restore();
            $message = "Actualité restaurée avec succès !";

            DB::commit();

            toast($message, 'success');
            $module = "Module Actualite ";
            $action = " restauration d'une actualite effectuee avec succes ";
            Logs::saveLog($module, $action);
            return redirect()->route('actualite.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            $module = "Module Actualite ";
            $action = " une erreur s'est produites lors de la restauration d'une actualite ";
            Logs::saveLog($module, $action);
            Log::error('Erreur interne du serveur: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Actualite $actualite)
    {
        try {
            DB::beginTransaction();

            $message = "";
            # code...
            $actualite->status = 2;
            $actualite->save();
            $message = "Actualité Supprimée avec succès !";
            $actualite->delete();

            DB::commit();

            toast($message, 'success');
            $module = "Module Actualite ";
            $action = " Actualite supprimee avec succes";
            Logs::saveLog($module, $action);
            return redirect()->route('actualite.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            $module = "Module Actualite ";
            $action = " Une erreur s'est produites lors de la suppression d'une actualite ";
            Logs::saveLog($module, $action);
            Log::error('Erreur interne du serveur: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
