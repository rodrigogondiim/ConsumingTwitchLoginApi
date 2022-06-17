<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Repository\AuthRepository;

class AuthController extends Controller
{

    public function __construct(private AuthRepository $repository)
    {
    }
    
   /**
    * @param Request $request
    * @param string $provider
    * @return void
    */
    public function auth(Request $request, string $provider)
    {   
        $result = $this->repository->getOAuth($request, $provider);
        if($result)
            return redirect()->route('index');        
    }

}
