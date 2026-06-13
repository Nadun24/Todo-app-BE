<?php

class NotificationService
{
    public function getAll($user)
    {
        return $user->notifications()->latest()->get()->map(function ($notification) {
            return [
                'id'         => $notification->id,
                'message'    => $notification->data['message'],
                'type'       => $notification->data['type'],
                'read_at'    => $notification->read_at,
                'created_at' => $notification->created_at,
            ];
        });
    }

    public function getUnreadCount($user): int
    {
        return $user->unreadNotifications()->count();
    }

    public function markRead($user, string $id)
    {
        $notification = $user->notifications()->findOrFail($id);
        $notification->markAsRead();
        return $notification->fresh();
    }

    public function markAllRead($user): void
    {
        $user->unreadNotifications()->update(['read_at' => now()]);
    }

    public function delete($user, string $id): void
    {
        $user->notifications()->findOrFail($id)->delete();
    }
}
