<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Models\Todo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = $request->user()->todos()->latest();

        if ($search = $request->query('search')) {
            $query->search($search);
        }

        if ($status = $request->query('status')) {
            $query->ofStatus($status);
        }

        if ($priority = $request->query('priority')) {
            $query->ofPriority($priority);
        }

        return response()->json([
            'success' => true,
            'data'    => $query->get(),
        ]);
    }

    public function store(StoreTodoRequest $request): JsonResponse
    {
        $todo = $request->user()->todos()->create([
            'title'       => $request->title,
            'description' => $request->description,
            'priority'    => $request->priority ?? 'medium',
            'due_date'    => $request->due_date,
            'status'      => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Todo created successfully.',
            'data'    => $todo,
        ]);
    }

    public function show(Request $request, string $id): JsonResponse
    {
        $todo = $request->user()->todos()->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $todo,
        ]);
    }

    public function update(UpdateTodoRequest $request, string $id): JsonResponse
    {
        $todo = $request->user()->todos()->findOrFail($id);

        $todo->update($request->only(['title', 'description', 'priority', 'due_date']));

        return response()->json([
            'success' => true,
            'message' => 'Todo updated successfully.',
            'data'    => $todo->fresh(),
        ]);
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $todo = $request->user()->todos()->findOrFail($id);
        $todo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Todo deleted successfully.',
        ]);
    }

    public function markComplete(Request $request, string $id): JsonResponse
    {
        $todo = $request->user()->todos()->findOrFail($id);

        $todo->update([
            'status'       => 'completed',
            'completed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Todo marked as completed.',
            'data'    => $todo->fresh(),
        ]);
    }

    public function markPending(Request $request, string $id): JsonResponse
    {
        $todo = $request->user()->todos()->findOrFail($id);

        $todo->update([
            'status'       => 'pending',
            'completed_at' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Todo marked as pending.',
            'data'    => $todo->fresh(),
        ]);
    }
}
