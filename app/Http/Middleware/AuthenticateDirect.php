<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Http\Request;
use App\Models\User;

class AuthenticateDirect
{
    
    private const MESSAGE = 'you isn\'t authorized.';

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $parts = $this->getToken($request);
        $payload = json_decode(base64_decode($parts[1]));
        $userNotExist = User::whereEmail($payload?->email)
            ->whereId($payload?->sub)
            ->doesntExist();

        if(empty($payload) or $userNotExist or time() > $payload->exp)
            return response()->json([
                'code' => 401, 
                'message' => self::MESSAGE
            ], 401);

        return $next($request);
    }

    /**
     * get parts token or expose error
     *
     * @param Request $request
     * @return AccessDeniedHttpException|array
     */
    private function getToken(Request $request): AccessDeniedHttpException|array
    {
        $bearer = $request->bearerToken();
        $parts = explode('.', $bearer);
        if(is_null($bearer) or empty($bearer) or count($parts) < 3)
            throw new AccessDeniedHttpException(self::MESSAGE);

        return $parts ;
    }
}
