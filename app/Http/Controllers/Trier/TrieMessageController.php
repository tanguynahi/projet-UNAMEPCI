<?php

namespace App\Http\Controllers\Trier;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TrieMessageController extends Controller
{
    //
    public function index(Request $request){
        $searchTerm = $request->input('search');
        // Effectuez la recherche dans votre modèle
        $results = Message::where('nom', 'like', '%' . $searchTerm . '%')
                            ->select('nom', 'email','prenom','sujet','lien_photo') // Sélectionnez les colonnes à retourner
                            ->get();
        return response()->json($results);
    }
}
