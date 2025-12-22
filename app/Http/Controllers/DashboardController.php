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

        // Format data for view
        $formattedCertificates = $recentCertificates->map(fn($c) => [
            'common_name' => $c->common_name,
            'organization' => $c->organization,
            'created_at' => $c->created_at->format('M d, Y'),
            'valid_to' => $c->valid_to->format('M d, Y'),
            'is_valid' => $c->valid_to > now(),
        ]);

        $formattedApiActivity = $recentApiActivity->map(fn($k) => [
            'name' => $k->name,
            'last_used_diff' => $k->last_used_at?->diffForHumans() ?? 'None',
        ]);

        return view('pages.dashboard', [
            'totalApiKeys' => $totalApiKeys,
            'totalCertificates' => $totalCertificates,
            'activeCertificates' => $activeCertificates,
            'expiringSoonCount' => $expiringSoonCount,
            'recentCertificates' => $formattedCertificates,
            'recentApiActivity' => $formattedApiActivity,
            'months' => $months,
            'issuanceData' => $issuanceData
        ]);
    }

    public function stats()
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
            ->get()->map(function($cert) {
                return [
                    'common_name' => $cert->common_name,
                    'organization' => $cert->organization,
                    'created_at' => $cert->created_at->format('M d, Y'),
                    'valid_to' => $cert->valid_to->format('M d, Y'),
                    'is_valid' => $cert->valid_to > now(),
                ];
            });
            
        $recentApiActivity = $user->apiKeys()
            ->whereNotNull('last_used_at')
            ->orderBy('last_used_at', 'desc')
            ->limit(5)
            ->get()->map(function($key) {
                return [
                    'name' => $key->name,
                    'last_used_diff' => $key->last_used_at->diffForHumans(),
                ];
            });

        // Chart Data
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

        return response()->json([
            'totalApiKeys' => $totalApiKeys,
            'totalCertificates' => $totalCertificates,
            'activeCertificates' => $activeCertificates,
            'expiringSoonCount' => $expiringSoonCount,
            'recentCertificates' => $recentCertificates,
            'recentApiActivity' => $recentApiActivity,
            'months' => $months,
            'issuanceData' => $issuanceData,
            'maxIssuance' => max($issuanceData) ?: 1,
        ]);
    }

    public function ping()
    {
        $userId = Auth::id();
        $timestamp = now()->getTimestampMs();
        
        \App\Events\PingResponse::dispatch($userId, $timestamp);
        
        return response()->noContent();
    }
}
