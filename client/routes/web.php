<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpotifyController;
use App\Http\Controllers\DownloadController;


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
    return view('authentication.auth');
})->middleware('alreadyLoggedIn');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['isLoggedIn', 'token.refresh'])->name('dashboard');


// Spotify
Route::get('/spotify/login', [SpotifyController::class, 'login'])->name('spotify.login');
Route::post('/spotify/logout', [SpotifyController::class, 'logout'])->name('spotify.logout');
Route::get('/spotify/callback', [SpotifyController::class, 'callback'])->name('spotify.callback');
Route::get('/spotify/user', [SpotifyController::class, 'user'])->name('spotify.user');
Route::get('/playlists', [SpotifyController::class, 'playlists'])->name('spotify.playlists');
Route::get('/playlists/{id}', [SpotifyController::class, 'playlist'])->name('spotify.playlist');
Route::get('/spotify/categories', [SpotifyController::class, 'categories'])->name('spotify.categories');
Route::get('/spotify/playlist/search', [SpotifyController::class, 'search'])->name('spotify.search');

// Download
Route::post('/download/song', [DownloadController::class, 'download'])->name('download.song');
Route::post('/download/playlist', [DownloadController::class, 'initiatePlaylistDownload'])->name('download.playlist.server');
Route::get('/download/playlist', [DownloadController::class, 'downloadPlaylist'])->name('download.playlist.client');
Route::get('/download/playlist/progress', [DownloadController::class, 'checkPlaylistDownloadStatus'])->name('download.playlist.progress');