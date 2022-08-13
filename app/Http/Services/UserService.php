<?php

declare(strict_types=1);

namespace App\Http\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\{Http, Auth};
use App\Models\{User, Friend};

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

    public function store(int $friend_id)
    {
        return Friend::create([
            'user_id' => Auth::user()->id,
            'friend_id' => $friend_id
        ]);
    }

    public function myFriends(): Collection
    {
        return Friend::select('id','user_id', 'friend_id')
        ->getFriends()
        ->with([
            'user:id,sub,name,email,picture',
            'friend:id,sub,name,email,picture'
        ])->get();
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
