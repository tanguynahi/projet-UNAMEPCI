<?php

namespace App\Http\Controllers\Chat;

use App\Models\Logs;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\FichierJointMessage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreConversationRequest;

class ConversationController extends Controller
{
    //

    public function indexMutualiste()
    {
        $mutualiste = auth()->user()->mutualiste;
        $messages = Message::where('email', '=', $mutualiste->email)
            ->where('sujet', '!=', null)
            ->where('mutualiste_id', $mutualiste->id)
            ->orderBy('created_at', 'Desc')
            ->get();
        $messageDelete = Message::where('sujet', '=', null)
            ->get();
        foreach ($messageDelete as $message) {
            $message->delete();
        }
        $module = "Module Boite a messagerie ";
        $action = "a consulte ses conversations";
        Logs::saveLog($module, $action);

        return view('home.admin.Conversations.index', compact('messages'));
    }
    public function createMutualiste()
    {
        $mutualiste = auth()->user()->mutualiste;
        $message = new Message();
        $message->mutualiste_id = $mutualiste->id;
        $message->email = $mutualiste->email;
        $message->statut = 2;
        $message->save();
        $message_id = $message->id;
        $messages = Message::whereId($message_id)->first();
        // dd($messages);*
        $module = "Module Boite a messagerie ";
        $action = "a consulte la page de creation d'une conversation";
        Logs::saveLog($module, $action);
        return view('home.admin.Conversations.create', compact('messages'));
    }
    public function storeMutualiste(StoreConversationRequest $request, $id)
    {
        DB::beginTransaction();
        $message = Message::findOrFail($id);
        if ($message) {
            $message->sujet = $request->sujet;
            $message->statut = 1;
            $message->save();
        }
        $conversation = new Conversation();
        $conversation->message_id =  $message->id;
        $conversation->message = $request->message;
        $conversation->statut = 2; // est lu pas encore lu
        $conversation->recepteur = 2;
        $conversation->save();
        $lien_document = null;
        // $ok = $request->lien_document;
        // dd($ok);
        if ($request->hasFile('lien_document')) {
            $file_name = md5(uniqid()) . '.' . $request->file('lien_document')->extension();
            $request->file('lien_document')->storeAs('images-messages/', $file_name);
            $lien_document = 'src-files/images-messages/' . $file_name;
        }
        $fichiers = FichierJointMessage::create([
            'message_id' =>  $message->id,
            'conversation_id' => $conversation->id,
            'lien_document' => $lien_document,
            'statut' => 1,
        ]);
        $fichiers->save();
        DB::commit();
        $module = "Module Boite a messagerie ";
        $action = "a enregistre un conversation ";
        Logs::saveLog($module, $action);
        return redirect()->route('mutualiste.message', $id)->with('success', 'votre message a bien etet envoyer avec success');
    }
    public function conversationMutualiste($id)
    {
        $messages = Message::findOrFail($id); // Récupère le message par son ID
        $conversations = Conversation::where('message_id', $messages->id)->get(); // Récupère les conversations liées à ce message
        foreach ($conversations as $key => $conversation) {
            if (($conversation->statut == 2) && ($conversation->recepteur == 1)) {
                $conversation->update([
                    'statut' => 1,
                ]);
            }
        }
        $messages->update([
            'statut' => 1,
        ]);
        $messages->save();
        $module = "Module Boite a messagerie ";
        $action = "a affiche la liste de ses message ";
        Logs::saveLog($module, $action);
        return view('home.admin.Conversations.debut', compact('messages', 'conversations'));
    }
    // traitement d'une conversation deja debuter
    public function discultionMutualiste(Request $request, $id)
    {
        // dd('test');
        $request->validate([
            'message' => 'required|string',
            'lien_document' => 'nullable'
        ]);
        // dd($request->lien_document);
        $message = Message::findOrFail($id);
        $conversation = new Conversation();
        $conversation->message_id =  $message->id;
        $conversation->message = $request->message;
        $conversation->statut = 2;
        $conversation->recepteur = 2;
        $conversation->save();


        $module = "Module Boite a messagerie ";
        $action = "a enregistre une conversation";
        Logs::saveLog($module, $action);

        // $message->save();
        $lien_document = null;

        if ($request->hasFile('lien_document')) {
            $file_name = md5(uniqid()) . '.' . $request->file('lien_document')->extension();
            $request->file('lien_document')->storeAs('images-messages/', $file_name);
            $lien_document = 'src-files/images-messages/' . $file_name;
        }
        $fichiers = FichierJointMessage::create([
            'message_id' =>  $message->id,
            'conversation_id' => $conversation->id,
            'lien_document' => $lien_document,
            'statut' => 1,
        ]);
        $fichiers->save();
        // dd($fichiers);
        return redirect()->back();
    }
}
