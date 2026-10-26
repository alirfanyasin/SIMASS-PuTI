<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class PortalModule extends Model
{
    use Auditable, HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'credit',
        'icon',
        'route_name',
        'url',
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

    public function getTargetUrl(): string
    {
        if (! empty($this->url)) {
            return $this->url;
        }

        if (! empty($this->route_name)) {
            if (str_starts_with($this->route_name, 'http://') || str_starts_with($this->route_name, 'https://')) {
                return $this->route_name;
            }

            if (Route::has($this->route_name)) {
                return route($this->route_name);
            }
        }

        return '#';
    }

    public function isExternalLink(): bool
    {
        if (! empty($this->url)) {
            return true;
        }

        if (! empty($this->route_name) && (str_starts_with($this->route_name, 'http://') || str_starts_with($this->route_name, 'https://'))) {
            return true;
        }

        return false;
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order')->orderBy('name');
    }
}
