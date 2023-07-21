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
Route::get('/spotify/user', [SpotifyController::class, 'user'])->name('spotify.user');
Route::get('/playlists', [SpotifyController::class, 'playlists'])->name('spotify.playlists');
Route::get('/playlists/{id}', [SpotifyController::class, 'playlist'])->name('spotify.playlist');