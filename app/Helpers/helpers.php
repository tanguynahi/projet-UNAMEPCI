<?php
// use DateTime;
use App\Models\User;
use App\Models\Inscription;
use Illuminate\Support\Str;
use App\Models\DroitAdhesion;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\CotisationMutualiste;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

if (!function_exists('calculateEndDate')) {
    function calculateEndDate($duration)
    {
        $startDate = now();

        switch ($duration) {
            case 'Trimestriel':
                $endD = $startDate->copy()->addMonths(3);
                break;
            case 'Semestriel':
                $endD = $startDate->copy()->addMonths(6);
                break;
            case 'Annuel':
                $endD = $startDate->copy()->addYear();
                break;
            default:
                $endD = $startDate;
                break;
        }

        // Calculer la différence en jours
        $daysRemaining = $startDate->diffInDays($endD);

        return $daysRemaining;
    }
}

if (!function_exists('calculateEndDate2')) {
    function calculateEndDate2($duration)
    {
        $startDate = Carbon::now();

        switch ($duration) {
            case 'Trimestriel':
                $endDate = $startDate->addMonths(3);
                break;
            case 'Semestriel':
                $endDate = $startDate->addMonths(6);
                break;
            case 'Annuel':
                $endDate = $startDate->addYear();
                break;
            default:
                $endDate = $startDate;
                break;
        }

        return $endDate;
    }
}

if (!function_exists('calculateEDate')) {
    function calculateEDate($durat)
    {
        $startDate = now();

        switch ($durat) {
            case 'Trimestriel':
                $end = $startDate->copy()->addMonths(3);
                break;
            case 'Semestriel':
                $end = $startDate->copy()->addMonths(6);
                break;
            case 'Annuel':
                $end = $startDate->copy()->addYear();
                break;
            default:
                $end = $startDate;
                break;
        }

        // Calculer la différence en jours
        $daysRemaining = $startDate->diffInDays($end);

        return $daysRemaining;
    }
}

if (!function_exists('daysUntilEndDate')) {
    // Fonction pour calculer le nombre de jours restants jusqu'à la fin de la souscription existante
    function daysUntilEndDate($endDate)
    {
        // Convertir les dates en objets Carbon pour faciliter les calculs
        $now = now();
        $endDate = \Carbon\Carbon::parse($endDate);

        // Calculer la différence en jours
        $daysRemaining = $now->diffInDays($endDate);

        return $daysRemaining;
    }
}

if (!function_exists('calculateDateFromDays')) {
    function calculateDateFromDays($days)
    {
        $startDate = now();
        $endDate = $startDate->copy()->addDays($days);

        return $endDate->format('Y-m-d H:i:s');
    }
}

if (!function_exists('getDaysDifference')) {
    function getDaysDifference($date1, $date2)
    {
        // Convertir les dates en objets Carbon pour faciliter le calcul
        $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $date1);
        $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $date2);

        // Utiliser la méthode diffInDays pour obtenir la différence en jours
        $differenceInDays = $startDate->diffInDays($endDate);

        return $differenceInDays;
    }
}


if (!function_exists('sendSMSOTP')) {
    function sendSMSOTP($contact, $code)
    {
        try {

            $token = getOAuthToken();

            // Construire le corps de la requête pour l'API Orange
            $body = [
                'outboundSMSMessageRequest' => [
                    'address' => 'tel:+225' . $contact,
                    'senderAddress' => 'tel:+225' . $contact,
                    'senderName' => 'MALIA',
                    'outboundSMSTextMessage' => [
                        'message' => 'Le code de OTP est : ' . $code
                    ]
                ]
            ];

            // Convertir le corps de la requête en JSON
            $jsonBody = json_encode($body);

            // Créer une ressource cURL
            $ch = curl_init('https://api.orange.com/smsmessaging/v1/outbound/tel:+225' . $contact . '/requests');

            // Configurer les options de la requête cURL
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token, // Remplacer VOTRE_TOKEN par votre token Orange
                'Content-Type: application/json',
            ]);

            // Exécuter la requête cURL
            $response = curl_exec($ch);

            // Vérifier les erreurs de cURL
            if (curl_errno($ch)) {
                throw new \Exception('Erreur cURL : ' . curl_error($ch));
            }

            // Fermer la ressource cURL
            curl_close($ch);

            // Renvoyer la réponse de l'API Orange
            return $response;
        } catch (\Exception $e) {
            // Si une erreur se produit, renvoyer un message d'erreur
            return response()->json(['error' => 'Erreur lors de l\'envoi du code OTP par SMS. Veuillez réessayer.'], 500);
        }
    }
}

// Retourne le token pour api d'envoi d'sms orange
if (!function_exists('getOAuthToken')) {
    function getOAuthToken()
    {
        try {
            $url = "https://api.orange.com/oauth/v3/token";

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $headers = array(
                "Content-Type: application/x-www-form-urlencoded",
                "Authorization: Basic bGlCaTZQVFlGeHFBWkZ6TFFaT3ZYamJoR0k0TGxLODU6VHZkejNNdDNIZEViYmZuZg=="
            );
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
                'grant_type' => 'client_credentials'
            )));

            //for debug only!
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($curl);
            curl_close($curl);
            // var_dump($resp);
            $access_token = json_decode($response);
            $token = $access_token->access_token;
            // echo $token;
            return $token;
        } catch (\Exception $e) {
            // Si une erreur se produit, renvoyer un message d'erreur
            throw new \Exception('Erreur lors de la récupération du jeton OAuth : ' . $e->getMessage());
        }
    }
}


if (!function_exists('webHookFPM')) {
    function webHookFPM()
    {
        try {
            $body = file_get_contents('php://input');
            $body = json_decode($body);
            // dd($body);
            if ($body->status === "Success") {
                // info('Webhook data received:', ['body' => $body]);
                // Requête Eloquent pour mettre à jour le statut du paiement
                // $payment = Payment::where('reference_id', $body->reference)->first();
                // if ($payment) {
                //     $payment->status = 'Success';
                //     $payment->save();
                //     // Envoi du mail de paiement effectue
                //     if (!empty($payment->client->email)) {
                //         # code...
                //         // Mail::send(new ClientSuccessPaymentMail($payment));
                //     }
                //     // Mail::send(new SuccessPaymentMail($payment));
                // }
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Une erreur s\'est produite (webHookMaliapayer).'], 500);
        }
    }
}

if (!function_exists('formatPhoneNumber')) {
    function formatPhoneNumber($phone, $sep = " ")
    {
        // Vérifier si le numéro de téléphone a déjà été formaté
        if (strpos($phone, $sep) !== false) {
            return $phone;
        }
        // Ajouter des tirets tous les deux chiffres
        $formatted = '';
        $length = strlen($phone);
        for ($i = 0; $i < $length; $i += 2) {
            $formatted .= substr($phone, $i, 2);
            if ($i < $length - 2) {
                $formatted .= $sep;
            }
        }
        return "(+225) " . $formatted;
    }
}

if (!function_exists('formatMontant')) {
    /**
     * Formate un montant en ajoutant des points tous les trois chiffres,
     * à partir de la droite vers la gauche.
     *
     * @param  int|string  $montant
     * @return string
     */
    function formatMontant($montant)
    {
        $devise = 'F CFA';

        $formatted = number_format($montant, 0, ',', '.');
        return $formatted . ' ' . $devise;
    }
}


if (!function_exists('formatDate')) {
    function formatDate($dateString, $separator = '-')
    {
        if (empty($dateString) || $dateString === '000-000-000') {
            return 'Date non disponible'; // Retourne un message par défaut ou une chaîne vide
        }
        // Créer un objet Carbon à partir de la chaîne de date d'origine
        $date = Carbon::createFromFormat('Y-m-d', $dateString);

        // Formater la date dans le format souhaité
        return $date->format('d' . $separator . 'm' . $separator . 'Y');
    }
}
if (!function_exists('formatDate02')) {
    function formatDate02($dateString, $separator = '/')
    {
        if (empty($dateString) || $dateString === '000-00-00' || $dateString === '000-00-00 00:00:00') {
            return 'Date non disponible';
        }

        try {
            // Carbon détecte automatiquement le format (date ou datetime)
            $date = Carbon::parse($dateString);

            return $date->format('d' . $separator . 'm' . $separator . 'Y');
        } catch (\Exception $e) {
            return 'Date invalide';
        }
    }
}

if (!function_exists('formatDateTime')) {
    function formatDateTime($dateString, $separator = '-')
    {
        // Créer un objet Carbon à partir de la chaîne de date d'origine
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $dateString);

        // Formater la date dans le format souhaité
        return $date->format('d' . $separator . 'm' . $separator . 'Y') . ' à ' . $date->format('H\hi');
    }
}


if (!function_exists('formatDate2')) {
    function formatDate2($dateString, $separator = '-')
    {
        // Créer un objet Carbon à partir de la chaîne de date d'origine
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $dateString);

        // Formater la date dans le format souhaité
        return $date->format('d' . $separator . 'm' . $separator . 'Y');
    }
}

if (!function_exists('formatTime')) {
    function formatTime($time)
    {
        $newTime = date('H\Hi', strtotime($time));
        return $newTime;
    }
}

if (!function_exists('formatTime2')) {
    function formatTime2($time)
    {
        $newTime = date('H:i', strtotime($time));
        return $newTime;
    }
}

if (!function_exists('generateUserCode')) {
    function generateUserCode(string $chaine, string $modelName)
    {
        if ($chaine === '') {
            throw new \InvalidArgumentException("La chaîne du code Utilisateur ne peut pas être vide.");
        }

        if (!class_exists($modelName)) {
            throw new \InvalidArgumentException("Le modèle du code Utilisateur spécifié n'existe pas.");
        }

        // Récupérer le dernier enregistrement du modèle spécifié
        $lastUser = $modelName::latest()->first();

        // Déterminer l'ID à utiliser pour le prochain utilisateur
        $idUser = (int)preg_replace('/[^0-9]/', '', $lastUser->id);
        $nextId = $lastUser ? ($idUser + 1) : 1;

        // Formater le code avec l'incrément sur le nombre de chiffres déterminé
        $code = $chaine . str_pad($nextId, strlen($nextId) < 2 ? 2 : strlen($nextId), '0', STR_PAD_LEFT);

        return $code;
    }
}


if (!function_exists('generateCode')) {
    function generateCode($chaine)
    {
        if ($chaine === '') {
            $chaine = "M";
        }

        $numDigits = 10;
        $date = date('d-m-Y'); // Format jour-mois-année
        $date_sans_tirets = str_replace('-', '', $date); // Suppression des tirets


        $min = pow(10, $numDigits - 1);
        $max = pow(10, $numDigits) - 1;
        $code = $chaine . '' . $date_sans_tirets . '' . rand($min, $max);
        return $code;
    }
}

if (!function_exists('formatOrderStatus')) {
    function formatOrderStatus($status)
    {
        $newOrderSatus = "";

        if ($status === "pending") {
            $newOrderSatus = "En attente";
        } elseif ($status === "delivered") {
            $newOrderSatus = "Livrée";
        } else {
            $newOrderSatus = "Annulée";
        }

        return $newOrderSatus;
    }
}

if (!function_exists('formatPaymentStatus')) {
    function formatPaymentStatus($status)
    {
        $newPaymentSatus = "";

        if ($status === "Pending") {
            $newPaymentSatus = "En attente";
        } elseif ($status === "Success") {
            $newPaymentSatus = "Succès";
        } else {
            $newPaymentSatus = "Échec";
        }

        return $newPaymentSatus;
    }
}

if (!function_exists('formatAccountStatus')) {
    function formatAccountStatus($status)
    {
        $newAccounSatus = "";

        if ($status === 1) {
            $newAccounSatus = "Activé";
        } else {
            $newAccounSatus = "Désactivé";
        }

        return $newAccounSatus;
    }
}

if (!function_exists('formatDemandeStatus')) {
    function formatDemandeStatus($status)
    {
        $newDemandeStatus = "";

        if ($status === 1) {
            $newDemandeStatus = "Acceptée";
        } else
        if ($status === 2) {
            $newDemandeStatus = "En cours";
        } else {
            $newDemandeStatus = "Rejetée";
        }

        return $newDemandeStatus;
    }
}

if (!function_exists('formatGender')) {
    function formatGender($gender)
    {
        $newGender = "";

        if ($gender === "Homme") {
            $newGender = "M.";
        } elseif ($gender === "Femme") {
            $newGender = "Mme ";
        } else {
            $newGender = "M./Mme";
        }

        return $newGender;
    }
}

if (!function_exists('formatRole')) {
    function formatRole($role)
    {
        $newRole = "";

        if ($role === "super-administrateur") {
            $newRole = "Super-admininistrateur";
        } elseif ($role === "administrateur") {
            $newRole = "Administrateur";
        } elseif ($role === "mutualiste") {
            $newRole = "Mutualiste";
        } else {
            $newRole = "ND";
        }

        return $newRole;
    }
}


if (!function_exists('formatSexe')) {
    function formatSexe($sexe)
    {
        $newSexe = "";

        if ($sexe === "M") {
            $newSexe = "Masculin";
        } elseif ($sexe === "F") {
            $newSexe = "Féminin";
        } else {
            $newSexe = "";
        }

        return $newSexe;
    }
}

if (!function_exists('generateCode2')) {
    function generateCode2($chaine)
    {
        if ($chaine === '') {
            $chaine = "P";
        }

        $time = microtime(true); // Temps actuel en secondes avec microsecondes
        $microseconds = sprintf("%06d", ($time - floor($time)) * 1000000); // Extraire les microsecondes
        $datetime = date('dmYHis') . $microseconds; // Formatage de la date avec les microsecondes
        $code = $chaine . $datetime;

        return $code;
    }
}

if (!function_exists('getInitials')) {
    function getInitials($words)
    {
        $wordList = explode(" ", $words);
        $firstWord = $wordList[0];
        $secondWord = $wordList[1];
        $firstInitial = $firstWord[0];
        $second_initial = $secondWord[0];
        return strtoupper($firstInitial) . strtoupper($second_initial);
    }
}

if (!function_exists('delete_file')) {
    function delete_file($url)
    {
        if (File::exists(public_path($url))) {
            File::delete(public_path($url));
        }
    }
}

if (!function_exists('couperTexte')) {
    function couperTexte($text, $characterCount)
    {
        if (strlen($text) <= $characterCount) {
            return $text;
        }

        $truncatedText = substr($text, 0, $characterCount);
        $lastSpace = strrpos($truncatedText, ' ');

        if ($lastSpace !== false) {
            $truncatedText = substr($truncatedText, 0, $lastSpace);
        }

        $truncatedText .= '...';

        return $truncatedText;
    }
}

if (!function_exists('couperTexte2')) {
    function couperTexte2($text, $numberOfWords)
    {

        $truncatedText = Str::of($text)->words($numberOfWords);

        return $truncatedText;
    }
}

if (!function_exists('afficher_lettres')) {
    function afficher_lettres($mot, $nombre_lettres)
    {
        return mb_substr($mot, 0, $nombre_lettres);
    }
}




if (!function_exists('statutUser')) {
    function statutUser($status)
    {
        $newStatus = $status == 1 ? 'Activé' : 'Désactivé';
        return $newStatus;
    }
}

if (!function_exists('referenceNumber')) {
    function referenceNumber()
    {
        $characters = '0123456789';
        $code = '';
        $charactersLength = strlen($characters);

        for ($i = 0; $i < 7; $i++) {
            $code .= $characters[rand(0, $charactersLength - 1)];
        }

        return $code;
    }
}

if (!function_exists('dateFr')) {
    function dateFr($date)
    {
        Carbon::setLocale('fr');
        $carbonDate = Carbon::parse($date);
        $day = sprintf('%02d', $carbonDate->day);
        $formattedDate = $day . ' ' . ucfirst($carbonDate->isoFormat('MMMM YYYY à HH:mm'));
        return $formattedDate;
    }
}


if (!function_exists('dateHistorique')) {
    function dateHistorique($date)
    {
        $temps = time() - strtotime($date);

        if ($temps < 60) {
            return "Il y a " . $temps . " Seconde" . ($temps > 1 ? "s" : "");
        } elseif ($temps < 3600) {
            $minutes = round($temps / 60);
            return "Il y a " . $minutes . " Minute" . ($minutes > 1 ? "s" : "");
        } elseif ($temps < 86400) {
            $heures = round($temps / 3600);
            return "Il y a " . $heures . " Heure" . ($heures > 1 ? "s" : "");
        } elseif ($temps < 604800) {
            $jours = round($temps / 86400);
            return "Il y a " . $jours . " Jour" . ($jours > 1 ? "s" : "");
        } elseif ($temps < 2419200) { // 28 jours
            $semaines = round($temps / 604800);
            return "Il y a " . $semaines . " Semaine" . ($semaines > 1 ? "s" : "");
        } elseif ($temps < 31536000) { // 1 an
            $mois = round($temps / 2419200);
            return "Il y a " . $mois . " Mois";
        } else {
            return dateFr($date);
        }
    }
}

if (!function_exists('dateHistorique2')) {
    function dateHistorique2($date)
    {
        $now = time();
        $timestamp = strtotime($date);
        $temps = $now - $timestamp;

        if ($temps < 60) {
            return "Il y a " . $temps . " Seconde" . ($temps > 1 ? "s" : "");
        } elseif ($temps < 3600) {
            $minutes = round($temps / 60);
            return "Il y a " . $minutes . " Minute" . ($minutes > 1 ? "s" : "");
        } elseif ($temps < 86400) {
            $heures = round($temps / 3600);
            return "Il y a " . $heures . " Heure" . ($heures > 1 ? "s" : "");
        } elseif ($temps < 604800) {
            $jours = round($temps / 86400);
            return "Il y a " . $jours . " Jour" . ($jours > 1 ? "s" : "");
        } else {
            $datetime1 = new DateTime('@' . $now);
            $datetime2 = new DateTime('@' . $timestamp);
            $interval = $datetime1->diff($datetime2);

            if ($interval->y > 0) {
                return "Il y a " . $interval->y . " An" . ($interval->y > 1 ? "s" : "");
            } elseif ($interval->m > 0) {
                return "Il y a " . $interval->m . " Mois" . ($interval->m > 1 ? "s" : "");
            } elseif ($interval->d >= 7) {
                $semaines = floor($interval->d / 7);
                return "Il y a " . $semaines . " Semaine" . ($semaines > 1 ? "s" : "");
            } else {
                return "Il y a " . $interval->d . " Jour" . ($interval->d > 1 ? "s" : "");
            }
        }
    }
}




// pour transformer date en temps ecouler
function tempsEcouleDepuis($dateTimeStr)
{
    $date = new DateTime($dateTimeStr);
    $now = new DateTime();

    $interval = $date->diff($now);

    // Format lisible
    if ($interval->y > 0) {
        return $interval->y . ' ' . ($interval->y > 1 ? 'années' : 'année');
    } elseif ($interval->m > 0) {
        return $interval->m . ' ' . ($interval->m > 1 ? 'mois' : 'mois');
    } elseif ($interval->d > 0) {
        return $interval->d . ' ' . ($interval->d > 1 ? 'jours' : 'jour');
    } elseif ($interval->h > 0) {
        return $interval->h . ' ' . ($interval->h > 1 ? 'heures' : 'heure');
    } elseif ($interval->i > 0) {
        return $interval->i . ' ' . ($interval->i > 1 ? 'minutes' : 'minute');
    } else {
        return $interval->s . ' ' . ($interval->s > 1 ? 'secondes' : 'seconde');
    }
}

// fonction helper qui permet d'afficher en fonction du temps donnee le jour qui est conserner en fonction du temps

// if (!function_exists('formatJour')) {
//     /**
//      * Formate une date pour afficher "Aujourd'hui", "Demain", "Hier" ou "dd MMM yyyy, à HH:mm"
//      *
//      * @param Carbon|string $date
//      * @return string
//      */
//     function formatJour($date)
//     {
//         // Convertir la date en objet Carbon si ce n'est pas déjà le cas
//         $date = $date instanceof Carbon ? $date : Carbon::parse($date);
//         $now = Carbon::now();

//         if ($date->isSameDay($now)) {
//             return 'Aujourd\'hui, à ' . $date->format('H:i');
//         } elseif ($date->isSameDay($now->copy()->subDay())) {
//             return 'Hier, à ' . $date->format('H:i');
//         } elseif ($date->isSameDay($now->copy()->addDay())) {
//             return 'Demain, à ' . $date->format('H:i');
//         } else {
//             return $date->format('d M Y, à H:i');
//         }
//     }
// }

function formatJour($date)
{
    // Convertir la date en objet Carbon si ce n'est pas déjà le cas
    $date = $date instanceof Carbon ? $date : Carbon::parse($date);
    $now = Carbon::now();

    // Durées spécifiques pour les périodes courtes
    $diffInMinutes = $date->diffInMinutes($now);
    $diffInHours = $date->diffInHours($now);

    // Si la différence est inférieure à une heure, afficher en minutes
    if ($diffInMinutes < 60) {
        if ($diffInMinutes == 0) {
            return 'À l\'instant';
        } elseif ($diffInMinutes == 1) {
            return 'Il y a une minute';
        } else {
            return "Il y a $diffInMinutes minutes";
        }
    }

    // Si la différence est inférieure à une journée, afficher en heures
    if ($diffInHours < 24) {
        if ($diffInHours == 1) {
            return 'Il y a une heure';
        } else {
            return "Il y a $diffInHours heures";
        }
    }

    // Cas spéciaux pour aujourd'hui, hier et demain
    if ($date->isSameDay($now)) {
        return 'Aujourd\'hui, à ' . $date->format('H:i');
    } elseif ($date->isSameDay($now->copy()->subDay())) {
        return 'Hier, à ' . $date->format('H:i');
    } elseif ($date->isSameDay($now->copy()->addDay())) {
        return 'Demain, à ' . $date->format('H:i');
    } else {
        // Pour toutes les autres dates, utiliser un format complet
        return $date->format('d M Y, à H:i');
    }
}
function messageBrut(array $tableauDeChaines)
{
    $chainefinale = '';
    // Parcourir le tableau et afficher chaque élément
    foreach ($tableauDeChaines as $chaine) {
        $chainefinale .= $chaine . "\n";
    }
    return $chainefinale;
}

function CREDENSHEL()
{
    // $cred = '28kahrg-q0';
    $cred = 'wjgzxa9p5z';
    return $cred;
}
function cleAPI()
{
    // $clea = 'shk_CE7472mbzFOelKIKozjtOJi0D36epyPua6xJ';
    $clea = 'shk_BOSo3tcuicp1paLAHxNUeM9dNg0uEIdJEsj0';
    return $clea;
}
function urlRetour()
{
    $exe = 'REEL';
    $exe = 'LOCAL';
    if ($exe == 'REEL') {
        return "https://mutualpay.paysecurehub.com/mutualiste/resultat-Paiement/";
    } else {
        return "https://127.0.0.1:8000/mutualiste/resultat-Paiement/";
    }
}
function urlCallback()
{
    $exe = 'REEL';
    $exe = 'LOCAL';
    if ($exe == 'REEL') {
        return "https://mutualpay.paysecurehub.com/paiements/newCallBack";
    } else {
        return "https://127.0.0.1:8000/paiements/newCallBack";
    }
}
function urlPaiement()
{
    $exe = 'REEL';
    $exe = 'LOCAL';
    if ($exe == 'REEL') {
        return "https://rest-airtime.paysecurehub.com/api/payhub-ws/build-away";
    } else {
        return "http://rest-airtime.paysecurehub.com/api/payhub-ws/build-away";
    }
}


function appelApiEmail()
{
    $exe = 'REEL';
    $exe = 'LOCAL';
    if ($exe == 'REEL') {
        return "https://mailtremo.paysecurehub.com/api/sendemail";
    } else {
        return "http://mailtremo.paysecurehub.com/api/sendemail";
    }
}


function NbreCotisation()
{
    $mutualiste = auth()->user()->mutualiste;
    $cotisation_mutualistes = CotisationMutualiste::where('mutualiste_id', $mutualiste->id)
        ->where('status', 2)
        ->get();
    $contCoti = $cotisation_mutualistes->count();
    // }
    return $contCoti;
}

function compteFPM()
{
    $NumoreCompte = 'CPT-20241021155256';
    return $NumoreCompte;
}
function compteTrans()
{
    $NumoreCompteTr = 'CPT-20241021155257';
    return $NumoreCompteTr;
}

function statusadhesion()
{
    $mutualisteId = auth()->user()->mutualiste->id;
    $droit_adhesions = DroitAdhesion::where('mutualiste_id', $mutualisteId)->first();
    $status = $droit_adhesions->status;
    return $status;
}


function datePaiementCotisa($dateTime)
{
    $date = new DateTime($dateTime);
    $jours = ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'];
    $mois = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'];
    $jour = $jours[$date->format('w')];
    $jourMois = $date->format('d');
    $moisAnnee = $mois[$date->format('n') - 1];
    $annee = $date->format('Y');
    $heure = $date->format('H');
    $minute = $date->format('i');
    $seconde = $date->format('s');
    return "{$jour} {$jourMois} {$moisAnnee} {$annee} à {$heure}h{$minute}min {$seconde} sec";
}

function etreAdmin()
{
    $etreSpuerAdmin = auth()->user()->hasRole('super-administrateur');
    return $etreSpuerAdmin;
}

// code mutualistee
if (!function_exists('generateUniqueCode')) {
    function generateUniqueCode($length = 12)
    {
        // Obtenez l'heure actuelle avec précision
        $datetime = new \DateTime(); // Utilisez \DateTime pour éviter l'avertissement
        $timestamp = $datetime->format('YmdHis'); // Format initial sans millisecondes

        // Définir les caractères autorisés
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()';
        $charactersLength = strlen($characters);
        $randomString = '';

        // Générer une chaîne aléatoire de caractères
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        // Combinez le timestamp et la chaîne aléatoire
        $uniqueCode = $timestamp . $randomString;

        // Vérifiez que le code est unique dans la table mutualiste
        while (DB::table('mutualistes')->where('code', $uniqueCode)->exists()) {
            // Si le code existe déjà, générez un nouveau code
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            $uniqueCode = $timestamp . $randomString;
        }

        return $uniqueCode;
    }
}
function urlAPI()
{
    return 'http://127.0.0.1:8000';
}

function dhSys(): String
{
    return date("YmdHis");
}
function getIp()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
function salutation(): string
{
    $heure = date('H');
    return ($heure >= 18 || $heure < 6) ? 'Bonsoir' : 'Bonjour';
}



function genereCodeInscription($length = 10)
{
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $maxAttempts = 10; // Nombre maximal de tentatives pour générer un code unique
    $attempt = 0;

    do {
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        // Vérifier si le code généré existe déjà en base de données
        $existingCode = Inscription::where('code', $randomString)->exists();

        $attempt++;

        if ($attempt > $maxAttempts) {
            throw new \Exception("Impossible de générer un code unique après $maxAttempts tentatives.");
        }
    } while ($existingCode);

    return $randomString;
}

function createFichiers($folder, $buff, $ext, $fileName = null)
{

    $urlPath = "";
    $ext = str_replace('.', '', $ext);

    if (!empty($buff) && !empty($ext)) {

        // Configuration
        $nextcloudUrl = env('NEXTCLOUD_BASE_URI');
        $username = env('NEXTCLOUD_USERNAME');
        $password = env('NEXTCLOUD_PASSWORD');

        $nextcloudUrl = $nextcloudUrl . $folder;

        if (empty($fileName)) {
            $now = Carbon::now();
            // heure avec les millièmes de secondes
            $formattedTimeMilli = $now->format('Hisv');
            $fileName = date('Ymd') . $formattedTimeMilli . '.' . $ext;
        }
        $file = $buff; //base64_decode($buff);

        // Chemin complet de l'URL WebDAV avec le nom de fichier
        $remoteFilePath = $nextcloudUrl . $fileName;

        // Lire le fichier local à uploader
        $fileContents = file_get_contents($file->getPathname());

        // Initialiser cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remoteFilePath);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // ⚠️ Désactivé pour développement
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // ⚠️ Désactivé pour développement
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // ✅ Suivre les redirections 301/302
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5); // Maximum 5 redirections
        curl_setopt($ch, CURLOPT_POSTREDIR, 3); // ✅ CRITIQUE : Garder le body (POST/PUT) lors des redirections 301/302/303
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fileContents);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: ' . $file->getMimeType(), // Type MIME du fichier
            'Content-Length: ' . strlen($fileContents), // ✅ Taille du fichier
            'OC-Checksum: SHA256:' . hash('sha256', $fileContents) // Vérification d'intégrité
        ]);

        // Exécuter la requête
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        $effectiveUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL); // URL finale après redirections
        curl_close($ch);

        // Vérifier si l'upload a réussi (codes 2xx = succès)
        if ($httpCode >= 200 && $httpCode < 300) {
            $urlPath = $fileName;
            Log::info("Fichier uploadé avec succès vers Nextcloud", [
                'filename' => $fileName,
                'http_code' => $httpCode,
                'folder' => $folder,
                'url_initiale' => $remoteFilePath,
                'url_finale' => $effectiveUrl
            ]);
        } else {
            // Logger l'erreur avec tous les détails
            Log::error("Échec de l'upload vers Nextcloud", [
                'filename' => $fileName,
                'http_code' => $httpCode,
                'curl_error' => $curlError,
                'response' => $response,
                'url_initiale' => $remoteFilePath,
                'url_finale' => $effectiveUrl,
                'folder' => $folder
            ]);
            throw new \Exception("Échec de l'upload du fichier vers le cloud. Code HTTP: $httpCode. Erreur: $curlError");
        }
    }
    return $urlPath;
}



// function envoyerMessageMutualiste($mutualiste, $lienDeValidation)
// {

//     $message = "Bonjour, Votre compte MAE-CI a ete creer avec succes. Login: $mutualiste->email,";

//     $mutualiste->update(['message' => $message]);
//     return $message;
// }

function envoyerMessageMutualiste($mutualiste, $lienDeValidation)
{
    $message = "Bonjour $mutualiste->nom, votre inscription UNAMEPCI a été validée. " .
        "Cliquez ici pour créer vos accès : $lienDeValidation ";

    $mutualiste->update([
        'message' => $message
    ]);

    return $message;
}

function appelApiSMS()
{
    $exe = 'REEL';
    $exe = 'LOCAL';
    if ($exe == 'REEL') {
        return "https://rest-ws.artisanconnecte.net/api/EnvoiMessage";
    } else {
        return "http://rest-ws.artisanconnecte.net/api/EnvoiMessage";
    }
}

function lienPdf($valeur)
{
    if (empty($valeur)) {
        return null;
    }
    return 'https://5058.nx6.cloudlws.com/s/x8iGcqHG5NKwXkQ/download?path=&files=' . $valeur;
}
function apiHttp($lien)
{
    $exe = 'LOCAL';
    $exe = 'REEL';
    if ($exe == 'REEL') {
        return $lien;
    } else {
        return str_replace('https', 'http', $lien);
    }
}


function nonDossierCloud()
{
    return "DOCSLABELIS";
}
