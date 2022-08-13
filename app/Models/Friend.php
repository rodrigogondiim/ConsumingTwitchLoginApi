<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Friend extends Model
{
    protected $table = 'friend';

    protected $fillable = [
        'user_id',
        'friend_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'friend_id', 'id');
    }

    public function friend()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function scopeFriendVerify(Builder $query, int $sub)
    {
        return $query->where(
            fn($q) => $q->whereUserId(Auth::user()->id)
            ->whereFriendId($sub)
        )->orWhere(
            fn($q) => $q->whereUserId($sub)
            ->whereFriendId(Auth::user()->id)
        )->doesntExist();
    }

    public function scopeGetFriends($query)
    {
        return $query->where(fn($q) => $q->whereUserId(Auth::user()->id))
        ->orWhere(fn($q) => $q->whereFriendId(Auth::user()->id));
    }
}
