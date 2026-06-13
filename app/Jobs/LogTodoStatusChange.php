<?php

namespace App\Jobs;

use App\Models\TodoStatusLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class LogTodoStatusChange implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private string $todoId,
        private int $userId,
        private ?string $fromStatus,
        private string $toStatus,
    ) {}

    public function handle(): void
    {
        TodoStatusLog::create([
            'todo_id'     => $this->todoId,
            'user_id'     => $this->userId,
            'from_status' => $this->fromStatus,
            'to_status'   => $this->toStatus,
        ]);
    }
}
