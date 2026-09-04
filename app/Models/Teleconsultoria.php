<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\TeleconsultoriaBuilder;
use App\Enums\RoleName;
use App\Enums\TeleconsultoriaStatus;
use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @mixin TeleconsultoriaBuilder
 *
 * @method static TeleconsultoriaBuilder query()
 */
final class Teleconsultoria extends Model
{
    use HasUuid;

    protected $fillable = [
        'solicitante_uuid',
        'service_uuid',
        'patient_name',
        'patient_birthday',
        'diagnostic_hypothesis',
        'clinical_history',
        'professional_opinion',
        'status',
    ];

    protected $attributes = [
        'status' => TeleconsultoriaStatus::PENDENTE,
    ];

    protected $appends = ['patient_initials'];

    public function solicitante()
    {
        return $this->belongsTo(User::class)->role(RoleName::SOLICITANTE->value);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function canBeReviewedBy(User $user): bool
    {
        if (! $user->hasRole(RoleName::ESPECIALISTA->value)) {
            return false;
        }

        return $user->getKey() === $this->service?->professional_uuid;
    }

    public function getPatientInitialsAttribute(): string
    {
        return Str::initials($this->patient_name, capitalize: true);
    }

    public function newEloquentBuilder($query): TeleconsultoriaBuilder
    {
        return new TeleconsultoriaBuilder($query);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'patient_birthday' => 'date',
            'status'           => TeleconsultoriaStatus::class,
        ];
    }
}
