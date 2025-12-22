<?php

namespace App\Events;

use App\Models\TicketReply;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketMessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $reply;

    /**
     * Create a new event instance.
     */
    public function __construct(TicketReply $reply)
    {
        $this->reply = $reply->load('user');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('ticket.' . $this->reply->ticket_id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'ticket.message.sent';
    }

    /**
     * Data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->reply->id,
            'message' => $this->reply->message,
            'user_id' => $this->reply->user_id,
            'user_name' => $this->reply->user->name,
            'is_staff' => $this->reply->user->isAdmin(),
            'created_at' => $this->reply->created_at->format('M d, Y H:i A'),
            'attachment_url' => $this->reply->attachment_path ? \Storage::url($this->reply->attachment_path) : null,
        ];
    }
}
