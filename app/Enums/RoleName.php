<?php

declare(strict_types=1);

namespace App\Enums;

enum RoleName: string
{
    case SOLICITANTE = 'Solicitante';
    case ESPECIALISTA = 'Especialista';

    public function label(): string
    {
        return $this->value;
    }

    public static function values(): array
    {
        return array_map(static fn (self $role): string => $role->value, self::cases());
    }
}
