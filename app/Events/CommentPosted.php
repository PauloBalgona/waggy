<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentPosted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function broadcastOn()
    {
        // Broadcast to all viewers of the post
        return new Channel('post.' . $this->comment->post_id);
    }

    public function broadcastWith()
    {
        $user = $this->comment->user;
        return [
            'id' => $this->comment->id,
            'content' => $this->comment->content,
            'created_at' => $this->comment->created_at->diffForHumans(),
            'user' => [
                'id' => $user->id,
                'pet_name' => $user->pet_name,
                'name' => $user->name,
                'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : asset('assets/usericon.png'),
            ],
        ];
    }
}
