<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpotifyController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/', function () {
    return view('auth');
});

Route::get('/spotify/login', [SpotifyController::class, 'login'])->name('spotify.login');
Route::get('/spotify/callback', [SpotifyController::class, 'callback'])->name('spotify.callback');