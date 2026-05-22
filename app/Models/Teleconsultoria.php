<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RoleName;
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

    #[\Override()]
    protected static function boot(): void
    {
        parent::boot();

        self::creating(static function (Teleconsultoria $teleconsultoria): void {
            $teleconsultoria->status = 'Pendente';
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
        ];
    }
}
