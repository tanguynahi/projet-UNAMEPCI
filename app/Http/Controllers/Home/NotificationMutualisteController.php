<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Notifications\MutualisteNotification;

class NotificationMutualisteController extends Controller
{
    //
    public function notificationMutualiste(){
        // $mutualiste = auth()->user()->mutualiste;

        // // // message que je veux envoyer  pour un service ou je voulais fais une notification
        // $message = " vous avez un cotisation en attent";
        // $mutualiste->notify(new MutualisteNotification($message));

        $mutualiste = auth()->user()->mutualiste;
        $notifications = $mutualiste->notifications; // user->unreadNotifications pour les notifications non lues
        // dd($notifications);
        return view('home.admin.notifications.index', compact('notifications'));
    }

}
