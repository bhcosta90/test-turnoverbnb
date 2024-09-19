<?php

declare(strict_types = 1);

use App\Models\{Deposit, User};
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\{actingAs, assertDatabaseCount, assertDatabaseHas, assertDatabaseMissing};

beforeEach(function () {
    $this->user = User::factory()->roleCustomer()->create();
    actingAs($this->user);

    $this->user->profile->update(['balance' => 10]);
});

describe('index action', function () {
    it('renders the deposit index page', function () {
        Deposit::factory()->for($this->user)->count(3)->create([
            'value' => 15,
        ]);

        // Act
        $response = $this->get('/deposit');

        // Assert
        $response->assertInertia(
            fn (Assert $page) => $page
                ->component('Customer/Deposit/Index')
                ->has('deposits.data', 3)
                ->where('valuePending', formatNumber(45))
                ->where('balance', formatNumber($this->user->profile->balance))
                ->where('create', true)
        );
    });

    it('forbids admin from accessing deposit index page', function () {
        actingAs(User::factory()->roleAdmin()->create());

        // Act
        $response = $this->get('/deposit');

        // Assert
        $response->assertStatus(403);
    });
});

describe('create action', function () {
    it('renders the deposit create page', function () {
        // Act
        $response = $this->get('/deposit/create');

        // Assert
        $response->assertInertia(fn (Assert $page) => $page->component('Customer/Deposit/Create'));
    });

    it('validates deposit creation fields', function () {
        Storage::fake();

        // fields required
        $response = $this->post('/deposit', []);
        $response->assertSessionHasErrors(['description', 'receipt', 'value']);

        // type values
        $response = $this->post('/deposit', [
            'value' => 'a',
        ]);
        $response->assertSessionHasErrors(['value']);

        // min values
        $response = $this->post('/deposit', [
            'value' => 0,
        ]);
        $response->assertSessionHasErrors(['value']);

        // max values
        $response = $this->post('/deposit', [
            'description' => str_repeat('a', 121),
            'value'       => 4000000001,
            'receipt'     => UploadedFile::fake()->image('receipt.jpg')->size(2049),
        ]);
        $response->assertSessionHasErrors(['description', 'receipt', 'value']);

        // file values
        $response = $this->post('/deposit', [
            'receipt' => 'testing',
        ]);
        $response->assertSessionHasErrors(['receipt']);

        // mime values
        $response = $this->post('/deposit', [
            'receipt' => UploadedFile::fake()->image('receipt.mp4'),
        ]);
        $response->assertSessionHasErrors(['receipt']);
    });

    it('creates a deposit', function () {
        $response = $this->post('/deposit', [
            'description' => 'testing',
            'value'       => 10,
            'receipt'     => UploadedFile::fake()->image('receipt.jpg'),
        ]);
        $response->assertSessionHasNoErrors();

        assertDatabaseCount('deposits', 1);
        assertDatabaseHas('deposits', [
            'description' => 'testing',
            'value'       => 1000,
        ]);
        assertDatabaseMissing('deposits', [
            'description' => 'testing',
            'value'       => 1000,
            'receipt'     => null,
        ]);
    });

    it('forbids admin from accessing deposit create page', function () {
        actingAs(User::factory()->roleAdmin()->create());

        // Act
        $response = $this->post('/deposit', [
            'description' => 'testing',
            'value'       => 10,
            'receipt'     => UploadedFile::fake()->image('receipt.jpg'),
        ]);

        // Assert
        $response->assertStatus(403);
    });
});

describe('status action', function () {

    it('renders the deposit edit page', function () {
        $deposit = Deposit::factory()->for($this->user)->create();

        actingAs(User::factory()->roleAdmin()->create());

        // Act
        $response = $this->get('/deposit/' . $deposit->id . '/edit');

        // Assert
        // Assert
        $response->assertInertia(
            fn (Assert $page) => $page
                ->component('Customer/Deposit/Edit')
        );
    });

    it('validates deposit status fields', function () {
        $deposit = Deposit::factory()->for($this->user)->create();

        actingAs(User::factory()->roleAdmin()->create());

        // fields required
        $response = $this->patch("/deposit/{$deposit->id}/status", []);
        $response->assertSessionHasErrors(['status']);

        // type values
        $response = $this->put("/deposit/{$deposit->id}/status", [
            'status' => 'a',
        ]);
        $response->assertSessionHasErrors(['status']);
    });

    it('updates deposit status', function () {
        $deposit = Deposit::factory()->for($this->user)->create(['value' => 15]);

        actingAs(User::factory()->roleAdmin()->create());

        $response = $this->patch("/deposit/{$deposit->id}/status", [
            'status' => true,
        ]);
        $response->assertSessionHasNoErrors();

        assertDatabaseHas('deposits', [
            'id'     => $deposit->id,
            'status' => true,
        ]);
        assertDatabaseHas('user_profile', [
            'id'      => $this->user->id,
            'balance' => 2500,
        ]);
    });

    it('forbids customer from updating deposit status', function () {
        $deposit = Deposit::factory()->recycle($this->user)->create([
            'value' => 15,
        ]);

        // Act
        $response = $this->patch("/deposit/{$deposit->id}/status", [
            'status' => true,
        ]);

        // Assert
        $response->assertStatus(403);
    });
});
