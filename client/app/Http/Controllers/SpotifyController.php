<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class SpotifyController extends Controller
{
    public function login()
    {
        $client_id = env('SPOTIFY_CLIENT_ID');
        $redirect_uri = env('APP_URL') .'/spotify/callback';
        $state = Str::random(16);
        $scope = 'user-read-private user-read-email';

        $query_params = [
            'response_type' => 'code',
            'client_id' => $client_id,
            'scope' => $scope,
            'redirect_uri' => $redirect_uri,
            'state' => $state,
        ];

        $spotify_auth_url = 'https://accounts.spotify.com/authorize?' . http_build_query($query_params);
        
        return redirect()->away($spotify_auth_url);
    }

    public function callback(Request $request)
    {
        $client_id = env('SPOTIFY_CLIENT_ID');
        $client_secret = env('SPOTIFY_CLIENT_SECRET');
        $code = $request->input('code'); 
        $redirect_uri = env('APP_URL') .'/spotify/callback';

        $response = Http::asForm()->post('https://accounts.spotify.com/api/token', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirect_uri,
            'client_id' => $client_id,
            'client_secret' => $client_secret,
        ]);

        $access_token = $response->json('access_token');
        $request->session()->put('spotifyAccessToken', $access_token);

        return redirect('/'); 
    }
}
