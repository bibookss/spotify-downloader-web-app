<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SpotifyController extends Controller
{
    public function getToken()
    {
        $client_id = env('SPOTIFY_CLIENT_ID');
        $client_secret = env('SPOTIFY_CLIENT_SECRET');

        // Base64 encode the client ID and client secret
        $credentials = base64_encode($client_id . ':' . $client_secret);

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $credentials,
            'Content-Type' => 'application/x-www-form-urlencoded',
        ])->post('https://accounts.spotify.com/api/token', [
            'grant_type' => 'client_credentials'
        ]);

        return $response->json();
    }
}
