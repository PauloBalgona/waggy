<?php

namespace App\Events;

use App\Models\Notification;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification;

    public function __construct(Notification $notification)
    {
        $this->notification = $notification->load('actor');
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->notification->user_id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->notification->id,
            'type' => $this->notification->type,
            'message' => $this->notification->message,
            'actor' => [
                'id' => $this->notification->actor->id ?? null,
                'pet_name' => $this->notification->actor->pet_name ?? null,
                'avatar' => isset($this->notification->actor->avatar) && $this->notification->actor->avatar ? asset('storage/' . $this->notification->actor->avatar) : asset('assets/usericon.png'),
            ],
            'created_at' => $this->notification->created_at->format('H:i'),
        ];
    }
}
