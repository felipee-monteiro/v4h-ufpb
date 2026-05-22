<?php

use Laravel\Fortify\Features;

beforeEach(function () {
    $this->skipUnlessFortifyHas(Features::registration());
});

test('registration screen can be rendered', function () {
    $response = $this->get(route('register'));

    $response->assertOk();
});

test('new users can register', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertStatus(302);
});

test('new specialists can register with a service category', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'Specialist User',
        'email' => 'specialist@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => 'especialista',
        'service_category' => 'Cardiologia',
    ]);

    $this->assertAuthenticated();
    $response->assertStatus(302);
});
