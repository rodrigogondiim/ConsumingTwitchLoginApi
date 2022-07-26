<?php

namespace App\Http\Interfaces;

use Illuminate\Http\Request;

interface ContractProvider {
    public function auth(Request $request): array;
    public function getUser(string $token): array;    
}
