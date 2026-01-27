<?php

namespace App\Http\Middleware;
use App\Providers\RouteServiceProvider;
use App\Models\DroitAdhesion;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }



    // pour authentifier par rapport a un droit d'adhesion
    // public function handle($request, Closure $next, ...$guards)
    // {
    //     if (Auth::check()) {
    //         $mutualiste = auth()->user()->mutualiste;
    //         // $droit_adhesion = DroitAdhesion::where('mutualiste_id', $mutualiste)->first();
    //         $droit_adhesion =  DroitAdhesion::where('mutualiste_id', $mutualiste->id)->first();
    //         //  dd($droit_adhesion);
    //         if ($droit_adhesion->status != 1) {
    //             $message = 'Veuillez vous acquitter de votre adhesion\nMutualPlay afin de profiter de tous \nles services de la plateforme';
    //             Session::flash('notification_pour_mutualiste', $message);
    //             // return redirect()->route('espace.accueil');

    //         }
    //     }

    //     return $next($request);
    // }
}
