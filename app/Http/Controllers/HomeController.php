<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Get all posts
        $query = Post::with('user')->latest();

        // Apply filters if present
        if ($request->has('province')) {
            $query->where('province', $request->province);
            if ($request->has('city')) {
                $query->where('city', $request->city);
            }
        }

        if ($request->has('age')) {
            $query->where('age', $request->age);
        }

        if ($request->has('breed')) {
            $query->where('breed', $request->breed);
        }

        if ($request->has('audience')) {
            if ($request->audience === 'public') {
                $query->where('audience', 'public');
            } elseif ($request->audience === 'friends') {
                // Get accepted friends IDs
                $friendIds = \App\Models\FriendRequest::where(function ($q) {
                    $q->where('sender_id', auth()->id())
                        ->orWhere('receiver_id', auth()->id());
                })
                    ->where('status', 'accepted')
                    ->get()
                    ->map(function ($request) {
                        return $request->sender_id == auth()->id() ? $request->receiver_id : $request->sender_id;
                    })
                    ->unique()
                    ->toArray();

                // Show only friends
                $query->whereIn('user_id', array_merge($friendIds, [auth()->id()]))
                    ->where('audience', 'friends');
            }
        }

        $posts = $query->get();

        //accepted friends
        $friendIds = \App\Models\FriendRequest::where(function ($query) {
            $query->where('sender_id', auth()->id())
                ->orWhere('receiver_id', auth()->id());
        })
            ->where('status', 'accepted')
            ->get()
            ->map(function ($request) {
                return $request->sender_id == auth()->id() ? $request->receiver_id : $request->sender_id;
            })
            ->unique()
            ->take(10);

        $contacts = User::whereIn('id', $friendIds)->get();

        $contacts->each(function ($contact) {
            $contact->is_online = true;
        });

        return view('home.index', compact('posts', 'contacts'));
    }
}
