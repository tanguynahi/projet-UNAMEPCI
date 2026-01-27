<?php

namespace App\Http\Controllers\Chat;

use App\Models\Logs;
use App\Models\User;
use App\Models\Message;
use App\Models\Mutualiste;
use App\Models\Conversation;
use Illuminate\Http\Request;
use App\Models\FichierJointMessage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use App\Notifications\MessageNotification;
use Illuminate\Support\Facades\Notification;

class MessageController extends Controller
{
    //
    public function index()
    {
        $messages = Message::where('sujet', '!=', null)
            ->where('statut', '!=', 3)
            ->orderBy('created_at', 'Desc')
            ->get();
        $messageEmails = Message::where('statut', 3)->get();

        $messageDelete = Message::where('sujet', '=', null)
            ->get();
        foreach ($messageDelete as $message) {
            $message->delete();
        }
        // dd($messages);
        $module = "Module Boite a messagerie (chez l'administrateur) ";
        $action = "a consulte la liste message ";
        Logs::saveLog($module, $action);

        return view('dashboard.messages.index', compact('messages', 'messageEmails')); // , 'mutualiste'
    }
    public function edit($id)
    {
        $messages = Message::where('sujet', '!=', Null)
            ->orderBy('created_at', 'Desc')
            ->where('statut', '!=', 3)
            ->get();
        $messageEmails = Message::where('statut', 3)->get();
        $message = Message::findOrFail($id);
        $conversations = Conversation::where('message_id', $message->id)
            ->get();
        // dd($conversations);
        foreach ($conversations as $key => $conversation) {
            if (($conversation->statut == 2) && ($conversation->recepteur == 2)) {
                $conversation->update([
                    'statut' => 1,
                ]);
            }
        }
        $mutualisteEmail = User::where('email', '=', $message->email)->first();
        // dd($mutualisteEmail);
        // $mutualiste = Mutualiste::where('email', '=', $mutualisteEmail->email)->get();
        $module = "Module Boite a messagerie (chez l'administrateur) ";
        $action = "ouvert une conversation ";
        Logs::saveLog($module, $action);

        return view('dashboard.messages.edit', compact('message', 'messages', 'conversations', 'mutualisteEmail',  'messageEmails')); //'mutualiste',
    }
    public function storeV(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
            'lien_document' => 'nullable'
        ]);
        $messages = Message::findOrFail($id);
        $conversation = new Conversation();
        $conversation->message_id =  $messages->id;
        $conversation->message = $request->message;
        $conversation->statut = 2;
        $conversation->recepteur = 1;
        $conversation->save();
        $messages->save();
        $module = "Module Boite a messagerie (chez l'administrateur) ";
        $action = "enregistre une conversation  ";
        Logs::saveLog($module, $action);
        $lien_document = null;
        // $ok = $request->lien_document;
        // dd($ok);
        if ($request->hasFile('lien_document')) {
            $file_name = md5(uniqid()) . '.' . $request->file('lien_document')->extension();
            $request->file('lien_document')->storeAs('images-messages/', $file_name);
            $lien_document = 'src-files/images-messages/' . $file_name;
        }
        $fichiers = FichierJointMessage::create([
            'message_id' =>  $messages->id,
            'conversation_id' => $conversation->id,
            'lien_document' => $lien_document,
            'statut' => 1,
        ]);
        $fichiers->save();
        return redirect()->back();
    }

    // ecrire une premiere conversation a un mutualiste
    public function createMessageMutualiste()
    {
        $mutualistes = Mutualiste::all();
        $messages = Message::where('sujet', '!=', null)
            ->orderBy('created_at', 'Desc')
            ->where('statut', '!=', 3)
            ->get();
        $messageEmails = Message::where('statut', 3)->get();
        // creation d'un message avec le statut pas vu
        $messagesCreate = new Message();
        $messagesCreate->statut = 2;
        $messagesCreate->save();
        // dd($messagesCreate);
        $module = "Module Boite a messagerie (chez l'administrateur) ";
        $action = "a debuter une nouvelle conversation avec un mutualiste  ";
        Logs::saveLog($module, $action);
        return view('dashboard.messages.contact.create_mutualiste', compact('mutualistes', 'messages', 'messagesCreate', 'messageEmails'));
    }

    // traitement du premier message envoyer par l'administrateur au mutualiste vers son interface et son email

    public function traitementMessageMutualiste(Request $request, $id)
    {
        $request->validate([
            'mutualiste_id' => 'required',
            'sujet' => 'required',
            'message' => 'required|string',
            'lien_document' => 'nullable'
        ]);
        // recuperation du message passe en parametre et sa mise a jour
        $message = Message::findOrFail($id);
        $mutualiste = Mutualiste::where('id', $request->mutualiste_id)->first();
        if ($message) {
            $message->sujet = $request->sujet;
            $message->mutualiste_id = $request->mutualiste_id;
            $message->email = $mutualiste->email;
            $message->statut = 1;
            $message->save();
        }
        // creation d'une conversation
        $conversation = new Conversation();
        $conversation->message_id =  $message->id;
        $conversation->message = $request->message;
        $conversation->statut = 2;
        $conversation->recepteur = 1;
        $conversation->save();
        $module = "Module Boite a messagerie (chez l'administrateur) ";
        $action = "a enregistre les conversation qui a l'id $conversation->id  ";
        Logs::saveLog($module, $action);

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
        if ($mutualiste && $mutualiste->email) {
            // $messageDetails = [
            //     'id' => $message->id,
            //     'sujet' => $request->sujet,
            //     'messageContent' => $request->message,
            //     'lien_document' => $lien_document // Inclure le chemin du document
            // ];
            // Mail::send('dashboard.messages.contact.index_autre', $messageDetails, function ($email) use ($mutualiste, $messageDetails) {
            //     $email->to($mutualiste->email)
            //         ->subject($messageDetails['sujet']);
            // });

            $salut  = salutation();
            $sujet = "$request->sujet";
            $message = " $salut, " . $mutualiste->prenom . ' ' . $mutualiste->nom . "<br>
                            $request->message
                            <br>";
            if (!empty($lien_document)) {
                $message .= "<img src='" . asset($lien_document) . "' style='width: 75px; height:75px'><br>";
            }

            $url = appelApiEmail();
            $template = View::make('home.admin.paiements.paiementAdhesion', ['contenumess' => $message])->render();
            $data = [
                'provider' => 'MUTUALPAY <info@mail-taseti.com>',
                "key_rsa" => 're_2i7H3Ynf_KRVm9VwTsrwrfF8isCBYvyyE',
                "destination" => $mutualiste->email,
                "sujet" => $sujet,
                "message" => $template
            ];
            $retourAPI = Http::post($url, $data);
            $res = $retourAPI->json();
            if ($retourAPI->status() == 200) {
                (int)$code = $res['status'];
                if ($code != 200) {
                    $message = "Une erreur s'est produite " . $code . ", DETAIL: " . messageBrut($res['message']) . " ERR: Envoye Paiement adhesion";
                    $module = "Envoyer de Mail pour une nouvelle conversation Mutualiste";
                    $action = "Echec d'envoyer de mail  : $message";
                    Logs::saveLog($module, $action);
                } else {
                    $module = "Envoyer de Mail a la creation Mutualiste";
                    $action = "Email envoyer avec success   : $mutualiste->nom , $mutualiste->prenom sur son email  $mutualiste->email";
                    Logs::saveLog($module, $action);
                }
            } else {

                $module = "Envoyer de Mail pour nouvelle conversation a un Mutualiste";
                $action = "Erreur lors de l'envoi de l'email. Statut API : " . $retourAPI->status();
                Logs::saveLog($module, $action);
            }
        }

        return redirect()->route('messages.edit', $id);
    }

    // ecrire a un particulier par Email
    public function createMessageAutre()
    {
        $messages = Message::where('sujet', '!=', null)
            ->orderBy('created_at', 'Desc')
            ->where('statut', '!=', 3)
            ->get();
        $messageEmails = Message::where('statut', 3)->get();
        // creation d'un message avec le statut pas vu
        $messagesAutre = new Message();
        $messagesAutre->statut = 3;
        $messagesAutre->save();
        return view('dashboard.messages.contact.create_autre', compact('messages', 'messagesAutre', 'messageEmails'));
    }
    // traitement du premier message envoyer par email
    public function traitementMessageParticulier(Request $request, $id)
    {
        $request->validate([
            'email' => 'required',
            'sujet' => 'required',
            'message' => 'required|string',
            'lien_document' => 'nullable'
        ]);
        // recuperation du message passe en parametre et sa mise a jour
        $message = Message::findOrFail($id);
        //  $mutualiste = Mutualiste::where('id', $request->mutualiste_id)->first();
        if ($message) {
            $message->sujet = $request->sujet;
            $message->email = $request->email;
            // $message->statut = 3;
            $message->save();
        }
        // creation d'une conversation
        $conversation = new Conversation();
        $conversation->message_id =  $message->id;
        $conversation->message = $request->message;
        $conversation->statut = 2;
        $conversation->recepteur = 3;
        $conversation->save();

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

        $emails = $request->email; // Assurez-vous que ce modèle existe
        // Envoi de l'email a un particulier
        if ($emails) {
            // $messageDetails = [
            //     'id' => $message->id,
            //     'sujet' => $request->sujet,
            //     'messageContent' => $request->message,
            //     'lien_document' => $lien_document
            // ];
            // Mail::send('dashboard.messages.contact.index_autre', $messageDetails, function ($email) use ($request, $messageDetails) {
            //     $email->to($request->email)
            //         ->subject($messageDetails['sujet']);
            // });



            $salut  = salutation();
            $sujet = "$request->sujet";
            $message = " $salut  Monsieur / Madame <br>
                            $request->message
                            <br>";
            if (!empty($lien_document)) {
                $message .= "<img src='" . asset($lien_document) . "' style='width: 75px; height:75px'><br>";
            }

            $url = appelApiEmail();
            $template = View::make('home.admin.paiements.paiementAdhesion', ['contenumess' => $message])->render();
            $data = [
                'provider' => 'MUTUALPAY <info@mail-taseti.com>',
                "key_rsa" => 're_2i7H3Ynf_KRVm9VwTsrwrfF8isCBYvyyE',
                "destination" => $emails,
                "sujet" => $sujet,
                "message" => $template
            ];
            $retourAPI = Http::post($url, $data);
            $res = $retourAPI->json();
            if ($retourAPI->status() == 200) {
                (int)$code = $res['status'];
                if ($code != 200) {
                    $message = "Une erreur s'est produite " . $code . ", DETAIL: " . messageBrut($res['message']) . " ERR: Envoye Paiement adhesion";

                    $module = "Envoyer de Mail pour une nouvelle conversation a un particulier";
                    $action = "Echec d'envoyer de mail  : $message";
                    Logs::saveLog($module, $action);
                } else {
                    $module = "Envoyer de Mail pour une nouvelle conversation  a un particulier";
                    $action = "Email envoyer avec success    sur le email  $emails";
                    Logs::saveLog($module, $action);
                }
            } else {

                $module = "Envoyer de Mail pour nouvelle conversation a un particulier";
                $action = "Erreur lors de l'envoi de l'email. Statut API : " . $retourAPI->status();
                Logs::saveLog($module, $action);
            }

        }
        return redirect()->route('message.editeparticulier', $id);
    }

    // page de conversation qui envoyer les email a un particulier
    public function editeParticulier($id)
    {
        $messages = Message::where('sujet', '!=', Null)
            ->where('statut', '!=', 3)
            ->orderBy('created_at', 'Desc')
            ->get();
        $message = Message::findOrFail($id);
        $messageEmails = Message::where('statut', 3)->get();
        $conversations = Conversation::where('message_id', $message->id)
            ->get();
        // dd($conversations);
        foreach ($conversations as $key => $conversation) {
            if (($conversation->statut == 3) && ($conversation->recepteur == 3)) {
                $conversation->update([
                    'statut' => 1,
                ]);
            }
        }
        $messageDelete = Message::where('sujet', '=', null)
            ->get();
        foreach ($messageDelete as $message) {
            $message->delete();
        }
        $module = "Envoyer de Mail pour nouvelle conversation a un particulier";
        $action = "a consulte les conversation d'un particulier";
        Logs::saveLog($module, $action);
        return view('dashboard.messages.contact.edit_autre', compact('messages', 'message', 'conversations', 'messageEmails'));
    }

    //Conversation deja entamer par un administrateur et un particulier par Email
    public function discultionParMail(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
            'lien_document' => 'nullable'
        ]);
        $messages = Message::findOrFail($id);
        $conversation = new Conversation();
        $conversation->message_id =  $messages->id;
        $conversation->message = $request->message;
        $conversation->statut = 2;
        $conversation->recepteur = 3;
        $conversation->save();
        $messages->save();

        $lien_document = null;

        if ($request->hasFile('lien_document')) {
            $file_name = md5(uniqid()) . '.' . $request->file('lien_document')->extension();
            $request->file('lien_document')->storeAs('images-messages/', $file_name);
            $lien_document = 'src-files/images-messages/' . $file_name;
        }
        $fichiers = FichierJointMessage::create([
            'message_id' =>  $messages->id,
            'conversation_id' => $conversation->id,
            'lien_document' => $lien_document,
            'statut' => 1,
        ]);
        $fichiers->save();
        $emails = $messages->email; // Assurez-vous que ce modèle existe
        // Envoi de l'email a un particulier
            //  dd($emails);
        if ($emails) {
            // $messageDetails = [
            //     'id' => $messages->id,
            //     'sujet' => $messages->sujet,
            //     'messageContent' => $request->message,
            //     'lien_document' => $lien_document // Inclure le chemin du document
            // ];
            // Mail::send('dashboard.messages.contact.index_autre', $messageDetails, function ($email) use ($messages, $messageDetails) {
            //     $email->to($messages->email)
            //         ->subject($messageDetails['sujet']);
            // });

            $salut  = salutation();
            $sujet = "$messages->sujet";
            $message = " $salut  Monsieur / Madame <br>
                            $request->message
                            <br>";
            if (!empty($lien_document)) {
                $message .= "<img src='" . asset($lien_document) . "' style='width: 75px; height:75px'><br>";
            }

            $url = appelApiEmail();
            $template = View::make('home.admin.paiements.paiementAdhesion', ['contenumess' => $message])->render();
            $data = [
                'provider' => 'MUTUALPAY <info@mail-taseti.com>',
                "key_rsa" => 're_2i7H3Ynf_KRVm9VwTsrwrfF8isCBYvyyE',
                "destination" => $emails,
                "sujet" => $sujet,
                "message" => $template
            ];
            $retourAPI = Http::post($url, $data);
            $res = $retourAPI->json();
            if ($retourAPI->status() == 200) {
                (int)$code = $res['status'];
                if ($code != 200) {
                    $message = "Une erreur s'est produite " . $code . ", DETAIL: " . messageBrut($res['message']) . " ERR: Envoye Paiement adhesion";

                    $module = "Envoyer de Mail pour une  conversation a un particulier";
                    $action = "Echec d'envoyer de mail  : $message";
                    Logs::saveLog($module, $action);
                } else {
                    $module = "Envoyer de Mail pour une  conversation  a un particulier";
                    $action = "Email envoyer avec success    sur le email  $emails";
                    Logs::saveLog($module, $action);
                }
            } else {

                $module = "Envoyer de Mail pour  conversation a un particulier";
                $action = "Erreur lors de l'envoi de l'email. Statut API : " . $retourAPI->status();
                Logs::saveLog($module, $action);
            }

        }
        return redirect()->back();
    }
}
