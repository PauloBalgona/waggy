<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use App\Models\FriendRequest;
use App\Models\Message;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationComposer
{
    public function compose($view)
    {
        if (Auth::check()) {
            $friendRequestCount = FriendRequest::where('receiver_id', Auth::id())
                ->where('status', 'pending')
                ->count();
            
            $messageCount = Message::where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->count();
            
            $notificationCount = Notification::where('user_id', Auth::id())
                ->where('is_read', false)
                ->count();

            $view->with('friendRequestCount', $friendRequestCount)
                 ->with('messageCount', $messageCount)
                 ->with('notificationCount', $notificationCount);
        }
    }
}
