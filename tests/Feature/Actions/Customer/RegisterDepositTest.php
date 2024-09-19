<?php

declare(strict_types = 1);

use App\Actions\Customer\RegisterDeposit;
use App\Interfaces\UserInterface;
use App\Models\{Deposit, User};
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\{DB, Event, Storage};
use Mockery\MockInterface;

it('creates a deposit, fires DepositCreateEvent, and handles transactions and storage', function () {
    // Arrange
    Event::fake();
    Storage::fake();
    DB::spy();

    $user = User::factory()->create();
    $data = [
        'value'       => 1000,
        'receipt'     => UploadedFile::fake()->image('receipt.jpg'),
        'description' => 'Deposit description',
    ];

    $userMock = mock(UserInterface::class, function (MockInterface $mock) use ($user) {
        $mock->shouldReceive('getUserLogin')->andReturn($user);
    });

    $registerDeposit = new RegisterDeposit($userMock);

    // Act
    $deposit = $registerDeposit->handle($data);

    // Assert
    expect($deposit)->toBeInstanceOf(Deposit::class)
        ->and((int) $deposit->value)->toBe((int) $data['value'])
        ->and($deposit->receipt)->toBe('deposits/' . $user->id . '/' . $data['receipt']->hashName());

    Storage::assertExists($deposit->receipt);
    DB::shouldHaveReceived('beginTransaction');
    DB::shouldHaveReceived('commit');
});

it('deletes the image if the event dispatch fails', function () {
    // Arrange
    Event::fake();
    Storage::fake();
    DB::spy();

    $user = User::factory()->create();
    $data = [
        'value'       => 1000,
        'receipt'     => UploadedFile::fake()->image('receipt.jpg'),
        'description' => 'Deposit description',
    ];

    $userMock = mock(UserInterface::class, function (MockInterface $mock) use ($user) {
        $mock->shouldReceive('getUserLogin')->andReturn($user);
    });

    $registerDeposit = new RegisterDeposit($userMock);

    DB::shouldReceive('commit')
        ->once()
        ->andThrow(new Exception('Event dispatch failed'));

    // Assert
    expect(fn () => $registerDeposit->handle($data))->toThrow(new Exception('Event dispatch failed'));
    Storage::assertMissing('deposits/' . $user->id . '/' . $data['receipt']->hashName());
    DB::shouldHaveReceived('beginTransaction');
    DB::shouldHaveReceived('rollBack');
});
