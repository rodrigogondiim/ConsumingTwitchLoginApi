<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Models\Friend;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\{Http, Auth};
use App\Enum\FriendStatus;

class FriendService
{

    public function index(): Collection
    {
        return Friend::select('id','from_user_id', 'to_user_id')
            ->getFriends()
            ->whereStatus(FriendStatus::ACCEPTED)
            ->get();
    }
    /**
     *
     * @param integer $friend_id
     * @return Friend
     */
    public function store(int $friend_id): Friend
    {
        return Friend::create([
            'from_user_id' => auth()->user()->id,
            'to_user_id' => $friend_id,
            'status' => FriendStatus::PENDENT
        ]);
    }

    public function show(int $id): Friend|null
    {
        return Friend::select('id','from_user_id', 'to_user_id')
            ->whereId($id)
            ->getFriends()
            ->whereStatus(FriendStatus::ACCEPTED)
            ->first();
    }

    public function showPendencyFriends(): Collection
    {
        return Friend::select('id','from_user_id', 'to_user_id')
            ->getFriends()
            ->whereToUserId(auth()->user()->id)
            ->whereStatus(FriendStatus::PENDENT)
            ->get();
    }
    
    public function showAcceptFriends(Friend $friend)
    {
        return tap($friend)->update(['status' => FriendStatus::ACCEPTED]);
    }

    public function showRecuseFriends(Friend $friend)
    {
        return tap($friend)->update(['status' => FriendStatus::RECUSED]);
    }

    /**
     * @param string $search
     * @return Array
     */
    private function findUser(string $search): Array
    {
        return Http::withHeaders([
            'Authorization' =>  Auth::user()->bearer,
            'Client-Id' => env('ID_TWITCH')
        ])->get("https://api.twitch.tv/helix/users?login={$search}")->json('data');
    }
}
