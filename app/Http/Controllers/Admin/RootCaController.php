<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CaCertificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class RootCaController extends Controller
{
    public function index()
    {
        $certificates = CaCertificate::all()->map(function($cert) {
            $cert->status = ($cert->valid_to > now()) ? 'valid' : 'expired';
            return $cert;
        });

        return view('pages.admin.root-ca.index', [
            'certificates' => $certificates,
            'title' => 'Root CA Management'
        ]);
    }

    public function renew(Request $request, CaCertificate $certificate)
    {
        $days = (int) $request->input('days', 3650);
        
        try {
            $newData = app(\App\Services\OpenSslService::class)->renewCaCertificate($certificate, $days);
            
            $certificate->update([
                'cert_content' => $newData['cert_content'],
                'serial_number' => $newData['serial_number'],
                'valid_from' => $newData['valid_from'],
                'valid_to' => $newData['valid_to'],
            ]);

            return redirect()->back()->with('success', 'Certificate renewed successfully (Re-signed).');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Renewal failed: ' . $e->getMessage());
        }
    }
}
