<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    protected $clientId;
    protected $clientSecret;
    protected $redirectUri;
    protected $applicationKey;

    public function __construct()
    {
        $this->clientId       = config('services.kangaroo.client_id');
        $this->clientSecret   = config('services.kangaroo.client_secret');
        $this->redirectUri    = config('services.kangaroo.redirect');
        $this->applicationKey = config('services.kangaroo.application_key');
    }

    public function passwordProvider(Request $request)
    {
        dd($request);
        // Exchange code for access token
        $response = Http::asForm()->withHeaders([
            'X-Application-Key' => $this->applicationKey,
        ])->post('https://api.kangaroorewards.com/oauth/token', [
            'grant_type'    => 'password',
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'username'      => config('services.kangaroo.username'),
            'password'      => config('services.kangaroo.password'),
            'scope'         => 'admin',
        ]);

        if ($response->failed()) {
            abort(500, 'OAuth token exchange failed');
        }

        $tokenData = $response->json();

        // Store token data in session (or database) for subsequent API calls
        session([
            'kangaroo_access_token'  => $tokenData['access_token'],
            'kangaroo_refresh_token' => $tokenData['refresh_token'],
            'kangaroo_expires_in'    => $tokenData['expires_in'],
        ]);

        return redirect()->route('customers.index');
    }

    // Redirect the user to Kangaroo Rewards OAuth
    public function redirectToProvider(Request $request)
    {
        $authUrl = 'https://api.kangaroorewards.com/oauth/authorize';
        $query = http_build_query([
            'client_id'    => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'response_type'=> 'code',
            'scope'        => 'admin', // adjust scopes as needed
            'state'        => csrf_token(),
        ]);

        return redirect("{$authUrl}?{$query}");
    }

    // Handle the OAuth callback and exchange code for token
    public function handleProviderCallback(Request $request)
    {
        // Validate state parameter here (compare with session)
        if ($request->input('state') !== csrf_token()) {
            abort(403, 'Invalid state');
        }

        $code = $request->input('code');

        // Exchange code for access token
        $response = Http::asForm()->withHeaders([
            'X-Application-Key' => $this->applicationKey,
        ])->post('https://api.kangaroorewards.com/oauth/token', [
            'grant_type'    => 'authorization_code',
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri'  => $this->redirectUri,
            'code'          => $code,
            'scope'         => 'admin',
        ]);

        if ($response->failed()) {
            abort(500, 'OAuth token exchange failed');
        }

        $tokenData = $response->json();

        // Store token data in session (or database) for subsequent API calls
        session([
            'kangaroo_access_token'  => $tokenData['access_token'],
            'kangaroo_refresh_token' => $tokenData['refresh_token'],
            'kangaroo_expires_in'    => $tokenData['expires_in'],
        ]);

        return redirect()->route('customers.index');
    }
}
