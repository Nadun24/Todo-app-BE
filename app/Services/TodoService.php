<?php

use App\Jobs\LogTodoStatusChange;
use App\Models\Todo;

class TodoService
{
    public function getAllTodos($user, array $filters)
    {
        $query = $user->todos()->latest();

        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        if (!empty($filters['status'])) {
            $query->ofStatus($filters['status']);
        }

        if (!empty($filters['priority'])) {
            $query->ofPriority($filters['priority']);
        }

        return $query->get();
    }

    public function createTodo($user, array $data)
    {
        $todo = $user->todos()->create([
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
            'priority'    => $data['priority'] ?? 'medium',
            'due_date'    => $data['due_date'] ?? null,
            'status'      => 'pending',
        ]);

        LogTodoStatusChange::dispatch($todo->id, $user->id, null, 'pending');

        return $todo;
    }

    public function getTodoById($user, string $id)
    {
        return $user->todos()->findOrFail($id);
    }

    public function updateTodo($user, string $id, array $data)
    {
        $todo = $user->todos()->findOrFail($id);
        $todo->update($data);
        return $todo->fresh();
    }

    public function deleteTodo($user, string $id)
    {
        $todo = $user->todos()->findOrFail($id);
        $todo->delete();
    }

    public function markComplete($user, string $id)
    {
        $todo = $user->todos()->findOrFail($id);
        $todo->update(['status' => 'completed', 'completed_at' => now()]);

        LogTodoStatusChange::dispatch($todo->id, $user->id, 'pending', 'completed');

        return $todo->fresh();
    }

    public function markPending($user, string $id)
    {
        $todo = $user->todos()->findOrFail($id);
        $todo->update(['status' => 'pending', 'completed_at' => null]);

        LogTodoStatusChange::dispatch($todo->id, $user->id, 'completed', 'pending');

        return $todo->fresh();
    }
}
