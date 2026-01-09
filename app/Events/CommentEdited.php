<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentEdited implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $commentId;
    public $content;
    public $postId;

    public function __construct($commentId, $content, $postId)
    {
        $this->commentId = $commentId;
        $this->content = $content;
        $this->postId = $postId;
    }

    public function broadcastOn()
    {
        return new Channel('post.' . $this->postId);
    }

    public function broadcastAs()
    {
        return 'comment.edited';
    }
}
