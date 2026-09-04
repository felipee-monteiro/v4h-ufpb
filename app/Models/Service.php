<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RoleName;
use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Service extends Model
{
    use HasUuid;

    protected $fillable = ['title', 'professional_uuid'];

    public function professional(): BelongsTo
    {
        return $this->belongsTo(User::class, 'professional_uuid')->role(RoleName::ESPECIALISTA->value);
    }

    public function teleconsultorias(): HasMany
    {
        return $this->hasMany(Teleconsultoria::class);
    }
}
