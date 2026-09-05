<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Backup;
use App\Services\BackupService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function index(Request $request)
    {
        $query = Backup::with('triggeredBy')->orderByDesc('created_at');
        $status = trim((string) $request->input('status', ''));
        $triggerType = trim((string) $request->input('trigger_type', ''));
        $createdDate = $request->input('created_date');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        if ($status !== '') {
            $query->where('status', $status);
        }

        if ($triggerType !== '') {
            $query->where('trigger_type', $triggerType);
        }

        if (is_string($createdDate) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $createdDate)) {
            $localDate = Carbon::createFromFormat('!Y-m-d', $createdDate, config('app.timezone'));
            $query->whereBetween('created_at', [
                $localDate->copy()->startOfDay()->format('Y-m-d H:i:s'),
                $localDate->copy()->endOfDay()->format('Y-m-d H:i:s'),
            ]);
        } elseif (is_string($dateFrom) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateFrom)) {
            $query->whereDate('created_at', '>=', $dateFrom);
        } elseif (is_string($dateTo) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateTo)) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $backups = $query->paginate(10)->withQueryString();
        $statuses = Backup::query()->select('status')->distinct()->orderBy('status')->pluck('status');
        $triggerTypes = Backup::query()->select('trigger_type')->distinct()->orderBy('trigger_type')->pluck('trigger_type');

        return view('admin.backups.index', [
            'backups' => $backups,
            'statuses' => $statuses,
            'triggerTypes' => $triggerTypes,
        ]);
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
