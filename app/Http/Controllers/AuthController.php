<?php

namespace App\Http\Controllers;

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

}
