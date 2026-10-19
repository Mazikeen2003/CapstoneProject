<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BudgetTransaction;
use App\Models\Project;
use App\Models\Scopes\RoleScopedScope;
use App\Services\PortalVisitService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ExpenditureController extends Controller
{
    public function index(Request $request)
    {
        PortalVisitService::logVisit('expenditures');

        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);
        $search = trim($filters['q'] ?? '');
        $trackingAvailable = BudgetTransaction::supportsCategoryTracking();

        $projects = Project::withoutGlobalScope(RoleScopedScope::class)
            ->with('barangay')
            ->withActualTransactionSum()
            ->where(static function (Builder $projects) use ($trackingAvailable): void {
                $projects->where('actual_budget', '>', 0);

                if ($trackingAvailable) {
                    $projects->orWhereHas('budgetTransactions', static fn (Builder $transactions) => $transactions
                        ->where('type', 'actual')
                        ->where('amount', '>', 0));
                }
            })
            ->when($search !== '', static function (Builder $projects) use ($search): void {
                $projects->where(static function (Builder $query) use ($search): void {
                    $query->where('project_name', 'like', "%{$search}%")
                        ->orWhere('project_code', 'like', "%{$search}%")
                        ->orWhereHas('barangay', static fn (Builder $barangays) => $barangays
                            ->where('barangay_name', 'like', "%{$search}%"));
                });
            })
            ->orderBy('project_name')
            ->paginate(25)
            ->withQueryString();

        return view('public.expenditures', compact('projects', 'trackingAvailable', 'search'));
    }

    public function show(int $projectId)
    {
        PortalVisitService::logVisit('expenditures');

        $project = Project::withoutGlobalScope(RoleScopedScope::class)
            ->with('barangay')
            ->withActualTransactionSum()
            ->findOrFail($projectId);

        $trackingAvailable = BudgetTransaction::supportsCategoryTracking();
        $transactions = $trackingAvailable
            ? $project->budgetTransactions()
                ->where('type', 'actual')
                ->orderByDesc('transaction_date')
                ->orderByDesc('transaction_id')
                ->get()
            : collect();
        $monthlyExpenditure = $transactions
            ->groupBy(static fn (BudgetTransaction $transaction) => $transaction->transaction_date?->format('Y-m')
                ?? $transaction->created_at?->format('Y-m')
                ?? 'undated')
            ->map(static fn ($monthTransactions) => $monthTransactions
                ->groupBy(static fn (BudgetTransaction $transaction) => in_array($transaction->category, BudgetTransaction::CATEGORIES, true)
                    ? $transaction->category
                    : 'Unclassified')
                ->map(static fn ($categoryTransactions) => [
                    'amount' => (float) $categoryTransactions->sum('amount'),
                    'entries' => $categoryTransactions->count(),
                ]))
            ->sortKeysDesc();
        $categorizedTotal = (float) $transactions->sum('amount');
        $unclassifiedLegacyExpenditure = max(0, (float) ($project->actual_budget ?? 0) - $categorizedTotal);
        $totalExpenditure = $project->actual_budget_total;

        return view('public.expenditure-project', compact(
            'project',
            'trackingAvailable',
            'monthlyExpenditure',
            'unclassifiedLegacyExpenditure',
            'totalExpenditure'
        ));
    }
}
