<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\Projet;
use App\Models\ImageProjet;
use Illuminate\Http\Request;
use App\Models\ProduitProjet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreProduitProjetRequest;
use App\Http\Requests\UpdateProduitProjetRequest;

class ProduitProjetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $projet_id = $request->projet_id;
        $produitprojets = ProduitProjet::whereProjetId($projet_id)->orderBy('libelle', 'asc')->withTrashed()->get();

        $projet = Projet::where('status',1)
        ->whereId($request->projet_id)->first();

        // dd($projet);
        $module = "Module produit projet";
        $action = " a consulte la liste des produits d'un projet";
        Logs::saveLog($module, $action);
        return view('dashboard.projets.produits_projet.index', compact(['produitprojets', 'projet']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $projet_id = $request->projet_id;
        $module = "Module produit projet";
        $action = " a affiche la page de creation d'un produit d'un projet";
        Logs::saveLog($module, $action);
        return view('dashboard.projets.produits_projet.create', compact('projet_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProduitProjetRequest $request)
    {
        try {

            DB::beginTransaction();

            $projet_id = $request->projet_id;

            $lien_photo = null;

            if ($request->hasFile('lien_photo')) {
                $file_name = md5(uniqid()) . '.' . $request->file('lien_photo')->extension();
                $request->file('lien_photo')->storeAs('images-produits/', $file_name);
                $lien_photo = 'src-files/images-produits/' . $file_name;
            }

            $produit = ProduitProjet::create([
                'administrateur_id' => auth()->user()->administrateur->id,
                'projet_id' => $projet_id,
                'type_paiement_id' => 4,
                'lien_photo' => $lien_photo,
                'libelle' => $request->libelle,
                'cout' => $request->cout,
                'contribution' => $request->contribution,
                'quantite' => $request->quantite,
                'description' => $request->description ?? '',
            ]);

            if ($request->hasFile('lien_image')) {
                // Bouclez sur les fichiers téléchargés
                foreach ($request->file('lien_image') as $index => $file) {
                    // Générez un nom de fichier unique avec l'extension d'origine
                    $file_name = md5(uniqid()) . $index . '.' . $file->extension();
                    // Vérifiez si le fichier existe déjà
                    if (Storage::exists('images-de-produit/' . $file_name)) {
                        // Générez un nouveau nom de fichier unique
                        $file_name = md5(uniqid()) . $index . '.' . $file->extension();
                    }

                    // Stockez le fichier dans le répertoire 'image_produit'
                    $file->storeAs('images-de-produit/', $file_name);
                    $lien_images_produit = 'src-files/images-de-produit/' . $file_name;

                    // Créez un enregistrement dans la table 'image_projets'

                    ImageProjet::create([
                        'produit_projet_id' => $produit->id,
                        'lien_image' => $lien_images_produit,
                        'type_image' => "Image de produit"
                    ]);
                }
            }


            DB::commit();

            toast('Produit ajouté avec succès !', 'success');
            $module = "Module produit projet";
            $action = " a enregistre le produit d'un projet";
            Logs::saveLog($module, $action);
            // Rediriger l'utilisateur ou effectuer d'autres actions
            return redirect()->route('produitprojets.index', ['projet_id' => $projet_id]);
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            // dd($e->getMessage());
            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');

            $module = "Module produit projet";
            $action = " une erreur s'est produit lors de l'ajout d'un produit d'un projet". $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProduitProjet $produitprojet)
    {
        $module = "Module produit projet";
        $action = "a  affiche la page de detail de  produit ayant id : $produitprojet->id";
        Logs::saveLog($module, $action);
        return view('dashboard.projets.produits_projet.show', compact('produitprojet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProduitProjet $produitprojet)
    {
        $module = "Module produit projet";
        $action = "a  affiche la page d'edition de  produit ayant id : $produitprojet->id";
        Logs::saveLog($module, $action);
        return view('dashboard.projets.produits_projet.edit', compact(['produitprojet']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProduitProjetRequest $request, ProduitProjet $produitprojet)
    {
        try {
            DB::beginTransaction();

            $files = $request->hasFile('lien_photo');
            $lien_photo = null;

            $description = $request->description;

            if ($files && !empty($produitprojet->lien_photo)) {
                if (File::exists(public_path($produitprojet->lien_photo))) {
                    File::delete(public_path($produitprojet->lien_photo));
                }

                $file_name = md5(uniqid()) . '.' . $request->file('lien_photo')->extension();
                // $file_name = Carbon::now()->timestamp . '.' . $request->file('lien_photo')->extension();
                $request->lien_photo->storeAs('images-produits/', $file_name);
                $lien_photo = 'src-files/images-produits/' . $file_name;
                $produitprojet->lien_photo = $lien_photo;
            }

            $produitprojet->administrateur_id = auth()->user()->administrateur->id;

            if ($produitprojet->libelle !== $request->libelle) {
                $produitprojet->libelle = $request->libelle;
            }

            if ($produitprojet->cout !== $request->cout) {
                $produitprojet->cout = $request->cout;
            }

            if ($produitprojet->contribution !== $request->contribution) {
                $produitprojet->contribution = $request->contribution;
            }

            if ($produitprojet->quantite !== $request->quantite) {
                $produitprojet->quantite = $request->quantite;
            }

            if ($produitprojet->description !== $request->description) {
                $produitprojet->description = $description;
            }

            $produitprojet->save();

            if ($produitprojet->imageProjets()->count() >= 0 && $request->file('lien_image')) {
                // $produitprojet->imagesProjet()->delete();
                // Supprimer les enregistrements des images dans la base de données
                // Supprimer les enregistrements des images dans la base de données
                $images = $produitprojet->imageProjets;
                // dd($images);

                foreach ($images as $image) {
                    // Supprimer physiquement les fichiers du système de fichiers
                    if (Storage::exists($image->lien_image)) {
                        Storage::delete($image->lien_image);
                    }
                    // Supprimer l'enregistrement de l'image dans la base de données
                    $image->delete();
                }
                // Bouclez sur les fichiers téléchargés pour ajouter les nouvelles images
                foreach ($request->file('lien_image') as $index => $file) {
                    // Générez un nom de fichier unique avec l'extension d'origine
                    $file_name = md5(uniqid()) . $index . '.' . $file->extension();

                    // Vérifiez si le fichier existe déjà
                    if (Storage::exists('images-de-produit/' . $file_name)) {
                        // Générez un nouveau nom de fichier unique
                        $file_name = md5(uniqid()) . $index . '.' . $file->extension();
                    }

                    // Stockez le fichier dans le répertoire 'image_produit'
                    $file->storeAs('images-de-produit/', $file_name);
                    $lien_images_produit = 'src-files/images-de-produit/' . $file_name;

                    // Créez un enregistrement dans la table 'image_produit'
                    ImageProjet::create([
                        'produit_projet_id' => $produitprojet->id,
                        'lien_image' => $lien_images_produit,
                        'type_image' => "Image de produit"
                    ]);
                }
            }


            DB::commit();

            toast('Produit modifié avec succès !', 'success');
            $module = "Module produit projet";
            $action = "a  modifier un produit";
            Logs::saveLog($module, $action);
            return redirect()->route('produitprojets.index', ['projet_id' => $produitprojet->projet_id]);
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');

            $module = "Module produit projet";
            $action = "Une erreur s'est produit lors de la mise a jour d'un produit " . $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource from storage.
     */
    public function restaureProduitProjet($id)
    {
        try {
            DB::beginTransaction();

            $produitRestaure = ProduitProjet::withTrashed()->findOrFail($id);

            $produitRestaure->status = 1;
            $produitRestaure->restore();
            $message = "Produit restauré avec succès !";

            DB::commit();

            toast($message, 'success');
            $module = "Module produit projet";
            $action = "a restaure un produit ayant id $produitRestaure->id ";
            Logs::saveLog($module, $action);
            return redirect()->route('produitprojets.index', ['projet_id' => $produitRestaure->projet_id]);
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module produit projet";
            $action = "Une erreur s'est produit lors de la restauration d'un produit " . $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProduitProjet $produitprojet)
    {
        try {
            DB::beginTransaction();

            $message = "";
            # code...
            $produitprojet->status = 2;
            $produitprojet->save();
            $message = "Produit Supprimé avec succès !";
            $produitprojet->delete();

            DB::commit();

            toast($message, 'success');
            $module = "Module produit projet";
            $action = "a supprime un produit ayant l'id : $produitprojet->id  ";
            Logs::saveLog($module, $action);
            return redirect()->route('produitprojets.index', ['projet_id' => $produitprojet->projet_id]);
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module produit projet";
            $action = "une erreur s'est produit lors de la suppression d'un produit ". $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }



    // la function qui permettra d'afficher la liste des produits selon un projet choisir dans le tableau de bord du mutualiste
    //ProduitProjet $produitProjet
    // public function listeProduitMutualiste(Projet $projet)
    // {
    //     $produitprojets = ProduitProjet::where('projet_id',$projet->id)->get();

    //     return view('home.admin.projets_admin.produits.index'
    //     ,compact(['projet','produitprojets'])
    // );
    // }

    // public function detailProduitMutualiste(ProduitProjet $produitprojet){

    //     return view('home.admin.projets_admin.produits.show',compact('produitprojet'));
    // }

}
