<?php

declare(strict_types=1);

namespace App\Actions\Teleconsultoria;

use App\Events\TeleconsultoriaOpinionRegistered;
use App\Models\Teleconsultoria;
use Illuminate\Support\Facades\DB;

final readonly class RegisterOpinion
{
    public function execute(Teleconsultoria $teleconsultoria, string $professionalOpinion): Teleconsultoria
    {
        DB::transaction(function () use ($teleconsultoria, $professionalOpinion): void {
            $teleconsultoria->update([
                'professional_opinion' => $professionalOpinion
            ]);
        });

        DB::afterCommit(fn () => event(new TeleconsultoriaOpinionRegistered($teleconsultoria)));

        return $teleconsultoria;
    }
}
