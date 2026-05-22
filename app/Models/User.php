<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

final class User extends Authenticatable
{
    /**
     * @use HasFactory<UserFactory>
     */
    use HasFactory;
    use Notifiable;
    use HasUuids;
    use HasRoles;
    public $primaryKey    = 'uuid';
    public $incrementing  = false;
    public $appends       = ['created_at_formatted'];
    public $keyType       = 'string';
    protected $guard_name = 'web';
    protected $fillable   = [
        'name',
        'email',
        'password',
    ];
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->when(\mb_trim($search), static function (Builder $query) use ($search): void {
            $query->where('name', 'ilike', "%{$search}%")
                ->orWhere('email', 'ilike', "%{$search}%");
        });
    }

    public function scopeNewest(Builder $query): Builder
    {
        return $query->orderByDesc('created_at');
    }

    public function getCreatedAtFormattedAttribute(): string
    {
        return $this->created_at->format('F j, Y');
    }

    public function teleconsultorias()
    {
        return $this->hasMany(Teleconsultoria::class, 'solicitante_uuid');
    }

    public function professionalService()
    {
        return $this->hasOne(Service::class, 'professional_uuid');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }
}
