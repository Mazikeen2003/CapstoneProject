<?php

namespace App\Console\Commands;

use App\Models\BudgetTransaction;
use App\Models\Project;
use App\Models\User;
use App\Notifications\MonthlyBudgetReminder;
use Illuminate\Console\Command;

class SendMonthlyBudgetReminders extends Command
{
    protected $signature = 'budget:send-monthly-reminders';

    protected $description = 'Notify planning personnel when monthly budget entries are due';

    public function handle(): int
    {
        $today = today();
        $isPlannedReminderDay = $today->day === 1;
        $isActualReminderDay = $today->isLastOfMonth();

        if (! $isPlannedReminderDay && ! $isActualReminderDay) {
            return self::SUCCESS;
        }

        $projects = Project::query()
            ->where(function ($query) {
                $query->where('lifecycle_stage', 5)
                    ->orWhereIn('current_status', ['Implementation', 'On Going']);
            })
            ->whereNotIn('current_status', ['Completed', 'Cancelled', 'On Hold'])
            ->whereDate('start_date', '<=', $today)
            ->with('budgetTransactions')
            ->get();
        $personnel = User::query()
            ->where('role_id', 3)
            ->where('is_disabled', false)
            ->get();

        foreach ($projects as $project) {
            $implementationStart = $project->start_date?->copy()->startOfDay();
            $isImplementationStartReminderDay = $implementationStart?->isSameDay($today) ?? false;
            $periodKey = $today->format('Y-m');

            if ($isPlannedReminderDay || $isImplementationStartReminderDay) {
                $this->notifyMissingEntries($project, $personnel, 'planned', $periodKey);
            }

            if ($isActualReminderDay) {
                $this->notifyMissingEntries($project, $personnel, 'actual', $periodKey);
            }
        }

        return self::SUCCESS;
    }

    private function notifyMissingEntries(Project $project, $personnel, string $type, string $periodKey): void
    {
        $submittedCategories = $project->budgetTransactions
            ->where('type', $type)
            ->filter(fn (BudgetTransaction $transaction) => $transaction->transaction_date?->format('Y-m') === $periodKey)
            ->pluck('category')
            ->unique();
        $missingCategories = collect(BudgetTransaction::CATEGORIES)->diff($submittedCategories)->values();

        if ($missingCategories->isEmpty()) {
            return;
        }

        $entryType = $type === 'planned' ? 'planned amount' : 'actual expenditure';
        $dueDate = $type === 'planned'
            ? ($project->start_date?->format('Y-m-d') === today()->toDateString()
                ? 'today, the project implementation start date'
                : 'today, the first day of the month')
            : 'today, the last day of the month';
        $title = 'Monthly budget entry due';
        $message = sprintf(
            'Enter the monthly %s for %s (%s). Missing categories: %s.',
            $entryType,
            $project->project_name,
            $dueDate,
            $missingCategories->implode(', ')
        );
        $key = sprintf('budget-reminder:%s:%d:%s', $type, $project->project_id, $periodKey);

        foreach ($personnel as $user) {
            if ($user->notifications()->where('data->reminder_key', $key)->exists()) {
                continue;
            }

            $user->notify(new MonthlyBudgetReminder(
                $title,
                $message,
                route('department.projects.show', $project->project_id),
                $key
            ));
        }
    }
}
