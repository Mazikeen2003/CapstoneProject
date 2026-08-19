<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\EditPermissionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $since = $request->date('since') ?? now()->subSeconds(30);

        $user = $request->user();
        $query = AuditLog::query()
            ->where('created_at', '>', $since)
            ->latest('created_at');

        if ($user->role_slug !== 'admin') {
            $query->where(function ($scope) use ($user) {
                if ($user->role_slug === 'city') {
                    $scope->where('table_name', 'projects');
                } elseif ($user->role_slug === 'department') {
                    $scope->where(function ($projectScope) use ($user) {
                        $projectScope->where('table_name', 'projects')
                            ->whereExists(function ($project) use ($user) {
                                $project->selectRaw('1')
                                    ->from('projects')
                                    ->whereColumn('projects.project_id', 'audit_logs.record_id')
                                    ->where('projects.created_by', $user->user_id);
                            });
                    })->orWhere(function ($permissionScope) use ($user) {
                        $permissionScope->where('table_name', 'edit_permission_requests')
                            ->whereExists(function ($requestQuery) use ($user) {
                                $requestQuery->selectRaw('1')
                                    ->from('edit_permission_requests')
                                    ->whereColumn('edit_permission_requests.request_id', 'audit_logs.record_id')
                                    ->where('edit_permission_requests.requested_by', $user->user_id);
                            });
                    });
                } elseif ($user->role_slug === 'barangay') {
                    $scope->where(function ($projectScope) use ($user) {
                        $projectScope->where('table_name', 'projects')
                            ->whereExists(function ($project) use ($user) {
                                $project->selectRaw('1')
                                    ->from('projects')
                                    ->whereColumn('projects.project_id', 'audit_logs.record_id')
                                    ->where('projects.barangay_id', $user->barangay_id);
                            });
                    })->orWhere(function ($permissionScope) use ($user) {
                        $permissionScope->where('table_name', 'edit_permission_requests')
                            ->whereExists(function ($requestQuery) use ($user) {
                                $requestQuery->selectRaw('1')
                                    ->from('edit_permission_requests')
                                    ->join('projects', 'projects.project_id', '=', 'edit_permission_requests.project_id')
                                    ->whereColumn('edit_permission_requests.request_id', 'audit_logs.record_id')
                                    ->where('projects.barangay_id', $user->barangay_id);
                            });
                    });
                } else {
                    $scope->whereRaw('1 = 0');
                }
            });
        }

        $notifications = $query
            ->limit($request->boolean('all') ? 500 : 50)
            ->get()
            ->map(function (AuditLog $log) use ($user): array {
                $subject = match ($log->table_name) {
                    'projects' => 'project',
                    'users' => 'user account',
                    'edit_permission_requests' => 'permission request',
                    default => str_replace('_', ' ', $log->table_name),
                };
                $action = match ($log->action) {
                    'create' => 'added',
                    'update' => 'updated',
                    'delete' => 'deleted',
                    'permission_requested' => 'requested a permission change for a',
                    'permission_approved' => 'approved a',
                    'permission_rejected' => 'rejected a',
                    default => $log->action,
                };
                $destination = $this->destinationFor($log, $user);

                return [
                    'id' => 'audit-log-' . $log->log_id,
                    'title' => 'System activity',
                    'message' => trim(($log->full_name ?: 'A user') . ' ' . $action . ' ' . $subject . '.'),
                    'time' => $log->created_at?->toIso8601String(),
                    'type' => 'audit_activity',
                    'url' => $destination,
                ];
            });

        return response()->json(['notifications' => $notifications]);
    }

    private function destinationFor(AuditLog $log, $user): ?string
    {
        if ($log->table_name === 'projects' && $user->role_slug !== 'admin') {
            return match ($user->role_slug) {
                'city' => route('city.projects.show', $log->record_id),
                'department' => route('department.projects.show', $log->record_id),
                'barangay' => route('barangay.projects.show', $log->record_id),
                default => null,
            };
        }

        if ($log->table_name === 'edit_permission_requests') {
            if ($user->role_slug === 'admin') {
                return route('admin.project-permissions.index');
            }

            $permissionRequest = EditPermissionRequest::find($log->record_id);
            if (! $permissionRequest) {
                return null;
            }

            return match ($user->role_slug) {
                'department' => route('department.projects.edit', $permissionRequest->project_id),
                'barangay' => route('barangay.projects.show', $permissionRequest->project_id),
                default => null,
            };
        }

        if ($log->table_name === 'users' && $user->role_slug === 'admin') {
            return route('admin.users.edit', $log->record_id);
        }

        return null;
    }
}