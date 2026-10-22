@php
    $budgetCategories = \App\Models\BudgetTransaction::CATEGORIES;
    $budgetTransactions = $project->budgetTransactions;
    $plannedPeriod = today()->addMonthNoOverflow()->format('Y-m');
    $actualPeriod = today()->subMonthNoOverflow()->format('Y-m');
    $plannedTransactions = $budgetTransactions->where('type', 'planned')
        ->whereIn('category', $budgetCategories)
        ->filter(fn ($transaction) => $transaction->transaction_date?->format('Y-m') === $plannedPeriod);
    $actualTransactions = $budgetTransactions->where('type', 'actual')
        ->whereIn('category', $budgetCategories)
        ->filter(fn ($transaction) => $transaction->transaction_date?->format('Y-m') === $actualPeriod);
    $categoryTotals = collect($budgetCategories)->mapWithKeys(function ($category) use ($plannedTransactions, $actualTransactions) {
        $planned = (float) $plannedTransactions->where('category', $category)->sum('amount');
        $actual = (float) $actualTransactions->where('category', $category)->sum('amount');

        return [$category => ['planned' => $planned, 'actual' => $actual]];
    });
    $categoryPlannedTotal = (float) $categoryTotals->sum(fn ($totals) => $totals['planned'] ?? 0);
    $categoryActualTotal = (float) $categoryTotals->sum(fn ($totals) => $totals['actual'] ?? 0);
    $plannedTotal = $categoryPlannedTotal;
    $actualTotal = $categoryActualTotal;
    $allCategorizedActual = (float) $budgetTransactions->where('type', 'actual')->whereIn('category', $budgetCategories)->sum('amount');
    $unclassifiedActual = max(0, (float) $project->actual_budget_total - $allCategorizedActual);
@endphp

<style>
    .project-budget-breakdown {
        border: 1px solid #e2e8f0 !important;
        border-radius: 1rem;
        box-shadow: 0 8px 24px rgb(15 23 42 / 0.06) !important;
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
        border-color: #334155 !important;
        background: #141321 !important;
        color: #f8fafc;
        box-shadow: 0 8px 24px rgb(2 6 23 / 0.28) !important;
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
    html.dark-mode .project-budget-breakdown .budget-transactions-table,
    .dark .project-budget-breakdown .budget-transactions-table { color: #cbd5e1; }
    html.dark-mode .project-budget-breakdown .budget-transactions-table thead,
    .dark .project-budget-breakdown .budget-transactions-table thead { color: #e2e8f0; }
    html.dark-mode .project-budget-breakdown .budget-transactions-table tr,
    .dark .project-budget-breakdown .budget-transactions-table tr { border-color: #334155 !important; }
    html.dark-mode .project-budget-breakdown .budget-section-description,
    .dark .project-budget-breakdown .budget-section-description,
    html.dark-mode .project-budget-breakdown .text-gray-600,
    .dark .project-budget-breakdown .text-gray-600 { color: #cbd5e1 !important; }
</style>

<section class="project-budget-breakdown mt-6 rounded-lg bg-white p-5">
    <div class="mb-4">
        <h2 class="budget-breakdown-title text-lg font-bold" style="color: #0f1e3d;">Category Budget Breakdown</h2>
        <p class="budget-section-description mt-1 text-sm text-gray-600">Plan future months in advance, then enter audited expenditure for the previous month during the 14th–15th entry window.</p>
    </div>

    @if (($allowEntry ?? false) && ($budgetTrackingAvailable ?? true))
        @if (session('budget_success'))
            <div role="status" class="mb-4 rounded-md border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('budget_success') }}</div>
        @endif
        @php
            $submittedPeriod = old('period');
            $oldPeriod = is_string($submittedPeriod) && preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $submittedPeriod)
                ? $submittedPeriod
                : null;
            $nextMonth = today()->addMonthNoOverflow()->startOfMonth();
            $previousMonth = today()->subMonthNoOverflow()->startOfMonth();
            $plannedPeriod = old('type') === 'planned' && $oldPeriod && $oldPeriod > today()->format('Y-m')
                ? $oldPeriod
                : $nextMonth->format('Y-m');
            $canEnterActual = in_array(today()->day, [14, 15], true);
        @endphp
        <div class="mb-6 grid gap-5 border-b border-gray-200 pb-6 lg:grid-cols-2">
            <section class="rounded-xl border border-gray-200 p-4">
                <h3 class="budget-section-title text-base font-bold" style="color: #0f1e3d;">Planned Budget</h3>
                <p class="budget-section-description mb-4 mt-1 text-sm text-gray-600">Set an estimate in advance for an upcoming month.</p>
                <form method="POST" action="{{ route('department.projects.budget-transactions.store', $project->project_id) }}" class="budget-form grid gap-3">
                    @csrf
                    <input type="hidden" name="type" value="planned">
                    <div class="min-w-0">
                        <label for="planned-budget-category" class="mb-1 block text-xs font-semibold text-gray-700">Category</label>
                        <select id="planned-budget-category" name="category" required class="w-full rounded-md border px-3 py-2 text-sm" style="border-color: #B2BEB5;">
                            @foreach ($budgetCategories as $category)
                                <option value="{{ $category }}" @selected(old('category') === $category)>{{ $category }}</option>
                            @endforeach
                        </select>
                        @error('category')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
                    </div>
                    <div class="min-w-0">
                        <label for="planned-budget-period" class="mb-1 block text-xs font-semibold text-gray-700">Planned month</label>
                        <input id="planned-budget-period" type="month" name="period" value="{{ $plannedPeriod }}" min="{{ $nextMonth->format('Y-m') }}" required class="w-full rounded-md border px-3 py-2 text-sm" style="border-color: #B2BEB5;">
                        @error('period')@if(old('type') === 'planned')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@endif @enderror
                    </div>
                    <div class="min-w-0">
                        <label for="planned-budget-amount" class="mb-1 block text-xs font-semibold text-gray-700">Planned amount (PHP)</label>
                        <input id="planned-budget-amount" type="text" name="amount" inputmode="decimal" autocomplete="off" value="{{ old('type') === 'planned' ? old('amount') : '' }}" required class="budget-amount-input w-full rounded-md border px-3 py-2 text-sm" style="border-color: #B2BEB5;">
                        @error('amount')@if(old('type') === 'planned')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@endif @enderror
                    </div>
                    <div class="min-w-0">
                        <label for="planned-budget-description" class="mb-1 block text-xs font-semibold text-gray-700">Description (optional)</label>
                        <input id="planned-budget-description" type="text" name="description" value="{{ old('type') === 'planned' ? old('description') : '' }}" maxlength="2000" class="w-full rounded-md border px-3 py-2 text-sm" style="border-color: #B2BEB5;">
                        @error('description')@if(old('type') === 'planned')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@endif @enderror
                    </div>
                    <button type="submit" class="budget-submit-button justify-self-start rounded-lg px-4 py-2 text-sm font-semibold">Save Planned Budget</button>
                </form>
            </section>

            <section class="rounded-xl border border-gray-200 p-4">
                <h3 class="budget-section-title text-base font-bold" style="color: #0f1e3d;">Audited Expenditure</h3>
                <p class="budget-section-description mb-4 mt-1 text-sm text-gray-600">
                    Enter the finalized, audited category total for {{ $previousMonth->format('F Y') }} on the 14th or 15th only. Costs should be tallied before entry.
                </p>
                @if ($canEnterActual)
                    <form method="POST" action="{{ route('department.projects.budget-transactions.store', $project->project_id) }}" class="budget-form grid gap-3">
                        @csrf
                        <input type="hidden" name="type" value="actual">
                        <input type="hidden" name="period" value="{{ $previousMonth->format('Y-m') }}">
                        <div class="min-w-0">
                            <label for="actual-budget-category" class="mb-1 block text-xs font-semibold text-gray-700">Category</label>
                            <select id="actual-budget-category" name="category" required class="w-full rounded-md border px-3 py-2 text-sm" style="border-color: #B2BEB5;">
                                @foreach ($budgetCategories as $category)
                                    <option value="{{ $category }}" @selected(old('type') === 'actual' && old('category') === $category)>{{ $category }}</option>
                                @endforeach
                            </select>
                            @error('category')@if(old('type') === 'actual')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@endif @enderror
                        </div>
                        <div class="min-w-0">
                            <label for="actual-budget-period-display" class="mb-1 block text-xs font-semibold text-gray-700">Expenditure month</label>
                            <input id="actual-budget-period-display" type="text" value="{{ $previousMonth->format('F Y') }}" readonly class="w-full rounded-md border bg-gray-50 px-3 py-2 text-sm" style="border-color: #B2BEB5;">
                            @error('period')@if(old('type') === 'actual')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@endif @enderror
                        </div>
                        <div class="min-w-0">
                            <label for="actual-budget-amount" class="mb-1 block text-xs font-semibold text-gray-700">Audited expenditure (PHP)</label>
                            <input id="actual-budget-amount" type="text" name="amount" inputmode="decimal" autocomplete="off" value="{{ old('type') === 'actual' ? old('amount') : '' }}" required class="budget-amount-input w-full rounded-md border px-3 py-2 text-sm" style="border-color: #B2BEB5;">
                            <p class="mt-1 text-xs text-gray-500">Enter one combined total for this category and month.</p>
                            @error('amount')@if(old('type') === 'actual')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@endif @enderror
                        </div>
                        <div class="min-w-0">
                            <label for="actual-budget-description" class="mb-1 block text-xs font-semibold text-gray-700">Description (optional)</label>
                            <input id="actual-budget-description" type="text" name="description" value="{{ old('type') === 'actual' ? old('description') : '' }}" maxlength="2000" class="w-full rounded-md border px-3 py-2 text-sm" style="border-color: #B2BEB5;">
                            @error('description')@if(old('type') === 'actual')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@endif @enderror
                        </div>
                        <label class="flex items-start gap-2 text-xs text-gray-700">
                            <input type="checkbox" name="audit_confirmed" value="1" required @checked(old('type') === 'actual' && old('audit_confirmed'))>
                            <span>I confirm these costs were audited and tallied for the selected month before entry.</span>
                        </label>
                        @error('audit_confirmed')<p class="text-xs text-red-700">{{ $message }}</p>@enderror
                        @error('type')<p class="text-xs text-red-700">{{ $message }}</p>@enderror
                        <button type="submit" class="budget-submit-button justify-self-start rounded-lg px-4 py-2 text-sm font-semibold">Save Audited Expenditure</button>
                    </form>
                @else
                    <p class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-3 text-sm text-amber-900">
                        Expenditure entry is available on the 14th and 15th of each month only. The entry will be for {{ $previousMonth->format('F Y') }}.
                    </p>
                @endif
            </section>
        </div>
    @elseif ($allowEntry ?? false)
        <p class="mb-6 border-b border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-900">Category budget entry is unavailable until the database migration is applied.</p>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full min-w-[560px] border-collapse text-left text-sm">
            <thead>
                <tr class="budget-table-headings border-b" style="border-color: #B2BEB5; color: #0f1e3d;">
                    <th class="px-3 py-2">Category</th>
                    <th class="px-3 py-2 text-right">Planned · {{ today()->addMonthNoOverflow()->format('F Y') }}</th>
                    <th class="px-3 py-2 text-right">Audited Expenditure · {{ today()->subMonthNoOverflow()->format('F Y') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categoryTotals as $category => $totals)
                    <tr class="budget-table-row border-b" style="border-color: #e5e7eb;">
                        <th scope="row" class="px-3 py-2 font-medium">{{ $category }}</th>
                        <td class="px-3 py-2 text-right">₱{{ number_format($totals['planned'], 2) }}</td>
                        <td class="px-3 py-2 text-right">₱{{ number_format($totals['actual'], 2) }}</td>
                    </tr>
                @endforeach
                <tr class="budget-table-total font-bold" style="color: #0f1e3d;">
                    <th scope="row" class="px-3 py-2">Grand Total</th>
                    <td class="px-3 py-2 text-right">₱{{ number_format($plannedTotal, 2) }}</td>
                    <td class="px-3 py-2 text-right">₱{{ number_format($actualTotal, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    @if ($unclassifiedActual > 0)
        <p class="mt-2 text-xs text-gray-600">Previously recorded all-time expenditure not assigned to a category: ₱{{ number_format($unclassifiedActual, 2) }}</p>
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
                            <thead class="budget-table-headings text-xs text-gray-600"><tr><th class="px-3 py-2">Month</th><th class="px-3 py-2">Type</th><th class="px-3 py-2 text-right">Amount</th><th class="px-3 py-2">Audit confirmation</th><th class="px-3 py-2">Description</th></tr></thead>
                            <tbody>
                                @foreach ($categoryEntries as $entry)
                                    <tr class="border-t" style="border-color: #e5e7eb;">
                                        <td class="px-3 py-2">{{ ($entry->transaction_date ?? $entry->created_at)?->format('F Y') ?? '—' }}</td>
                                        <td class="px-3 py-2">{{ ucfirst($entry->type) }}</td>
                                        <td class="px-3 py-2 text-right">₱{{ number_format((float) $entry->amount, 2) }}</td>
                                        <td class="px-3 py-2">{{ $entry->type === 'actual' ? ($entry->audit_confirmed_at?->format('M d, Y H:i') ?? 'Legacy entry') : '—' }}</td>
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
        const amountInputs = document.querySelectorAll('.budget-amount-input');
        if (!amountInputs.length) return;

        function formatAmountInput(amountInput) {
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

        amountInputs.forEach((amountInput) => {
            amountInput.addEventListener('input', () => formatAmountInput(amountInput));
            amountInput.form?.addEventListener('submit', function() {
                amountInput.value = amountInput.value.replace(/,/g, '');
            });
            formatAmountInput(amountInput);
        });
    })();
</script>