<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use Auth;

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

        return back()->with('success', 'Comment posted successfully!');
    }
}
