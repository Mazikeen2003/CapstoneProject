<?php

namespace App\Services;

use App\Models\Backup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * BackupService handles pure-PHP database export generation and retention.
 *
 * Note: This service generates SQL backup files only. Restoring from this
 * backup file is intended to be performed manually through phpMyAdmin or
 * another import tool, not automatically by the application.
 */
class BackupService
{
    public const THROTTLE_MINUTES = 5;
    public const MAX_BACKUPS_TO_KEEP = 20;

    public static function createBackup(string $triggerType, ?int $userId = null, bool $force = false): ?Backup
    {
        if (! $force) {
            $latest = Backup::where('status', 'completed')->latest('created_at')->first();
            if ($latest && $latest->created_at->greaterThan(now()->subMinutes(self::THROTTLE_MINUTES))) {
                return null;
            }
        }

        $backup = Backup::create([
            'trigger_type' => $triggerType,
            'triggered_by_user_id' => $userId,
            'status' => 'pending',
        ]);

        try {
            $sql = self::dumpDatabaseToSql();
            $safeTriggerType = preg_replace('/[^A-Za-z0-9_]+/', '_', $triggerType);
            $timestamp = now()->format('Ymd_His');
            $path = "backups/backup_{$safeTriggerType}_{$timestamp}.sql";

            Storage::disk('local')->put($path, $sql);

            $backup->update([
                'file_path' => $path,
                'file_size' => Storage::disk('local')->size($path),
                'status' => 'completed',
                'error_message' => null,
            ]);

            self::pruneOldBackups();
        } catch (\Throwable $exception) {
            $backup->update([
                'status' => 'failed',
                'error_message' => substr($exception->getMessage(), 0, 1000),
            ]);
        }

        return $backup;
    }

    private static function dumpDatabaseToSql(): string
    {
        $pdo = DB::connection()->getPdo();
        $databaseName = DB::getDatabaseName();
        $excludedTables = [
            'sessions',
            'cache',
            'cache_locks',
            'jobs',
            'failed_jobs',
            'migrations',
            'password_reset_tokens',
        ];

        $header = "-- ProjectTracker Backup generated " . now()->toDateTimeString() . "\n";
        $header .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        $sql = $header;

        $tables = DB::select('SHOW TABLES');
        foreach ($tables as $row) {
            $rowArray = (array) $row;
            $table = reset($rowArray);
            if (! $table || in_array($table, $excludedTables, true)) {
                continue;
            }

            $createRows = DB::select("SHOW CREATE TABLE `{$table}`");
            $createRow = $createRows[0] ?? null;
            if (! $createRow) {
                continue;
            }

            $createStatement = $createRow->{'Create Table'} ?? $createRow->Create_Table ?? null;
            if (! $createStatement) {
                continue;
            }

            $sql .= "DROP TABLE IF EXISTS `{$table}`;\n";
            $sql .= $createStatement . ";\n\n";

            $rows = DB::table($table)->get();
            if ($rows->isEmpty()) {
                continue;
            }

            $columns = array_map(fn($column) => "`{$column}`", array_keys((array) $rows->first()));
            $columnList = implode(', ', $columns);

            $batch = [];
            foreach ($rows as $index => $rowData) {
                $values = [];
                foreach ((array) $rowData as $value) {
                    $values[] = self::formatValue($pdo, $value);
                }

                $batch[] = '(' . implode(', ', $values) . ')';

                if (count($batch) >= 100) {
                    $sql .= "INSERT INTO `{$table}` ({$columnList}) VALUES\n" . implode(",\n", $batch) . ";\n\n";
                    $batch = [];
                }
            }

            if (! empty($batch)) {
                $sql .= "INSERT INTO `{$table}` ({$columnList}) VALUES\n" . implode(",\n", $batch) . ";\n\n";
            }
        }

        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

        return $sql;
    }

    private static function formatValue(\PDO $pdo, $value): string
    {
        if (is_null($value)) {
            return 'NULL';
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        if (is_array($value) || is_object($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        }

        $quoted = $pdo->quote((string) $value);
        if ($quoted !== false) {
            return $quoted;
        }

        return "'" . addslashes((string) $value) . "'";
    }

    private static function pruneOldBackups(): void
    {
        $idsToKeep = Backup::where('status', 'completed')
            ->orderByDesc('created_at')
            ->limit(self::MAX_BACKUPS_TO_KEEP)
            ->pluck('backup_id');

        $oldBackups = Backup::where('status', 'completed')
            ->whereNotIn('backup_id', $idsToKeep)
            ->get();

        if ($oldBackups->isEmpty()) {
            return;
        }

        foreach ($oldBackups as $backup) {
            if ($backup->file_path && Storage::disk('local')->exists($backup->file_path)) {
                Storage::disk('local')->delete($backup->file_path);
            }
            $backup->delete();
        }
    }
}
