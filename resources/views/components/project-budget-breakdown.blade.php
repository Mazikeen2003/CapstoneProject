@php
    $budgetCategories = \App\Models\BudgetTransaction::CATEGORIES;
    $budgetTransactions = $project->budgetTransactions;
    $plannedTransactions = $budgetTransactions->where('type', 'planned')->whereIn('category', $budgetCategories);
    $actualTransactions = $budgetTransactions->where('type', 'actual')->whereIn('category', $budgetCategories);
    $categoryTotals = collect($budgetCategories)->mapWithKeys(function ($category) use ($plannedTransactions, $actualTransactions) {
        $planned = (float) $plannedTransactions->where('category', $category)->sum('amount');
        $actual = (float) $actualTransactions->where('category', $category)->sum('amount');

        return [$category => ['planned' => $planned, 'actual' => $actual, 'variance' => $planned - $actual]];
    });
    $categoryPlannedTotal = (float) $categoryTotals->sum(fn ($totals) => $totals['planned'] ?? 0);
    $categoryActualTotal = (float) $categoryTotals->sum(fn ($totals) => $totals['actual'] ?? 0);
    $plannedTotal = $categoryPlannedTotal;
    $actualTotal = $categoryActualTotal;
    $unclassifiedActual = max(0, (float) $project->actual_budget_total - $categoryActualTotal);
    $budgetOverage = $actualTotal - $plannedTotal;
@endphp

<section class="rounded-lg bg-white p-5 shadow-sm" style="border: 1px solid #B2BEB5;">
    <div class="mb-4">
        <h2 class="text-lg font-bold" style="color: #0f1e3d;">Category Budget Breakdown</h2>
        <p class="mt-1 text-sm text-gray-600">Planned allocations and recorded expenditure by category.</p>
    </div>

    @if ($budgetOverage > 0)
        <div role="alert" class="mb-4 rounded-md border border-red-300 bg-red-50 px-4 py-3 text-sm font-semibold text-red-800">
            Actual expenditure exceeds the planned or approved budget by ₱{{ number_format($budgetOverage, 2) }}.
        </div>
    @endif

    @if (($allowEntry ?? false) && ($budgetTrackingAvailable ?? true))
        @if (session('budget_success'))
            <div role="status" class="mb-4 rounded-md border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('budget_success') }}</div>
        @endif
        <form method="POST" action="{{ route('department.projects.budget-transactions.store', $project->project_id) }}" class="mb-6 grid gap-3 border-b border-gray-200 pb-6 sm:grid-cols-2">
            @csrf
            <div class="min-w-0">
                <label for="budget-category" class="mb-1 block text-xs font-semibold text-gray-700">Category</label>
                <select id="budget-category" name="category" required class="w-full rounded-md border px-3 py-2 text-sm" style="border-color: #B2BEB5;">
                    @foreach ($budgetCategories as $category)
                        <option value="{{ $category }}" @selected(old('category') === $category)>{{ $category }}</option>
                    @endforeach
                </select>
                @error('category')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
            </div>
            <div class="min-w-0">
                <label for="budget-type" class="mb-1 block text-xs font-semibold text-gray-700">Entry type</label>
                <select id="budget-type" name="type" required class="w-full rounded-md border px-3 py-2 text-sm" style="border-color: #B2BEB5;">
                    <option value="planned" @selected(old('type', 'planned') === 'planned')>Planned</option>
                    <option value="actual" @selected(old('type') === 'actual')>Actual expenditure</option>
                </select>
                @error('type')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
            </div>
            <div class="min-w-0">
                <label for="budget-amount" class="mb-1 block text-xs font-semibold text-gray-700">Amount (PHP)</label>
                <input id="budget-amount" type="number" name="amount" min="0.01" step="0.01" value="{{ old('amount') }}" required class="w-full rounded-md border px-3 py-2 text-sm" style="border-color: #B2BEB5;">
                @error('amount')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
            </div>
            <div class="min-w-0">
                <label for="budget-date" class="mb-1 block text-xs font-semibold text-gray-700">Date</label>
                <input id="budget-date" type="date" name="transaction_date" value="{{ old('transaction_date', today()->toDateString()) }}" required class="w-full rounded-md border px-3 py-2 text-sm" style="border-color: #B2BEB5;">
                @error('transaction_date')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
            </div>
            <div class="min-w-0 sm:col-span-2">
                <label for="budget-description" class="mb-1 block text-xs font-semibold text-gray-700">Description</label>
                <input id="budget-description" type="text" name="description" value="{{ old('description') }}" maxlength="2000" class="w-full rounded-md border px-3 py-2 text-sm" style="border-color: #B2BEB5;">
                @error('description')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
            </div>
            <div class="sm:col-span-2">
                <button type="submit" class="rounded-md px-4 py-2 text-sm font-semibold" style="background: #c9a84c; color: #0f1e3d;">Add budget entry</button>
            </div>
        </form>
    @elseif ($allowEntry ?? false)
        <p class="mb-6 border-b border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-900">Category budget entry is unavailable until the database migration is applied.</p>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full min-w-[560px] border-collapse text-left text-sm">
            <thead>
                <tr class="border-b" style="border-color: #B2BEB5; color: #0f1e3d;">
                    <th class="px-3 py-2">Category</th>
                    <th class="px-3 py-2 text-right">Planned Amount</th>
                    <th class="px-3 py-2 text-right">Actual Spent</th>
                    <th class="px-3 py-2 text-right">Variance</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categoryTotals as $category => $totals)
                    <tr class="border-b {{ $totals['variance'] < 0 ? 'bg-red-50 text-red-800' : '' }}" style="border-color: #e5e7eb;">
                        <th scope="row" class="px-3 py-2 font-medium">{{ $category }}</th>
                        <td class="px-3 py-2 text-right">₱{{ number_format($totals['planned'], 2) }}</td>
                        <td class="px-3 py-2 text-right">₱{{ number_format($totals['actual'], 2) }}</td>
                        <td class="px-3 py-2 text-right">₱{{ number_format($totals['variance'], 2) }}@if($totals['variance'] < 0) <span class="font-semibold">Over budget</span>@endif</td>
                    </tr>
                @endforeach
                <tr class="font-bold" style="color: #0f1e3d;">
                    <th scope="row" class="px-3 py-2">Grand Total</th>
                    <td class="px-3 py-2 text-right">₱{{ number_format($plannedTotal, 2) }}</td>
                    <td class="px-3 py-2 text-right">₱{{ number_format($actualTotal, 2) }}</td>
                    <td class="px-3 py-2 text-right">₱{{ number_format($plannedTotal - $actualTotal, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    @if ($unclassifiedActual > 0)
        <p class="mt-2 text-xs text-gray-600">Previously recorded expenditure not assigned to a category: ₱{{ number_format($unclassifiedActual, 2) }}</p>
    @endif

    <div class="mt-6 space-y-4">
        <h3 class="text-base font-bold" style="color: #0f1e3d;">Budget Transactions</h3>
        @foreach ($budgetCategories as $category)
            @php $categoryEntries = $budgetTransactions->where('category', $category)->whereIn('type', ['planned', 'actual'])->sortByDesc(fn ($transaction) => $transaction->transaction_date ?? $transaction->created_at); @endphp
            <div class="overflow-hidden rounded-md border" style="border-color: #B2BEB5;">
                <h4 class="border-b px-3 py-2 text-sm font-semibold" style="background: #f8f7f5; color: #0f1e3d; border-color: #B2BEB5;">{{ $category }}</h4>
                @if ($categoryEntries->isEmpty())
                    <p class="px-3 py-3 text-sm text-gray-500">No entries recorded.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[560px] text-left text-sm">
                            <thead class="text-xs text-gray-600"><tr><th class="px-3 py-2">Date</th><th class="px-3 py-2">Type</th><th class="px-3 py-2 text-right">Amount</th><th class="px-3 py-2">Description</th></tr></thead>
                            <tbody>
                                @foreach ($categoryEntries as $entry)
                                    <tr class="border-t" style="border-color: #e5e7eb;">
                                        <td class="px-3 py-2">{{ ($entry->transaction_date ?? $entry->created_at)?->format('M d, Y') ?? '—' }}</td>
                                        <td class="px-3 py-2">{{ ucfirst($entry->type) }}</td>
                                        <td class="px-3 py-2 text-right">₱{{ number_format((float) $entry->amount, 2) }}</td>
                                        <td class="px-3 py-2">{{ $entry->description ?: '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</section>