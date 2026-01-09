<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReplyPosted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $reply;

    public function __construct($reply)
    {
        $this->reply = $reply;
    }

    public function broadcastOn()
    {
        // Make sure comment is loaded!
        return new Channel('post.' . $this->reply->comment->post_id);
    }

    public function broadcastAs()
    {
        return 'reply.posted';
    }
}
