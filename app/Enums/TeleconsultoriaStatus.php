<?php

declare(strict_types=1);

namespace App\Enums;

enum TeleconsultoriaStatus: string
{
    case PENDENTE     = 'Pendente';
    case CONCLUIDA    = 'Concluída';
    case CANCELADA    = 'Cancelada';
    case EM_ANDAMENTO = 'Em Andamento';
}
