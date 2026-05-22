<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RoleName;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Service extends Model
{
    use HasUuids;

    protected $fillable = ['title', 'professional_uuid'];

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    public function professional(): BelongsTo
    {
        return $this->belongsTo(User::class, 'professional_uuid')->role(RoleName::ESPECIALISTA->value);
    }

    public function teleconsultorias()
    {
        return $this->hasMany(Teleconsultoria::class);
    }
}
