<?php

declare(strict_types=1);

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Enums\RoleName;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

final class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;
    use ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param array<string, string> $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'password'         => $this->passwordRules(),
            'role'             => ['nullable', 'string', Rule::in(RoleName::values())],
            'service_category' => [
                'nullable',
                'string',
                'exists:services,uuid',
                'required_if:role,' . RoleName::ESPECIALISTA->value,
            ],
        ])->validate();

        $user = DB::transaction(static function () use ($input): User {
            $user = User::create([
                'name'     => $input['name'],
                'email'    => $input['email'],
                'password' => $input['password'],
            ]);

            $user->assignRole($input['role'] ?? RoleName::SOLICITANTE->value);

            if ($input['service_category']) {
                $user->professionalService()->save(Service::whereKey($input['service_category'])->first());
            }

            return $user;
        });

        return $user;
    }
}
