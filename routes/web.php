<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoutteController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SendMailController;
use App\Http\Controllers\SubscribersController;
use App\Http\Controllers\TestController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/subscribers', [SubscribersController::class, 'list']
)->middleware(['auth', 'verified'])->name('subscribers');

Route::get('/run-example', [TestController::class, 'runScraptingTest']
)->middleware(['auth', 'verified'])->name('run-example');

Route::get('/edit-website', [WebsiteController::class, 'get']
)->middleware(['auth', 'verified'])->name('edit-website');

Route::post('/edit-website', [WebsiteController::class, 'store']
)->middleware(['auth', 'verified'])->name('edit-website');

Route::get('/edit-settings', [SettingsController::class, 'get']
)->middleware(['auth', 'verified'])->name('edit-settings');

Route::post('/edit-settings', [SettingsController::class, 'store']
)->middleware(['auth', 'verified'])->name('edit-settings');

Route::delete('subscribers/{subscribe_id}/delete-subscriber', [SubscribersController::class, 'delete'])
->middleware(['auth', 'verified'])->name('subscriber.delete-subscriber');

Route::get('/sorry-to-see-you-go', function () {
    return view('sorry-to-see-you-go');
})->name('sorry-to-see-you-go');

require __DIR__.'/auth.php';
