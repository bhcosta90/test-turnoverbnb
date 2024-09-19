<?php

declare(strict_types = 1);

use App\Actions\Admin\UpdateDeposit;
use App\Models\{Deposit, User};
use Illuminate\Support\Facades\DB;

it('updates the deposit status and user balance', function () {
    // Arrange
    DB::spy();

    $user = User::factory()->roleCustomer()->create();
    $user->profile->update(['balance' => 1000]);
    $deposit = Deposit::factory()->for($user)->create(['value' => 500, 'status' => false]);

    $updateDeposit = new UpdateDeposit();

    // Act
    $updatedDeposit = $updateDeposit->handle($deposit, true);

    // Assert
    expect($updatedDeposit->status)->toBeTrue()
        ->and((int) $user->profile->refresh()->balance)->toBe(1500);

    DB::shouldHaveReceived('beginTransaction');
    DB::shouldHaveReceived('commit');
});

it('updates the deposit status without changing the balance if status is false', function () {
    // Arrange
    DB::spy();

    $user = User::factory()->roleCustomer()->create();
    $user->profile->update(['balance' => 1000]);
    $deposit = Deposit::factory()->for($user)->create(['value' => 500, 'status' => false]);

    $updateDeposit = new UpdateDeposit();

    // Act
    $updatedDeposit = $updateDeposit->handle($deposit, false);

    // Assert
    expect($updatedDeposit->status)->toBeFalse()
        ->and((int) $user->profile->refresh()->balance)->toBe(1000);

    DB::shouldHaveReceived('beginTransaction');
    DB::shouldHaveReceived('commit');
});

it('rolls back the transaction if an exception is thrown', function () {
    // Arrange
    DB::spy();

    $user = User::factory()->roleCustomer()->create();
    $user->profile->update(['balance' => 1000]);

    // Mock the update method to throw an exception
    $depositMock = Mockery::mock(Deposit::class)->makePartial();
    $depositMock->shouldReceive('update')->andThrow(new Exception('Test Exception'));
    $depositMock->user   = $user;
    $depositMock->value  = 500;
    $depositMock->status = false;

    $updateDeposit = new UpdateDeposit();

    // Assert
    expect(fn () => $updateDeposit->handle($depositMock, true))->toThrow(Exception::class);

    DB::shouldHaveReceived('beginTransaction');
    DB::shouldHaveReceived('rollBack');
});
