<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\TicketReply;

class NewTicketReply extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    public $reply;

    public function __construct(TicketReply $reply)
    {
        $this->reply = $reply;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toArray(object $notifiable): array
    {
        $ticket = $this->reply->ticket;
        $url = $notifiable->isAdmin() 
            ? route('admin.tickets.show', $ticket->id)
            : route('support.show', $ticket->id);

        return [
            'icon' => 'chat',
            'title' => "New Reply on Ticket #{$ticket->ticket_number}",
            'body' => "{$this->reply->user->name}: " . \Illuminate\Support\Str::limit($this->reply->message, 50),
            'action_url' => $url,
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
            'created_at_human' => now()->diffForHumans(),
            'read_url' => route('notifications.read', $this->id),
        ]);
    }
}
