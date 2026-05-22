<?php

use App\Enums\RoleName;
use App\Models\User;
use Database\Seeders\RoleSeeder;

test('role middleware protects solicitante dashboard route', function () {
    app(RoleSeeder::class)->run();

    $solicitante = User::factory()->create();
    $solicitante->assignRole(RoleName::SOLICITANTE->value);

    $this->actingAs($solicitante)
        ->get(route('solicitante.dashboard'))
        ->assertOk();

    $especialista = User::factory()->create();
    $especialista->assignRole(RoleName::ESPECIALISTA->value);

    $this->actingAs($especialista)
        ->get(route('solicitante.dashboard'))
        ->assertForbidden();
});
