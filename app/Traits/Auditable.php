<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(function (Model $model): void {
            static::recordAuditLog($model, 'created', null, $model->getAttributes());
        });

        static::updated(function (Model $model): void {
            $old = array_intersect_key($model->getOriginal(), $model->getDirty());
            $new = $model->getDirty();
            // Don't log if only updated_at changed
            unset($old['updated_at'], $new['updated_at']);

            if (! empty($new)) {
                static::recordAuditLog($model, 'updated', $old, $new);
            }
        });

        static::deleted(function (Model $model): void {
            static::recordAuditLog($model, 'deleted', $model->getOriginal(), null);
        });
    }

    protected static function recordAuditLog(Model $model, string $action, ?array $old, ?array $new): void
    {
        // Don't log passwords or sensitive tokens
        if ($old) {
            unset($old['password'], $old['remember_token']);
        }
        if ($new) {
            unset($new['password'], $new['remember_token']);
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->getKey(),
            'old_values' => $old,
            'new_values' => $new,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'created_at' => now(),
        ]);
    }
}
