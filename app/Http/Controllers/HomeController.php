<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // Start query - get ALL posts from ALL users
        $query = Post::with(['user', 'likes'])
            ->withCount('likes', 'comments', 'replies')
            ->orderBy('created_at', 'desc');

        // Apply filters based on URL parameters


        // Filter by age (from filter form) - only if numeric
        if ($request->filled('filter_age') && is_numeric($request->input('filter_age'))) {
            $query->where('age', (int)$request->input('filter_age'));
        }

        // Filter by breed (from filter form)
        if ($request->filled('filter_breed')) {
            $query->where('breed', $request->input('filter_breed'));
        }

        // Filter by city
        if ($request->has('city') && $request->city != '') {
            $query->where('city', $request->city);
        }

        // Filter by province
        if ($request->has('province') && $request->province != '') {
            $query->where('province', 'like', '%' . $request->province . '%');
        }

        // Filter by interest
        if ($request->has('interest') && $request->interest != '') {
            $query->where('interest', $request->interest);
        }

        // Filter by audience (from filter form)
        if ($request->filled('filter_audience')) {
            $aud = strtolower($request->input('filter_audience'));
            if ($aud === 'friends' || $aud === 'friends only') {
                // Get friend IDs of current user using FriendRequest model
                $friendIds = \App\Models\FriendRequest::where(function ($q) {
                    $q->where('sender_id', auth()->id())
                        ->orWhere('receiver_id', auth()->id());
                })
                    ->where('status', 'accepted')
                    ->get()
                    ->map(function ($friendRequest) {
                        return $friendRequest->sender_id == auth()->id()
                            ? $friendRequest->receiver_id
                            : $friendRequest->sender_id;
                    })
                    ->unique()
                    ->toArray();

                $friendIds[] = auth()->id(); // Include own posts
                $query->whereIn('user_id', $friendIds);
                $query->where('audience', 'friends'); // Only show friends-only posts
            } elseif ($aud === 'public') {
                $query->where('audience', 'public'); // Only show public posts
            }
        }

        // Get blocked users and exclude their posts
        $blockedUserIds = auth()->user()->blockedUsers()->pluck('blocked_user_id')->toArray();
        if (!empty($blockedUserIds)) {
            $query->whereNotIn('user_id', $blockedUserIds);
        }

        // Also exclude posts from users who have blocked the current user
        $usersWhoBlockedMe = \App\Models\Block::where('blocked_user_id', auth()->id())->pluck('user_id')->toArray();
        if (!empty($usersWhoBlockedMe)) {
            $query->whereNotIn('user_id', $usersWhoBlockedMe);
        }


        // Paginate the filtered posts (10 per page)
        $posts = $query->paginate(10);

        // Combine comments + replies into a single comments_count value for display

        foreach ($posts as $p) {
            $p->comments_count = ($p->comments_count ?? 0) + ($p->replies_count ?? 0);
        }

        // Get friends for the contacts sidebar (exclude blocked users)
        $blockedUserIds = auth()->user()->blockedUsers()->pluck('blocked_user_id')->toArray();
        $friends = auth()->user()->friends()
            ->whereNotIn('users.id', $blockedUserIds)
            ->get();

        return view('home.index', compact('posts', 'friends'));
    }
}