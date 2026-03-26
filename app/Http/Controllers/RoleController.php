<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        //
        $us = auth()->user()->administrateur;
        $roles = Role::all();
        return view('dashboard.roles.index', compact('roles', 'us'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $us = auth()->user()->administrateur;
        $permissions = Permission::all();

        // Grouper par le suffixe (après le dernier '-')
        $groupedPermissions = $permissions->groupBy(function ($permission) {
            return Str::afterLast($permission->name, '-');
        });
        return view('dashboard.roles.create', compact('groupedPermissions', 'us'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        //
        try {
            DB::beginTransaction();

            $role = Role::create(['name' => $request->name]);
            $role->syncPermissions($request->permissions);

            DB::commit();

            toast('Rôle créé avec succès !', 'success');

            return redirect()->route('roles.index');
        } catch (\Throwable $e) {
            DB::rollBack();

            toast('Une erreur s\'est produite, veuillez réessayer.', 'error');

            // Log personnalisé
            $module = "Module Rôle";
            $action = "Une erreur s'est produite lors de l'ajout d'un rôle.";
            Logs::saveLog($module, $action);

            // Log technique pour debug
            Log::error('Erreur interne du serveur: ' . $e->getMessage());

            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        // $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $us = auth()->user()->administrateur;

        $groupedPermissions = $permissions->groupBy(function ($permission) {
            return Str::afterLast($permission->name, '-');
        });

        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('dashboard.roles.show', compact('role', 'groupedPermissions', 'rolePermissions', 'us'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        //
        $permissions = Permission::all();

        $groupedPermissions = $permissions->groupBy(function ($permission) {
            return Str::afterLast($permission->name, '-');
        });
        $us = auth()->user()->administrateur;

        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('dashboard.roles.edit', compact('role', 'groupedPermissions', 'rolePermissions', 'us'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        //
        try {
            DB::beginTransaction();

            $role->update(['name' => $request->name]);
            $role->syncPermissions($request->permissions);

            DB::commit();

            toast('Rôle mis à jour avec succès !', 'success');

            return redirect()->route('roles.edit', $role->id);
        } catch (\Throwable $e) {
            DB::rollBack();

            toast('Une erreur s\'est produite, veuillez réessayer.', 'error');

            $module = "Module Rôle";
            $action = "Une erreur s'est produite lors de la mise à jour d'un rôle.";
            Logs::saveLog($module, $action);

            Log::error('Erreur interne du serveur: ' . $e->getMessage());

            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        try {
            DB::beginTransaction();
            // Vérifier si le rôle est utilisé par des utilisateurs
            if ($role->users()->count() > 0) {
                toast('Impossible de supprimer ce rôle car il est associé à des utilisateurs.', 'error');
                return redirect()->route('roles.index');
            }

            // Détacher toutes les permissions associées au rôle
            $role->permissions()->detach();

            // Supprimer le rôle
            $role->delete();

            DB::commit();

            toast('Rôle supprimé avec succès !', 'success');

            return redirect()->route('roles.index');
        } catch (\Exception $e) {
            DB::rollBack();

            toast('Une erreur s\'est produite, veuillez réessayer.', 'error');

            $module = "Module Rôle";
            $action = "Une erreur s'est produite lors de la supression d'un rôle.";
            Logs::saveLog($module, $action);

            Log::error('Erreur interne du serveur: ' . $e->getMessage());

            return redirect()->back();
        }
    }
}
