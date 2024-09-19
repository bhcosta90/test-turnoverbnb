<?php

declare(strict_types = 1);

namespace Database\Factories;

use App\Models\{Order, User};
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'description' => $this->faker->text(),
            'value'       => $this->faker->numberBetween(10000, 1000000) / 100,
            'created_at'  => Carbon::now(),
            'updated_at'  => Carbon::now(),
            'user_id'     => User::factory(),
        ];
    }
}
