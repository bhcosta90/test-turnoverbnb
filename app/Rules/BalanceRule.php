<?php

declare(strict_types = 1);

namespace App\Rules;

use App\Models\{UserProfile};
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BalanceRule implements ValidationRule
{
    public function __construct(protected ?UserProfile $profile)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->profile && $this->profile->balance < $value) {
            $fail('Insufficient funds');
        }
    }
}
