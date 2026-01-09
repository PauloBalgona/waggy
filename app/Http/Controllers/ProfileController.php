<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\DogPhoto;
use App\Models\User;
use App\Models\FriendRequest;
use App\Models\PostLike;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $posts = Post::where('user_id', $user->id)
            ->with('likes')
            ->orderBy('created_at', 'desc')
            ->get();

        $dogPhotos = DogPhoto::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get friends
        $friendIds = FriendRequest::where(function ($query) {
            $query->where('sender_id', auth()->id())
                ->orWhere('receiver_id', auth()->id());
        })
            ->where('status', 'accepted')
            ->get()
            ->map(function ($request) {
                return $request->sender_id == auth()->id() ? $request->receiver_id : $request->sender_id;
            })
            ->unique();

        $friends = User::whereIn('id', $friendIds)->get();

        // Get liked posts
        $likedPostIds = PostLike::where('user_id', auth()->id())->pluck('post_id');
        $likedPosts = Post::whereIn('id', $likedPostIds)
            ->with('user', 'likes')
            ->orderBy('created_at', 'desc')
            ->get();

        $isOwnProfile = true;

        // No friend request
        $friendRequest = null;

        return view('profiles.profile', compact('user', 'posts', 'dogPhotos', 'friends', 'likedPosts', 'isOwnProfile', 'friendRequest'));
    }

    public function show($userId)
    {
        $user = User::findOrFail($userId);

        if ($user->id === auth()->id()) {
            return redirect()->route('profile');
        }

        $posts = Post::where('user_id', $user->id)
            ->with('likes')
            ->orderBy('created_at', 'desc')
            ->get();

        $dogPhotos = DogPhoto::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get friends of the viewed user
        $friendIds = FriendRequest::where(function ($query) use ($userId) {
            $query->where('sender_id', $userId)
                ->orWhere('receiver_id', $userId);
        })
            ->where('status', 'accepted')
            ->get()
            ->map(function ($request) use ($userId) {
                return $request->sender_id == $userId ? $request->receiver_id : $request->sender_id;
            })
            ->unique();

        $friends = User::whereIn('id', $friendIds)->get();

        // Get liked posts
        $likedPostIds = PostLike::where('user_id', $user->id)->pluck('post_id');
        $likedPosts = Post::whereIn('id', $likedPostIds)
            ->with('user', 'likes')
            ->orderBy('created_at', 'desc')
            ->get();

        $isOwnProfile = false;

        //EXISTING FRIEND REQUEST
        $friendRequest = FriendRequest::where(function ($q) use ($userId) {
            $q->where('sender_id', auth()->id())
                ->where('receiver_id', $userId);
        })
            ->orWhere(function ($q) use ($userId) {
                $q->where('sender_id', $userId)
                    ->where('receiver_id', auth()->id());
            })
            ->first();

        return view('profiles.profile', compact('user', 'posts', 'dogPhotos', 'friends', 'likedPosts', 'isOwnProfile', 'friendRequest'));
    }

    // SEND FRIEND REQUEST
    public function addFriend($receiverId)
    {
        FriendRequest::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $receiverId,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Friend request sent!');
    }

    // CANCEL FRIEND REQUEST
    public function cancelFriend($receiverId)
    {
        FriendRequest::where('sender_id', auth()->id())
            ->where('receiver_id', $receiverId)
            ->where('status', 'pending')
            ->delete();

        return back()->with('success', 'Friend request cancelled!');
    }

    // UNFRIEND
    public function unfriend($friendId)
    {
        FriendRequest::where(function ($query) use ($friendId) {
            $query->where('sender_id', auth()->id())
                ->where('receiver_id', $friendId);
        })->orWhere(function ($query) use ($friendId) {
            $query->where('sender_id', $friendId)
                ->where('receiver_id', auth()->id());
        })->where('status', 'accepted')
            ->delete();

        return back()->with('success', 'Unfriended successfully!');
    }

    /**
     * Get user by ID for API (JSON response)
     */
    public function apiGetUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json([
            'id' => $user->id,
            'pet_name' => $user->pet_name,
            'avatar' => $user->avatar,
            'pet_breed' => $user->pet_breed,
            'pet_age' => $user->pet_age,
        ]);
    }

    /**
     * Search users by pet name for API
     */
    public function apiSearchUsers()
    {
        $query = request()->input('q', '');

        if (strlen($query) < 1) {
            return response()->json([]);
        }

        $users = User::where('pet_name', 'ilike', '%' . $query . '%')
            ->select('id', 'pet_name', 'pet_breed', 'avatar')
            ->limit(10)
            ->get();

        return response()->json($users);
    }
}
