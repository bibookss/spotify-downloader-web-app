<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;

class DownloadController extends Controller
{
    public function download(Request $request) 
    {
        // Get the 'title' and 'artist' from the request
        $title = $request->input('title');
        $artist = $request->input('artist');

        // Make an HTTP GET request to the backend service
        $response = Http::get('http://localhost:8001/api/download', [
            'title' => $title,
            'artist' => $artist,
        ]);

        $fileContent = $response->body();

        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $title . '.m4a"',
        ];
    
        // Return the file content as a download response
        return Response::make($fileContent, 200, $headers);
    }

}

