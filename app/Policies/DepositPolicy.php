<?php

declare(strict_types = 1);

namespace App\Policies;

use App\Models\{Deposit, User};
use Illuminate\Auth\Access\HandlesAuthorization;

class DepositPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->isCustomer();
    }

    public function create(User $user): bool
    {
        return !$user->isAdmin();
    }

    public function edit(User $user, Deposit $deposit): bool
    {
        return $this->viewAprove($user) && $deposit->status === null;
    }

    public function viewAprove(User $user): bool
    {
        return $user->isAdmin();
    }
}
