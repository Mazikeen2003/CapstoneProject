<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditLogService
{
    /**
     * Attributes that must never be persisted in audit log JSON.
     */
    private const EXCLUDED_FIELDS = [
        'password_hash',
        'remember_token',
        'otp_code',
        'otp_expires_at',
    ];

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
        $oldValues = self::withoutExcludedFields($oldValues);
        $newValues = array_intersect_key($model->getAttributes(), $oldValues);
        $newValues = self::withoutExcludedFields($newValues);

        // Only log fields that actually changed
        $changedOld = [];
        $changedNew = [];
        foreach ($oldValues as $key => $value) {
            if (! self::valuesAreEquivalent($key, $newValues[$key] ?? null, $value)) {
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
            'old_values' => $old === null ? null : self::withoutExcludedFields($old),
            'new_values' => $new === null ? null : self::withoutExcludedFields($new),
            'full_name'  => Auth::user()?->full_name ?: Auth::user()?->username,
            'created_at' => now(),
        ]);
    }

    private static function withoutExcludedFields(array $values): array
    {
        return array_diff_key($values, array_flip(self::EXCLUDED_FIELDS));
    }

    private static function valuesAreEquivalent(string $key, mixed $newValue, mixed $oldValue): bool
    {
        if ($key !== 'permissions') {
            return $newValue == $oldValue;
        }

        return self::normalizePermissionsValue($newValue) === self::normalizePermissionsValue($oldValue);
    }

    private static function normalizePermissionsValue(mixed $value): mixed
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $value = $decoded;
            }
        }

        if (! is_array($value)) {
            return $value;
        }

        foreach ($value as $key => $item) {
            $value[$key] = self::normalizePermissionsValue($item);
        }

        if (! array_is_list($value)) {
            ksort($value);
        }

        return $value;
    }

    /**
     * Log a failed authentication attempt.
     *
     * This intentionally does not rely on a Model instance because the
     * attempt may not correspond to any existing user.
     */
    public static function logFailedLogin(string $attemptedEmail, ?string $ipAddress = null): void
    {
        try {
            AuditLog::create([
                'user_id'    => null,
                'action'     => 'login_failed',
                'table_name' => 'users',
                'record_id'  => null,
                'old_values' => null,
                'new_values' => [
                    'attempted_email' => $attemptedEmail,
                    'ip_address' => $ipAddress,
                ],
                'full_name'  => $attemptedEmail,
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // Swallow any logging errors so they don't interfere with auth flow.
        }
    }
}
