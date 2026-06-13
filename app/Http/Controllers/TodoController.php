<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use ErrorLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use ResponseHelper;
use TodoService;

class TodoController extends Controller
{
    protected $todoService;

    public function __construct(TodoService $todoService)
    {
        $this->todoService = $todoService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $todos = $this->todoService->getAllTodos(
                $request->user(),
                $request->only(['search', 'status', 'priority'])
            );

            return ResponseHelper::success($todos, 'Todos retrieved successfully');
        } catch (\Exception $e) {
            ErrorLogger::logError($e);
            return ResponseHelper::error(null, 'Failed to retrieve todos', 500);
        }
    }

    public function store(StoreTodoRequest $request): JsonResponse
    {
        try {
            $todo = $this->todoService->createTodo($request->user(), $request->validated());

            return ResponseHelper::success($todo, 'Todo created successfully', 201);
        } catch (\Exception $e) {
            ErrorLogger::logError($e);
            return ResponseHelper::error(null, 'Failed to create todo', 500);
        }
    }

    public function show(Request $request, string $id): JsonResponse
    {
        try {
            $todo = $this->todoService->getTodoById($request->user(), $id);

            return ResponseHelper::success($todo, 'Todo retrieved successfully');
        } catch (\Exception $e) {
            ErrorLogger::logError($e);
            return ResponseHelper::error(null, 'Todo not found', 404);
        }
    }

    public function update(UpdateTodoRequest $request, string $id): JsonResponse
    {
        try {
            $todo = $this->todoService->updateTodo($request->user(), $id, $request->validated());

            return ResponseHelper::success($todo, 'Todo updated successfully');
        } catch (\Exception $e) {
            ErrorLogger::logError($e);
            return ResponseHelper::error(null, 'Failed to update todo', 500);
        }
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        try {
            $this->todoService->deleteTodo($request->user(), $id);

            return ResponseHelper::success(null, 'Todo deleted successfully');
        } catch (\Exception $e) {
            ErrorLogger::logError($e);
            return ResponseHelper::error(null, 'Failed to delete todo', 500);
        }
    }

    public function markComplete(Request $request, string $id): JsonResponse
    {
        try {
            $todo = $this->todoService->markComplete($request->user(), $id);

            return ResponseHelper::success($todo, 'Todo marked as completed');
        } catch (\Exception $e) {
            ErrorLogger::logError($e);
            return ResponseHelper::error(null, 'Failed to update todo', 500);
        }
    }

    public function markPending(Request $request, string $id): JsonResponse
    {
        try {
            $todo = $this->todoService->markPending($request->user(), $id);

            return ResponseHelper::success($todo, 'Todo marked as pending');
        } catch (\Exception $e) {
            ErrorLogger::logError($e);
            return ResponseHelper::error(null, 'Failed to update todo', 500);
        }
    }
}
