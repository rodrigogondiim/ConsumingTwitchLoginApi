<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Support\Facades\Auth;

class Friend extends Model
{
    protected $table = 'friends';

    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'from_user_id', 'id');
    }

    public function friend()
    {
        return $this->belongsTo(User::class, 'to_user_id', 'id');
    }

    public function scopeFriendVerify(Builder $query, int $sub)
    {
        return $query->where(
            fn($q) => $q->whereFromUserId(Auth::user()->id)
            ->whereToUserId($sub)
        )->orWhere(
            fn($q) => $q->whereFromUserId($sub)
            ->whereToUserId(Auth::user()->id)
        )->doesntExist();
    }

    public function scopeGetFriends($query)
    {
        return $query->where(fn($q) => $q->whereFromUserId(Auth::user()->id))
        ->orWhere(fn($q) => $q->whereToUserId(Auth::user()->id))
        ->with(['user' => fn($q) => $q->where('id', '<>', Auth::user()->id)])
        ->with(['friend' => fn($q) => $q->where('id', '<>', Auth::user()->id)]);
    }
}
