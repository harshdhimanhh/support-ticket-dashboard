<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessageEvent implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Message $message
    ) {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel(
                'ticket.' . $this->message->ticket_id
            ),
        ];
    }

    public function broadcastAs(): string
    {
        return 'NewMessageEvent';
    }

    /**
     * Send a small, explicit payload so every connected client renders the
     * message that was just created (and never a stale serialized model).
     */
    public function broadcastWith(): array
    {
        return [
            'message' => [
                'id' => $this->message->id,
                'ticket_id' => $this->message->ticket_id,
                'user_type' => $this->message->user_type,
                'message' => $this->message->message,
                'created_at' => $this->message->created_at?->toISOString(),
            ],
        ];
    }
}
