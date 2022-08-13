<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Http};
use App\Models\User;

class Authenticate
{
    
    private const MESSAGE = 'you is unauthorized.';

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $this->getToken($request);
        $sctClient = $this->findAutheticity($token);

        if($sctClient->status() !== 200)
            return throw new AccessDeniedHttpException(self::MESSAGE);
        
        $userVersion = User::whereSub($sctClient->json('sub'))->first();
        $userVersion->bearer = $token;
        Auth::login($userVersion);
        return $next($request);
    }

    /**
     * get token or explode error
     *
     * @param Request $request
     * @return AccessDeniedHttpException|String
     */
    private function getToken(Request $request): AccessDeniedHttpException|String
    {
        $bearer = $request->bearerToken();
        if(is_null($bearer) or empty($bearer))
            return throw new AccessDeniedHttpException(self::MESSAGE);

        return "Bearer {$bearer}" ;
    }

    /**
     * if find to one infomation
     *
     * @param string $token
     * @return Response
     */
    private function findAutheticity(string $token): Response
    {
        return Http::withHeaders([
            'Authorization' =>  $token
        ])->get('https://id.twitch.tv/oauth2/userinfo');
    }
}
