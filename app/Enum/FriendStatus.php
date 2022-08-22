<?php

namespace App\Enum;

enum FriendStatus: string{
    case PENDENT = 'pendent';
    case ACCEPTED = 'accepted';
    case RECUSED = 'recused';
}