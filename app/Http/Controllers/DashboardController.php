<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use App\Models\Certificate;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Basic Counts
        $totalApiKeys = $user->apiKeys()->count();
        $totalCertificates = $user->certificates()->count();
        $activeCertificates = $user->certificates()
            ->where('valid_to', '>', now())
            ->count();
        $expiringSoonCount = $user->certificates()
            ->where('valid_to', '>', now())
            ->where('valid_to', '<=', now()->addDays(14))
            ->count();

        // Recent Activity
        $recentCertificates = $user->certificates()
            ->latest()
            ->limit(5)
            ->get();
            
        $recentApiActivity = $user->apiKeys()
            ->whereNotNull('last_used_at')
            ->orderBy('last_used_at', 'desc')
            ->limit(5)
            ->get();

        // Chart Data: Certificates issued per month (last 6 months)
        $months = [];
        $issuanceData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M');
            $issuanceData[] = $user->certificates()
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        return view('pages.dashboard', compact(
            'totalApiKeys',
            'totalCertificates',
            'activeCertificates',
            'expiringSoonCount',
            'recentCertificates',
            'recentApiActivity',
            'months',
            'issuanceData'
        ));
    }
}
