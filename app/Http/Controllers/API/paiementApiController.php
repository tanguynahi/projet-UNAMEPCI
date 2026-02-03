<?php

namespace App\Http\Controllers\API;

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
    // public function callback(Request $request)
    // {
    //     // Valider les données reçues si nécessaire
    //     try {
    //         DB::beginTransaction();
    //         $request->validate([
    //             'code' => 'required',
    //             'codePaiement' => 'required',
    //             'datePaiement' => 'required',
    //             'HeurePaiement' => 'required',
    //             'referencePaiement' => 'required',
    //             'montant' => 'required',
    //             'benefice' => 'required',
    //             'service_id' => 'required',
    //             'moyenPaiement' => 'required',
    //             'no_transation' => 'required',
    //             'numTel' => 'required',
    //             'p_last_wallet_amount' => 'required',
    //             'p_new_wallet_amount' => 'required',
    //         ]);
    //         // recupere la code de paiement
    //         $codePaiement = 'codePaiement';
    //         $code = 'code';
    //         // recupere dans ma table paiement initial id du code de paiement
    //         $paiementinit = PaiementInitiale::where('reference', $codePaiement)->first();
    //         // verifier sur le status de retour est un success (code = 200)
    //         if ($paiementinit && $code === 200) {
    //             $paiementinit->status = 1;
    //             $mutualisteId =  $paiementinit->mutualiste_id;
    //             // remplissage de la table paiement
    //             // DB::beginTransaction();
    //             $paiement = new Paiement();
    //             $paiement->reference = $codePaiement;
    //             $paiement->mutualiste_id = $mutualisteId;
    //             $paiement->type_paiement_id =  $paiementinit->type_paiemen_id;
    //             $paiement->montant_initial = $paiementinit->montant_initial;
    //             $paiement->frais = $request->input('benefice');
    //             $paiement->montant_total = $paiementinit->montant_initial + $request->input('benefice');
    //             $paiement->moyen_paiement = $request->input('moyenPaiement');
    //             $paiement->contact_paiement = $request->input('numTel');
    //             $paiement->status = 1;
    //             // $paiement->created_at  = $request->input('datePaiement');
    //             $paiement->save();
    //             if ($paiementinit->type_paiemen_id == 1) {
    //                 DB::beginTransaction();
    //                 $droit_adhesion =  DroitAdhesion::where('mutualiste_id', $mutualisteId)->first();
    //                 $droit_adhesion->update([
    //                     'status' => 1,
    //                 ]);
    //                 $droit_adhesion->save();
    //             }
    //             DB::commit();
    //             // message de succes
    //             toast('Le paiement a été effectué avec succès !', 'success');
    //             // route de redirection vers la route des historique ou du recu
    //             return redirect()->route('recu');
    //         } // verifier sur le status de retour n'est pas un success (code != 200) envoyer un message de not paiement a utilisateur
    //         else {
    //             DB::rollBack();
    //             toast('Vous n\'avez pas effectué de paiement.', 'error');
    //             Log::error('Paiement non trouvé pour la référence : ' . $request->input('code_paiement'));
    //             // Répondre avec un message d'erreur
    //             return response()->json(['error' => 'Paiement non trouvé pour la référence donnée'], 404);
    //         }
    //     } catch (\Throwable $e) {
    //         DB::rollback();
    //         Log::error('Erreur interne du serveur: ' . $e->getMessage());
    //         return redirect()->back()->with('error', 'Une erreur s\'est produite, veuillez réessayer.');
    //     }
    // }

    // function callback de return

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
}
