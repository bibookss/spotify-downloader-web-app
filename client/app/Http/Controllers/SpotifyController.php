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
        $redirect_uri = env('APP_URL') . '/spotify/callback';
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

    public function logout(Request $request)
    {
        $request->session()->forget('spotifyAccessToken');
        $request->session()->forget('spotifyUser');

        return redirect('/');
    }

    public function callback(Request $request)
    {
        $client_id = env('SPOTIFY_CLIENT_ID');
        $client_secret = env('SPOTIFY_CLIENT_SECRET');
        $code = $request->input('code');
        $redirect_uri = env('APP_URL') . '/spotify/callback';

        $response = Http::asForm()->post('https://accounts.spotify.com/api/token', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirect_uri,
            'client_id' => $client_id,
            'client_secret' => $client_secret,
        ]);

        $access_token = $response->json('access_token');
        $request->session()->put('spotifyAccessToken', $access_token);

        // Get the user data and store it in the session
        $user = $this->user();
        $request->session()->put('spotifyUser', $user);

        // Get the user's playlist
        $playlists = $this->playlists();
        $request->session()->put('spotifyPlaylists', $playlists);

        return redirect('/dashboard');
    }

    public function user()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . session('spotifyAccessToken'),
        ])->get('https://api.spotify.com/v1/me');

        $response = $response->json();

        $user = [
            'name' => $response['display_name'],
            'email' => $response['email'],
        ];

        // Check if the user has a profile picture
        if (!empty($response['images'])) {
            $user['image'] = $response['images'][0]['url'];
        } else {
            // Set a default image URL if the user doesn't have a profile picture
            $user['image'] = 'default-image-url';
        }

        return $user;
    }

    public function playlists()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . session('spotifyAccessToken'),
        ])->get('https://api.spotify.com/v1/me/playlists');

        if ($response->failed()) {
            dd('API request failed', $response->json());
        }

        $response = $response->json();

        $playlists = [];
        foreach ($response['items'] as $playlist) {
            $playlists[] = [
                'name' => $playlist['name'],
                'image' => $playlist['images'][0]['url'],
                'id' => $playlist['id'],
                'description' => $playlist['description'],
                'owner' => $playlist['owner']['display_name'],
            ];
        }



        return $playlists;
    }

    public function featuredPlaylist()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . session('spotifyAccessToken'),
        ])->get('https://api.spotify.com/v1/browse/featured-playlists');

        $response = $response->json();

        $playlists = [];
        foreach ($response['playlists']['items'] as $playlist) {
            $playlists[] = [
                'name' => $playlist['name'],
                'image' => $playlist['images'][0]['url'],
                'id' => $playlist['id'],
                'description' => $playlist['description'],
                'owner' => $playlist['owner']['display_name'],
            ];
        }

        return $playlists;
    }

    public function playlist(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . session('spotifyAccessToken'),
        ])->get('https://api.spotify.com/v1/playlists/' . $request->id);

        $response = $response->json();

        $playlist = [
            'name' => $response['name'],
            'image' => $response['images'][0]['url'],
            'id' => $response['id'],
            'description' => $response['description'],
            'owner' => $response['owner']['display_name'],
            'num_tracks' => $response['tracks']['total'],
        ];

        $tracks = [];
        foreach ($response['tracks']['items'] as $track) {
            $tracks[] = [
                'name' => $track['track']['name'],
                'image' => $track['track']['album']['images'][0]['url'],
                'id' => $track['track']['id'],
                'artist' => $track['track']['artists'][0]['name'],
                'album' => $track['track']['album']['name'],
                'duration' => $track['track']['duration_ms'],
            ];
        }

        $playlist['tracks'] = $tracks;
        $playlist['duration'] = array_sum(array_column($tracks, 'duration')); // in ms

        return $playlist;
    }
}
