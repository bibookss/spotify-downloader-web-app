<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;

class TokenRefreshMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the access token has expired or is about to expire (within a buffer time)
        if ($this->shouldRefreshToken()) {
            // Refresh the token
            $this->refreshToken();
        }

        return $next($request);
    }

    public function shouldRefreshToken()
    {
        $expirationTime = session('spotifyExpiresIn');
        $currentTime = time();
        $bufferTime = 3600;

        return $expirationTime - $currentTime < $bufferTime;
    }

    public function refreshToken()
    {
        $client_id = env('SPOTIFY_CLIENT_ID');
        $client_secret = env('SPOTIFY_CLIENT_SECRET');
        $refresh_token = session('spotifyRefreshToken');

        $response = Http::asForm()->post('https://accounts.spotify.com/api/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refresh_token,
            'client_id' => $client_id,
            'client_secret' => $client_secret,
        ]);

        $access_token = $response->json('access_token');
        session(['spotifyAccessToken' => $access_token]);
        session(['spotifyExpiresIn' => time() + $response->json('expires_in')]);

    }
}
