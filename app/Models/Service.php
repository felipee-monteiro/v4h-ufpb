<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RoleName;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Service extends Model
{
    use HasUuids;
    public $incrementing  = false;
    protected $fillable   = ['title', 'professional_uuid'];
    protected $primaryKey = 'uuid';
    protected $keyType    = 'string';

    public function professional(): BelongsTo
    {
        return $this->belongsTo(User::class, 'professional_uuid')->role(RoleName::ESPECIALISTA->value);
    }

    public function teleconsultorias(): HasMany
    {
        return $this->hasMany(Teleconsultoria::class);
    }
}
