<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifierRoleUtilisateur
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Vérifier si l'utilisateur est connecté
        if (Auth::check()) {
            $user = Auth::user();

            // Vérifier si l'utilisateur a l'un des rôles requis
            foreach ($roles as $role) {
                if ($user->hasRole($role)) {
                    return $next($request);
                }
            }

            // Rediriger les super-administrateurs et administrateurs vers le dashboard
            if ($user->hasRole('super-administrateur') || $user->hasRole('administrateur')) {
                return redirect()->route('dashboard');
            }

            // Rediriger les mutualistes vers espace.accueil
            if ($user->hasRole('mutualiste')) {
                return redirect()->route('espace.accueil');
            }
        }

        // L'utilisateur n'est pas connecté, rediriger vers la page de connexion
        return redirect()->route('connexion');
    }

}
