<?php

namespace App\Http\Repository;

use App\Http\Interfaces\ContractProvider;
use App\Http\Services\TwitchService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\{JsonResponse, Response};

class AuthRepository
{

    public function getOAuth($request, string $provider): bool
    {
        $authProvider = $this->getProvider($provider);
        $token = $authProvider->auth($request);
        $user  = $authProvider->getUser($token['access_token']);
        $account = $this->setAuthenticatedUser($user);
        Auth::login($account);

        return true;
    }

    public function setAuthenticatedUser(array $user): User
    {
        $current = User::whereSub($user['sub'])->first();
        
        if(!$current)
            $current = User::firstOrCreate([
                'name' => $user['preferred_username'],
                'email' => $user['email'],
                'sub' => $user['sub'],
                'type' => 'twitch',
                'password' => md5(time())
            ]);    
        

        return $current;
    }

    private function getProvider(string $provider): ContractProvider
    {
        return match($provider){
            'twitch' => new TwitchService()
        };
    }
}
