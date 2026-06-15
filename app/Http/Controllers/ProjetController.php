<?php

namespace App\Http\Controllers;

use DOMDocument;
use App\Models\Logs;
use App\Models\Projet;
use App\Models\ImageProjet;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreProjetRequest;
use App\Http\Requests\UpdateProjetRequest;

class ProjetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projets = Projet::orderBy('libelle', 'asc')->withTrashed()->get();
        $module = "Module  projet";
        $action = "a consulte la liste des projet";
        Logs::saveLog($module, $action);
        return view('dashboard.projets.index', compact('projets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $module = "Module  projet";
        $action = "a affiche la page de creation d'un projet ";
        Logs::saveLog($module, $action);
        return view('dashboard.projets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjetRequest $request)
    {
        try {
            DB::beginTransaction();

            $lien_photo = null;

            if ($request->hasFile('lien_photo')) {
                $file_name = md5(uniqid()) . '.' . $request->file('lien_photo')->extension();
                $request->file('lien_photo')->storeAs('images-projets/', $file_name);
                $lien_photo = 'src-files/images-projets/' . $file_name;
            }

            $projet = Projet::create([
                'administrateur_id' => auth()->user()->administrateur->id,
                'libelle' => $request->libelle,
                'description' => $request->description ?? '',
                'lien_photo' => $lien_photo,
            ]);


            // Vérifiez si le champ 'lien_image' existe et contient des fichiers
            if ($request->hasFile('lien_image')) {
                // Bouclez sur les fichiers téléchargés
                foreach ($request->file('lien_image') as $index => $file) {
                    // Générez un nom de fichier unique avec l'extension d'origine
                    $file_name = md5(uniqid()) . $index . '.' . $file->extension();

                    // Vérifiez si le fichier existe déjà
                    if (Storage::exists('images-de-projet/' . $file_name)) {
                        // Générez un nouveau nom de fichier unique
                        $file_name = md5(uniqid()) . $index . '.' . $file->extension();
                    }

                    // Stockez le fichier dans le répertoire 'image_projets'
                    $file->storeAs('images-de-projet/', $file_name);
                    $lien_images_projet = 'src-files/images-de-projet/' . $file_name;

                    // Créez un enregistrement dans la table 'image_projets'
                    ImageProjet::create([
                        'projet_id' => $projet->id,
                        'lien_image' => $lien_images_projet,
                        'type_image' => "Image de projet"
                    ]);
                }
            }


            DB::commit();

            toast('Projet ajouté avec succès !', 'success');
            $module = "Module  projet";
            $action = "a enregiste le projet ayant l'id : $projet->id";
            Logs::saveLog($module, $action);
            // Rediriger l'utilisateur ou effectuer d'autres actions
            return redirect()->route('projets.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
            // Capturer toute autre exception (erreur 500)
            Log::error('Erreur interne du serveur: ' . $e->getMessage());
            $module = "Module  projet";
            $action = "Une erreur s'est produit lors de l'enregistrement d'un projet";
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Projet $projet)
    {
        $module = "Module  projet";
        $action = "a affiche la page de detail du projet ayant l'id : $projet->id";
        Logs::saveLog($module, $action);
        return view('dashboard.projets.show', compact('projet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Projet $projet)
    {
        $module = "Module  projet";
        $action = "a affiche la page de d'edition du projet ayant l'id : $projet->id";
        Logs::saveLog($module, $action);
        return view('dashboard.projets.edit', compact('projet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjetRequest $request, Projet $projet)
    {
        try {
            DB::beginTransaction();

            $files = $request->hasFile('lien_photo');
            $lien_photo = null;

            $description = $request->description;

            if ($files && !empty($projet->lien_photo)) {
                if (File::exists(public_path($projet->lien_photo))) {
                    File::delete(public_path($projet->lien_photo));
                }

                $file_name = md5(uniqid()) . '.' . $request->file('lien_photo')->extension();
                // $file_name = Carbon::now()->timestamp . '.' . $request->file('lien_photo')->extension();
                $request->lien_photo->storeAs('images-projets/', $file_name);
                $lien_photo = 'src-files/images-projets/' . $file_name;
                $projet->lien_photo = $lien_photo;
            }

            $projet->administrateur_id = auth()->user()->administrateur->id;

            if ($projet->libelle !== $request->libelle) {
                $libelleExists = Projet::where('libelle', $request->libelle)->withTrashed()->exists();

                if ($libelleExists) {
                    DB::rollBack();
                    toast("Ce projet '$request->libelle' existe déjà, mise à jour annulée.", 'error');
                    return redirect()->back();
                }

                $projet->libelle = $request->libelle;
            }

            if ($projet->description !== $request->description) {
                $projet->description = $description;
            }

            $projet->save();

            if ($projet->imagesProjet()->count() >= 0 && $request->file('lien_image')) {
                $projet->imagesProjet()->delete();
                // Bouclez sur les fichiers téléchargés pour ajouter les nouvelles images
                foreach ($request->file('lien_image') as $index => $file) {
                    // Générez un nom de fichier unique avec l'extension d'origine
                    $file_name = md5(uniqid()) . $index . '.' . $file->extension();

                    // Vérifiez si le fichier existe déjà
                    if (Storage::exists('images-de-projet/' . $file_name)) {
                        // Générez un nouveau nom de fichier unique
                        $file_name = md5(uniqid()) . $index . '.' . $file->extension();
                    }

                    // Stockez le fichier dans le répertoire 'images-de-projet'
                    $file->storeAs('images-de-projet/', $file_name);
                    $lien_images_projet = 'src-files/images-de-projet/' . $file_name;

                    // Créez un enregistrement dans la table 'image_projets'
                    ImageProjet::create([
                        'projet_id' => $projet->id,
                        'lien_image' => $lien_images_projet,
                        'type_image' => "Image de projet"
                    ]);
                }
            }


            DB::commit();

            toast('Projet modifié avec succès !', 'success');
            $module = "Module  projet";
            $action = "a modifier un projet";
            Logs::saveLog($module, $action);
            return redirect()->route('projets.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module  projet";
            $action = "une erreur s'est produit lors de la mise a jour d'un projet " . $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource from storage.
     */
    public function restaureProjet($id)
    {
        try {
            DB::beginTransaction();

            $projetRestaure = Projet::withTrashed()->findOrFail($id);

            $projetRestaure->status = 1;
            $projetRestaure->restore();
            $message = "Projet restauré avec succès !";

            DB::commit();

            toast($message, 'success');
            $module = "Module  projet";
            $action = "a restaure un projet " ;
            Logs::saveLog($module, $action);
            return redirect()->route('projets.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module  projet";
            $action = "une erreur s'est produit lors de la restauration d'un projet " . $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Projet $projet)
    {
        try {
            DB::beginTransaction();

            $message = "";
            # code...
            $projet->status = 2;
            $projet->save();
            $message = "Projet Supprimé avec succès !";
            $projet->delete();

            DB::commit();

            toast($message, 'success');
            $module = "Module  projet";
            $action = "a supprimer un projet " ;
            Logs::saveLog($module, $action);
            return redirect()->route('projets.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module  projet";
            $action = "une erreur s'est produit lors de la suppression d'un projet " . $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }
}
