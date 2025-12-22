<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return $user->id === $id;
});

Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('ticket.{ticketId}', function ($user, $ticketId) {
    // Ensure we convert ticketId to string if it's not already
    $ticket = \App\Models\Ticket::where('id', (string) $ticketId)->first();
    
    if (!$ticket) {
        return false;
    }

    // Allow owner or admin
    return (string) $user->id === (string) $ticket->user_id || $user->isAdmin();
});
