<?php

namespace App\Http\Controllers\Admin;

use App\Models\AuditLog;

class AuditLogController
{
    public function index()
    {
        // Paginate audit logs with eager loading
        $logs = AuditLog::with('user')
            ->latest('created_at')
            ->paginate(25);

        return view('admin.audit-logs.index', compact('logs'));
    }

    public function show(AuditLog $log)
    {
        $log = AuditLog::with('user')->findOrFail($log->log_id);

        return view('admin.audit-logs.show', compact('log'));
    }
}