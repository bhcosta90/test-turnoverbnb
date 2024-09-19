<?php

declare(strict_types = 1);

use App\Models\{User, UserProfile};

use function Pest\Laravel\assertDatabaseCount;

it('can create a user profile with a balance', function () {
    $user = User::factory()->create();

    $userProfile = UserProfile::factory()->recycle($user)->create([
        'balance' => 100.50,
    ]);

    expect($userProfile->balance)->toBe(100.50)
        ->and($userProfile->user->is($user))->toBeTrue();
});

it('can associate a user with a user profile', function () {
    $user        = User::factory()->roleAdmin()->create();
    $userProfile = new UserProfile([
        'balance' => 100.50,
    ]);

    assertDatabaseCount('user_profile', 0);
    $user->profile()->save($userProfile);
    assertDatabaseCount('user_profile', 1);
});
