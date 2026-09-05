<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = $this->buildAuditLogQuery($request);

        $logs = $query->paginate(15)->withQueryString();
        $users = User::orderBy('username')->get(['user_id', 'username']);
        $actions = AuditLog::distinct()->orderBy('action')->pluck('action');

        return view('admin.audit-logs.index', compact('logs', 'users', 'actions'));
    }

    public function exportPdf(Request $request)
    {
        $query = $this->buildAuditLogQuery($request);
        $logs = $query->get();

        $pdf = Pdf::loadView('reports.audit-log-pdf', [
            'title' => 'Audit Logs Export',
            'generated_by' => Auth::user()->username,
            'generated_date' => now()->format('M d, Y H:i A'),
            'logs' => $logs,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('audit_logs_export_' . now()->format('Y-m-d') . '.pdf');
    }

    public function show(AuditLog $log)
    {
        $log = AuditLog::with('user')->findOrFail($log->log_id);

        return view('admin.audit-logs.show', compact('log'));
    }

    private function buildAuditLogQuery(Request $request)
    {
        $query = AuditLog::with('user')->select('audit_logs.*')->latest('audit_logs.created_at');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        if (is_string($dateFrom) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateFrom)) {
            $from = Carbon::createFromFormat('!Y-m-d', $dateFrom, config('app.timezone'))->startOfDay();
            $query->where('audit_logs.created_at', '>=', $from->format('Y-m-d H:i:s'));
        }

        if (is_string($dateTo) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateTo)) {
            $to = Carbon::createFromFormat('!Y-m-d', $dateTo, config('app.timezone'))->endOfDay();
            $query->where('audit_logs.created_at', '<=', $to->format('Y-m-d H:i:s'));
        }

        if ($request->filled('sort')) {
            [$column, $direction] = $this->parseSortParam($request->input('sort'));

            if ($column === 'user') {
                $query->leftJoin('users', 'audit_logs.user_id', '=', 'users.user_id')
                    ->reorder('users.username', $direction);
            } else {
                $query->reorder('audit_logs.' . $column, $direction);
            }
        }

        return $query;
    }

    private function parseSortParam(string $sort): array
    {
        $allowlist = ['created_at', 'action', 'table_name', 'user'];
        $separator = strrpos($sort, '_');

        if ($separator === false) {
            return ['created_at', 'desc'];
        }

        $column = substr($sort, 0, $separator);
        $direction = substr($sort, $separator + 1);
        $direction = strtolower($direction) === 'asc' ? 'asc' : 'desc';

        if (!in_array($column, $allowlist, true)) {
            return ['created_at', 'desc'];
        }

        return [$column, $direction];
    }
}