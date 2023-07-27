<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;

class DownloadController extends Controller
{
    /**
     * Download a song from the backend service
     *
     * @param Request $request (title, artist)
     * @return void
     * 
     * Sample request:
     * {
     *  "title": "The Less I Know The Better",
     *  "artist": "Tame Impala"
     * }
     */
    public function download(Request $request) 
    {
        $data =[
            'title' => $request->input('title'),
            'artist' => $request->input('artist'),
        ];

        $response = Http::post('http://localhost:8001/song/download', $data);

        $fileContent = $response->body();

        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $data['title'] . '.m4a"',
        ];
    
        return Response::make($fileContent, 200, $headers);
    }

    /**
     * Download a playlist from the backend service
     *
     * @param Request $request (playlistId)
     * @return void
     * 
     * Sample request:
     * {
     *  'items'
     * }
     */
    public function downloadPlaylist(Request $request) 
    {
        $playlistId = $request->input('id');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . session('spotifyAccessToken'),
        ])->get('https://api.spotify.com/v1/playlists/' . $playlistId);

        $response = $response->json();
        
        $playlistName = $response['name'];
        $tracks = [];
        foreach ($response['tracks']['items'] as $track) {
            $tracks[] = [
                'title' => $track['track']['name'],
                'artist' => $track['track']['artists'][0]['name'],
            ];
        }

        $timeout = 0;
        $response = Http::timeout($timeout)->post('http://localhost:8001/playlist/download', $tracks);

        $fileContent = $response->body();

        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $playlistName . '.zip"',
        ];

        return Response::make($fileContent, 200, $headers);
    }
}

