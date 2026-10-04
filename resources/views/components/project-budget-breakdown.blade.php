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

<style>
    .project-budget-breakdown {
        border: 0 !important;
        box-shadow: none !important;
    }
    .budget-submit-button {
        border: 0;
        border-radius: 10px;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: #fff;
        box-shadow: 0 4px 14px -4px rgba(245, 158, 11, 0.5);
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .budget-submit-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 20px -4px rgba(245, 158, 11, 0.6);
    }
    html.dark-mode .project-budget-breakdown,
    .dark .project-budget-breakdown {
        background: #141321 !important;
        color: #f8fafc;
    }
    html.dark-mode .project-budget-breakdown .budget-breakdown-title,
    .dark .project-budget-breakdown .budget-breakdown-title,
    html.dark-mode .project-budget-breakdown .budget-section-title,
    .dark .project-budget-breakdown .budget-section-title,
    html.dark-mode .project-budget-breakdown .budget-table-heading,
    .dark .project-budget-breakdown .budget-table-heading,
    html.dark-mode .project-budget-breakdown .budget-table-total,
    .dark .project-budget-breakdown .budget-table-total {
        color: #f8fafc !important;
    }
    html.dark-mode .project-budget-breakdown .budget-section-description,
    .dark .project-budget-breakdown .budget-section-description,
    html.dark-mode .project-budget-breakdown .budget-form label,
    .dark .project-budget-breakdown .budget-form label,
    html.dark-mode .project-budget-breakdown .budget-empty,
    .dark .project-budget-breakdown .budget-empty,
    html.dark-mode .project-budget-breakdown .budget-table-headings,
    .dark .project-budget-breakdown .budget-table-headings {
        color: #cbd5e1 !important;
    }
    html.dark-mode .project-budget-breakdown input,
    html.dark-mode .project-budget-breakdown select,
    .dark .project-budget-breakdown input,
    .dark .project-budget-breakdown select {
        background-color: #222136 !important;
        border-color: rgba(255, 255, 255, 0.12) !important;
        color: #f8fafc !important;
        color-scheme: dark;
    }
    html.dark-mode .project-budget-breakdown .budget-form,
    .dark .project-budget-breakdown .budget-form {
        border-color: rgba(255, 255, 255, 0.12) !important;
    }
    html.dark-mode .project-budget-breakdown .budget-table-row,
    .dark .project-budget-breakdown .budget-table-row,
    html.dark-mode .project-budget-breakdown .budget-transactions-table tr,
    .dark .project-budget-breakdown .budget-transactions-table tr {
        border-color: rgba(255, 255, 255, 0.12) !important;
    }
    html.dark-mode .project-budget-breakdown .budget-table-row:not(.bg-red-50),
    .dark .project-budget-breakdown .budget-table-row:not(.bg-red-50) {
        background: transparent;
        color: #cbd5e1;
    }
    html.dark-mode .project-budget-breakdown .budget-table-row.bg-red-50,
    .dark .project-budget-breakdown .budget-table-row.bg-red-50 {
        background: rgba(127, 29, 29, .3) !important;
        color: #fecaca !important;
    }
    html.dark-mode .project-budget-breakdown .budget-transaction-card,
    .dark .project-budget-breakdown .budget-transaction-card {
        border-color: rgba(255, 255, 255, 0.12) !important;
        background: #141321;
    }
    html.dark-mode .project-budget-breakdown .budget-transaction-heading,
    .dark .project-budget-breakdown .budget-transaction-heading {
        background: #222136 !important;
        border-color: rgba(255, 255, 255, 0.12) !important;
        color: #f8fafc !important;
    }
</style>

<section class="project-budget-breakdown mt-6 rounded-lg bg-white p-5">
    <div class="mb-4">
        <h2 class="budget-breakdown-title text-lg font-bold" style="color: #0f1e3d;">Category Budget Breakdown</h2>
        <p class="budget-section-description mt-1 text-sm text-gray-600">Enter one planned and actual amount per category each month. Planned amounts are dated on the first day of the month, or the implementation start date in the first month; actual expenditure is dated on the last day.</p>
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
        @php
            $submittedPeriod = old('period');
            $budgetPeriod = is_string($submittedPeriod) && preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $submittedPeriod)
                ? $submittedPeriod
                : today()->format('Y-m');
            $budgetType = old('type', 'planned');
            $budgetPeriodStart = \Carbon\Carbon::createFromFormat('!Y-m', $budgetPeriod);
            $implementationStart = $project->start_date;
            $budgetEntryDate = $budgetType === 'planned' && $implementationStart && $implementationStart->isSameMonth($budgetPeriodStart)
                ? $implementationStart->toDateString()
                : ($budgetType === 'planned' ? $budgetPeriodStart->copy()->startOfMonth()->toDateString() : $budgetPeriodStart->copy()->endOfMonth()->toDateString());
        @endphp
        <form method="POST" action="{{ route('department.projects.budget-transactions.store', $project->project_id) }}" class="budget-form mb-6 grid gap-3 border-b border-gray-200 pb-6 sm:grid-cols-2">
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
                    <option value="planned" @selected($budgetType === 'planned')>Monthly planned amount</option>
                    <option value="actual" @selected(old('type') === 'actual')>Actual expenditure</option>
                </select>
                @error('type')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
            </div>
            <div class="min-w-0">
                <label for="budget-period" class="mb-1 block text-xs font-semibold text-gray-700">Month</label>
                <input id="budget-period" type="month" name="period" value="{{ $budgetPeriod }}" max="{{ today()->format('Y-m') }}" required class="w-full rounded-md border px-3 py-2 text-sm" style="border-color: #B2BEB5;">
                @error('period')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
            </div>
            <div class="min-w-0">
                <label for="budget-amount" class="mb-1 block text-xs font-semibold text-gray-700">Amount (PHP)</label>
                <input id="budget-amount" type="text" name="amount" inputmode="decimal" autocomplete="off" value="{{ old('amount') }}" required class="w-full rounded-md border px-3 py-2 text-sm" style="border-color: #B2BEB5;">
                @error('amount')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
            </div>
            <div class="min-w-0">
                <label for="budget-date" class="mb-1 block text-xs font-semibold text-gray-700">Fixed entry date</label>
                <input id="budget-date" type="date" value="{{ $budgetEntryDate }}" readonly aria-describedby="budget-date-help" class="w-full rounded-md border px-3 py-2 text-sm" style="border-color: #B2BEB5;">
                <p id="budget-date-help" class="mt-1 text-xs text-gray-500">Assigned automatically and cannot be changed.</p>
            </div>
            <div class="min-w-0 sm:col-span-2">
                <label for="budget-description" class="mb-1 block text-xs font-semibold text-gray-700">Description</label>
                <input id="budget-description" type="text" name="description" value="{{ old('description') }}" maxlength="2000" class="w-full rounded-md border px-3 py-2 text-sm" style="border-color: #B2BEB5;">
                @error('description')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
            </div>
            <div class="sm:col-span-2">
                <button type="submit" class="budget-submit-button rounded-lg px-4 py-2 text-sm font-semibold">Add budget entry</button>
            </div>
        </form>
    @elseif ($allowEntry ?? false)
        <p class="mb-6 border-b border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-900">Category budget entry is unavailable until the database migration is applied.</p>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full min-w-[560px] border-collapse text-left text-sm">
            <thead>
                <tr class="budget-table-headings border-b" style="border-color: #B2BEB5; color: #0f1e3d;">
                    <th class="px-3 py-2">Category</th>
                    <th class="px-3 py-2 text-right">Planned Amounts</th>
                    <th class="px-3 py-2 text-right">Actual Expenditure</th>
                    <th class="px-3 py-2 text-right">Variance</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categoryTotals as $category => $totals)
                    <tr class="budget-table-row border-b {{ $totals['variance'] < 0 ? 'bg-red-50 text-red-800' : '' }}" style="border-color: #e5e7eb;">
                        <th scope="row" class="px-3 py-2 font-medium">{{ $category }}</th>
                        <td class="px-3 py-2 text-right">₱{{ number_format($totals['planned'], 2) }}</td>
                        <td class="px-3 py-2 text-right">₱{{ number_format($totals['actual'], 2) }}</td>
                        <td class="px-3 py-2 text-right">₱{{ number_format($totals['variance'], 2) }}@if($totals['variance'] < 0) <span class="font-semibold">Over budget</span>@endif</td>
                    </tr>
                @endforeach
                <tr class="budget-table-total font-bold" style="color: #0f1e3d;">
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
        <h3 class="budget-section-title text-base font-bold" style="color: #0f1e3d;">Budget Transactions</h3>
        @foreach ($budgetCategories as $category)
            @php $categoryEntries = $budgetTransactions->where('category', $category)->whereIn('type', ['planned', 'actual'])->sortByDesc(fn ($transaction) => $transaction->transaction_date ?? $transaction->created_at); @endphp
            <div class="budget-transaction-card overflow-hidden rounded-md border" style="border-color: #B2BEB5;">
                <h4 class="budget-transaction-heading border-b px-3 py-2 text-sm font-semibold" style="background: #f8f7f5; color: #0f1e3d; border-color: #B2BEB5;">{{ $category }}</h4>
                @if ($categoryEntries->isEmpty())
                    <p class="budget-empty px-3 py-3 text-sm text-gray-500">No entries recorded.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="budget-transactions-table w-full min-w-[560px] text-left text-sm">
                            <thead class="budget-table-headings text-xs text-gray-600"><tr><th class="px-3 py-2">Date</th><th class="px-3 py-2">Type</th><th class="px-3 py-2 text-right">Amount</th><th class="px-3 py-2">Description</th></tr></thead>
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

<script>
    (() => {
        const amountInput = document.getElementById('budget-amount');
        const amountForm = amountInput?.form;
        if (!amountInput || !amountForm) return;
        const typeInput = document.getElementById('budget-type');
        const periodInput = document.getElementById('budget-period');
        const fixedDateInput = document.getElementById('budget-date');
        const implementationStartDate = @json($project->start_date?->format('Y-m-d'));

        function updateFixedDate() {
            if (!typeInput || !periodInput || !fixedDateInput || !periodInput.value) return;
            const [year, month] = periodInput.value.split('-').map(Number);
            const firstDay = new Date(year, month - 1, 1);
            const lastDay = new Date(year, month, 0);
            const startDate = implementationStartDate ? new Date(`${implementationStartDate}T00:00:00`) : null;
            const isImplementationStartMonth = startDate
                && startDate.getFullYear() === year
                && startDate.getMonth() === month - 1;
            const fixedDate = typeInput.value === 'planned'
                ? (isImplementationStartMonth ? startDate : firstDay)
                : lastDay;

            fixedDateInput.value = [
                fixedDate.getFullYear(),
                String(fixedDate.getMonth() + 1).padStart(2, '0'),
                String(fixedDate.getDate()).padStart(2, '0')
            ].join('-');
        }

        function formatAmountInput() {
            const value = amountInput.value;
            const cursor = amountInput.selectionStart ?? value.length;
            const prefix = value.slice(0, cursor).replace(/,/g, '');
            const digitsBeforeCursor = (prefix.match(/\d/g) || []).length;
            const decimalBeforeCursor = prefix.includes('.');
            const normalized = value.replace(/,/g, '').replace(/[^\d.]/g, '');
            const decimalIndex = normalized.indexOf('.');
            const integer = (decimalIndex === -1 ? normalized : normalized.slice(0, decimalIndex)).replace(/\./g, '');
            const decimal = decimalIndex === -1
                ? ''
                : normalized.slice(decimalIndex + 1).replace(/\./g, '').slice(0, 2);
            const groupedInteger = integer.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            const formatted = groupedInteger + (decimalIndex === -1 ? '' : `.${decimal}`);

            amountInput.value = formatted;

            let nextCursor = 0;
            let countedDigits = 0;
            while (nextCursor < formatted.length && countedDigits < digitsBeforeCursor) {
                if (/\d/.test(formatted[nextCursor])) countedDigits++;
                nextCursor++;
            }
            if (decimalBeforeCursor && decimalIndex !== -1 && nextCursor === groupedInteger.length) {
                nextCursor++;
            }

            amountInput.setSelectionRange(nextCursor, nextCursor);
        }

        amountInput.addEventListener('input', formatAmountInput);
        typeInput?.addEventListener('change', updateFixedDate);
        periodInput?.addEventListener('change', updateFixedDate);
        amountForm.addEventListener('submit', function() {
            amountInput.value = amountInput.value.replace(/,/g, '');
        });
        formatAmountInput();
        updateFixedDate();
    })();
</script>