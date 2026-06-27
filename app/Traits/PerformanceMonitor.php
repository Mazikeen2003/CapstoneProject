<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait PerformanceMonitor
{
    /**
     * Log database queries in development mode.
     * Useful for finding N+1 query problems.
     * 
     * Usage in controller:
     *   use PerformanceMonitor;
     *   
     *   public function index() {
     *       $this->startQueryMonitoring();
     *       // ... your code ...
     *       $this->logQueryMetrics();
     *   }
     */

    protected function startQueryMonitoring()
    {
        if (!app()->isLocal()) {
            return;
        }

        DB::enableQueryLog();
    }

    protected function logQueryMetrics()
    {
        if (!app()->isLocal()) {
            return;
        }

        $queries = DB::getQueryLog();
        $queryCount = count($queries);
        $totalTime = 0;

        foreach ($queries as $query) {
            $totalTime += $query['time'];
        }

        $message = "Executed {$queryCount} queries in {$totalTime}ms";

        if ($queryCount > 10) {
            \Log::warning("⚠️  PERFORMANCE: $message - Consider adding eager loading");
        } else {
            \Log::info("✅ Performance: $message");
        }
    }

    /**
     * Quick helper to see N+1 query issues
     */
    protected function debugQueries()
    {
        if (!app()->isLocal()) {
            return [];
        }

        return DB::getQueryLog();
    }
}