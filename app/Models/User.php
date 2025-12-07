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
        'avatar',
        'city',
        'province'
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
        return User::where(function ($query) {
            $query->where('friend_requests.sender_id', $this->id)
                ->where('friend_requests.receiver_id', '!=', $this->id)
                ->where('friend_requests.status', 'accepted');
        })->orWhere(function ($query) {
            $query->where('friend_requests.receiver_id', $this->id)
                ->where('friend_requests.sender_id', '!=', $this->id)
                ->where('friend_requests.status', 'accepted');
        })->join('friend_requests', function ($join) {
            $join->on('users.id', '=', 'friend_requests.sender_id')
                ->orOn('users.id', '=', 'friend_requests.receiver_id');
        })->where('users.id', '!=', $this->id)->select('users.*')->distinct();
    }


}
