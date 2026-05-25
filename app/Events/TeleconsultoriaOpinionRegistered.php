<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Teleconsultoria;

final class TeleconsultoriaOpinionRegistered
{
    public function __construct(private Teleconsultoria $teleconsultoria)
    {
    }
}
