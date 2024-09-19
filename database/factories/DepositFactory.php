<?php

declare(strict_types = 1);

namespace Database\Factories;

use App\Models\{Deposit, User};
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class DepositFactory extends Factory
{
    protected $model = Deposit::class;

    public function definition(): array
    {
        return [
            'user_id'     => User::factory(),
            'description' => $this->faker->sentence(3),
            'value'       => $this->faker->numberBetween(10000, 1000000) / 100,
            'receipt'     => 'fake.png',
            'created_at'  => Carbon::now(),
            'updated_at'  => Carbon::now(),
        ];
    }
}
