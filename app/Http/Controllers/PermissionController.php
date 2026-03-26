<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $us = Auth()->user()->administrateur;
        $permissions = Permission::all()->map(function ($permission) {
            $permission->group = Str::afterLast($permission->name, '-');
            return $permission;
        });

        return view('dashboard.permissions.index', compact('permissions', 'us'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $us = Auth()->user()->administrateur;
        return view('dashboard.permissions.create', compact('us'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
        ], [
            'name.required' => 'Le nom de la permission est requis.',
            'name.unique' => 'Cette permission existe déjà.',
        ]);
        // dd($request->all());

        try {
            DB::beginTransaction();

            Permission::create([
                'name' => $validated['name'],
                'guard_name' => 'web',
            ]);

            DB::commit();
            toast('Permission créée avec succès.', 'success');
            return redirect()->route('permissions.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création de la permission : ' . $e->getMessage());
            $module = 'Permissions';
            $action = 'Création de la permission';
            Logs::saveLog($module, $action, $e->getMessage(), 'Erreur');
            return redirect()->back()->withErrors(['error' => 'Erreur lors de la création de la permission.'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        return view('dashboard.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  Permission $permission)
    {
        //
        $request->validate([
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'Le nom de la permission est requis.',
            // 'name.unique' => 'Cette permission existe déjà.',
        ]);

        try {

            DB::beginTransaction();
            // Vérifier si la permission existe déjà
            if (Permission::where('name', $request->name)->where('id', '!=', $permission->id)->exists()) {
                toast('Cette permission existe déjà.', 'warning');
                return redirect()->back()->withInput();
            }

            // Mettre à jour la permission
            $permission->update([
                'name' => $request->name,
                'guard_name' => 'web', // Assurez-vous que le guard_name est correct
            ]);

            DB::commit();
            toast('Permission mise à jour avec succès.', 'success');
            // Rediriger vers la liste des permissions
            return redirect()->route('permissions.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            // En cas d'erreur, afficher un message d'erreur
            toast('Erreur lors de la mise à jour de la permission : ' . $e->getMessage(), 'error');
            // Rediriger vers la page précédente ou vers une page d'erreur
            Log::error('Erreur lors de la mise à jour de la permission : ' . $e->getMessage());
            $module = 'Permissions';
            $action = 'Mise à jour de la permission';
            Logs::saveLog($module, $action, $e->getMessage(), 'Erreur');
            return redirect()->back()->withErrors(['error' => 'Erreur lors de la mise à jour de la permission.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        try {
            DB::beginTransaction();
            // Vérifier si la permission est utilisée par des rôles
            if ($permission->roles()->count() > 0) {
                toast('Cette permission ne peut pas être supprimée car elle est utilisée par des rôles.', 'warning');
                return redirect()->route('permissions.index');
            }

            // Supprimer la permission
            $permission->delete();

            DB::commit();
            toast('Permission supprimée avec succès.', 'success');
            // Rediriger vers la liste des permissions
            return redirect()->route('permissions.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            // En cas d'erreur, afficher un message d'erreur
            toast('Erreur lors de la suppression de la permission : ' . $e->getMessage(), 'error');
            // Rediriger vers la page précédente ou vers une page d'erreur
            Log::error('Erreur lors de la suppression de la permission : ' . $e->getMessage());
            $module = 'Permissions';
            $action = 'Suppression de la permission';
            Logs::saveLog($module, $action, $e->getMessage(), 'Erreur');
            return redirect()->back()->withErrors(['error' => 'Erreur lors de la suppression de la permission.']);
        }
    }
}
