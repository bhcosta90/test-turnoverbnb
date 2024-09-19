<?php

declare(strict_types = 1);

use App\Models\{Deposit, User};
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\{actingAs, get};

it('renders the deposit status page with the correct data', function () {
    // Arrange
    $user     = User::factory()->roleAdmin()->create();
    $deposits = Deposit::factory()->count(3)->create(['status' => null]);

    // Act
    actingAs($user);
    $response = get('/deposit/status');

    // Assert
    $response->assertStatus(200);
    $response->assertInertia(
        fn (Assert $page) => $page
            ->component('Customer/Deposit/Status')
            ->has('deposits.data', 3)
            ->has(
                'deposits.data.0',
                fn (Assert $page) => $page
                    ->where('id', $deposits[0]->id)
                    ->etc()
            )
    );
});

it('forbids access to deposit status page for unauthorized users', function () {
    // Arrange
    $user = User::factory()->roleCustomer()->create();

    // Act
    actingAs($user);
    $response = get('/deposit/status');

    // Assert
    $response->assertStatus(403);
});
