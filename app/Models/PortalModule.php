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

    public const COLORS = [
        'red' => [
            'label' => 'Telkom Red',
            'bg_icon' => 'bg-telkom-50 dark:bg-telkom-950 text-telkom-700 dark:text-telkom-400',
            'border_hover' => 'hover:border-telkom-600 dark:hover:border-telkom-600',
            'badge' => 'bg-telkom-50 text-telkom-700 dark:bg-telkom-950 dark:text-telkom-400 border border-telkom-200 dark:border-telkom-800',
            'text_accent' => 'text-telkom-700 dark:text-telkom-400',
            'dot' => 'bg-telkom-600',
        ],
        'blue' => [
            'label' => 'Sky Blue',
            'bg_icon' => 'bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400',
            'border_hover' => 'hover:border-blue-500 dark:hover:border-blue-500',
            'badge' => 'bg-blue-50 text-blue-700 dark:bg-blue-950 dark:text-blue-300 border border-blue-200 dark:border-blue-800',
            'text_accent' => 'text-blue-600 dark:text-blue-400',
            'dot' => 'bg-blue-500',
        ],
        'emerald' => [
            'label' => 'Emerald Green',
            'bg_icon' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400',
            'border_hover' => 'hover:border-emerald-600 dark:hover:border-emerald-500',
            'badge' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800',
            'text_accent' => 'text-emerald-600 dark:text-emerald-400',
            'dot' => 'bg-emerald-500',
        ],
        'purple' => [
            'label' => 'Royal Purple',
            'bg_icon' => 'bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400',
            'border_hover' => 'hover:border-purple-500 dark:hover:border-purple-500',
            'badge' => 'bg-purple-50 text-purple-700 dark:bg-purple-950 dark:text-purple-300 border border-purple-200 dark:border-purple-800',
            'text_accent' => 'text-purple-600 dark:text-purple-400',
            'dot' => 'bg-purple-500',
        ],
        'amber' => [
            'label' => 'Warm Amber',
            'bg_icon' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400',
            'border_hover' => 'hover:border-amber-500 dark:hover:border-amber-500',
            'badge' => 'bg-amber-50 text-amber-700 dark:bg-amber-950 dark:text-amber-300 border border-amber-200 dark:border-amber-800',
            'text_accent' => 'text-amber-600 dark:text-amber-400',
            'dot' => 'bg-amber-500',
        ],
        'cyan' => [
            'label' => 'Teal / Cyan',
            'bg_icon' => 'bg-cyan-50 dark:bg-cyan-950/60 text-cyan-600 dark:text-cyan-400',
            'border_hover' => 'hover:border-cyan-500 dark:hover:border-cyan-500',
            'badge' => 'bg-cyan-50 text-cyan-700 dark:bg-cyan-950 dark:text-cyan-300 border border-cyan-200 dark:border-cyan-800',
            'text_accent' => 'text-cyan-600 dark:text-cyan-400',
            'dot' => 'bg-cyan-500',
        ],
        'rose' => [
            'label' => 'Rose Pink',
            'bg_icon' => 'bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400',
            'border_hover' => 'hover:border-rose-500 dark:hover:border-rose-500',
            'badge' => 'bg-rose-50 text-rose-700 dark:bg-rose-950 dark:text-rose-300 border border-rose-200 dark:border-rose-800',
            'text_accent' => 'text-rose-600 dark:text-rose-400',
            'dot' => 'bg-rose-500',
        ],
        'indigo' => [
            'label' => 'Deep Indigo',
            'bg_icon' => 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400',
            'border_hover' => 'hover:border-indigo-500 dark:hover:border-indigo-500',
            'badge' => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800',
            'text_accent' => 'text-indigo-600 dark:text-indigo-400',
            'dot' => 'bg-indigo-500',
        ],
        'dark' => [
            'label' => 'Dark Slate',
            'bg_icon' => 'bg-gray-900 text-white dark:bg-gray-100 dark:text-gray-900',
            'border_hover' => 'hover:border-gray-900 dark:hover:border-gray-100',
            'badge' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-700',
            'text_accent' => 'text-gray-900 dark:text-gray-100',
            'dot' => 'bg-gray-900 dark:bg-gray-200',
        ],
    ];

    protected $fillable = [
        'code',
        'name',
        'description',
        'credit',
        'icon',
        'color',
        'route_name',
        'url',
        'status',
        'order',
        'allowed_roles',
    ];

    public function getColorTheme(): array
    {
        $color = $this->color ?? 'red';

        return self::COLORS[$color] ?? self::COLORS['red'];
    }

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
