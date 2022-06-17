<?php

namespace App\Http\Repository;

use App\Http\Interfaces\ContractProvider;
use App\Http\Services\TwitchService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthRepository
{

    /**
     * @param Request $request
     * @param string $provider
     * @return boolean
     */
    public function getOAuth(Request $request, string $provider): bool
    {
        $authProvider = $this->getProvider($provider);
        $token = $authProvider->auth($request);
        $user  = $authProvider->getUser($token['access_token']);
        $account = $this->setAuthenticatedUser($user);
        Auth::login($account);

        return true;
    }

    /**
     * @param array $user
     * @return User
     */
    public function setAuthenticatedUser(array $user): User
    {
        $current = User::whereSub($user['sub'])->first();
        if(!$current)
            $current = User::firstOrCreate([
                'name' => $user['preferred_username'],
                'email' => $user['email'],
                'sub' => $user['sub'],
                'picture' => $user['picture'],
                'type' => 'twitch',
                'password' => md5(time())
            ]);    
        
        return $current;
    }

    /**
     * @param string $provider
     * @return ContractProvider
     */
    private function getProvider(string $provider): ContractProvider
    {
        return match($provider){
            'twitch' => new TwitchService()
        };
    }
}
