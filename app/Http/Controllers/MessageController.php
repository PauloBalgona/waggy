<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use App\Models\Block;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

class MessageController extends Controller
{
    // SHOW ALL CONVERSATIONS
    public function index()
    {
        $userId = Auth::id();

        // Mark all unread messages as read when visiting messages page
        Message::where('receiver_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Get all messages grouped by conversation partner
        $messages = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with('sender', 'receiver')
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(function ($message) use ($userId) {
                // Exclude messages that have been deleted for this user
                return !$message->isDeletedFor($userId);
            });

        // Build conversations with proper structure, excluding blocked users
        $blockedUserIds = Auth::user()->blockedUsers()->pluck('blocked_user_id')->toArray();
        $usersWhoBlockedMe = Block::where('blocked_user_id', $userId)->pluck('user_id')->toArray();
        $allBlockedIds = array_merge($blockedUserIds, $usersWhoBlockedMe);

        $conversationPartners = [];
        $conversationData = [];

        foreach ($messages as $message) {
            $partnerId = $message->sender_id == $userId ? $message->receiver_id : $message->sender_id;
            
            // Skip if partner is blocked
            if (in_array($partnerId, $allBlockedIds)) {
                continue;
            }
            
            if (!isset($conversationData[$partnerId])) {
                $otherUser = $message->sender_id == $userId ? $message->receiver : $message->sender;
                $conversationData[$partnerId] = (object)[
                    'other_user' => $otherUser,
                    'last_message' => $message,
                ];
            }
        }

        $conversations = array_values($conversationData);

        return view('messages.index', compact('conversations'));
    }

    // OPEN CHAT WITH USER
    public function conversation($userId)
    {
        $authId = Auth::id();
        $user = User::findOrFail($userId);

        // Check if user is blocked
        if (Auth::user()->hasBlocked($userId) || Auth::user()->isBlockedBy($userId)) {
            return back()->with('error', 'You cannot message this user.');
        }

        // Mark all unread messages from this user as read
        Message::where('sender_id', $userId)
            ->where('receiver_id', $authId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Get messages between current user and selected user
        $messages = Message::where(function ($q) use ($authId, $userId) {
            $q->where('sender_id', $authId)
                ->where('receiver_id', $userId);
        })
            ->orWhere(function ($q) use ($authId, $userId) {
                $q->where('sender_id', $userId)
                    ->where('receiver_id', $authId);
            })
            ->orderBy('created_at', 'asc')
            ->get()
            ->filter(function ($message) use ($authId) {
                // Exclude messages that have been deleted for this user
                return !$message->isDeletedFor($authId);
            });

        // Get all conversations for the sidebar
        $allMessages = Message::where('sender_id', $authId)
            ->orWhere('receiver_id', $authId)
            ->with('sender', 'receiver')
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(function ($message) use ($authId) {
                // Exclude messages that have been deleted for this user
                return !$message->isDeletedFor($authId);
            });

        // Build conversations with proper structure
        $conversationPartners = [];
        $conversationData = [];

        foreach ($allMessages as $message) {
            $partnerId = $message->sender_id == $authId ? $message->receiver_id : $message->sender_id;
            
            if (!isset($conversationData[$partnerId])) {
                $otherUser = $message->sender_id == $authId ? $message->receiver : $message->sender;
                $conversationData[$partnerId] = (object)[
                    'other_user' => $otherUser,
                    'last_message' => $message,
                ];
            }
        }

        $conversations = array_values($conversationData);

        return view('messages.index', compact('user', 'messages', 'conversations'));
    }

    // GET CONVERSATION (AJAX - returns JSON)
    public function getConversation($userId)
    {
        $authId = Auth::id();
        $sinceId = request('since_id');

        // Only get messages sent by the other user to the current user
        $query = Message::where('sender_id', $userId)
            ->where('receiver_id', $authId);

        if ($sinceId) {
            $query->where('id', '>', $sinceId);
        }

        $messages = $query->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'messages' => $messages->map(function ($message) {
                return [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'receiver_id' => $message->receiver_id,
                    'message' => $message->message,
                    'created_at' => $message->created_at->format('H:i'),
                    'sender' => [
                        'id' => $message->sender->id,
                        'pet_name' => $message->sender->pet_name,
                        'avatar' => $message->sender->avatar ? asset('storage/' . $message->sender->avatar) : asset('assets/usericon.png'),
                    ]
                ];
            }),
        ]);
    }

    // SEND MESSAGE
    public function store(Request $request, User $user)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $user->id,
            'message' => $request->input('message'),
        ]);

        return back();
    }

    // SEND MESSAGE (POST route)
    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:2000',
        ]);

        try {
            // Check if users are blocked
            if (Auth::user()->hasBlocked($request->receiver_id) || Auth::user()->isBlockedBy($request->receiver_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot message this user.',
                ], 403);
            }

            $message = Message::create([
                'sender_id' => Auth::id(),
                'receiver_id' => $request->receiver_id,
                'message' => $request->message,
            ]);

            // Broadcast the newly created message to the recipient
            try {
                event(new MessageSent($message));
            } catch (\Exception $e) {
                logger()->warning('Broadcast MessageSent failed: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'message' => $message->message,
                    'created_at' => $message->created_at->format('h:i A'),
                ]
            ]);
        } catch (\Exception $e) {
            // Log the exception and return JSON error so the frontend can display a helpful message
            logger()->error('Message send failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
            ], 500);
        }
    }

    // SEND FRIEND REQUEST (MESSAGE REQUEST)
    public function sendRequest(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
        ]);

        // Check if already messaging
        $existing = Message::where('sender_id', Auth::id())
            ->where('receiver_id', $request->receiver_id)
            ->first();

        if (!$existing) {
            Message::create([
                'sender_id' => Auth::id(),
                'receiver_id' => $request->receiver_id,
                'message' => null,
                'status' => 'pending',
            ]);
        }

        return back()->with('success', 'Message request sent!');
    }

    // ACCEPT MESSAGE REQUEST
    public function acceptRequest(Request $request, $messageId)
    {
        $message = Message::findOrFail($messageId);

        if ($message->receiver_id !== Auth::id()) {
            return back()->withErrors('Unauthorized');
        }

        $message->update(['status' => 'accepted']);

        return back()->with('success', 'Request accepted!');
    }

    // REJECT MESSAGE REQUEST
    public function rejectRequest(Request $request, $messageId)
    {
        $message = Message::findOrFail($messageId);

        if ($message->receiver_id !== Auth::id()) {
            return back()->withErrors('Unauthorized');
        }

        $message->delete();

        return back()->with('success', 'Request rejected!');
    }

    // DELETE MESSAGE
    public function deleteMessage($messageId)
    {
        $message = Message::findOrFail($messageId);

        // Only allow user to delete their own messages
        if ($message->sender_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $message->delete();

        return response()->json([
            'success' => true,
            'message' => 'Message deleted'
        ]);
    }

    // DELETE CONVERSATION
    public function deleteConversation($userId)
    {
        $authId = Auth::id();

        // Mark all messages in this conversation as deleted for the current user only
        Message::where(function ($q) use ($authId, $userId) {
            $q->where('sender_id', $authId)
                ->where('receiver_id', $userId);
        })
        ->orWhere(function ($q) use ($authId, $userId) {
            $q->where('sender_id', $userId)
                ->where('receiver_id', $authId);
        })
        ->get()
        ->each(function ($message) use ($authId) {
            $message->markDeletedFor($authId);
        });

        return back()->with('success', 'Conversation deleted');
    }
}
