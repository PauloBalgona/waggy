<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'pet_name',
        'pet_breed',
        'pet_age',
        'pet_gender',
        'pet_features',
        'certificate_verified',
        'certificate_path',
        'certificate_rejected_at',
        'avatar',
        'city',
        'province',
        'is_admin',
        'admin_role',
        'email_verified_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'certificate_verified' => 'boolean',
        ];
    }

    // ====================================
    // BASIC RELATIONSHIPS
    // ====================================

    public function dog()
    {
        return $this->hasOne(Dog::class);
    }

    public function certificate()
    {
        return $this->hasOne(Certificate::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }


    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function sentFriendRequests()
    {
        return $this->hasMany(FriendRequest::class, 'sender_id');
    }

    public function receivedFriendRequests()
    {
        return $this->hasMany(FriendRequest::class, 'receiver_id');
    }

    public function friends()
    {
        return User::join('friend_requests', function ($join) {
            $join->on('users.id', '=', 'friend_requests.sender_id')
                ->orOn('users.id', '=', 'friend_requests.receiver_id');
        })
            ->where(function ($query) {
                $query->where('friend_requests.sender_id', $this->id)
                    ->orWhere('friend_requests.receiver_id', $this->id);
            })
            ->where('friend_requests.status', 'accepted')
            ->where('users.id', '!=', $this->id)
            ->select('users.*')
            ->distinct();
    }

    public function blockedUsers()
    {
        return $this->hasMany(Block::class, 'user_id', 'id')
            ->with('blockedUser');
    }

    public function blockedByUsers()
    {
        return $this->hasMany(Block::class, 'blocked_user_id', 'id')
            ->with('user');
    }

    public function isBlockedBy($userId)
    {
        return Block::where('user_id', $userId)
            ->where('blocked_user_id', $this->id)
            ->exists();
    }

    public function hasBlocked($userId)
    {
        return Block::where('user_id', $this->id)
            ->where('blocked_user_id', $userId)
            ->exists();
    }

    /**
     * Check if user is a super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->is_admin && $this->admin_role === 'super_admin';
    }

    /**
     * Check if user is an admin (including super admin)
     */
    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }


}
