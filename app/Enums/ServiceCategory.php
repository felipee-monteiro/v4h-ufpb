<?php

declare(strict_types=1);

namespace App\Enums;

enum ServiceCategory: string
{
    case CARDIOLOGIA = 'Cardiologia';
    case CIRURGIA_ROBOTICA = 'Cirurgia Robótica';
    case ODONTOLOGIA = 'Odontologia';
    case DOENCAS_RARAS = 'Doenças Raras';
    case OXIGENOTERAPIA = 'Oxigenoterapia';

    public function label(): string
    {
        return $this->value;
    }

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_map(static fn (self $category): string => $category->value, self::cases());
    }
}
