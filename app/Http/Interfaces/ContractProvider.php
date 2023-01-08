<?php

namespace App\Http\Interfaces;

interface ContractProvider {
    public function auth(?string $state, ?string $code): array;
    public function getUser(string $token): array;    
}
