<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Session;


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
    public function initiatePlaylistDownload(Request $request) 
    {
        Session::forget('downloadId');
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

        $response = Http::post('http://localhost:8001/playlist/download/server', $tracks)->json();
        // Store the download id in the session
        $downloadId = $response['download_id'];
        session(['downloadId' => $downloadId]);
        
        return response()->json(['download_id' => $downloadId]);
    }

    /**
     * Check the status of a playlist download
     *
     * @return void
     */
    public function checkPlaylistDownloadStatus()
    {
        $downloadId = session('downloadId');
        $response = Http::get('http://localhost:8001/playlist/download/status/' . $downloadId)->json();

        if ($response['status'] === 'failed') {
            return response()->json(['error' => 'Download failed'], 500);
        }

        return $response['status'];
    }

    /**
     * Download a playlist from the backend service
     *
     * @return void
     */
    public function downloadPlaylist()
    {
        ini_set('memory_limit', '512M'); // Set a higher memory limit, adjust as needed
        $downloadId = session('downloadId');
        $url = 'http://localhost:8001/playlist/download/client';
        try {
            $response = Http::withOptions([
                'stream' => true,
            ])->get($url, ['download_id' => $downloadId]);

            if ($response->ok()) {
                $headers = [
                    'Content-Type' => 'application/zip',
                    'Content-Disposition' => 'attachment; filename="downloaded_file.zip"',
                ];

                return response()->streamDownload(function () use ($response) {
                    echo $response->body();
                }, $downloadId . '.zip', $headers);
            } else {
                return response()->json(['error' => 'Download failed'], $response->status());
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred during the download'], 500);
        }  
        // finally {
        //     Session::forget('downloadId');
        // }
    }
}

