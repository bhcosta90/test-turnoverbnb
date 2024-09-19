<?php

declare(strict_types = 1);

use App\Models\{Order, User};
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\{actingAs, assertDatabaseCount, assertDatabaseHas};

beforeEach(function () {
    $this->user = User::factory()->roleCustomer()->create();
    actingAs($this->user);

    $this->user->profile->update(['balance' => 10]);
});

describe('index action', function () {
    it('renders the order index page', function () {
        Order::factory()->for($this->user)->count(3)->create([
            'value' => 15,
        ]);

        // Act
        $response = $this->get('/order');

        // Assert
        $response->assertInertia(
            fn (Assert $page) => $page
                ->component('Customer/Order/Index')
                ->has('orders.data', 3)
                ->where('create', true)
        );
    });

    it('forbids admin from accessing order index page', function () {
        actingAs(User::factory()->roleAdmin()->create());

        // Act
        $response = $this->get('/order');

        // Assert
        $response->assertStatus(403);
    });
});

describe('create action', function () {
    it('renders the order create page', function () {
        // Act
        $response = $this->get('/order/create');

        // Assert
        $response->assertInertia(fn (Assert $page) => $page->component('Customer/Order/Create'));
    });

    it('validates order creation fields', function () {
        Storage::fake();

        // fields required
        $response = $this->post('/order', []);
        $response->assertSessionHasErrors(['description', 'value']);

        // type values
        $response = $this->post('/order', [
            'value' => 'a',
        ]);
        $response->assertSessionHasErrors(['value']);

        // min values
        $response = $this->post('/order', [
            'value' => 0,
        ]);
        $response->assertSessionHasErrors(['value']);

        // max values
        $response = $this->post('/order', [
            'description' => str_repeat('a', 121),
            'value'       => 4000000001,
        ]);
        $response->assertSessionHasErrors(['description', 'value']);
    });

    it('creates a order', function () {
        $response = $this->post('/order', [
            'description' => 'testing',
            'value'       => 10,
        ]);
        $response->assertSessionHasNoErrors();

        assertDatabaseCount('orders', 1);
        assertDatabaseHas('orders', [
            'description' => 'testing',
            'value'       => 1000,
        ]);
    });

    it('fails to create a order with an invalid value', function () {
        $response = $this->post('/order', [
            'description' => 'testing',
            'value'       => 11,
        ]);
        $response->assertSessionHasErrors(['value']);
    });

    it('forbids admin from accessing order create page', function () {
        actingAs(User::factory()->roleAdmin()->create());

        // Act
        $response = $this->post('/order', [
            'description' => 'testing',
            'value'       => 10,
        ]);

        // Assert
        $response->assertStatus(403);
    });
});
