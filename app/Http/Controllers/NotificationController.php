<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\FriendRequest;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        // Mark all unread notifications as read when viewing the page
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $notifications = Notification::with('actor')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('notifications.index', compact('notifications'));
    }

    public function markRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
            
        $notification->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $notification->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Return unread notifications count and latest unread notification JSON.
     */
    public function unreadCount()
    {
        $count = Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();

        $latest = Notification::with('actor')
            ->where('user_id', auth()->id())
            ->where('is_read', false)
            ->latest()
            ->first();

        return response()->json([
            'count' => $count,
            'latest' => $latest ? [
                'id' => $latest->id,
                'type' => $latest->type,
                'message' => $latest->message,
                'actor' => $latest->actor ? [
                    'id' => $latest->actor->id,
                    'pet_name' => $latest->actor->pet_name,
                    'avatar' => $latest->actor->avatar,
                ] : null,
                'post_id' => $latest->post_id,
                'created_at' => $latest->created_at->toDateTimeString(),
            ] : null,
        ]);
    }

    /**
     * Return friend request, message and notification unread counts as JSON.
     */
    public function counts()
    {
        $userId = Auth::id();

        $friendRequestCount = FriendRequest::where('receiver_id', $userId)
            ->where('status', 'pending')
            ->count();

        $messageCount = Message::where('receiver_id', $userId)
            ->where('is_read', false)
            ->count();

        $notificationCount = Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'friend_request_count' => $friendRequestCount,
            'message_count' => $messageCount,
            'notification_count' => $notificationCount,
        ]);
    }
}
