<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Certificate;
use App\Models\Ticket;
use App\Models\CaCertificate;
use App\Helpers\MenuHelper;

class SearchController extends Controller
{
    public function global(Request $request)
    {
        $query = $request->get('q');
        $user = Auth::user();
        
        if (!$query || strlen($query) < 2) {
            return response()->json([
                'Navigation' => $this->getNavigation($user, $query)
            ]);
        }

        $results = [];

        // 1. Navigation Search
        $navigation = $this->getNavigation($user, $query);
        if (!empty($navigation)) {
            $results['Navigation'] = $navigation;
        }

        if ($user->isAdmin()) {
            // 2. Admin: User Search
            $users = User::where('name', 'like', "%$query%")
                ->orWhere('email', 'like', "%$query%")
                ->limit(5)
                ->get()
                ->map(fn($u) => [
                    'label' => $u->name,
                    'sublabel' => $u->email,
                    'url' => route('admin.users.index') . '?search=' . $u->email,
                    'icon' => 'user'
                ]);
            if ($users->count() > 0) $results['Users'] = $users;

            // 3. Admin: Ticket Search
            $tickets = Ticket::where('ticket_id', 'like', "%$query%")
                ->orWhere('subject', 'like', "%$query%")
                ->limit(5)
                ->get()
                ->map(fn($t) => [
                    'label' => $t->subject,
                    'sublabel' => '#' . $t->ticket_id,
                    'url' => route('admin.tickets.show', $t->id),
                    'icon' => 'ticket'
                ]);
            if ($tickets->count() > 0) $results['Tickets'] = $tickets;

            // 4. Admin: Root CA Search
            $cas = CaCertificate::where('common_name', 'like', "%$query%")
                ->orWhere('uuid', 'like', "%$query%")
                ->limit(5)
                ->get()
                ->map(fn($ca) => [
                    'label' => $ca->common_name,
                    'sublabel' => 'Type: ' . strtoupper($ca->ca_type),
                    'url' => route('admin.root-ca.index'),
                    'icon' => 'shield'
                ]);
            if ($cas->count() > 0) $results['Root CAs'] = $cas;
            
        } else {
            // 4. Customer: Certificate Search
            $certs = Certificate::where('user_id', $user->id)
                ->where(function($q) use ($query) {
                    $q->where('common_name', 'like', "%$query%")
                      ->orWhere('uuid', 'like', "%$query%");
                })
                ->limit(5)
                ->get()
                ->map(fn($c) => [
                    'label' => $c->common_name,
                    'sublabel' => 'UUID: ' . substr($c->uuid, 0, 8) . '...',
                    'url' => route('certificate.index') . '?uuid=' . $c->uuid,
                    'icon' => 'certificate'
                ]);
            if ($certs->count() > 0) $results['Certificates'] = $certs;

            // 5. Customer: Ticket Search
            $tickets = Ticket::where('user_id', $user->id)
                ->where(function($q) use ($query) {
                    $q->where('ticket_id', 'like', "%$query%")
                      ->orWhere('subject', 'like', "%$query%");
                })
                ->limit(5)
                ->get()
                ->map(fn($t) => [
                    'label' => $t->subject,
                    'sublabel' => '#' . $t->ticket_id,
                    'url' => route('support.show', $t->id),
                    'icon' => 'ticket'
                ]);
            if ($tickets->count() > 0) $results['Tickets'] = $tickets;
        }

        return response()->json($results);
    }

    private function getNavigation($user, $query = null)
    {
        $menuGroups = MenuHelper::getMenuGroups();
        $allNavs = [];

        foreach ($menuGroups as $group) {
            // Skip Template Gallery group explicitly just in case
            if ($group['title'] === 'Template Gallery' || $group['title'] === 'Development') continue;

            foreach ($group['items'] as $item) {
                // Process main item
                $this->processMenuItem($item, $allNavs);

                // Process subItems if any
                if (isset($item['subItems'])) {
                    foreach ($item['subItems'] as $subItem) {
                        $this->processMenuItem($subItem, $allNavs);
                    }
                }
            }
        }

        return collect($allNavs)
            ->filter(function($nav) use ($query) {
                // Exclude templates
                if (isset($nav['route_name']) && str_starts_with($nav['route_name'], 'templates.')) return false;
                
                // Check query
                if ($query && stripos($nav['label'], $query) === false) return false;
                
                return true;
            })
            ->unique('url') // Avoid duplicates
            ->values()
            ->take(8) // Give more suggestions
            ->toArray();
    }

    private function processMenuItem($item, &$allNavs)
    {
        if (!isset($item['route_name'])) return;

        $allNavs[] = [
            'label' => $item['name'],
            'url' => route($item['route_name']),
            'icon' => $this->mapIcon($item['icon'] ?? 'default'),
            'route_name' => $item['route_name']
        ];
    }

    private function mapIcon($sidebarIcon)
    {
        // Map common sidebar icons to search icons
        $map = [
            'dashboard' => 'home',
            'certificate' => 'certificate',
            'support-ticket' => 'ticket',
            'users' => 'users',
            'user-profile' => 'user',
            'settings' => 'settings',
            'api-key' => 'key',
            'server-settings' => 'shield',
        ];

        return $map[$sidebarIcon] ?? 'default';
    }
}
