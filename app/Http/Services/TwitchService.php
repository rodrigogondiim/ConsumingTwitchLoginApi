<?php

namespace App\Http\Services;

use App\Http\Interfaces\ContractProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class TwitchService implements ContractProvider
{

    protected ?string $uri_login;
    protected ?string $uri_redirect;
    protected ?string $client;

   /**
    * @param Request $request
    * @return array
    */
    public function auth(Request $request): array
    {
        $this->uri_login = env('LOGIN_TWITCH_URI'); 
        $this->uri_redirect = env('APP_URL').':'.env('APP_PORT').env('REDIRECT_TWITCH_URI');
        $this->client = env('ID_TWITCH');

        $claims = '{"id_token":{"email":null,"email_verified":null},"userinfo":{"email":null,"email_verified":null,"preferred_username":null,"picture":null,"updated_at":null}}';
        $state = $request->query('state');
        if(is_null($state)){
            $state = md5(time());
            return redirect(mountUri($claims, $state, $this->uri_login, $this->client, $this->uri_redirect))->send();
        }

        return $this->getToken($request->query('code'));
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
        dd($access_token);
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
