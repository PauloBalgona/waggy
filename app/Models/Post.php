<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Reply;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'age',
        'breed',
        'province',
        'city',
        'interest',
        'audience',
        'message',
        'photo',
    ];
    public function getAgeFormattedAttribute()
    {
        $months = $this->age;
        $years = intdiv($months, 12);

        if ($years > 0) {
            return "{$years} year" . ($years > 1 ? 's' : '');
        }

        return "{$months} month" . ($months > 1 ? 's' : '');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Replies on this post (has-many-through Comment -> Reply).
     */
    public function replies()
    {
        return $this->hasManyThrough(Reply::class, Comment::class, 'post_id', 'comment_id', 'id', 'id');
    }

    /**
     * Total comments count including replies. Accessible as `$post->comments_count`.
     */
    public function getCommentsCountAttribute()
    {
        $commentsCount = $this->relationLoaded('comments') ? $this->comments->count() : $this->comments()->count();
        $repliesCount = $this->relationLoaded('replies') ? $this->replies->count() : $this->replies()->count();

        return $commentsCount + $repliesCount;
    }
}
