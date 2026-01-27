<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Logs;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\MutualisteLoginRequest;

class ConnexionMutualisteController extends Controller
{
    public function connexionMutualiste(MutualisteLoginRequest $request){
        try {
            // Valider les données de la requête
            // $validator = $request->validated();

            // Vérifier si l'e-mail existe dans la table "users"
            $user = User::where('email', $request->email)->first();


            // Si l'e-mail existe dans la table "user" et le mot de passe est correct, connecter l'utilisateur
            if ($user && password_verify($request->password, $user->password)) {
                Auth::login($user);

                // Vérifier si l'utilisateur connecté a l'un des rôles spécifiques avant de le rediriger
                if (Auth::user()->hasRole('mutualiste')) {
                    // Rediriger l'utilisateur vers /dashboard
                    $message = "Bienvenue ! ".formatGender(auth()->user()->mutualiste->genre)."".auth()->user()->mutualiste->nom." ".auth()->user()->mutualiste->prenom.".";
                    toast($message, 'success');
                    $module = "Module Connexion mutualiste ";
                    $action = "L'mutualiste  " . auth()->user()->mutualiste->nom . " " . auth()->user()->mutualiste->prenom . "a l'id" . auth()->user()->mutualiste->id." a ete connecter";
                    Logs::saveLog($module, $action);
                    return redirect()->route('espace.accueil');
                } else {
                    // Déconnecter l'utilisateur
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    toast('Connecté vous ici ', 'warning');
                    $module = "Module Connexion  mutualiste";
                    $action = "mutualiste  a un mot de passe incorrect";
                    Logs::saveLog($module, $action);
                    return redirect()->route('login');
                }
            } else {
                // Si l'e-mail n'est pas trouvé dans la table "users" ou le mot de passe est incorrect, afficher un message d'erreur
                // toast('Mot de passe incorrect.', 'error');

                return back()->withInput()->withErrors(['password' => 'Mot de passe incorrect.']);
            }
        } catch (\Exception $e) {
            // Gérer les erreurs
            toast('Une erreur s\'est produite. Veuillez réessayer plus tard.', 'error');
            $module = "Module Connexion  mutualiste";
            $action = "une erreur s'est produite lors de la connexion d'un mutualiste";
            Logs::saveLog($module, $action);
            return back()->withInput()->withErrors(['error' => 'Une erreur s\'est produite. Veuillez réessayer plus tard.']);
        }
    }

}
