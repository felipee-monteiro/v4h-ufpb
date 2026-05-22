<?php

use App\Enums\RoleName;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Support\Facades\Schema;

test('it creates solicitante and especialista roles and assigns them to a user', function () {
    app(RoleSeeder::class)->run();

    expect(Role::where('name', RoleName::SOLICITANTE->value)->exists())->toBeTrue();
    expect(Role::where('name', RoleName::ESPECIALISTA->value)->exists())->toBeTrue();

    $user = User::factory()->create();

    $user->assignRole([RoleName::SOLICITANTE->value, RoleName::ESPECIALISTA->value]);

    expect($user->hasAllRoles([RoleName::SOLICITANTE->value, RoleName::ESPECIALISTA->value]))->toBeTrue();
    expect($user->getRoleNames()->sort()->values()->all())->toBe([RoleName::ESPECIALISTA->value, RoleName::SOLICITANTE->value]);
});
