<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\Notification;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')->latest()->get();
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

        $age = $request->input('age');
        $audience = strtolower($request->input('audience'));

        // Default audience 
        if (!in_array($audience, ['public', 'friends'])) {
            $audience = 'public';
        }

        $photoPath = null;

        // Handle file upload
        if ($request->hasFile('photoUpload')) {
            $photoPath = $request->file('photoUpload')->store('posts', 'public');
        }

        if (!$photoPath && $request->image_base64) {
            $data = $request->image_base64;
            $data = str_replace('data:image/png;base64,', '', $data);
            $data = str_replace('data:image/jpeg;base64,', '', $data);
            $data = base64_decode($data);

            $fileName = 'post_' . time() . '.jpg';
            $path = storage_path('app/public/posts/' . $fileName);

            // Create directory
            if (!file_exists(storage_path('app/public/posts'))) {
                mkdir(storage_path('app/public/posts'), 0755, true);
            }

            file_put_contents($path, $data);
            $photoPath = 'posts/' . $fileName;
        }

        // CREATE POST
        Post::create([
            'user_id' => auth()->id(),
            'message' => $request->input('content'),
            'age' => $age,
            'breed' => $request->input('breed'),
            'province' => $request->input('province'),
            'city' => $request->input('city'),
            'interest' => $request->input('interest'),
            'audience' => $audience,
            'photo' => $photoPath,
        ]);

        // CLEAR SESSION IMAGE
        session()->forget('uploaded_image_path');

        return redirect()->route('home')->with('success', 'Post created successfully!');
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

            // Create notification
            if ($post->user_id !== auth()->id()) {
                Notification::create([
                    'user_id' => $post->user_id,
                    'actor_id' => auth()->id(),
                    'type' => 'like',
                    'message' => auth()->user()->pet_name . ' liked your post',
                    'data' => json_encode(['post_id' => $post->id, 'liker_id' => auth()->id()]),
                ]);
            }
        }

        return back();
    }

    public function create(Request $request)
    {
        $image = $request->image ?? null;
        return view('posts.index', compact('image'));
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

    public function clearUploadSession(Request $request)
    {
        session()->forget('uploaded_image');
        return response()->json(['success' => true]);
    }

    public function postingPage()
    {
        $image = session('uploaded_image') ?? null;
        return view('posts.index', compact('image'));
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

    public function report($id)
    {
        return back()->with('success', 'Post has been reported.');
    }

    public function block($user_id)
    {
        return back()->with('success', 'User has been blocked.');
    }
}