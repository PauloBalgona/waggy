<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message->load('sender');
    }

    /**
     * Get the channels the event should broadcast on.
     * We'll broadcast on the recipient's private channel `user.{id}`
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->message->receiver_id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'sender_id' => $this->message->sender_id,
            'receiver_id' => $this->message->receiver_id,
            'message' => $this->message->message,
            'created_at' => $this->message->created_at->format('H:i'),
            'sender' => [
                'id' => $this->message->sender->id,
                'pet_name' => $this->message->sender->pet_name,
                'avatar' => $this->message->sender->avatar ? asset('storage/' . $this->message->sender->avatar) : asset('assets/usericon.png'),
            ],
        ];
    }
}
