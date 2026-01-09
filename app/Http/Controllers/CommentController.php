<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Notification;
use App\Models\Reply;
use Auth;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function index(Post $post)
    {
        $post->load([
            'user',
            'likes',
        ])->loadCount('likes');

        // Load all comments for this post
        $comments = Comment::where('post_id', $post->id)
            ->with('user')
                ->with('replies.user')
            ->latest()
            ->get();

        return view('comment.index', compact('post', 'comments'));
    }

    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'content' => $request->input('content'),
        ]);

        // Create notification for post owner
        if ($post->user_id !== Auth::id()) {
            Notification::create([
                'user_id' => $post->user_id,
                'actor_id' => Auth::id(),
                'type' => 'comment',
                'message' => Auth::user()->pet_name . ' commented on your post',
                'post_id' => $post->id,
            ]);
        }

        return back()->with('success', 'Comment posted successfully!');
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        // Check if user is the comment author or the post owner
        if ($comment->user_id !== Auth::id() && $comment->post->user_id !== Auth::id()) {
            if (request()->wantsJson()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            return back()->with('error', 'Unauthorized');
        }

        $comment->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Comment deleted successfully!']);
        }

        return back()->with('success', 'Comment deleted successfully!');
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        // Check if user is the comment author
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update([
            'content' => $request->input('content'),
        ]);

        return response()->json([
            'success' => true,
            'content' => $comment->content,
            'updated_at' => $comment->updated_at->diffForHumans(),
        ]);
    }

    public function storeReply(Request $request, $commentId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = Comment::findOrFail($commentId);

        $reply = Reply::create([
            'comment_id' => $commentId,
            'user_id' => Auth::id(),
            'content' => $request->input('content'),
        ]);

        // Create notification for comment author (if not replying to own comment)
        if ($comment->user_id !== Auth::id()) {
            try {
                Notification::create([
                    'user_id' => $comment->user_id,
                    'actor_id' => Auth::id(),
                    'type' => 'reply',
                    'message' => Auth::user()->pet_name . ' replied to your comment',
                    'post_id' => $comment->post_id,
                ]);
            } catch (\Throwable $e) {
                // Log the error so we can diagnose why notifications aren't being created
                Log::error('Failed to create reply notification', [
                    'error' => $e->getMessage(),
                    'comment_id' => $commentId,
                    'actor_id' => Auth::id(),
                    'recipient_id' => $comment->user_id,
                ]);
            }
        }

        // Always return JSON for AJAX requests (Content-Type header check)
        if ($request->wantsJson() || $request->header('Content-Type') === 'application/json') {
            return response()->json([
                'success' => true,
                'reply' => $reply->load('user'),
            ], 200, [], JSON_UNESCAPED_SLASHES);
        }

        return back()->with('success', 'Reply posted successfully!');
        }

        public function destroyReply($replyId)
        {
            $reply = Reply::findOrFail($replyId);
            $comment = $reply->comment;

            // Check if user is the reply author or comment author or post owner
            if ($reply->user_id !== Auth::id() && $comment->user_id !== Auth::id() && $comment->post->user_id !== Auth::id()) {
                if (request()->wantsJson()) {
                    return response()->json(['error' => 'Unauthorized'], 403);
                }
                return back()->with('error', 'Unauthorized');
            }

            $reply->delete();

            if (request()->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Reply deleted successfully!']);
            }

            return back()->with('success', 'Reply deleted successfully!');
    }
}
