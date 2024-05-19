<?php

namespace App\Models;

use App\Enum\FriendStatus;
use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Friend extends Model
{
    protected $table = 'friends';

    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'status'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id', 'id');
    }

    public function friend(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id', 'id');
    }

    public function scopeItIsMyFriend(Builder $query, int $id): Builder
    {
        return $query->where(
            fn($q) => $q->whereFromUserId(auth()->user()->id)
                ->whereToUserId($id)
                ->whereStatus(FriendStatus::ACCEPTED)
        )->orWhere(
            fn($q) => $q->whereFromUserId($id)
                ->whereToUserId(auth()->user()->id)
                ->whereStatus(FriendStatus::ACCEPTED)
        );
    }

    public function scopeByMeSolicited(Builder $query, int $id): Builder
    {
        return $query->where(
            fn($q) => $q->whereFromUserId(auth()->user()->id)
                ->whereToUserId($id)
                ->whereNot(fn($q) => $q->whereStatus(FriendStatus::ACCEPTED))
                ->whereStatus(FriendStatus::PENDENT)
        );
    }

    public function scopeOuterSolicited(Builder $query, int $sub): Builder
    {
        return $query->where(
            fn($q) => $q->whereFromUserId($sub)
                ->whereToUserId(auth()->user()->id)
                ->whereStatus(FriendStatus::PENDENT)
        );
    }

    public function scopeGetFriends(Builder $query): Builder
    {
        return $query->where(fn($q) => $q->whereFromUserId(auth()->user()->id))
            ->orWhere(fn($q) => $q->whereToUserId(auth()->user()->id))
            ->with('friend');
    }
}
