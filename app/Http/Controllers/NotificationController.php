<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        // Redirect to the target URL if available
        if (isset($notification->data['action_url'])) {
            return redirect($notification->data['action_url']);
        }

        return back();
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    }
    public function getUnread()
    {
        $notifications = Auth::user()->unreadNotifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'data' => [
                    'title' => $notification->data['title'] ?? 'Notification',
                    'body' => $notification->data['body'] ?? '',
                    'icon' => $notification->data['icon'] ?? null,
                    'action_url' => $notification->data['action_url'] ?? null,
                ],
                'created_at_human' => $notification->created_at->diffForHumans(),
                'read_url' => route('notifications.read', $notification->id),
            ];
        });

        return response()->json([
            'notifications' => $notifications,
            'count' => $notifications->count()
        ]);
    }
}
