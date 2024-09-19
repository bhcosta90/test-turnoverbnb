<?php

declare(strict_types = 1);

namespace Database\Seeders;

use App\Models\{User};
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->roleAdmin()->create([
            'email' => 'admin@gmail.com',
        ]);

        User::factory()
            ->roleCustomer()
            ->create([
                'email' => 'user@gmail.com',
            ]);
    }
}
