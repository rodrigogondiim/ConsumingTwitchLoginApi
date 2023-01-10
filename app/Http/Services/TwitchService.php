<?php

namespace App\Http\Services;

use App\Http\Interfaces\ContractProvider;
use Illuminate\Support\Facades\Http;

class TwitchService implements ContractProvider
{

    private ?string $uri_login;
    private ?string $uri_redirect;
    private ?string $client;

   /**
    * @param string|null $state
    * @param string|null $code
    * @return array
    */
    public function auth(?string $state, ?string $code): array
    {
        $this->uri_login = env('LOGIN_TWITCH_URI'); 
        $this->uri_redirect = env('APP_URL').':'.env('APP_PORT').env('REDIRECT_TWITCH_URI');
        $this->client = env('ID_TWITCH');

        $claims = '{"id_token":{"email":null,"email_verified":null},"userinfo":{"email":null,"email_verified":null,"preferred_username":null,"picture":null,"updated_at":null}}';
        if(is_null($state)){
            $state = md5(time());
            return redirect(mountUri($claims, $state, $this->uri_login, $this->client, $this->uri_redirect))->send();
        }
        return $this->getToken($code);
    }

    /**
     * getting the token generated in auth
     * @param string $code
     * @return array
     */
    private function getToken(string $code): array
    {
        return Http::post(env('URI_TOKEN'), [
            'client_id' => $this->client,
            'client_secret' => env('SECRET_TWITCH'),
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->uri_redirect
        ])->json();
    }

    /**
     * get the user authenticated
     * @param string $access_token
     * @return array
     */
    public function getUser(string $access_token): array
    {
        return Http::withHeaders($this->getHeader($access_token))
            ->get('https://id.twitch.tv/oauth2/userinfo')
            ->json();
    }

    /**
     * defining header from application
     * @param string $access_token
     * @return array
     */
    private function getHeader(string $access_token): array
    {
        return [
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $access_token" 
        ];
    }
}
