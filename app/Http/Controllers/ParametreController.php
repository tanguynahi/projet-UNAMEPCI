<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\Ville;
use App\Models\Parametre;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\StoreParametreRequest;
use App\Http\Requests\UpdateParametreRequest;
use App\Http\Requests\UpdateParametreLienReseauxSociauxRequest;

class ParametreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $villes = Ville::orderBy('libelle')->get();
        $administrateur = auth()->user()->administrateur;
        $parametre = Parametre::whereId(1)->first();

        $module = "Module Parametre";
        $action = " a consulte la liste de la table parametre";
        Logs::saveLog($module, $action);
        return view('dashboard.parametres.index', compact('villes', 'administrateur', 'parametre'));
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
    public function store(StoreParametreRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Parametre $parametre)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Parametre $parametre)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateParametreRequest $request, Parametre $parametre)
    {
    }

    public function updateParametreInfosSite(UpdateParametreRequest $request, Parametre $parametre)
    {
        // dd('test');
        try {
            DB::beginTransaction();

            $files = $request->hasFile('lien_logo');
            $lien_logo = null;

            $files2 = $request->hasFile('lien_photo_directeur');
            $lien_photo_directeur = null;

            $mot_du_directeur = $request->mot_du_directeur;

            // Log the file presence
            // Log::info('lien du logo si charger: ' . $files);
            // Log::info('lien de la photo du Directeur si charger: ' . $files2);

            if ($files) {
                // Log::info('Supression du logo s\'il y\'en avait');

                if (File::exists(public_path($parametre->lien_logo))) {
                    File::delete(public_path($parametre->lien_logo));
                }

                $file_name = md5(uniqid()) . '.' . $request->file('lien_logo')->extension();
                $request->lien_logo->storeAs('images-parametres/', $file_name);
                $lien_logo = 'src-files/images-parametres/' . $file_name;
            }

            if ($files2) {
                // Log::info('Supression de la photo du Directeur s\'il y\'en avait');

                if (File::exists(public_path($parametre->lien_photo_directeur))) {
                    File::delete(public_path($parametre->lien_photo_directeur));
                }

                $file_name2 = md5(uniqid()) . '.' . $request->file('lien_photo_directeur')->extension();
                $request->lien_photo_directeur->storeAs('images-parametres/', $file_name2);
                $lien_photo_directeur = 'src-files/images-parametres/' . $file_name2;
            }

            $parametre->lien_logo = $lien_logo ? $lien_logo : $parametre->lien_logo;
            $parametre->lien_photo_directeur = $lien_photo_directeur ? $lien_photo_directeur : $parametre->lien_photo_directeur;

            $parametre->administrateur_id = auth()->user()->administrateur->id;

            $parametre->nom_site_web = $request->nom_site_web;
            $parametre->mot_du_directeur = $mot_du_directeur;
            $parametre->contact_1 = $request->contact_1;
            $parametre->contact_2 = $request->contact_2;
            $parametre->email_1 = $request->email_1;
            $parametre->email_2 = $request->email_2;
            $parametre->adresse = $request->adresse_site;
            $parametre->lien_video = $request->lien_video;
            $parametre->lien_google_map = $request->lien_google_map;

            $parametre->save();

            DB::commit();

            toast('Infos du site web modifiées avec succès !', 'success');
            $module = "Module Parametre";
            $action = " a ajouter les information dans la table parametre";
            Logs::saveLog($module, $action);
            return redirect()->route('parametres.index')->withFragment('#list-item-3');
        } catch (\Throwable $e) {
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            Log::error('Erreur interne du serveur: ' . $e->getMessage());
            $module = "Module Parametre";
            $action = " une erreur s'est produite lors de l'ajout des information de la table parametre";
            Logs::saveLog($module, $action);
            $previousUrl = url()->previous() . '#list-item-3';

            return redirect($previousUrl)->withInput();
        }
    }

    public function updateParametreLienReseauxSociaux(UpdateParametreLienReseauxSociauxRequest $request, Parametre $parametre)
    {
        try {
            DB::beginTransaction();

            $parametre->administrateur_id = auth()->user()->administrateur->id;

            // Mettre à jour les liens des réseaux sociaux s'ils sont présents dans la requête
            $parametre->lien_facebook = $request->filled('lien_facebook') ? $request->lien_facebook : null;
            $parametre->lien_twitter = $request->filled('lien_twitter') ? $request->lien_twitter : null;
            $parametre->lien_instagram = $request->filled('lien_instagram') ? $request->lien_instagram : null;
            $parametre->lien_linkedin = $request->filled('lien_linkedin') ? $request->lien_linkedin : null;
            $parametre->lien_youtube = $request->filled('lien_youtube') ? $request->lien_youtube : null;
            $parametre->lien_whatsapp = $request->filled('lien_whatsapp') ? $request->lien_whatsapp : null;

            // Sauvegarder les changements
            $parametre->save();

            DB::commit();

            toast('Liens des réseaux sociaux modifiés avec succès !', 'success');
            $module = "Module Parametre";
            $action = " a modifier les information consernant les lien de site de la table parametre";
            Logs::saveLog($module, $action);
            return redirect()->route('parametres.index')->withFragment('#list-item-4');
        } catch (\Throwable $e) {
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            Log::error('Erreur interne du serveur: ' . $e->getMessage());
            $module = "Module Parametre";
            $action = " une erreur s'est produite lors de la modification des lien de la table parametre du site";
            Logs::saveLog($module, $action);
            $previousUrl = url()->previous() . '#list-item-4';

            return redirect($previousUrl)->withInput();
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Parametre $parametre)
    {
        //
    }
}
