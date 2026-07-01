<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Project;
use App\Models\Report;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $users = User::count();

        $projects = Project::count();
        // projects use 'current_status' in the schema
        $projects_published = Project::where('current_status', 'published')->count();
        $projects_draft = Project::where('current_status', 'draft')->count();

        $reports = Schema::hasTable('reports') ? Report::count() : 0;

        $auditLogs = Schema::hasTable('audit_logs') ? AuditLog::count() : 0;
        $recentActivity = Schema::hasTable('audit_logs') ? AuditLog::with('user')->latest()->take(5)->get() : collect();

        return view('admin.dashboard', compact(
            'users',
            'projects',
            'projects_published',
            'projects_draft',
            'reports',
            'auditLogs',
            'recentActivity'
        ));
    }
}