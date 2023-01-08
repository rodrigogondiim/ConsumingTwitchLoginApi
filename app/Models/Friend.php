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

    public function scopeFriendVerify(Builder $query, int $sub): bool
    {
        return $query->where(
            fn($q) => $q->whereFromUserId(auth()->user()->id)
                ->whereToUserId($sub)
                ->whereStatus(FriendStatus::ACCEPTED)
        )->orWhere(
            fn($q) => $q->whereFromUserId($sub)
                ->whereToUserId(auth()->user()->id)
                ->whereStatus(FriendStatus::ACCEPTED)
        )->exists();
    }

    public function scopeGetFriends(Builder $query): Builder
    {
        return $query->where(fn($q) => $q->whereFromUserId(auth()->user()->id))
            ->orWhere(fn($q) => $q->whereToUserId(auth()->user()->id))
            ->with(['friend' => fn($q) => $q->where('id', '<>', auth()->user()->id)]);
    }
}
