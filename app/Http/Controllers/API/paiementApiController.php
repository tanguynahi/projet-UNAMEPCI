<?php

namespace App\Http\Controllers\API;

use App\Models\Logs;
use App\Models\Paiement;
use App\Models\Mutualiste;
use App\Models\CarteMembre;
use App\Models\Facturation;
use Illuminate\Http\Request;
use App\Models\DroitAdhesion;
use App\Models\PaiementInitiale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\CotisationMutualiste;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use App\Models\DemandeAccompagnement;
use Illuminate\Support\Facades\Validator;
use App\Notifications\MutualisteNotification;

class paiementApiController extends Controller
{

    public function newCallBack(Request $request)
    {
        // $log = new Log();
        (string) $RetourPaiementEnJSON = json_encode($request->input());
        (string) $Chaine = "Debut callback paiement, recu: " . $RetourPaiementEnJSON;

        try {
            (int) $Code = $request->code;
            (int) $Montant = $request->montant;
            (string) $codePaiement = $request->codePaiement;

            // Recupère le paiement en attente avec le statut '2'
            $paiementinit = PaiementInitiale::where('reference', $codePaiement)
                ->where('status', 2)
                ->first();

            if (!empty($paiementinit->id)) {
                if ($Code == 200) {
                    $paiement = new Paiement();
                    $paiement->montant_total = $Montant;
                    $paiement->moyen_paiement = $request->moyenPaiement;
                    $paiement->status = 1;
                    $paiement->code_paiement = $codePaiement;
                    $paiement->date_paiement_final = $request->datePaiement;
                    $paiement->heure_paiement_final = $request->HeurePaiement;
                    $paiement->reference = $request->referencePaiement;
                    $paiement->type_paiement_id = $request->service_id;
                    // $paiement->no_transation = $request->no_transation;  // champs a creer
                    $paiement->mutualiste_id = $paiementinit->mutualiste_id;
                    $paiement->contact_paiement = $request->numTel;
                    $paiement->montant_initial = $paiementinit->montant_initial;
                    // $paiement->chainejson = '$RetourPaiementEnJSON';
                    $paiement->save();

                    $paiementinit->status = 1; //
                    $paiementinit->reference = $request->referencePaiement;

                    ($Code == 200) ? $paiementinit->message_retour = 'SUCCESSFUL' : $paiementinit->message_retour = $request->cleretour;

                    $mutualiste = Mutualiste::where('id', $paiementinit->mutualiste_id)->first();
                    switch ($paiementinit->type_paiement_id) {
                        case 1:
                            // cas de paiement droit d'adhesion
                            $mutualisteId = $paiementinit->mutualiste_id;
                            $droit_adhesion = DroitAdhesion::where('mutualiste_id', $mutualisteId)
                                ->update([
                                    'status' => 1,
                                ]);

                            if ($droit_adhesion->status == 1) {
                                $sujet = "Paiement de droit d'adhésion sur votre compte UNAMEPCI";
                                $message = "
                                    Bonjour, " . $mutualiste->prenom . ' ' . $mutualiste->nom . "<br>
                                    C'est officiel, votre paiement d'adhésion a été confirmé ! 🎉 Bienvenue dans la communauté de Union Nationale des Medecins Prives de Côte d'Ivoire !<br>
                                    Nous sommes super excités de vous avoir avec nous. Votre adhésion vous ouvre les portes à un monde de nouvelles opportunités, d'événements passionnants et de nombreuses ressources.<br>
                                    Prenez le temps d'explorer ce qui vous attend et faites-en le maximum !<br>
                                    Votre soutien signifie beaucoup pour nous, et nous sommes impatients de voir tout ce que vous accomplirez avec nous.<br>
                                    Bienvenue à bord, et profitons de cette aventure ensemble !<br>
                                    Merci d'utiliser notre plateforme! <br>
                                    Si vous rencontrez des problèmes avec votre compte, n'hésitez pas à nous contacter.
                                ";
                                $url = appelApiEmail();
                                $template = View::make('home.admin.paiements.paiementAdhesion', ['contenumess' => $message])->render();
                                $data = [
                                    'provider' => 'UNAMEPCI <info@mail-taseti.com>',
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
                                        Log::error($message);
                                    }
                                } else {
                                    Log::error("Erreur lors de l'envoi de l'email. Statut API : " . $retourAPI->status());
                                }
                            }
                            break;
                        // case 2:
                        //     // cas de cotisation
                        //     $cotisationMutualiste = CotisationMutualiste::where('id', $paiementinit->correspondance_id)->first();
                        //     if (!empty($cotisationMutualiste)) {
                        //         if ($cotisationMutualiste->frequence_paiement == 'Annuelle') {
                        //             $cotisationMutualiste->montant_paye = $cotisationMutualiste->montant_paye + $Montant;
                        //             $cotisationMutualiste->montant = $cotisationMutualiste->montant - $Montant;
                        //             $cotisationMutualiste->save();

                        //             if ($cotisationMutualiste->montant >= 0) {
                        //                 $cotisationMutualiste->status = 1;
                        //                 $cotisationMutualiste->save();
                        //             }
                        //         } else {
                        //             $cotisationMutualiste->update([
                        //                 'status' => 1,
                        //             ]);
                        //         }
                        //     }
                        //     break;
                        case 2:
                            // Cas de cotisation
                            $cotisationMutualiste = CotisationMutualiste::find($paiementinit->correspondance_id);

                            if (!$cotisationMutualiste) {
                                break;
                            }

                            if ($cotisationMutualiste->frequence_paiement === 'Annuelle') {

                                // Mettre à jour les montants
                                $cotisationMutualiste->montant_paye += $Montant;
                                $cotisationMutualiste->montant -= $Montant;

                                // Empêcher un solde négatif
                                if ($cotisationMutualiste->montant < 0) {
                                    $cotisationMutualiste->montant = 0;
                                }

                                // Si tout est payé → statut validé
                                if ($cotisationMutualiste->montant == 0) {
                                    $cotisationMutualiste->status = 1;
                                }

                                $cotisationMutualiste->save();
                            } else {
                                // Autres fréquences (mensuelle, trimestrielle…)
                                $cotisationMutualiste->update([
                                    'status' => 1
                                ]);
                            }
                            break;

                        case 3:
                            //cas de prêt
                            $demandeAccompagnement = DemandeAccompagnement::where('id', $paiementinit->correspondance_id)->first();
                            if (!empty($demandeAccompagnement)) {
                                $demandeAccompagnement->update([
                                    'payer' => $demandeAccompagnement->payer + $paiementinit->montant_initial,
                                ]);
                            }
                            break;
                        case 4:
                            // cas de projet
                            $facturation = Facturation::where('id', $paiementinit->correspondance_id)->first();
                            if (!empty($facturation)) {
                                // les id des periodes : 1 immediat, 2 journaliere, 3 hebdomadaire ,4 mensuelle, 5 Annuelle , 6 Aperiodique
                                // les statut : 1 - en attent , 2 : soldes , 3 : refuse
                                $facturation->update([
                                    'total_payer' => $paiementinit->montant_initial,
                                    'total_apayer' => $facturation->total_apayer - $paiementinit->montant_initial,
                                    'status' => 2,
                                ]);
                            }
                            break;
                        case 5:
                            // paiement pour carte Membre
                            $mutualisteId = $paiementinit->mutualiste_id;
                            $carteMembre = CarteMembre::where('mutualiste_id', $mutualisteId)
                                ->update([
                                    'status' => 1,
                                ]);

                            if ($carteMembre->status == 1) {
                                $sujet = "Confirmation de paiement – Carte Membre UNAMEPCI";

                                $message = "
                                    Bonjour " . $mutualiste->prenom . " " . $mutualiste->nom . ",<br><br>

                                    Félicitations 🎉 !
                                    Nous vous informons que le paiement de votre **carte de membre UNAMEPCI** a été effectué avec succès.<br><br>
                                    Votre adhésion est désormais **active** et vous bénéficiez pleinement des services et avantages offerts par le **Union Nationale des Medecins Prives de Côte d'Ivoire**.<br><br>
                                    Nous vous remercions pour votre confiance et sommes ravis de vous compter parmi nos membres.<br><br>
                                    Si vous avez besoin d’assistance ou d’informations complémentaires, notre équipe reste à votre disposition.<br><br>
                                    Cordialement,<br>
                                    <strong>L’équipe UNAMEPCI</strong>
                                    ";

                                $url = appelApiEmail();
                                $template = View::make('home.admin.paiements.paiementAdhesion', ['contenumess' => $message])->render();
                                $data = [
                                    'provider' => 'UNAMEPCI <info@mail-taseti.com>',
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
                                        Log::error($message);
                                    }
                                } else {
                                    Log::error("Erreur lors de l'envoi de l'email. Statut API : " . $retourAPI->status());
                                }
                            }

                            break;

                        default:
                            // Ajoutez ici d'autres types de paiement si nécessaire
                            break;
                    }
                } else {
                    // // paiement echouer
                    // $mutualiste = Mutualiste::where('id', $paiementinit->mutualiste_id)->first();
                    // switch ($paiementinit->type_paiement_id) {
                    //     case 1:
                    //         // cas de paiement droit d'adhesion
                    //         // $mutualisteId = $paiementinit->mutualiste_id;
                    //         // $droit_adhesion = DroitAdhesion::where('mutualiste_id', $mutualisteId)
                    //         //     ->update([
                    //         //         'status' => 1,
                    //         //     ]);

                    //         // if ($droit_adhesion->status == 1) {
                    //         //     $sujet = "Paiement de droit d'adhésion sur votre compte MUTUALPAY";
                    //         //     $message = "
                    //         //         Bonjour, " . $mutualiste->prenom . ' ' . $mutualiste->nom . "<br>
                    //         //         C'est officiel, votre paiement d'adhésion a été confirmé ! 🎉 Bienvenue dans la communauté du Fond de Prévoyance Militaire !<br>
                    //         //         Nous sommes super excités de vous avoir avec nous. Votre adhésion vous ouvre les portes à un monde de nouvelles opportunités, d'événements passionnants et de nombreuses ressources.<br>
                    //         //         Prenez le temps d'explorer ce qui vous attend et faites-en le maximum !<br>
                    //         //         Votre soutien signifie beaucoup pour nous, et nous sommes impatients de voir tout ce que vous accomplirez avec nous.<br>
                    //         //         Bienvenue à bord, et profitons de cette aventure ensemble !<br>
                    //         //         Merci d'utiliser notre plateforme! <br>
                    //         //         Si vous rencontrez des problèmes avec votre compte, n'hésitez pas à nous contacter.
                    //         //     ";
                    //         //     $url = "https://mailtremo.paysecurehub.com/api/sendemail";
                    //         //     $template = View::make('home.admin.paiements.paiementAdhesion', ['contenumess' => $message])->render();

                    //         //     $data = [
                    //         //         'provider' => 'MUTUALPAY <info@mail-taseti.com>',
                    //         //         "key_rsa" => 're_2i7H3Ynf_KRVm9VwTsrwrfF8isCBYvyyE',
                    //         //         "destination" => $mutualiste->email,
                    //         //         "sujet" => $sujet,
                    //         //         "message" => $template
                    //         //     ];
                    //         //     $retourAPI = Http::post($url, $data);
                    //         //     $res = $retourAPI->json();

                    //         //     if ($retourAPI->status() == 200) {
                    //         //         (int)$code = $res['status'];
                    //         //         if ($code != 200) {
                    //         //             $message = "Une erreur s'est produite " . $code . ", DETAIL: " . messageBrut($res['message']) . " ERR: Envoye Paiement adhesion";
                    //         //             Log::error($message);
                    //         //         }
                    //         //     } else {
                    //         //         Log::error("Erreur lors de l'envoi de l'email. Statut API : " . $retourAPI->status());
                    //         //     }
                    //         // }
                    //         break;
                    //     case 2:
                    //         // cas de cotisation
                    //         $cotisationMutualiste = CotisationMutualiste::where('id', $paiementinit->correspondance_id)->first();
                    //         if (!empty($cotisationMutualiste)) {
                    //             $cotisationMutualiste->update([
                    //                 'status' => 1,
                    //             ]);
                    //         }
                    //         break;
                    //     case 3:
                    //         //cas de prêt
                    //         break;
                    //     case 4:
                    //         // cas de projet
                    //         $facturation = Facturation::where('id', $paiementinit->correspondance_id)->first();
                    //         if (!empty($facturation)) {
                    //             $facturation->update([
                    //                 'total_payer' => $paiementinit->montant_initial,
                    //                 'total_apayer' => $facturation->total_apayer - $paiementinit->montant_initial,
                    //             ]);
                    //         }
                    //         break;

                    //     default:
                    //         // Ajoutez ici d'autres types de paiement si nécessaire
                    //         break;
                    // }
                    // paiement echouer
                    $paiementinit->status = 3; //
                    $paiementinit->reference = $request->referencePaiement;
                }
                $paiementinit->save();

                // $log->id_concerne = $codePaiement;
                // $log->contenu = '$Chaine';
                // $log->titre = "Log callback paiement";
                // $log->save();

            } else {
                $Chaine .= "\n//// verification code paiement:#" . $codePaiement . "# introuvable ou déjà notifié dans 'paiement_en_attentes'";
                // $log->contenu = '$Chaine';
                // $log->titre = "Log callback paiement";
                // $log->id_concerne = $codePaiement;
                // $log->save();
            }
        } catch (\Throwable $e) {
            $Chaine .= "\n/// Une erreur s'est produite. DETAIL_ERR: " . $e->getMessage();
            // $log->contenu = '$Chaine';
            // $log->titre = "Log callback cas erreur";
            // $log->id_concerne = $request->codePaiement;
            // $log->save();
        }

        return 'Ok';
    }






    public function newCallBack02(Request $request)
    {
        // $log = new Log();
        (string) $RetourPaiementEnJSON = json_encode($request->input());
        (string) $Chaine = "Debut callback paiement, recu: " . $RetourPaiementEnJSON;
        try {

            if (empty($request->Details[0]['referenceePaiement'])) {
                $response['code'] = 200;
                $response['message'] = "transaction notifiee avec des informations manquantes.";
                $Chaine .= "\n** transaction notifie avec un 'Details' vide. code 404.";

                $module = "erreur donnee manquante notifie par tresormoney";
                $action = "$Chaine";
                Logs::saveLog($module, $action);
                return response()->json($response);
            } else {
                (int) $Code = $request->code;

                (string) $codePaiement = $request->codePaiement;
                // Recupère le paiement en attente avec le statut '2'
                $paiementinit = PaiementInitiale::where('code_paiement', $codePaiement)
                    ->where('status', 2)
                    ->first();
                (int) $Montant = $request->montant  ?? $paiementinit->montant_initial;

                if (!empty($paiementinit->id)) {
                    if ($Code == 200) {

                        $date = str_replace(['/', '-'], '', $request->Details[0]['datePaiement']);
                        $date = substr($date, 0, 8);
                        $date = substr($date, 0, 4) . '-' . substr($date, 4, 2) . '-' . substr($date, 6, 2);

                        // Traitement de l'heure
                        $heure = str_replace(':', '', $request->Details[0]['HeurePaiement']);
                        $heure = substr($heure, 0, 9);
                        $heure = substr($heure, 0, 2) . ':' . substr($heure, 2, 2) . ':' . substr($heure, 4, 2);


                        $paiement = new Paiement();
                        $paiement->montant_total = $Montant ?? $paiementinit->montant_initial;
                        $paiement->moyen_paiement =  "Tresor Money";
                        $paiement->status = 1;
                        $paiement->code_paiement = $codePaiement;
                        $paiement->date_paiement_final =  $date ?? '';
                        // $paiement->heure_paiement_final = '' ?? $heure ?? '';
                        $paiement->reference =  $request->Details[0]['referenceePaiement'];
                        $paiement->type_paiement_id = $paiementinit->type_paiement_id;
                        // $paiement->no_transation = $request->no_transation;  // champs a creer
                        $paiement->mutualiste_id = $paiementinit->mutualiste_id;
                        $paiement->contact_paiement = $request->numTel;
                        $paiement->montant_initial = $paiementinit->montant_initial;
                        $paiement->correspondance_id = $paiementinit->correspondance_id;
                        // $paiement->chainejson = '$RetourPaiementEnJSON';

                        // $paiement->status = ($Code == 200 ? 1 : 2);
                        $paiement->save();
                        $paiementinit->moyen_paiement =  "Tresor Money";
                        $paiementinit->status = 1; //
                        $paiementinit->reference = $request->Details[0]['referenceePaiement'];

                        // ($Code == 200) ? $paiementinit->message_retour = 'SUCCESSFUL' : $paiementinit->message_retour = $request->cleretour;

                        $mutualiste = Mutualiste::findOrFail($paiement->mutualiste_id);
                        (int)  $typID = $paiement->type_paiement_id ?? $paiementinit->type_paiement_id ?? 0;
                        (int) $idCord = $paiement->correspondance_id ?? $paiementinit->correspondance_id  ?? 0;
                        switch ($typID) {
                            case 1:
                                // cas de paiement droit d'adhesion
                                $mutualisteId = $paiement->mutualiste_id ?? $paiementinit->mutualiste_id;
                                $droit_adhesion = DroitAdhesion::where('mutualiste_id', $mutualisteId)->first();
                                if (!empty($droit_adhesion)) {
                                    $nouveauMontant = $droit_adhesion->montant - $Montant;

                                    if ($nouveauMontant <= 0) {
                                        $droit_adhesion->montant = 0;
                                        $droit_adhesion->status = 1;
                                    } else {
                                        $droit_adhesion->montant = $nouveauMontant;
                                        $droit_adhesion->status = 2;
                                    }
                                    $droit_adhesion->save();
                                } else {
                                    $Chaine .= "\droit d'adhesion pas defini pour ce mutualiste error '";
                                    $module = " paiement Retour API";
                                    $action = "$Chaine";
                                    Logs::saveLog($module, $action);
                                }
                                if ($droit_adhesion->status == 1) {
                                    $sujet = "Paiement de droit d'adhésion sur votre compte MAE-CI";
                                    $message = "
                                    Bonjour M/Mme/Mlle:, " . $mutualiste->prenom . ' ' . $mutualiste->nom . "<br>
                                    C'est officiel, votre paiement d'adhésion a été confirmé ! 🎉 Bienvenue dans la Mutuelle des Auto-Ecoles de Côte d'Ivoire (MAE-CI) !<br>
                                    Nous sommes super excités de vous avoir avec nous. Votre adhésion vous ouvre les portes à un monde de nouvelles opportunités, d'événements passionnants et de nombreuses ressources.<br>
                                    Prenez le temps d'explorer ce qui vous attend et faites-en le maximum !<br>
                                    Votre soutien signifie beaucoup pour nous, et nous sommes impatients de voir tout ce que vous accomplirez avec nous.<br>
                                    Bienvenue à bord, et profitons de cette aventure ensemble !<br>
                                    Merci d'utiliser notre plateforme! <br>
                                    Si vous rencontrez des problèmes avec votre compte, n'hésitez pas à nous contacter.
                                 ";
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
                                            Log::error($message);
                                        }
                                    } else {
                                        Log::error("Erreur lors de l'envoi de l'email. Statut API : " . $retourAPI->status());
                                    }
                                } else {
                                    $sujet = "Paiement éffectuer pour le droit d'adhésion sur votre compte MAE-CI";
                                    $message = "
                                        Bonjour M/Mme/Mlle: " . $mutualiste->prenom . ' ' . $mutualiste->nom . "<br>
                                        C'est officiel, Vous avez effectué un paiement pour le droit d'adhésion ! 🎉<br>
                                        Bienvenue dans la Mutuelle des Auto-Ecoles de Côte d'Ivoire (MAE-CI) !<br>
                                        Nous sommes super excités de vous avoir avec nous. Votre adhésion vous ouvre les portes à un monde de nouvelles opportunités, d'événements passionnants et de nombreuses ressources.<br>
                                        Prenez le temps d'explorer ce qui vous attend et faites-en le maximum !<br>
                                        Montant restant : " . formatMontant($droit_adhesion->montant ?? 0) . "<br>
                                        Votre soutien signifie beaucoup pour nous, et nous sommes impatients de voir tout ce que vous accomplirez avec nous.<br>
                                        Bienvenue à bord, et profitons de cette aventure ensemble !<br>
                                        Merci d'utiliser notre plateforme !<br>
                                        Si vous rencontrez des problèmes avec votre compte, n'hésitez pas à nous contacter.
                                        ";
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
                                            Log::error($message);
                                        }
                                    } else {
                                        Log::error("Erreur lors de l'envoi de l'email. Statut API : " . $retourAPI->status());
                                    }
                                }
                                break;
                            case 2:
                                // cas de cotisation
                                $cotisationMutualiste = CotisationMutualiste::find($idCord);
                                if (!$cotisationMutualiste) {
                                    $Chaine .= " id cotisation incorrecter";
                                    $module = " paiement ";
                                    $action = "$Chaine";
                                    Logs::saveLog($module, $action);
                                    break;
                                }
                                if (!empty($cotisationMutualiste)) {
                                    if ($cotisationMutualiste->frequence_paiement === 'Annuelle') {

                                        // Mettre à jour les montants
                                        $cotisationMutualiste->montant_paye += $Montant;
                                        $cotisationMutualiste->montant -= $Montant;

                                        // Empêcher un solde négatif
                                        if ($cotisationMutualiste->montant < 0) {
                                            $cotisationMutualiste->montant = 0;
                                        }

                                        // Si tout est payé → statut validé
                                        if ($cotisationMutualiste->montant == 0) {
                                            $cotisationMutualiste->status = 1;
                                        }

                                        $cotisationMutualiste->save();
                                    } else {
                                        // Autres fréquences (mensuelle, trimestrielle…)
                                        $cotisationMutualiste->update([
                                            'status' => 1
                                        ]);
                                    }

                                    $Chaine .= " enregistre cotisations id $cotisationMutualiste->id ";
                                    $module = " paiement ";
                                    $action = "$Chaine";
                                    Logs::saveLog($module, $action);
                                } else {
                                    $Chaine .= " id cotisation incorrecter";
                                    $module = " paiement ";
                                    $action = "$Chaine";
                                    Logs::saveLog($module, $action);
                                }

                                break;
                            case 3:
                                //cas de prêt
                                $demandeAccompagnement = DemandeAccompagnement::find($idCord);
                                if (!empty($demandeAccompagnement)) {
                                    $demandeAccompagnement->payer = $demandeAccompagnement->payer + $paiement->montant_initial;
                                    $demandeAccompagnement->save();
                                    $Chaine .= " pret solde $demandeAccompagnement->id  ";
                                    $module = " paiement ";
                                    $action = "$Chaine";
                                    Logs::saveLog($module, $action);
                                } else {
                                    $Chaine .= " id pret incorrecter";
                                    $module = " paiement ";
                                    $action = "$Chaine";
                                    Logs::saveLog($module, $action);
                                }
                                break;
                            case 4:
                                // cas de projet
                                $facturation = Facturation::find($idCord);
                                if (!empty($facturation)) {

                                    $facturation->total_payer = $paiement->montant_initial;
                                    $facturation->total_apayer = $facturation->total_apayer - $paiement->montant_initial;
                                    $facturation->status = 2;
                                    $facturation->save();
                                    $Chaine .= " facturation   solde id: $facturation->id  ";
                                    $module = " paiement ";
                                    $action = "$Chaine";
                                    Logs::saveLog($module, $action);
                                } else {
                                    $Chaine .= " id projet incorrecter";
                                    $module = " paiement ";
                                    $action = "$Chaine";
                                    Logs::saveLog($module, $action);
                                }
                                break;

                            case 5:
                                // cas de bordereau
                                $carteMembre = CarteMembre::findOFail($idCord);
                                if (!empty($carteMembre)) {
                                    $carteMembre->status = 1;
                                    $carteMembre->save();

                                if ($carteMembre->status == 1) {
                                    $sujet = "Confirmation de paiement – Carte Membre UNAMEPCI";

                                    $message = "
                                    Bonjour " . $mutualiste->prenom . " " . $mutualiste->nom . ",<br><br>

                                    Félicitations 🎉 !
                                    Nous vous informons que le paiement de votre **carte de membre UNAMEPCI** a été effectué avec succès.<br><br>
                                    Votre adhésion est désormais **active** et vous bénéficiez pleinement des services et avantages offerts par le **Union Nationale des Medecins Prives de Côte d'Ivoire**.<br><br>
                                    Nous vous remercions pour votre confiance et sommes ravis de vous compter parmi nos membres.<br><br>
                                    Si vous avez besoin d’assistance ou d’informations complémentaires, notre équipe reste à votre disposition.<br><br>
                                    Cordialement,<br>
                                    <strong>L’équipe UNAMEPCI</strong>
                                    ";

                                    $url = appelApiEmail();
                                    $template = View::make('home.admin.paiements.paiementAdhesion', ['contenumess' => $message])->render();
                                    $data = [
                                        'provider' => 'UNAMEPCI <info@mail-taseti.com>',
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
                                            Log::error($message);
                                        }
                                    } else {
                                        Log::error("Erreur lors de l'envoi de l'email. Statut API : " . $retourAPI->status());
                                    }
                                }
                                } else {
                                    $Chaine .= " id bordereau incorrecter";
                                    $module = " paiement ";
                                    $action = "$Chaine";
                                    Logs::saveLog($module, $action);
                                }






                                break;

                            default:
                                // Ajoutez ici d'autres types de paiement si nécessaire (exceptions)
                                $Chaine .= " id type de ce paiement es non defini";
                                $module = " paiement ";
                                $action = "$Chaine";
                                Logs::saveLog($module, $action);
                                break;
                        }
                    } else {
                        // paiement echouer
                        $paiementinit->status = 3; //
                        $paiementinit->reference = $request->Details[0]['referenceePaiement'];
                    }
                    $paiementinit->save();
                    $Chaine .= "\n//// retour Paiement effectue '";
                    $module = " paiement Retour API";
                    $action = "$Chaine";
                    Logs::saveLog($module, $action);
                } else {
                    $Chaine .= "\n//// verification code paiement:#" . $codePaiement . "# introuvable ou déjà notifié dans 'paiement_en_attentes'";
                    $module = " paiement Retour API";
                    $action = "$Chaine";
                    Logs::saveLog($module, $action);
                }
            }
        } catch (\Throwable $e) {
            $Chaine .= "\n/// Une erreur s'est produite. DETAIL_ERR: " . $e->getMessage();
            $module = " paiement Retour API";
            $action = "$Chaine";
            Logs::saveLog($module, $action);
        }

        return 'Ok';
    }
}
