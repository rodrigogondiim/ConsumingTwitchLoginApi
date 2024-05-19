<?php

namespace App\Http\Services;

use App\Models\User;
use App\Http\Interfaces\ContractProvider;
use App\Http\Services\TwitchService;
use App\Jobs\SendMail;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthService
{

    /**
     * @param Request $request
     * @param string $provider
     * @return bool
     */
    public function getOAuth(Request $request, string $provider): bool
    {
        $authProvider = $this->getProvider($provider);
        $token = $authProvider->auth($request->query('state'), $request->query('code'));
        $user  = $authProvider->getUser($token['access_token']);
        $account = $this->setAuthenticatedUser($user);
        Auth::login($account);
        return Auth::check();
    }

    /**
     * @param array $user
     * @return User
     */
    public function setAuthenticatedUser(array $user): User
    {

        if(!$current = User::whereSub($user['sub'])->first()){
            $defaultPassword = rand(10000, 99999); 
            $current = User::create([
                'name' => $user['preferred_username'],
                'email' => $user['email'],
                'sub' => $user['sub'],
                'picture' => $user['picture'],
                'type' => 'twitch',
                'password' => password_hash($defaultPassword, PASSWORD_BCRYPT)
            ]);
            SendMail::dispatch($current->name, $defaultPassword);
        }
        return $current;
    }

    /**
     *
     * @param Request $rqs
     * @return boolean
     */
    public function login(Request $rqs): bool
    {
        if (!password_verify($rqs->password, $rqs->usr()->password))
            throw new Exception('credentials don\'t valid!');

        Auth::login($rqs->usr());
        return Auth::check();
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
