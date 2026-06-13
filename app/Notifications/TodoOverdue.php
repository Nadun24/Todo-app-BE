<?php

namespace App\Notifications;

use App\Models\Todo;
use Illuminate\Notifications\Notification;

class TodoOverdue extends Notification
{
    public function __construct(protected Todo $todo) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'todo_id'  => $this->todo->id,
            'title'    => $this->todo->title,
            'due_date' => $this->todo->due_date->toDateString(),
            'type'     => 'overdue',
            'message'  => "Your todo \"{$this->todo->title}\" is overdue.",
        ];
    }
}
