<?php

declare(strict_types=1);

namespace App\Contracts;

interface ProfessionalValidator
{
    public function validate(string $unique_number): void;
}
