<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;

test('user model and permission tables are UUID compatible', function () {
    $tableNames = config('permission.table_names', []);
    $columnNames = config('permission.column_names');

    $user = new User();

    expect($user->getKeyType())->toBe('string');
    expect($user->getKeyName())->toBe('uuid');
    expect($user->getIncrementing())->toBeFalse();

    expect(Schema::hasTable($tableNames['permissions']))->toBeTrue();
    expect(Schema::hasTable($tableNames['roles']))->toBeTrue();
    expect(Schema::hasTable($tableNames['model_has_permissions']))->toBeTrue();
    expect(Schema::hasTable($tableNames['model_has_roles']))->toBeTrue();
    expect(Schema::hasTable($tableNames['role_has_permissions']))->toBeTrue();

    $uuidTypes = ['string', 'varchar'];

    expect(Schema::hasColumn($tableNames['permissions'], 'id'))->toBeTrue();
    expect(in_array(Schema::getColumnType($tableNames['permissions'], 'id'), $uuidTypes, true))->toBeTrue();
    expect(Schema::hasColumn($tableNames['roles'], 'id'))->toBeTrue();
    expect(in_array(Schema::getColumnType($tableNames['roles'], 'id'), $uuidTypes, true))->toBeTrue();

    expect(in_array(Schema::getColumnType($tableNames['role_has_permissions'], $columnNames['permission_pivot_key'] ?? 'permission_id'), $uuidTypes, true))->toBeTrue();
    expect(in_array(Schema::getColumnType($tableNames['role_has_permissions'], $columnNames['role_pivot_key'] ?? 'role_id'), $uuidTypes, true))->toBeTrue();
    expect(in_array(Schema::getColumnType($tableNames['model_has_permissions'], $columnNames['model_morph_key']), $uuidTypes, true))->toBeTrue();
    expect(in_array(Schema::getColumnType($tableNames['model_has_roles'], $columnNames['model_morph_key']), $uuidTypes, true))->toBeTrue();
});
