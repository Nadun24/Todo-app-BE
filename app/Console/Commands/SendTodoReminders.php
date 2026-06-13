<?php

namespace App\Console\Commands;

use App\Models\Todo;
use App\Notifications\DueDateReminder;
use App\Notifications\TodoOverdue;
use Illuminate\Console\Command;

class SendTodoReminders extends Command
{
    protected $signature = 'todos:send-reminders {--test : Include todos due today for local testing}';
    protected $description = 'Send due-date and overdue notifications for pending todos';

    public function handle(): void
    {
        $this->sendDueSoonReminders();
        $this->sendOverdueReminders();
        $this->info('Todo reminders sent.');
    }

    private function sendDueSoonReminders(): void
    {
        Todo::with('user')
            ->where('status', 'pending')
            ->whereDate('due_date', today()->addDay())
            ->each(function (Todo $todo) {
                if ($this->notAlreadySent($todo, DueDateReminder::class)) {
                    $todo->user->notify(new DueDateReminder($todo));
                }
            });
    }

    private function sendOverdueReminders(): void
    {
        $operator = $this->option('test') ? '<=' : '<';

        Todo::with('user')
            ->where('status', 'pending')
            ->whereDate('due_date', $operator, today())
            ->each(function (Todo $todo) {
                if ($this->notAlreadySent($todo, TodoOverdue::class)) {
                    $todo->user->notify(new TodoOverdue($todo));
                }
            });
    }

    private function notAlreadySent(Todo $todo, string $notificationClass): bool
    {
        return $todo->user->notifications()
            ->where('type', $notificationClass)
            ->whereDate('created_at', today())
            ->whereRaw("(data::jsonb)->>'todo_id' = ?", [$todo->id])
            ->doesntExist();
    }
}
