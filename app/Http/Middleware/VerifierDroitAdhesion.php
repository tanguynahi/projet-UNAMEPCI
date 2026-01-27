<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\DroitAdhesion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class VerifierDroitAdhesion
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::check()){


            $mutualisteId = auth()->user()->mutualiste->id;
            // $droit_adhesion = DroitAdhesion::where('mutualiste_id', $mutualiste)->first();
            $droit_adhesion =  DroitAdhesion::where('mutualiste_id', $mutualisteId)->first();
            //  dd($droit_adhesion);
            if ($droit_adhesion && $droit_adhesion->status != 1) {
                $message = 'Veuillez vous acquitter de votre adhesion\nMutualPlay afin de profiter de tous \nles services de la plateforme';
                Session::flash('notification_pour_mutualiste', $message);
                return redirect()->route('espace.accueil');
            }
        }
        return $next($request);
    }
}
