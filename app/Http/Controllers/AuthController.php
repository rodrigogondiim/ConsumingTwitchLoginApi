<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Repository\AuthRepository;

class AuthController extends Controller
{
    private $repository;

    public function __construct(AuthRepository $repository)
    {
        $this->repository = $repository;
    }
   /*
    * @param  Request  $request
    * @return Response
    */
    public function auth(Request $request, string $provider)
    {   
        $result = $this->repository->getOAuth($request, $provider);
        if($result)
            return redirect()->route('index');

        
    }

}
