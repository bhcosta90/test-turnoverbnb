<?php

declare(strict_types = 1);

namespace App\Models\Enums\User;

enum Role: int
{
    case Admin    = 1;
    case Customer = 2;
}
