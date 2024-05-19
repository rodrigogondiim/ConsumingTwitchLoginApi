<?php

namespace App\Http\Services;

use App\Models\Friend;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\{Http, Auth, Event};
use App\Enum\FriendStatus;
use App\Events\RequestFriend;

class FriendService
{

    public function index(): Collection
    {
        return Friend::getFriends()
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
        $solicitation_friend = Friend::create([
            'from_user_id' => auth()->user()->id,
            'to_user_id' => $friend_id,
            'status' => FriendStatus::PENDENT
        ]);

        $solicitation_friend->friend->update(['view_notification' => true]);

        Event::dispatch(new RequestFriend($solicitation_friend->load('user')));
        
        return $solicitation_friend;
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
        return Friend::select('id','from_user_id', 'created_at')
            ->whereToUserId(auth()->user()->id)
            ->whereStatus(FriendStatus::PENDENT)
            ->with('user:id,name,picture,created_at')
            ->orderBy('created_at', 'DESC')
            ->get();
    }
    
    public function friendship(Friend $friend, bool $accept): Friend
    {
        return tap($friend)->update(['status' => $accept ? FriendStatus::ACCEPTED : FriendStatus::RECUSED]);
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
