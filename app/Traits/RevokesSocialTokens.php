<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait RevokesSocialTokens
{
    /**
     * Revoke social provider token
     */
    protected function revokeSocialToken($provider, $token)
    {
        try {
            if ($provider === 'google') {
                Http::post('https://oauth2.googleapis.com/revoke', [
                    'token' => $token,
                ]);
            } elseif ($provider === 'github') {
                $clientId = config('services.github.client_id');
                $clientSecret = config('services.github.client_secret');
                
                Http::withBasicAuth($clientId, $clientSecret)
                    ->delete("https://api.github.com/applications/{$clientId}/grant", [
                        'access_token' => $token,
                    ]);
            }
            \Log::info("Revoked {$provider} token.");
        } catch (\Exception $e) {
            \Log::error("Failed to revoke {$provider} token: " . $e->getMessage());
        }
    }
}
