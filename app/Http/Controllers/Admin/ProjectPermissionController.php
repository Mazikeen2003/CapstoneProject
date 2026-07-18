<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\EditPermissionRequest;
use App\Models\Project;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectPermissionController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Project::class);

        $requests = EditPermissionRequest::with(['project', 'requester', 'reviewer'])
            ->latest('created_at')
            ->get();

        return view('admin.project-permissions.index', compact('requests'));
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

        return redirect()->route('admin.project-permissions.index')->with('success', 'Permission request approved.');
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

        return redirect()->route('admin.project-permissions.index')->with('success', 'Permission request rejected.');
    }
}
