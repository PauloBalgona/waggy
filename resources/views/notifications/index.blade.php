@extends('navbar.nav')
@section('title', 'Notifications - Waggy')
@section('body-class', 'bg-gray-900')

@section('content')
    <style>
        .notification-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 24px;
            border-bottom: 1px solid #2d3748;
            transition: background-color 0.2s;
            cursor: pointer;
            text-decoration: none;
            position: relative;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-item:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .notification-item.unread {
            background-color: rgba(59, 130, 246, 0.05);
        }

        .notification-delete-btn {
            background: none;
            border: none;
            color: #6b7280;
            cursor: pointer;
            padding: 8px;
            border-radius: 6px;
            transition: all 0.2s;
            font-size: 16px;
        }

        .notification-delete-btn:hover {
            color: #ef4444;
            background-color: rgba(239, 68, 68, 0.1);
        }

        .notification-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #3d4557;
            flex-shrink: 0;
        }

        .notification-content {
            flex: 1;
        }

        .notification-text {
            color: white;
            font-size: 14px;
            margin: 0;
            line-height: 1.5;
        }

        .notification-actor {
            font-weight: 600;
            color: white;
        }

        .notification-breed {
            color: #8b95a5;
            margin: 0 4px;
        }

        .notification-message {
            color: #e5e7eb;
        }

        .notification-time {
            color: #6b7280;
            font-size: 12px;
            margin-top: 6px;
            margin-bottom: 0;
        }

        .notifications-empty {
            padding: 24px;
            color: #6b7280;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .notification-item {
                padding: 12px 16px;
                gap: 12px;
            }

            .notification-avatar {
                width: 44px;
                height: 44px;
            }

            .notification-text {
                font-size: 13px;
            }

            .notification-time {
                font-size: 11px;
            }

            .notification-delete-btn {
                padding: 6px;
                font-size: 14px;
            }
            .notif-title {
        position: relative;
        left: 12px;
    }
        }

        @media (max-width: 480px) {
            div[style*="min-h-screen"] {
                padding: 12px 8px !important;
            }

            h1 {
                font-size: 22px !important;
                margin-bottom: 16px !important;
            }

            .notification-item {
                padding: 10px 12px;
                gap: 10px;
            }

            .notification-avatar {
                width: 40px;
                height: 40px;
                min-width: 40px;
            }

            .notification-text {
                font-size: 12px;
                line-height: 1.4;
            }

            .notification-actor {
                display: block;
                font-size: 13px;
            }

            .notification-breed {
                display: none;
            }

            .notification-message {
                font-size: 12px;
            }

            .notification-time {
                font-size: 10px;
                margin-top: 4px;
            }

            .notification-delete-btn {
                padding: 4px;
                font-size: 12px;
            }
        }
    </style>

    <div style="min-h-screen bg-gray-900 text-white p-8;">
       <h1 class="notif-title"
    style="color: white; font-size: 28px; font-weight: 600; margin-bottom: 24px;">
    Notifications
</h1>

        <div style="space-y: 4;">
            @forelse($notifications as $notification)
                @php
                    $actor = $notification->actor;
                    $message = $notification->message;
                    if ($actor && $actor->pet_name) {
                        $message = preg_replace('/^' . preg_quote($actor->pet_name, '/') . '\s*/', '', $message);
                    }
                    
                    // Determine the redirect URL based on notification type
                    $redirectUrl = route('home');
                    if (($notification->type === 'like' || $notification->type === 'comment') && $notification->post_id) {
                        // Check if post exists before redirecting to it
                        $post = \App\Models\Post::find($notification->post_id);
                        if ($post) {
                            $redirectUrl = route('posts.show', $notification->post_id);
                        }
                    } elseif ($notification->type === 'friend_request') {
                        $redirectUrl = route('friend.requests');
                    } elseif ($notification->type === 'message') {
                        $redirectUrl = route('messages.conversation', $notification->actor_id);
                    } elseif ($notification->type === 'connect') {
                        $redirectUrl = route('profile.show', $notification->actor_id);
                    }
                @endphp

                <a href="javascript:void(0);" onclick="markNotificationAsRead({{ $notification->id }}, '{{ $redirectUrl }}')" class="notification-item {{ !$notification->is_read ? 'unread' : '' }}" style="display: flex; text-decoration: none;" data-post-id="{{ $notification->post_id ?? '' }}" data-type="{{ $notification->type }}" data-url="{{ $redirectUrl }}">
                    <img src="{{$actor->avatar ? asset('storage/' . $actor->avatar) : asset('assets/usericon.png') }}"
                        alt="Avatar"
                        class="notification-avatar">

                    <div class="notification-content">
                        <p class="notification-text">
                            @if($actor)
                                <span class="notification-actor">
                                    {{ $actor->pet_name ?? 'Unknown' }}
                                </span>
                                @if($actor->pet_breed)
                                    <span class="notification-breed">
                                        · {{ $actor->pet_breed }}
                                    </span>
                                @endif
                                <span class="notification-message">
                                    {{ $message }}
                                </span>
                            @else
                                <span class="notification-actor">System</span>
                                <span class="notification-message">
                                    {{ $notification->message }}
                                </span>
                            @endif
                        </p>

                        <!-- Time -->
                        <p class="notification-time">
                            {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <button type="button" class="notification-delete-btn" onclick="deleteNotification(event, {{ $notification->id }})">
                        <i class="bi bi-trash"></i>
                    </button>
                </a>

            @empty
                <div class="notifications-empty">
                    <p>No notifications found</p>
                </div>
            @endforelse
        </div>

        <script>
            function markNotificationAsRead(notificationId, redirectUrl) {
                // Mark notification as read via AJAX
                fetch(`/notifications/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Redirect to the appropriate page
                    window.location.href = redirectUrl;
                })
                .catch(error => {
                    console.error('Error marking notification as read:', error);
                    // Still redirect even if there's an error
                    window.location.href = redirectUrl;
                });
            }

            function deleteNotification(event, notificationId) {
                event.stopPropagation();
                if (confirm('Delete this notification?')) {
                    fetch(`/notifications/${notificationId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        location.reload();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to delete notification');
                    });
                }
            }
        </script>
    </div>
@endsection