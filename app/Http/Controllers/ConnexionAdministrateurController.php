<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Administrateur;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Requests\AdministrateurLoginRequest;

class ConnexionAdministrateurController extends Controller
{
    public function connexionAdministrateur(AdministrateurLoginRequest $request)
    {
        try {
            // Valider les données de la requête
            // $validator = $request->validated();

            // Vérifier si l'e-mail existe dans la table "users"
            $user = User::where('email', $request->email)->first();


            // Si l'e-mail existe dans la table "admins" et le mot de passe est correct, connecter l'utilisateur
            if ($user && password_verify($request->password, $user->password)) {
                Auth::login($user);

                // Vérifier si l'utilisateur connecté a l'un des rôles spécifiques avant de le rediriger
                if (Auth::user()->hasAnyRole(['super-administrateur', 'administrateur'])) {
                    // Rediriger l'utilisateur vers /dashboard
                    $message = "Bienvenue ! " . formatGender(auth()->user()->administrateur->genre) . "" . auth()->user()->administrateur->nom . " " . auth()->user()->administrateur->prenom . ".";

                       $user->administrateur->update([
                        'disponibilite' => 'en ligne',
                    ]);
                    toast($message, 'success');
                    $module = "Module Connexion Administrateur ";
                    $action = "L'administrateur  " . auth()->user()->administrateur->nom . " " . auth()->user()->administrateur->prenom . "a l'id" . auth()->user()->administrateur->id." a ete connecter";
                    Logs::saveLog($module, $action);
                    return redirect()->route('dashboard');
                } else {
                    // Déconnecter l'utilisateur
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    toast('Connecté vous ici ', 'warning');
                    $module = "Module Connexion Administrateur ";
                    $action = "L'administrateur  a ete deconnecter";
                    Logs::saveLog($module, $action);
                    return redirect()->route('connexion');
                }
            } else {
                // Si l'e-mail n'est pas trouvé dans la table "admins" ou le mot de passe est incorrect, afficher un message d'erreur
                toast('Mot de passe incorrect.', 'error');
                $module = "Module Connexion  Administrateur";
                $action = "L'administrateur  a un mot de passe incorrect";
                Logs::saveLog($module, $action);
                return back()->withInput()->withErrors(['password' => 'Mot de passe incorrect.']);
            }
        } catch (\Exception $e) {
            // Gérer les erreurs
            toast('Une erreur s\'est produite. Veuillez réessayer plus tard.', 'error');
            return back()->withInput()->withErrors(['error' => 'Une erreur s\'est produite. Veuillez réessayer plus tard.']);
        }
    }
}
