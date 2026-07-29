<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Backup;
use App\Services\BackupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function index()
    {
        $backups = Backup::with('triggeredBy')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.backups.index', compact('backups'));
    }

    public function manual(Request $request): RedirectResponse
    {
        BackupService::createBackup('manual', Auth::id(), force: true);

        return back()->with('status', 'Manual backup has been queued.');
    }

    public function download(Backup $backup)
    {
        if ($backup->status !== 'completed' || ! $backup->file_path || ! Storage::disk('local')->exists($backup->file_path)) {
            abort(404);
        }

        return Storage::disk('local')->download($backup->file_path, basename($backup->file_path));
    }

    public function destroy(Backup $backup): RedirectResponse
    {
        if ($backup->file_path && Storage::disk('local')->exists($backup->file_path)) {
            Storage::disk('local')->delete($backup->file_path);
        }

        $backup->delete();

        return back()->with('status', 'Backup deleted successfully.');
    }
}
