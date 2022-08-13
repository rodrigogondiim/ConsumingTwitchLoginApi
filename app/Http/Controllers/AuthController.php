<?php

namespace App\Http\Controllers;

use Illuminate\Http\{Request, RedirectResponse};
use App\Http\Repository\AuthRepository;

class AuthController extends Controller
{

    public function __construct(private AuthRepository $repository)
    {
    }
    
   /**
    * @param Request $request
    * @param string $provider
    * 
    */
    public function auth(Request $request, string $provider): RedirectResponse
    {
        if($this->repository->getOAuth($request, $provider))
            return redirect()->route('index');
    }

}
