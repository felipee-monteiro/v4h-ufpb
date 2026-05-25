<?php

declare(strict_types=1);

namespace App\Actions\Teleconsultoria;

use App\Events\TeleconsultoriaOpinionRegistered;
use App\Models\Teleconsultoria;
use Illuminate\Support\Facades\DB;

final class RegisterOpinion
{
    public function __invoke(Teleconsultoria $teleconsultoria, string $professionalOpinion): Teleconsultoria
    {
        DB::transaction(static function () use ($teleconsultoria, $professionalOpinion): void {
            $teleconsultoria->registerProfessionalOpinion($professionalOpinion);

            DB::afterCommit(static function () use ($teleconsultoria): void {
                event(new TeleconsultoriaOpinionRegistered($teleconsultoria->refresh()->load('service.professional')));
            });
        });

        return $teleconsultoria;
    }
}
