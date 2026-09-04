<?php

declare(strict_types=1);

namespace App\Builders;

use App\Models\Teleconsultoria;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

final class TeleconsultoriaBuilder extends Builder
{
    public function getTeleconsultoriasBySolicitante(User $user): self
    {
        return $this->whereBelongsTo($user, 'solicitante')->orderByDesc(Teleconsultoria::CREATED_AT);
    }

    public function getDashboardIndexDataForUser(User $user)
    {
        return $this::with('service.professional')
            ->where(fn (Builder $query) => $query->whereBelongsTo($user, 'solicitante')
                ->orWhereHas('service.professional', fn (Builder $query) => $query->whereKey($user->getKey())))
            ->latest()
            ->get();
    }
}
