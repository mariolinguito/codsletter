<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscribersController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/{token}/subscribe', [SubscribersController::class, 'subscribe'])
->name('subscriber.subscribe');

Route::get('/unsubscribe/{email}/{token}', [SubscribersController::class, 'confirmUnsubscribe'])
->name('subscriber.confirm-unsubscribe');
