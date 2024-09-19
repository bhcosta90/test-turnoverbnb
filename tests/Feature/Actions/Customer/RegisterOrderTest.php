<?php

declare(strict_types = 1);

use App\Actions\Customer\RegisterOrder;
use App\Exceptions\InsufficientBalanceException;
use App\Interfaces\UserInterface;
use App\Models\{Order, User};
use Illuminate\Support\Facades\DB;
use Mockery\MockInterface;

it('creates a order and updates the user balance', function () {
    // Arrange
    DB::spy();

    $user = User::factory()->roleCustomer()->create();
    $user->profile->update(['balance' => 2000]);
    $data = [
        'value'       => 1000,
        'description' => 'Order description',
    ];

    $userMock = mock(UserInterface::class, function (MockInterface $mock) use ($user) {
        $mock->shouldReceive('getUserLogin')->andReturn($user);
    });

    $registerOrder = new RegisterOrder($userMock);

    // Act
    $order = $registerOrder->handle($data);

    // Assert
    expect($order)->toBeInstanceOf(Order::class)
        ->and((int) $order->value)->toBe((int) $data['value'])
        ->and($order->description)->toBe($data['description'])
        ->and((int) $user->profile->balance)->toBe(1000);

    DB::shouldHaveReceived('beginTransaction');
    DB::shouldHaveReceived('commit');
});

it('throws an exception if the balance is insufficient', function () {
    // Arrange
    DB::spy();

    $user = User::factory()->roleCustomer()->create();
    $user->profile->update(['balance' => 500]);
    $data = [
        'value'       => 1000,
        'description' => 'Order description',
    ];

    $userMock = mock(UserInterface::class, function (MockInterface $mock) use ($user) {
        $mock->shouldReceive('getUserLogin')->andReturn($user);
    });

    $registerOrder = new RegisterOrder($userMock);

    // Assert
    expect(fn () => $registerOrder->handle($data))->toThrow(InsufficientBalanceException::class);

    DB::shouldHaveReceived('beginTransaction');
    DB::shouldHaveReceived('rollBack');
});
