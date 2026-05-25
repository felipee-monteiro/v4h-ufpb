<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RoleName;
use App\Enums\TeleconsultoriaStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

final class Teleconsultoria extends Model
{
    use HasUuids;
    public $incrementing  = false;
    protected $primaryKey = 'uuid';
    protected $keyType    = 'string';
    protected $fillable   = [
        'solicitante_uuid',
        'service_uuid',
        'patient_name',
        'patient_initials',
        'patient_birthday',
        'diagnostic_hypothesis',
        'clinical_history',
        'professional_opinion',
        'status',
    ];

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
        if (!$user->hasRole(RoleName::ESPECIALISTA->value)) {
            return false;
        }

        return $user->getKey() === $this->service?->professional_uuid;
    }

    public function registerProfessionalOpinion(string $professionalOpinion): void
    {
        $this->professional_opinion = $professionalOpinion;
        $this->save();
    }

    #[\Override()]
    protected static function boot(): void
    {
        parent::boot();

        self::creating(static function (Teleconsultoria $teleconsultoria): void {
            $teleconsultoria->status = TeleconsultoriaStatus::PENDENTE->value;
        });
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
