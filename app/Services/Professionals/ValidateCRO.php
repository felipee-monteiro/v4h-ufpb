<?php

declare(strict_types=1);

namespace App\Services\Professionals;

use App\Contracts\ProfessionalValidator;
use App\Exceptions\InvalidCROException;

final class ValidateCRO implements ProfessionalValidator
{
    public function validate(string $unique_number): void
    {
        throw_if(blank($unique_number), InvalidCROException::class);
    }
}
