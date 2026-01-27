<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationMutualisteRecuperationController extends Controller
{
    //
    public function unreadCount()
    {
        // Récupérer le nombre de notifications non lues pour l'utilisateur connecté
        $unreadCount = auth()->user()->mutualiste->unreadNotifications->count();

        return response()->json(['unread_count' => $unreadCount]);
    }
}
