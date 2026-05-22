<?php

use App\Models\User;

test('guests are redirected from the user list to login', function () {
    $response = $this->get(route('dashboard.users.index'));

    $response->assertRedirect(route('login'));
});

test('authenticated users can view the paginated user list', function () {
    $user = User::factory()->create();
    User::factory()->count(20)->create();

    $this->actingAs($user);

    $response = $this->get(route('dashboard.users.index'));

    $response->assertOk();
    $response->assertSee('Registered users');
    $response->assertSee($user->name);
});

test('the user list can be filtered by search', function () {
    $matchingUser = User::factory()->create(['name' => 'Search Match', 'email' => 'match@example.com']);
    User::factory()->create(['name' => 'Other User', 'email' => 'other@example.com']);

    $this->actingAs($matchingUser);

    $response = $this->get(route('dashboard.users.index', ['search' => 'Search']));

    $response->assertOk();
    $response->assertSee('Search Match');
    $response->assertDontSee('Other User');
});
