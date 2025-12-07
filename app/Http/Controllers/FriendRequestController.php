<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FriendRequest;
use App\Models\User;
use App\Models\Notification;
use App\Models\Friend;
use Auth;

class FriendRequestController extends Controller
{
    // Show all friend
    public function index()
    {
        $requests = FriendRequest::where('receiver_id', Auth::id())
            ->where('status', 'pending')
            ->with('sender')
            ->get();

        return view('friend-requests.index', compact('requests'));
    }

    // Send friend request
    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
        ]);

        $senderId = Auth::id();
        $receiverId = $request->receiver_id;

        $existingRequest = FriendRequest::where('sender_id', $senderId)
            ->where('receiver_id', $receiverId)
            ->first();

        if ($existingRequest) {
            if ($existingRequest->status == 'rejected') {
                $existingRequest->update(['status' => 'pending']);
                return back()->with('success', 'Friend request sent!');
            } elseif ($existingRequest->status == 'pending') {
                return back()->with('error', 'Friend request already sent.');
            } elseif ($existingRequest->status == 'accepted') {
                return back()->with('error', 'You are already friends.');
            }
        } else {
            FriendRequest::create([
                'sender_id' => $senderId,
                'receiver_id' => $receiverId,
                'status' => 'pending',
            ]);

            // Create notification for receiver
            Notification::create([
                'user_id' => $receiverId,
                'actor_id' => $senderId,
                'type' => 'friend_request',
                'message' => Auth::user()->pet_name . ' sent you a friend request',
                'data' => json_encode(['sender_id' => $senderId]),
            ]);

            return back()->with('success', 'Friend request sent!');
        }
    }

    // Accept friend request
    public function accept(Request $request)
    {
        $friendRequest = FriendRequest::find($request->id);
        if ($friendRequest && $friendRequest->receiver_id == Auth::id()) {
            $friendRequest->update(['status' => 'accepted']);
        }

        return back()->with('success', 'Friend request accepted!');
    }

    // Decline friend request
    public function decline(Request $request)
    {
        $friendRequest = FriendRequest::find($request->id);

        // If not found by id, assume id is sender_id from notifications
        if (!$friendRequest) {
            $friendRequest = FriendRequest::where('sender_id', $request->id)
                ->where('receiver_id', Auth::id())
                ->where('status', 'pending')
                ->first();
        }

        if ($friendRequest && $friendRequest->receiver_id == Auth::id()) {
            $friendRequest->update(['status' => 'rejected']);
        }

        return back()->with('success', 'Friend request declined!');
    }

    // Cancel friend request
    public function cancel(Request $request)
    {
        $friendRequest = FriendRequest::where('id', $request->id)
            ->where('sender_id', Auth::id())
            ->first();

        if ($friendRequest) {
            $friendRequest->delete();
        }

        return back()->with('success', 'Friend request cancelled!');
    }

}
