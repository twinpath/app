<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Ticket;

class TicketStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'icon' => 'check-circle',
            'title' => 'Ticket Status Updated',
            'body' => "Your ticket #{$this->ticket->ticket_number} has been marked as " . ucfirst($this->ticket->status),
            'action_url' => route('support.show', $this->ticket->id),
            'type' => $this->ticket->status === 'closed' ? 'success' : 'info',
            'created_at' => now(),
        ];
    }
}
