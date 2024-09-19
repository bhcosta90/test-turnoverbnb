<?php

declare(strict_types = 1);

namespace App\Observers;

use App\Models\Enums\User\Role;
use App\Models\User;

class UserObserver
{
    public function created(User $user): void
    {
        if ($user->role === Role::Customer) {
            $user->profile()->create([
                'balance' => 0,
            ]);
        }
    }
}
