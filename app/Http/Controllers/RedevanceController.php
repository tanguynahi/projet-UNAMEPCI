<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\Periode;
use App\Models\Redevance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreRedevanceRequest;
use App\Http\Requests\UpdateRedevanceRequest;

class RedevanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:super-administrateur|administrateur')->only(['index', 'create', 'store', 'show', 'edit', 'update']);
        $this->middleware('role:super-administrateur')->only(['restaureRedevance', 'destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $redevances = Redevance::orderBy('libelle', 'ASC')->withTrashed()->get();
        $module = "Module redevance";
        $action = "a consulte la liste des redevances";
        Logs::saveLog($module, $action);
        return view('dashboard.redevances.index', compact('redevances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $periodes = Periode::all();
        $module = "Module redevance";
        $action = "a affiche la page de creation d'une redevance";
        Logs::saveLog($module, $action);
        return view('dashboard.redevances.create', compact('periodes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRedevanceRequest $request)
    {
        try {
            DB::beginTransaction();

            $redevanceExists = Redevance::where([
                ['periode_id', $request->periode_id],
                ['libelle', $request->libelle],
            ])->exists();

            if ($redevanceExists) {
                toast('Redevance' . $request->libelle . ' déjà existante avec la meme période!', 'error');
                return redirect()->back();
            }

           $redevance = Redevance::create([
                'administrateur_id' => auth()->user()->administrateur->id,
                'periode_id' => $request->periode_id,
                'libelle' => $request->libelle,
                'description' => $request->description ?? ''
            ]);

            DB::commit();

            toast('Redevance ajoutée avec succès !', 'success');
            $module = "Module redevance";
            $action = "a ajouter la redevance ayant : id :$redevance->id ,id periode: $redevance->periode_id , libelle : $redevance->libelle";
            Logs::saveLog($module, $action);
            // Rediriger l'utilisateur ou effectuer d'autres actions
            return redirect()->route('redevances.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast('Une erreur s\'est produit, Veuillez réessayer.', 'error');
            $module = "Module redevance";
            $action = "une erreur serveur s'est produite lors de l'ajout d'une redevance " . $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Redevance $redevance)
    {
        $module = "Module redevance";
        $action = "a affiche la page de detail de la redevance ayant pour id : $redevance->id";
        Logs::saveLog($module, $action);
        return view('dashboard.redevances.show', compact('redevance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Redevance $redevance)
    {
        $periodes = Periode::all();
        $module = "Module redevance";
        $action = "a affiche la page d'edition de la redevance ayant pour id : $redevance->id";
        Logs::saveLog($module, $action);
        return view('dashboard.redevances.edit', compact('redevance', 'periodes'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(UpdateRedevanceRequest $request, Redevance $redevance)
    {
        try {
            DB::beginTransaction();

            $redevance->administrateur_id = auth()->user()->administrateur->id;
            $message = null;

            $samePeriode = $redevance->periode_id == $request->periode_id;
            $sameLibelle = $redevance->libelle == $request->libelle;

            $redevanceExists = Redevance::where([
                ['periode_id', $request->periode_id],
                ['libelle', $request->libelle],
            ])->withTrashed()->exists();

            if ($redevanceExists) {
                if ($samePeriode && $sameLibelle) {
                    $message = toast('Redevance modifiée avec succès !', 'success');
                } else {
                    $message = toast('Redevance déjà existante avec la même période!', 'error');
                }
            } else {
                if (!$samePeriode) {
                    $redevance->periode_id = $request->periode_id;
                }
                if (!$sameLibelle) {
                    $redevance->libelle = $request->libelle;
                }
                $message = toast('Redevance modifiée avec succès !', 'success');
            }

            if ($redevance->description !== $request->description) {
                $redevance->description = $request->description;
            }

            $redevance->save();

            DB::commit();

            $message;
            $module = "Module redevance";
            $action = "a modifier une redevance ayant pour id , $redevance->id";
            Logs::saveLog($module, $action);

            return redirect()->route('redevances.index');
        } catch (\Throwable $e) {
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            $module = "Module redevance";
            $action = "une erreur serveur s'est produite lors la modificaton une redevance " . $e->getMessage();
            Logs::saveLog($module, $action);

            return redirect()->back();
        }
    }


    public function restaureRedevance($id)
    {
        try {
            DB::beginTransaction();

            $RedevanceRestaure = Redevance::withTrashed()->findOrFail($id);

            $RedevanceRestaure->status = 1;
            $RedevanceRestaure->restore();
            $message = "Redevance restaurée avec succès !";

            DB::commit();

            toast($message, 'success');
            $module = "Module redevance";
            $action = "a restaure la redevance ayant pour id : $RedevanceRestaure->id " ;
            Logs::saveLog($module, $action);
            return redirect()->route('redevances.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)
            $module = "Module redevance";
            $action = "une erreur serveur s'est produite lors de la restauration  d'une redevance " . $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Redevance $redevance)
    {
        try {
            DB::beginTransaction();

            $message = "";
            # code...
            $redevance->status = 2;
            $redevance->save();
            $message = "Redevance Supprimée avec succès !";
            $redevance->delete();


            DB::commit();

            toast($message, 'success');
            $module = "Module redevance";
            $action = "a supprimer la redevance ayant pour id : $redevance->id" ;
            Logs::saveLog($module, $action);

            return redirect()->route('redevances.index');
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollBack();

            toast("Une erreur s'est produite, veuillez réessayer.", 'error');
            // Capturer toute autre exception (erreur 500)
            Log::error('Erreur interne du serveur: ' . $e->getMessage());
            $module = "Module redevance";
            $action = "une erreur serveur s'est produite lors de la suppression  d'une redevance " . $e->getMessage();
            Logs::saveLog($module, $action);
            return redirect()->back();
        }
    }
}
