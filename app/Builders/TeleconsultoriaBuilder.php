<?php

declare(strict_types=1);

namespace App\Builders;

use App\Enums\RoleName;
use App\Models\Service;
use App\Models\Teleconsultoria;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

final class TeleconsultoriaBuilder extends Builder
{
    public function getTeleconsultoriasBySolicitante(User $user): self
    {
        return $this->whereBelongsTo($user, 'solicitante')->orderByDesc(Teleconsultoria::CREATED_AT);
    }

    public function visibleTo(User $user, array $columns = [
        'uuid',
        'patient_name',
        'service_uuid',
        'created_at',
        'status',
        'clinical_history',
        'diagnostic_hypothesis',
        'professional_opinion',
    ], array $extraRelations = []): self
    {
        $createdAt = Teleconsultoria::CREATED_AT;

        $branches = collect([
            Teleconsultoria::query()->toBase()
                ->select($columns)
                ->where('solicitante_uuid', $user->getKey()),
        ]);

        if ($user->hasRole(RoleName::ESPECIALISTA->value)) {
            $branches->push(
                Teleconsultoria::query()->toBase()
                    ->select($columns)
                    ->whereIn('service_uuid', Service::query()
                        ->where('professional_uuid', $user->getKey())
                        ->select($columns))
            );
        }

        $union = $branches->shift();
        $branches->each(fn ($branch) => $union->union($branch));

        return $this
            ->with(array_merge(['service', 'service.professional', 'solicitante'], $extraRelations))
            ->fromSub($union, 'teleconsultorias')
            ->orderByDesc($createdAt);
    }
}
