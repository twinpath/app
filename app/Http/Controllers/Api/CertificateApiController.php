<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CertificateApiController extends Controller
{
    /**
     * Display a listing of the user's certificates.
     */
    public function index(Request $request)
    {
        $user = $request->get('authenticated_user');
        
        $certificates = $user->certificates()
            ->latest()
            ->get([
                'uuid', 
                'common_name', 
                'organization', 
                'san',
                'valid_from', 
                'valid_to', 
                'cert_content', 
                'key_content'
            ])
            ->map(function ($cert) {
                return [
                    'id' => $cert->uuid,
                    'common_name' => $cert->common_name,
                    'organization' => $cert->organization,
                    'san' => $cert->san,
                    'issued_at' => $cert->valid_from->toIso8601String(),
                    'expires_at' => $cert->valid_to->toIso8601String(),
                    'certificate' => $cert->cert_content,
                    'private_key' => $cert->key_content,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $certificates
        ]);
    }
}
