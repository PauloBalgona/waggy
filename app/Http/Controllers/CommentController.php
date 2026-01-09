<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Notification;
use App\Models\Reply;
use App\Events\CommentPosted;
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
        try {
            $request->validate([
                'content' => 'required|string|max:1000',
            ]);

            $comment = Comment::create([
                'user_id' => Auth::id(),
                'post_id' => $post->id,
                'content' => $request->input('content'),
            ]);

            // Load the user relationship
            $comment->load('user');

            // Broadcast to all viewers of the post
            try {
                broadcast(new CommentPosted($comment))->toOthers();
            } catch (\Exception $e) {
                Log::warning('Failed to broadcast comment', ['error' => $e->getMessage()]);
            }

            // Create notification for post owner
            if ($post->user_id !== Auth::id()) {
                try {
                    Notification::create([
                        'user_id' => $post->user_id,
                        'actor_id' => Auth::id(),
                        'type' => 'comment',
                        'message' => (Auth::user()->pet_name ?? Auth::user()->name) . ' commented on your post',
                        'post_id' => $post->id,
                    ]);
                } catch (\Exception $e) {
                    Log::warning('Failed to create notification', ['error' => $e->getMessage()]);
                }
            }

            // Always return JSON for AJAX requests
            if ($request->wantsJson() || $request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'comment' => [
                        'id' => $comment->id,
                        'content' => $comment->content,
                        'user' => [
                            'name' => $comment->user->pet_name ?? $comment->user->name,
                            'avatar' => $comment->user->avatar,
                        ],
                        'created_at' => 'just now',
                    ]
                ]);
            }

            return back()->with('success', 'Comment posted successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->wantsJson() || $request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            Log::error('Failed to create comment', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->wantsJson() || $request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create comment: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Failed to create comment');
        }
    }

    public function destroy($id)
    {
        try {
            $comment = Comment::findOrFail($id);

            // Check if user is the comment author or the post owner
            if ($comment->user_id !== Auth::id() && $comment->post->user_id !== Auth::id()) {
                if (request()->wantsJson()) {
                    return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
                }
                return back()->with('error', 'Unauthorized');
            }

            $postId = $comment->post_id;
            $comment->delete();

            // Broadcast real-time delete event
            broadcast(new \App\Events\CommentDeleted($id, $postId))->toOthers();

            if (request()->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Comment deleted successfully!']);
            }

            return back()->with('success', 'Comment deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to delete comment', ['error' => $e->getMessage()]);
            
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to delete comment'], 500);
            }
            
            return back()->with('error', 'Failed to delete comment');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $comment = Comment::findOrFail($id);

            // Check if user is the comment author
            if ($comment->user_id !== Auth::id()) {
                return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
            }

            $request->validate([
                'content' => 'required|string|max:1000',
            ]);

            $comment->update([
                'content' => $request->input('content'),
            ]);

            // Broadcast real-time edit event
            broadcast(new \App\Events\CommentEdited($comment->id, $comment->content, $comment->post_id))->toOthers();

            return response()->json([
                'success' => true,
                'content' => $comment->content,
                'updated_at' => $comment->updated_at->diffForHumans(),
            ]);
            {
                try {
                    $reply = Reply::findOrFail($replyId);
                    $comment = $reply->comment;

                    // Check if user is the reply author
                    if ($reply->user_id !== Auth::id()) {
                        return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
                    }

                    $request->validate([
                        'content' => 'required|string|max:1000',
                    ]);

                    $reply->update([
                        'content' => $request->input('content'),
                    ]);

                    // Broadcast real-time edit event
                    broadcast(new \App\Events\ReplyEdited($reply->id, $reply->content, $reply->comment_id, $comment->post_id))->toOthers();

                    return response()->json([
                        'success' => true,
                        'content' => $reply->content,
                        'updated_at' => $reply->updated_at->diffForHumans(),
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to update reply', ['error' => $e->getMessage()]);
                    return response()->json(['success' => false, 'message' => 'Failed to update reply'], 500);
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to update comment', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to update comment'], 500);
        }
    }

    public function storeReply(Request $request, $commentId)
    {
        try {
            $request->validate([
                'content' => 'required|string|max:1000',
            ]);

            $comment = Comment::findOrFail($commentId);


            $reply = Reply::create([
                'comment_id' => $commentId,
                'user_id' => Auth::id(),
                'content' => $request->input('content'),
            ]);

            // Load user and comment relationship for broadcasting
            $reply->load(['user', 'comment']);

            // Broadcast reply to all users viewing this post
            broadcast(new \App\Events\ReplyPosted($reply))->toOthers();

            // Create notification for comment author (if not replying to own comment)
            if ($comment->user_id !== Auth::id()) {
                try {
                    Notification::create([
                        'user_id' => $comment->user_id,
                        'actor_id' => Auth::id(),
                        'type' => 'reply',
                        'message' => (Auth::user()->pet_name ?? Auth::user()->name) . ' replied to your comment',
                        'post_id' => $comment->post_id,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to create reply notification', [
                        'error' => $e->getMessage(),
                        'comment_id' => $commentId,
                        'actor_id' => Auth::id(),
                        'recipient_id' => $comment->user_id,
                    ]);
                }
            }

            // Always return JSON for AJAX requests
            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'reply' => [
                        'id' => $reply->id,
                        'content' => $reply->content,
                        'user' => [
                            'name' => $reply->user->pet_name ?? $reply->user->name,
                            'pet_name' => $reply->user->pet_name ?? $reply->user->name,
                            'avatar' => $reply->user->avatar,
                        ],
                        'created_at' => 'just now',
                    ]
                ], 200);
            }

            return back()->with('success', 'Reply posted successfully!');

        } catch (\Exception $e) {
            Log::error('Failed to create reply', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create reply: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Failed to create reply');
        }
    }

    public function destroyReply($replyId)
    {
        try {
            $reply = Reply::findOrFail($replyId);
            $comment = $reply->comment;

            // Check if user is the reply author or comment author or post owner
            if ($reply->user_id !== Auth::id() && $comment->user_id !== Auth::id() && $comment->post->user_id !== Auth::id()) {
                if (request()->wantsJson()) {
                    return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
                }
                return back()->with('error', 'Unauthorized');
            }

            $postId = $comment->post->id;
            $reply->delete();

            // Broadcast real-time delete event
            broadcast(new \App\Events\ReplyDeleted($replyId, $postId))->toOthers();

            if (request()->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Reply deleted successfully!']);
            }

            return back()->with('success', 'Reply deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to delete reply', ['error' => $e->getMessage()]);
            
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to delete reply'], 500);
            }
            
            return back()->with('error', 'Failed to delete reply');
        }
    }
}