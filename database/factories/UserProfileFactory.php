<?php

declare(strict_types = 1);

namespace Database\Factories;

use App\Models\{User, UserProfile};
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class UserProfileFactory extends Factory
{
    protected $model = UserProfile::class;

    public function definition(): array
    {
        return [
            'id'         => User::factory(),
            'balance'    => $this->faker->numberBetween(10000, 1000000) / 100,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
