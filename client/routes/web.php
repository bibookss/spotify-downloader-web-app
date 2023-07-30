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
Route::controller(SpotifyController::class)->group(function () {
    Route::prefix('spotify')->group(function () {
        Route::get('/login', 'login')->name('spotify.login');
        Route::post('/logout', 'logout')->name('spotify.logout');
        Route::get('/callback', 'callback')->name('spotify.callback');

        Route::group(['middleware' => ['isLoggedIn', 'token.refresh']], function () {
            Route::get('/user', 'user')->name('spotify.user');
            Route::get('/playlists', 'playlists')->name('spotify.playlists');
            Route::get('/playlists/{id}', 'playlist')->name('spotify.playlist');
            Route::get('/categories', 'categories')->name('spotify.categories');
            Route::get('/playlist/search', 'search')->name('spotify.search');
            Route::get('/albums/{id}', 'album')->name('spotify.album');
        });
    });
});

// Download
Route::controller(DownloadController::class)->group(function () {
    Route::prefix('download')->group(function () {
        Route::post('/song', 'download')->name('download.song');
        Route::post('/playlist', 'initiatePlaylistDownload')->name('download.playlist.server');
        Route::get('/playlist', 'downloadPlaylist')->name('download.playlist.client');
        Route::get('/playlist/progress', 'checkPlaylistDownloadStatus')->name('download.playlist.progress');
    });
});

