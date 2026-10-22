<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortalModule extends Model
{
    use Auditable, HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'icon',
        'route_name',
        'status',
        'order',
        'allowed_roles',
    ];

    protected function casts(): array
    {
        return [
            'allowed_roles' => 'array',
            'order' => 'integer',
        ];
    }

    public function isAccessibleBy(?User $user = null): bool
    {
        if (empty($this->allowed_roles)) {
            return true;
        }

        if (! $user) {
            return false;
        }

        return $user->hasAnyRole($this->allowed_roles);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order')->orderBy('name');
    }
}
