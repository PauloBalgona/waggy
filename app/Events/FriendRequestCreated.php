<?php

namespace App\Events;

use App\Models\FriendRequest;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FriendRequestCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $friendRequest;

    public function __construct(FriendRequest $friendRequest)
    {
        $this->friendRequest = $friendRequest->load('sender');
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->friendRequest->receiver_id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->friendRequest->id,
            'sender_id' => $this->friendRequest->sender_id,
            'sender' => [
                'id' => $this->friendRequest->sender->id,
                'pet_name' => $this->friendRequest->sender->pet_name,
                'avatar' => $this->friendRequest->sender->avatar ? asset('storage/' . $this->friendRequest->sender->avatar) : asset('assets/usericon.png'),
            ],
            'created_at' => $this->friendRequest->created_at->format('H:i'),
        ];
    }
}
