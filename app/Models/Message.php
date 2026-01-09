<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['sender_id', 'receiver_id', 'message', 'status', 'is_read', 'conversation_id', 'deleted_for'];

    protected $casts = [
        'deleted_for' => 'array',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Check if this message is deleted for a specific user
     */
    public function isDeletedFor($userId)
    {
        $deletedFor = $this->deleted_for ?? [];
        return in_array($userId, $deletedFor);
    }

    /**
     * Mark message as deleted for a specific user
     */
    public function markDeletedFor($userId)
    {
        $deletedFor = $this->deleted_for ?? [];
        if (!in_array($userId, $deletedFor)) {
            $deletedFor[] = $userId;
            $this->deleted_for = $deletedFor;
            $this->save();
        }
    }
}

