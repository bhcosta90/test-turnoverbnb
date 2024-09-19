<?php

declare(strict_types = 1);

namespace Database\Seeders;

use App\Models\{Deposit, Enums\User\Role, User};
use DB;
use Illuminate\Database\Seeder;

class DepositSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            foreach (User::query()->whereRole(Role::Customer)->get() as $user) {
                Deposit::factory(30)->recycle($user)->create();
            }
        });
    }
}
