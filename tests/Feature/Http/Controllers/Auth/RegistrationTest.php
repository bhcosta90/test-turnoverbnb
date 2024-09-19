<?php

declare(strict_types = 1);

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'email'                 => 'test@gmail.com',
        'password'              => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('new users can register with valid password', function () {
    Session::start();
    app()->detectEnvironment(fn () => 'production');

    $response = $this->post('/register', [
        '_token'                => csrf_token(),
        'email'                 => 'test@gmail.com',
        'password'              => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasErrors('password');

    $response = $this->post('/register', [
        '_token'                => csrf_token(),
        'email'                 => 'test@gmail.com',
        'password'              => 'Password#123',
        'password_confirmation' => 'Password#123',
    ]);

    $response->assertSessionHasNoErrors();
    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});
