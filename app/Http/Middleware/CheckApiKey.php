<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ApiKey;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $keyString = $request->header('X-API-KEY');

        if (!$keyString) {
            return response()->json([
                'success' => false,
                'message' => 'API Key is missing. Please provide it in the X-API-KEY header.'
            ], 401);
        }

        $apiKey = ApiKey::where('key', $keyString)->first();

        if (!$apiKey || !$apiKey->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or inactive API Key.'
            ], 401);
        }

        // Update last used timestamp
        $apiKey->update(['last_used_at' => now()]);

        // Put the user in the request context
        $request->merge(['authenticated_user' => $apiKey->user]);
        
        // Alternatively, if we want to use Auth facade, we can manually log in the user for this request
        // \Auth::login($apiKey->user);

        return $next($request);
    }
}
