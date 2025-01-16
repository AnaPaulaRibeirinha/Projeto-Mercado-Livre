<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class OAuthController extends Controller
{
    public function generatePKCE()
    {
        $codeVerifier = Str::random(64); 
        $codeChallenge = $this->base64_url_encode(hash('sha256', $codeVerifier, true)); // Use o método privado

        session(['code_verifier' => $codeVerifier, 'code_challenge' => $codeChallenge]);

        return $codeChallenge;
    }

    public function redirectToProvider()
    {
        $clientId = env('MERCADO_LIVRE_CLIENT_ID');
        $redirectUri = env('MERCADO_LIVRE_REDIRECT_URI');

    
        $authUrl = "https://auth.mercadolivre.com.br/authorization?response_type=code&client_id={$clientId}&redirect_uri={$redirectUri}";

        return redirect($authUrl);
    }


    public function handleProviderCallback(Request $request)
    {
        $code = $request->query('code');

        if (!$code) {
            return response()->json(['error' => 'Authorization code not found'], 400);
        }

        $client = new Client();
        $response = $client->post('https://api.mercadolibre.com/oauth/token', [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => env('MERCADO_LIVRE_CLIENT_ID'),
                'client_secret' => env('MERCADO_LIVRE_CLIENT_SECRET'),
                'code' => $code,
                'redirect_uri' => env('MERCADO_LIVRE_REDIRECT_URI'),
            ],
        ]);

        $responseData = json_decode($response->getBody(), true);

        session([
            'mercado_livre_access_token' => $responseData['access_token'],
            'mercado_livre_refresh_token' => $responseData['refresh_token'],
            'mercado_livre_expires_in' => now()->addSeconds($responseData['expires_in']),
        ]);

        return redirect()->route('home')->with('success', 'Autenticado com sucesso!');
    }
    private function base64_url_encode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
