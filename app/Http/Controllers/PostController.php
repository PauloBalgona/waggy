<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\Notification;
use App\Models\Block;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')->latest()->paginate(10);
        return view('posts.index', compact('posts'));
    }

    public function store(Request $request)
    {
        // VALIDATION
        $request->validate([
            'content' => 'nullable|string|max:1000',
            'age' => 'nullable|integer|min:1|max:5',
            'breed' => 'nullable|string',
            'province' => 'nullable|string',
            'city' => 'nullable|string',
            'interest' => 'nullable|string',
            'audience' => 'nullable|in:public,friends',
            'photoUpload' => 'nullable|image|max:4096',
            'image_base64' => 'nullable|string'
        ]);

        $audience = strtolower($request->input('audience', 'public'));
        if (!in_array($audience, ['public', 'friends'])) {
            $audience = 'public';
        }

        $photoPath = null;

        // FILE UPLOAD
        if ($request->hasFile('photoUpload')) {
            $photoPath = $request->file('photoUpload')->store('posts', 'public');
        }

        // BASE64 IMAGE
        if (!$photoPath && $request->image_base64) {
            $data = preg_replace('#^data:image/\w+;base64,#i', '', $request->image_base64);
            $fileName = 'post_' . time() . '.jpg';
            $path = storage_path('app/public/posts/' . $fileName);

            if (!file_exists(dirname($path))) {
                mkdir(dirname($path), 0755, true);
            }

            file_put_contents($path, base64_decode($data));
            $photoPath = 'posts/' . $fileName;
        }

        // CREATE POST
        $post = Post::create([
            'user_id' => auth()->id(),
            'message' => $request->content,
            'age' => $request->age,
            'breed' => $request->breed,
            'province' => $request->province,
            'city' => $request->city,
            'interest' => $request->interest,
            'audience' => $audience,
            'photo' => $photoPath,
        ]);

        session()->forget('uploaded_image_path');

        // Broadcast PostCreated event for real-time updates
        try {
            $postHtml = view('components.post-card', ['post' => $post->load('user')])->render();
            \Illuminate\Support\Facades\Queue::push(function () use ($post, $postHtml) {
                event(new \App\Events\PostCreated($post, $postHtml));
            });
        } catch (\Exception $e) {
            \Log::error('Failed to broadcast PostCreated: ' . $e->getMessage());
        }

        // AJAX RESPONSE
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'post' => $post->load('user'),
            ]);
        }

        return redirect()->route('home')->with('success', 'Post created successfully!');
    }

    public function show($id)
    {
        $post = Post::with(['user', 'comments.user', 'comments.replies.user'])->findOrFail($id);

        if (
            Auth::user()->hasBlocked($post->user_id) ||
            Auth::user()->isBlockedBy($post->user_id)
        ) {
            return redirect()->route('home')->with('error', 'You cannot view this post.');
        }

        return view('posts.show', compact('post'));
    }

    public function like($id)
    {
        $post = Post::findOrFail($id);

        $existingLike = PostLike::where('post_id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $post->decrement('likes_count');
        } else {
            PostLike::create([
                'post_id' => $id,
                'user_id' => auth()->id(),
            ]);

            $post->increment('likes_count');

            if ($post->user_id !== auth()->id()) {
                Notification::create([
                    'user_id' => $post->user_id,
                    'actor_id' => auth()->id(),
                    'type' => 'like',
                    'message' => auth()->user()->pet_name . ' liked your post',
                    'post_id' => $post->id,
                ]);
            }
        }

        return back();
    }

    public function setUploadSession(Request $request)
    {
        if ($request->hasFile('image')) {
            $image = base64_encode(file_get_contents($request->file('image')));
            session(['uploaded_image' => 'data:image/png;base64,' . $image]);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function clearUploadSession()
    {
        session()->forget('uploaded_image');
        return response()->json(['success' => true]);
    }

    public function postingPage()
    {
        $image = session('uploaded_image');
        $userPetAge = auth()->user()->pet_age;
        $userPetBreed = auth()->user()->pet_breed;

        return view('posts.index', compact('image', 'userPetAge', 'userPetBreed'));
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== auth()->id()) {
            return back()->with('error', 'You are not allowed to delete this post.');
        }

        if ($post->photo && file_exists(storage_path('app/public/' . $post->photo))) {
            unlink(storage_path('app/public/' . $post->photo));
        }

        $post->delete();

        return back()->with('success', 'Post deleted successfully.');
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== auth()->id()) {
            return back()->with('error', 'You are not allowed to edit this post.');
        }

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== auth()->id()) {
            return back()->with('error', 'You are not allowed to edit this post.');
        }

        $request->validate([
            'content' => 'nullable|string|max:1000',
            'age' => 'nullable|integer|min:1|max:5',
            'breed' => 'nullable|string',
            'province' => 'nullable|string',
            'city' => 'nullable|string',
            'interest' => 'nullable|string',
            'audience' => 'nullable|in:public,friends',
            'photoUpload' => 'nullable|image|max:4096',
        ]);

        $audience = strtolower($request->input('audience', 'public'));
        if (!in_array($audience, ['public', 'friends'])) {
            $audience = 'public';
        }

        $photoPath = $post->photo;

        if ($request->hasFile('photoUpload')) {
            if ($post->photo && file_exists(storage_path('app/public/' . $post->photo))) {
                unlink(storage_path('app/public/' . $post->photo));
            }

            $photoPath = $request->file('photoUpload')->store('posts', 'public');
        }

        $post->update([
            'message' => $request->content,
            'age' => $request->age,
            'breed' => $request->breed,
            'province' => $request->province,
            'city' => $request->city,
            'interest' => $request->interest,
            'audience' => $audience,
            'photo' => $photoPath,
        ]);

        return redirect()->route('home')->with('success', 'Post updated successfully!');
    }

    public function report($id)
    {
        return back()->with('success', 'Post has been reported.');
    }

    public function block($user_id)
    {
        $currentUserId = Auth::id();

        if ($currentUserId === (int)$user_id) {
            return back()->with('error', 'You cannot block yourself.');
        }

        if (Auth::user()->hasBlocked($user_id)) {
            return back()->with('error', 'This user is already blocked.');
        }

        Block::create([
            'user_id' => $currentUserId,
            'blocked_user_id' => $user_id,
        ]);

        return back()->with('success', 'User has been blocked.');
    }

    public function unblock($user_id)
    {
        Block::where('user_id', Auth::id())
            ->where('blocked_user_id', $user_id)
            ->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'blocked_user_id' => $user_id
            ]);
        }

        return back()->with('success', 'User has been unblocked.');
    }
}
