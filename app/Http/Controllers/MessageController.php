<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // SHOW ALL CONVERSATIONS
    public function index()
    {
        $userId = Auth::id();

        // Get unique conversation partners
        $conversations = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with('sender', 'receiver')
            ->latest()
            ->get()
            ->groupBy(function ($msg) use ($userId) {
                return $msg->sender_id == $userId ? $msg->receiver_id : $msg->sender_id;
            });

        return view('message.index', compact('conversations'));
    }

    // OPEN CHAT WITH USER
    public function show(User $user)
    {
        $authId = Auth::id();

        $messages = Message::where(function ($q) use ($authId, $user) {
            $q->where('sender_id', $authId)
                ->where('receiver_id', $user->id);
        })
            ->orWhere(function ($q) use ($authId, $user) {
                $q->where('sender_id', $user->id)
                    ->where('receiver_id', $authId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return view('message.show', compact('user', 'messages'));
    }

    // SEND MESSAGE
    public function store(Request $request, User $user)
    {
        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $user->id,
            'content' => $request->input('content'),
        ]);

        return back();
    }
}
