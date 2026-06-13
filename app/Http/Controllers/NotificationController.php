<?php

namespace App\Http\Controllers;

use ErrorLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use NotificationService;
use ResponseHelper;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        try {
            $notifications = $this->notificationService->getAll($user);
            return ResponseHelper::success($notifications, 'Notifications retrieved successfully');
        } catch (\Exception $e) {
            ErrorLogger::logError($e);
            return ResponseHelper::error(null, 'Failed to retrieve notifications', 500);
        }
    }

    public function unreadCount(Request $request): JsonResponse
    {
        $user = auth()->user();
        try {
            $count = $this->notificationService->getUnreadCount($user);
            return ResponseHelper::success(['count' => $count], 'Unread count retrieved');
        } catch (\Exception $e) {
            ErrorLogger::logError($e);
            return ResponseHelper::error(null, 'Failed to retrieve unread count', 500);
        }
    }

    public function markRead(Request $request, string $id): JsonResponse
    {
        $user = auth()->user();
        try {
            $notification = $this->notificationService->markRead($user, $id);
            return ResponseHelper::success($notification, 'Notification marked as read');
        } catch (\Exception $e) {
            ErrorLogger::logError($e);
            return ResponseHelper::error(null, 'Failed to mark notification as read', 500);
        }
    }

    public function markAllRead(Request $request): JsonResponse
    {
        $user = auth()->user();
        try {
            $this->notificationService->markAllRead($user);
            return ResponseHelper::success(null, 'All notifications marked as read');
        } catch (\Exception $e) {
            ErrorLogger::logError($e);
            return ResponseHelper::error(null, 'Failed to mark notifications as read', 500);
        }
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $user = auth()->user();
        try {
            $this->notificationService->delete($user, $id);
            return ResponseHelper::success(null, 'Notification deleted successfully');
        } catch (\Exception $e) {
            ErrorLogger::logError($e);
            return ResponseHelper::error(null, 'Failed to delete notification', 500);
        }
    }
}
