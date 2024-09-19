<?php

declare(strict_types = 1);

use App\Models\User;
use App\Rules\BalanceRule;
use Illuminate\Support\Facades\Validator;

it('fails validation if user balance is insufficient', function () {
    // Arrange
    $user = User::factory()->roleCustomer()->create();
    $user->profile->update(['balance' => 500]);

    $rule = new BalanceRule($user->profile);

    // Act
    $validator = Validator::make(['amount' => 1000], ['amount' => [$rule]]);

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->first('amount'))->toBe('Insufficient funds');
});

it('passes validation if user balance is sufficient', function () {
    // Arrange
    $user = User::factory()->roleCustomer()->create();
    $user->profile->update(['balance' => 1500]);

    $rule = new BalanceRule($user->profile);

    // Act
    $validator = Validator::make(['amount' => 1000], ['amount' => [$rule]]);

    // Assert
    expect($validator->passes())->toBeTrue();
});
