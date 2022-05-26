<?php

namespace App\Http\Services;

use App\Http\Interfaces\ContractProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\{JsonResponse, Response};

class TwitchService implements ContractProvider
{
   /*
    * @param  Request  $request
    * @return Response
    */
    public function auth($request): array
    {   
        $login_url = 'https://id.twitch.tv/oauth2/authorize';
        $redirect_uri = 'http://localhost:3000/auth/twitch';
        $claims = '{"id_token":{"email":null,"email_verified":null},"userinfo":{"email":null,"email_verified":null,"preferred_username":null,"picture":null,"updated_at":null}}';
        $client_id = env('ID_TWITCH');
        $state = $request->query('state');

        if(is_null($state)){
            $state = md5(time());
            $redirect = sprintf(
                '%s?response_type=code&client_id=%s&redirect_uri=%s&scope=%s&state=%s&claims=%s',
                $login_url,
                $client_id,
                $redirect_uri,
                'channel:read:polls+openid+user:read:email',
                $state,
                $claims
            );
            return redirect($redirect)->send();
        }

        return $this->getToken($request->query('code'), $client_id, $redirect_uri);  
    }

    public function getUser(string $access_token): array
    {
        return Http::withHeaders($this->getHeader($access_token))
        ->get('https://id.twitch.tv/oauth2/userinfo')
        ->json();
    }

    private function getToken(string $code, string $client_id, string $redirect_uri): array
    {
        $token_base = 'https://id.twitch.tv/oauth2/token';
        $client_scret = env('SECRET_TWITCH');

        return Http::post($token_base, [
            'client_id' => $client_id,
            'client_secret' => $client_scret,
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $redirect_uri
        ])->json();
    }

    private function getHeader(string $access_token): array
    {
        return [
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $access_token" 
        ];
    }
}
