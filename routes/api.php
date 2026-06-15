<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\paiementApiController;
use App\Http\Controllers\Home\NotificationMutualisteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::post('/api/payment-callback', [paiementApiController::class, 'handleCallback']);

Route::controller(paiementApiController::class)->group(function () {
    Route::post('/paiements/callback', 'callback')->name('paiements.callback');
    Route::post('/paiements/newCallBack', 'newCallBack')->name('paiements.newCallBack');


    Route::post('/paiements/newCallBackLiens', 'newCallBack02')->name('pay.newCallBacks');
    Route::post('/paiements/newCallBackLiensPourAdmin', 'newCallBackPourAdmin')->name('pay.newCallBacksPourAdmin');
});

Route::get('/notifications/unread-count', [NotificationMutualisteController::class, 'unreadCount']);
