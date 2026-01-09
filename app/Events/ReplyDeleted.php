<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReplyDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $replyId;
    public $postId;

    public function __construct($replyId, $postId)
    {
        $this->replyId = $replyId;
        $this->postId = $postId;
    }

    public function broadcastOn()
    {
        return new Channel('post.' . $this->postId);
    }

    public function broadcastAs()
    {
        return 'reply.deleted';
    }
}
