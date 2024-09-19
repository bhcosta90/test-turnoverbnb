<?php

declare(strict_types = 1);

use App\Models\Enums\User\Role;
use App\Models\User;

it('creates a profile for user role', function () {
    // Arrange
    $user = User::factory()->create(['role' => Role::Customer]);

    $this->assertDatabaseHas('user_profile', ['id' => $user->id]);
});

it('does not create a profile for non-user role', function () {
    $user = User::factory()->create(['role' => Role::Admin]);
    $this->assertDatabaseMissing('user_profile', ['id' => $user->id]);
});
