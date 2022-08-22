<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Models\{User, Friend};
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\{Http, Auth};
use App\Enum\FriendStatus;


class UserService
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
