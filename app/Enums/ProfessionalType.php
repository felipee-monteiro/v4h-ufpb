<?php

declare(strict_types=1);

namespace App\Enums;

use App\Contracts\ProfessionalValidator;
use App\Exceptions\InvalidProfessionalException;
use App\Services\Professionals\ValidateCRO;
use App\Services\Professionals\ValidateCRM;

enum ProfessionalType: string
{
    case MEDICO = 'medico';
    case PSICOLOGO = 'psicologo';

    final public function label(): string
    {
        return match ($this) {
            self::MEDICO => 'Médico',
            self::PSICOLOGO => 'Psicólogo',
        };
    }

    /**
     * @throws InvalidProfessionalException
     */
    final public function validator(): ProfessionalValidator
    {
        return match ($this) {
            self::MEDICO => app()->make(ValidateCRM::class),
            self::PSICOLOGO => app()->make(ValidateCRO::class)
        };
    }

    /**
     * @return array<string>
     */
    final public static function values(): array
    {
        return array_map(static fn (self $type): string => $type->value, self::cases());
    }
}
