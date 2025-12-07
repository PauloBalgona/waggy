<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\User;
use App\Models\FriendRequest;

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
        View::composer('navbar.nav', function ($view) {
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

                $view->with('friends', $friends);
            } else {
                $view->with('friends', collect());
            }
        });
    }
}
