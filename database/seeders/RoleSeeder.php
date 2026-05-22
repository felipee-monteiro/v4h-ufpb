<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Enums\RoleName;
use Illuminate\Database\Seeder;

final class RoleSeeder extends Seeder
{
    /**
     * Seed the application's roles.
     */
    public function run(): void
    {
        $guardName = config('auth.defaults.guard');

        foreach (RoleName::cases() as $role) {
            Role::firstOrCreate([
                'name' => $role->value,
                'guard_name' => $guardName,
            ]);
        }
    }
}
