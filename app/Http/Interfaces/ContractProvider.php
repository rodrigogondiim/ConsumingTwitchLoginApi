<?php

namespace App\Http\Interfaces;

use Illuminate\Http\Request;

interface ContractProvider {
    public function auth(?string $state, ?string $code): array;
    public function getUser(string $token): array;
}
