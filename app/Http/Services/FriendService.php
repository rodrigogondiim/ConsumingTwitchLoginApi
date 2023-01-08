<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Models\{User, Friend};
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\{Http, Auth};
use App\Enum\FriendStatus;

class FriendService
{

    /**
     * @param string|null $search
     * @return Collection
     */
    public function index(?string $search = null): Collection
    {
        if($search)
            return collect($this->findUser($search));

        return User::getUsers()->get();
    }

    public function store(int $friend_id): Friend
    {
        return Friend::create([
            'from_user_id' => Auth::user()->id,
            'to_user_id' => $friend_id,
            'status' => FriendStatus::PENDENT
        ]);
    }

    public function showFriends(): Collection
    {
        return Friend::select('id','from_user_id', 'to_user_id')
            ->getFriends()
            ->whereStatus('accepted')
            ->get();
    }

    public function showPedencyFriends(): Collection
    {
        return Friend::select('id','from_user_id', 'to_user_id')
            ->getFriends()
            ->whereToUserId(auth()->user()->id)
            ->whereStatus('pendent')
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
