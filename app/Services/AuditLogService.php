<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditLogService
{
    /**
     * Log a create action.
     */
    public static function logCreate(Model $model): void
    {
        self::write('create', $model, null, $model->getAttributes());
    }

    /**
     * Log an update action. Pass the model's original attributes
     * (i.e. call this AFTER ->update() but you captured ->getOriginal() before).
     */
    public static function logUpdate(Model $model, array $oldValues): void
    {
        $newValues = array_intersect_key($model->getAttributes(), $oldValues);

        // Only log fields that actually changed
        $changedOld = [];
        $changedNew = [];
        foreach ($oldValues as $key => $value) {
            if (($newValues[$key] ?? null) != $value) {
                $changedOld[$key] = $value;
                $changedNew[$key] = $newValues[$key] ?? null;
            }
        }

        if (empty($changedOld)) {
            return; // nothing actually changed, don't log a no-op
        }

        self::write('update', $model, $changedOld, $changedNew);
    }

    /**
     * Log a delete action.
     */
    public static function logDelete(Model $model): void
    {
        self::write('delete', $model, $model->getAttributes(), null);
    }

    private static function write(string $action, Model $model, ?array $old, ?array $new): void
    {
        AuditLog::create([
            'user_id'    => Auth::id(),
            'action'     => $action,
            'table_name' => $model->getTable(),
            'record_id'  => $model->getKey(),
            'old_values' => $old,
            'new_values' => $new,
            'full_name'  => Auth::user()?->full_name ?: Auth::user()?->username,
            'created_at' => now(),
        ]);
    }
}
