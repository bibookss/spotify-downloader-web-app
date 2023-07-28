<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cookie;

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
        $refresh_token = $response->json('refresh_token');
        $expires_in = $response->json('expires_in');

        $request->session()->put('spotifyAccessToken', $access_token);
        $request->session()->put('spotifyRefreshToken', $refresh_token);
        $request->session()->put('spotifyExpiresIn', $expires_in);

        // Get the user data and store it in the session
        $user = $this->user();
        $request->session()->put('spotifyUser', $user);

        // Get the user's playlist
        $playlists = $this->playlists();
        $request->session()->put('spotifyPlaylists', $playlists);

        // Get featured playlists
        $featuredPlaylist = $this->featuredPlaylist();
        $request->session()->put('spotifyFeaturedPlaylists', $featuredPlaylist);

        // Get Artists for each playlists
        foreach ($playlists as $key => $playlist) {
            $artists = $this->getPlaylistArtists($playlist['id']);
            $playlists[$key]['artists'] = $artists;
        }

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
                'added_at' => (new DateTime($track['added_at']))->format('M d, Y')
            ];
        }

        $playlist['duration'] = $this->millisecondsToText(array_sum(array_column($tracks, 'duration')));

        foreach ($tracks as &$track) {
            $track['duration'] = $this->millisecondsToMinSec($track['duration']);
        }        

        $playlist['tracks'] = $tracks;

        return view('play-list-page', ['playListData' => $playlist]);
    }

    public function album(Request $request) {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . session('spotifyAccessToken'),
        ])->get('https://api.spotify.com/v1/albums/' . $request->id);

        $response = $response->json();

        $album = [
            'name' => $response['name'],
            'image' => $response['images'][0]['url'],
            'id' => $response['id'],
            'released_date' => $response['release_date'],
            'artist' => $response['artists'][0]['name'],
            'num_tracks' => $response['total_tracks'],
        ];

        $tracks = [];
        foreach ($response['tracks']['items'] as $track) {
            $tracks[] = [
                'name' => $track['name'],
                'id' => $track['id'],
                'duration' => $track['duration_ms'],
            ];
        }

        $album['duration'] = $this->millisecondsToText(array_sum(array_column($tracks, 'duration')));

        foreach ($tracks as &$track) {
            $track['duration'] = $this->millisecondsToMinSec($track['duration']);
        }        

        $album['tracks'] = $tracks;

        dd($album);

        return $album;
    }

    public function getPlaylistArtists($playlist_id)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . session('spotifyAccessToken'),
        ])->get('https://api.spotify.com/v1/playlists/' . $playlist_id . '/tracks');

        if ($response->failed()) {
            return []; // Return an empty array if the API request fails
        }

        $response = $response->json();

        $artists = [];
        foreach ($response['items'] as $item) {
            // Ensure 'track' and 'artists' keys are present before looping
            if (isset($item['track'], $item['track']['artists'])) {
                foreach ($item['track']['artists'] as $artist) {
                    $artists[] = $artist['name'];
                }
            }
        }

        return $artists;
    }

    // Helper function to get category playlists
    public function categories()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . session('spotifyAccessToken'),
        ])->get('https://api.spotify.com/v1/browse/categories');

        $response = $response->json();

        $category_ids = [];
        foreach ($response['categories']['items'] as $category) {
            $category_ids[] = $category['id'];
        }

        $categories = [];
        foreach ($category_ids as $category_id) {
            $category = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('spotifyAccessToken'),
            ])->get('https://api.spotify.com/v1/browse/categories/' . $category_id . '/playlists');

            if ($category->failed()) {
                continue;
            }

            $category = $category->json();
            $categories[] = [
                'name' => $category['playlists']['items'][0]['name'],
                'image' => $category['playlists']['items'][0]['images'][0]['url'],
                'id' => $category['playlists']['items'][0]['id'],
                'description' => $category['playlists']['items'][0]['description'],
                'owner' => $category['playlists']['items'][0]['owner']['display_name'],
            ];
        }

        // To render call in a loop then pass id to playlist
        return $categories;
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . session('spotifyAccessToken'),
        ])->get('https://api.spotify.com/v1/search?q=' . $query . '&type=playlist');

        $response = $response->json();

        $playlists = [];
        foreach ($response['playlists']['items'] as $playlist) {
            $playlists[] = [
                'name' => $playlist['name'],
                'image' => $playlist['images'][0]['url'] ?? null,
                'id' => $playlist['id'],
                'description' => $playlist['description'],
                'owner' => $playlist['owner']['display_name'],
                'url' => $playlist['external_urls']['spotify'],
            ];
        }   

        return view('search-result-page', ['playlists' => $playlists]);
    }

    // Helper function to convert the milliseconds duration to text
    function millisecondsToText($milliseconds) {
        $seconds = floor($milliseconds / 1000);
        $minutes = floor($seconds / 60);
        $hours = floor($minutes / 60);
        $seconds %= 60;
        $minutes %= 60;
    
        $formattedText = '';
    
        if ($hours > 0) {
            $formattedText .= $hours . ' hour';
            if ($hours > 1) {
                $formattedText .= 's';
            }
        }
    
        if ($minutes > 0) {
            if ($formattedText !== '') {
                $formattedText .= ' ';
            }
            $formattedText .= $minutes . ' min';
            if ($minutes > 1) {
                $formattedText .= 's';
            }
        }
    
        if ($seconds > 0) {
            if ($formattedText !== '') {
                $formattedText .= ' ';
            }
            $formattedText .= $seconds . ' sec';
            if ($seconds > 1) {
                $formattedText .= 's';
            }
        }
    
        return $formattedText;
    }

    //helper function to convert milliseconds to min:sec
    function millisecondsToMinSec($milliseconds) {
        $seconds = floor($milliseconds / 1000);
        $minutes = floor($seconds / 60);
        $seconds %= 60;
    
        return sprintf("%02d:%02d", $minutes, $seconds);
    }
}
