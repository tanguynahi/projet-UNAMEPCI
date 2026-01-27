<?php

namespace App\Models;

// use GeoIp2\Database\Reader;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Logs extends Model
{
    use HasFactory;
    protected $fillable = [
        "user_id",
        "ip",
        "module",
        "navigator",
        "action",
        "pays",
        "codepays",
        "url",
    ];


    public static function saveLog($module, $action )
    {
        $activity = new logs;

        $activity->user_id = Auth::user()->id ?? 0;
        $activity->module = $module;
        $activity->action = $action;

        $agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

        if (preg_match('/Linux/i', $agent)) $os = 'Linux';
        elseif (preg_match('/Mac/i', $agent)) $os = 'Mac';
        elseif (preg_match('/iPhone/i', $agent)) $os = 'iPhone';
        elseif (preg_match('/iPad/i', $agent)) $os = 'iPad';
        elseif (preg_match('/Droid/i', $agent)) $os = 'Droid';
        elseif (preg_match('/Unix/i', $agent)) $os = 'Unix';
        elseif (preg_match('/Windows/i', $agent)) $os = 'Windows';
        else $os = 'Unknown';

        if (preg_match('/Firefox/i', $agent)) $br = 'Firefox';
        elseif (preg_match('/Mac/i', $agent)) $br = 'Mac';
        elseif (preg_match('/Chrome/i', $agent)) $br = 'Chrome';
        elseif (preg_match('/Opera/i', $agent)) $br = 'Opera';
        elseif (preg_match('/MSIE/i', $agent)) $br = 'IE';
        else $br = 'Unknown'; //  Unknown = Inconnue
        setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

        $activity->pays        = (isset($_SERVER['GEOIP_COUNTRY_NAME'])) ? $_SERVER['GEOIP_COUNTRY_NAME'] : '';
        $activity->codepays    = (isset($_SERVER['GEOIP_COUNTRY_CODE'])) ? $_SERVER['GEOIP_COUNTRY_CODE'] : '';
        $activity->url        = (isset($_SERVER['SCRIPT_URI'])) ? $_SERVER['SCRIPT_URI'] : '';

        $activity->pays = $br . '/' . $os;;
        $activity->navigator = $br . '/' . $os;;
        $activity->navigator = $br . '/' . $os;;
        $activity->navigator = $br . '/' . $os;;
        $activity->ip = getIp();

        $activity->save();

        return $activity;
    }
    // public static function saveLog($module, $action)
    // {
    //     $activity = new logs;

    //     // Getting the user ID
    //     $activity->user_id = Auth::user()->id ?? 0;
    //     $activity->module = $module;
    //     $activity->action = $action;

    //     // Get user agent to determine OS and Browser
    //     $agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    //     // Determine the operating system
    //     if (preg_match('/Linux/i', $agent)) $os = 'Linux';
    //     elseif (preg_match('/Mac/i', $agent)) $os = 'Mac';
    //     elseif (preg_match('/iPhone/i', $agent)) $os = 'iPhone';
    //     elseif (preg_match('/iPad/i', $agent)) $os = 'iPad';
    //     elseif (preg_match('/Droid/i', $agent)) $os = 'Droid';
    //     elseif (preg_match('/Unix/i', $agent)) $os = 'Unix';
    //     elseif (preg_match('/Windows/i', $agent)) $os = 'Windows';
    //     else $os = 'Unknown';

    //     // Determine the browser
    //     if (preg_match('/Firefox/i', $agent)) $br = 'Firefox';
    //     elseif (preg_match('/Chrome/i', $agent)) $br = 'Chrome';
    //     elseif (preg_match('/Opera/i', $agent)) $br = 'Opera';
    //     elseif (preg_match('/MSIE/i', $agent)) $br = 'IE';
    //     else $br = 'Unknown';

    //     // Set locale for French
    //     setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

    //     // Set Browser and OS information
    //     $activity->navigator = $br . '/' . $os;

    //     // Use MaxMind's GeoIP2 database to get country info
    //     try {
    //         // Path to your GeoIP2 database
    //         $reader = new Reader('/path/to/GeoLite2-Country.mmdb');
    //         $record = $reader->country($_SERVER['REMOTE_ADDR']);

    //         // Get country name and code
    //         $country = $record->country->name ?? 'Unknown';
    //         $countryCode = $record->country->isoCode ?? 'Unknown';

    //         $reader->close(); // Close the reader
    //     } catch (\GeoIp2\Exception\GeoIp2Exception $e) {
    //         $country = 'Unknown';
    //         $countryCode = 'Unknown';
    //     }

    //     // Save country and country code to activity
    //     $activity->pays = $country;
    //     $activity->codepays = $countryCode;

    //     // Get the script URL
    //     $activity->url = $_SERVER['SCRIPT_URI'] ?? '';

    //     // Log the IP address
    //     $activity->ip = getIp();  // Make sure getIp() is correctly implemented

    //     // Save the activity
    //     $activity->save();

    //     return $activity;
    // }
}
