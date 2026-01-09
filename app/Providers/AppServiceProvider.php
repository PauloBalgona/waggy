<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\User;
use App\Models\FriendRequest;
use App\Models\Message;
use App\Models\Notification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Composer for navbar views (nav, nav1, nav2)
        View::composer(['navbar.nav', 'navbar.nav1', 'navbar.nav2'], function ($view) {
            if (auth()->check()) {
                $friends = FriendRequest::where(function ($query) {
                    $query->where('sender_id', auth()->id())
                        ->orWhere('receiver_id', auth()->id());
                })
                    ->where('status', 'accepted')
                    ->with(['sender', 'receiver'])
                    ->get()
                    ->map(function ($request) {
                        return $request->sender_id == auth()->id() ? $request->receiver : $request->sender;
                    })
                    ->unique('id')
                    ->take(10); // Limit to 10 friends for sidebar

                // Get notification counts
                $friendRequestCount = FriendRequest::where('receiver_id', auth()->id())
                    ->where('status', 'pending')
                    ->count();
                
                $messageCount = Message::where('receiver_id', auth()->id())
                    ->where('is_read', false)
                    ->count();
                
                $notificationCount = Notification::where('user_id', auth()->id())
                    ->where('is_read', false)
                    ->count();

                $view->with('friends', $friends)
                     ->with('friendRequestCount', $friendRequestCount)
                     ->with('messageCount', $messageCount)
                     ->with('notificationCount', $notificationCount);
            } else {
                $view->with('friends', collect())
                     ->with('friendRequestCount', 0)
                     ->with('messageCount', 0)
                     ->with('notificationCount', 0);
            }
        });
    }
}
