<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReplyEdited implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $replyId;
    public $content;
    public $commentId;
    public $postId;

    public function __construct($replyId, $content, $commentId, $postId)
    {
        $this->replyId = $replyId;
        $this->content = $content;
        $this->commentId = $commentId;
        $this->postId = $postId;
    }

    public function broadcastOn()
    {
        return new Channel('post.' . $this->postId);
    }

    public function broadcastAs()
    {
        return 'reply.edited';
    }
}
