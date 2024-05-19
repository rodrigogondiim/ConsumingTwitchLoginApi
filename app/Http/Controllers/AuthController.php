<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\{Request, RedirectResponse};
use App\Http\Services\AuthService;

class AuthController extends Controller
{

    public function __construct(private AuthService $service)
    {
    }
    
    /**
    * @param Request $request
    * @param string $provider
    * 
    */
    public function auth(Request $request, string $provider): RedirectResponse
    {
        if($this->service->getOAuth($request, $provider))
            return redirect()->route('index');
    }

    public function authWithPayload()
    {
        return view('auth');
    }

    public function login(LoginRequest $request)
    {
        $this->service->login($request);
        return redirect()->route('index');
    }

}
