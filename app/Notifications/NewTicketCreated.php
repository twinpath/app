<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Ticket;

class NewTicketCreated extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    public $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'icon' => 'ticket',
            'title' => 'New Support Ticket',
            'body' => "{$this->ticket->user->name} created ticket #{$this->ticket->ticket_number}: {$this->ticket->subject}",
            'action_url' => route('admin.tickets.show', $this->ticket->id),
            'type' => 'info',
            'created_at' => now(),
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'id' => $this->id,
            'data' => $this->toArray($notifiable),
            'created_at' => now(),
            'created_at_human' => now()->diffForHumans(), // Pre-calculate for frontend
            'read_url' => route('notifications.read', $this->id),
        ]);
    }
}
