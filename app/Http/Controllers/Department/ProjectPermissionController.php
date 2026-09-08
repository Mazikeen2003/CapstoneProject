<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\EditPermissionRequest;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectPermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = Auth::user();

            if (! $user || ! $user->isDepartmentHead()) {
                abort(403, 'Department Head access required.');
            }

            return $next($request);
        });
    }

    public function index()
    {
        $this->authorize('viewAny', Project::class);

        $requests = EditPermissionRequest::with(['project', 'requester', 'reviewer'])
            ->latest('created_at')
            ->get();

        return response()
            ->view('department.project-permissions.index', compact('requests'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    public function approve(Request $request, $id)
    {
        $permissionRequest = EditPermissionRequest::findOrFail($id);
        $permissionRequest->status = 'approved';
        $permissionRequest->reviewed_by = Auth::id();
        $permissionRequest->reviewed_at = now();
        $permissionRequest->review_notes = $request->input('review_notes');
        $permissionRequest->save();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'permission_approved',
            'table_name' => 'edit_permission_requests',
            'record_id' => $permissionRequest->request_id,
            'old_values' => ['status' => 'pending'],
            'new_values' => ['status' => 'approved', 'reviewed_by' => Auth::id()],
            'full_name' => Auth::user()?->full_name ?: Auth::user()?->username,
            'created_at' => now(),
        ]);

        return redirect()->route('department.project-permissions.index')->with('success', 'Permission request approved.');
    }

    public function reject(Request $request, $id)
    {
        $permissionRequest = EditPermissionRequest::findOrFail($id);
        $permissionRequest->status = 'rejected';
        $permissionRequest->reviewed_by = Auth::id();
        $permissionRequest->reviewed_at = now();
        $permissionRequest->review_notes = $request->input('review_notes');
        $permissionRequest->save();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'permission_rejected',
            'table_name' => 'edit_permission_requests',
            'record_id' => $permissionRequest->request_id,
            'old_values' => ['status' => 'pending'],
            'new_values' => ['status' => 'rejected', 'reviewed_by' => Auth::id()],
            'full_name' => Auth::user()?->full_name ?: Auth::user()?->username,
            'created_at' => now(),
        ]);

        return redirect()->route('department.project-permissions.index')->with('success', 'Permission request rejected.');
    }
}
