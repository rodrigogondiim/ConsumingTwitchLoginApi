<?php

namespace App\Http\Interfaces;

interface ContractProvider {
    public function auth($request): array;

    public function getUser(string $token): array;
}
