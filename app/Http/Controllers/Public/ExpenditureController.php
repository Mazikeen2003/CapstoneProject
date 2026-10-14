<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BudgetTransaction;
use App\Models\Scopes\RoleScopedScope;
use App\Services\PortalVisitService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExpenditureController extends Controller
{
    public function index(Request $request)
    {
        PortalVisitService::logVisit('expenditures');

        $filters = $request->validate([
            'category' => ['nullable', Rule::in([...BudgetTransaction::CATEGORIES, 'unclassified'])],
            'q' => ['nullable', 'string', 'max:100'],
        ]);
        $category = $filters['category'] ?? null;
        $search = trim($filters['q'] ?? '');
        $trackingAvailable = BudgetTransaction::supportsCategoryTracking();
        $transactions = null;
        $transactionCount = 0;
        $totalExpenditure = 0.0;

        if ($trackingAvailable) {
            $baseQuery = BudgetTransaction::query()
                ->where('type', 'actual')
                ->whereHas('project', static fn (Builder $projects) => $projects->withoutGlobalScope(RoleScopedScope::class));

            $transactionCount = (clone $baseQuery)->count();
            $totalExpenditure = (float) (clone $baseQuery)->sum('amount');

            $query = (clone $baseQuery)
                ->with([
                    'project' => static fn (Builder $projects) => $projects
                        ->withoutGlobalScope(RoleScopedScope::class)
                        ->with('barangay'),
                ]);

            if ($category === 'unclassified') {
                $query->where(static function (Builder $transactions): void {
                    $transactions->whereNull('category')
                        ->orWhereNotIn('category', BudgetTransaction::CATEGORIES);
                });
            } elseif ($category !== null) {
                $query->where('category', $category);
            }

            if ($search !== '') {
                $query->whereHas('project', static function (Builder $projects) use ($search): void {
                    $projects->withoutGlobalScope(RoleScopedScope::class)
                        ->where(static function (Builder $projectQuery) use ($search): void {
                            $projectQuery->where('project_name', 'like', "%{$search}%")
                                ->orWhere('project_code', 'like', "%{$search}%")
                                ->orWhereHas('barangay', static fn (Builder $barangays) => $barangays
                                    ->where('barangay_name', 'like', "%{$search}%"));
                        });
                });
            }

            $transactions = $query
                ->orderByDesc('transaction_date')
                ->orderByDesc('transaction_id')
                ->paginate(25)
                ->withQueryString();
        }

        return view('public.expenditures', compact(
            'transactions',
            'transactionCount',
            'totalExpenditure',
            'trackingAvailable',
            'category',
            'search'
        ));
    }
}
