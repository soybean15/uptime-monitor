<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class Notifications extends Component
{
    use WithPagination;

    public function render()
    {
        $notifications = auth()->user()->notifications()->paginate(15);
        $unreadCount = auth()->user()->unreadNotifications->count();

        return view('livewire.notifications', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }

    public function markAsRead($notificationId)
    {
        $notification = auth()->user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->markAsRead();
        }
        $this->dispatch('notificationRead');
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        $this->dispatch('allNotificationsRead');
    }

    public function deleteNotification($notificationId)
    {
        auth()->user()->notifications()->find($notificationId)?->delete();
    }

    public function deleteAllNotifications()
    {
        auth()->user()->notifications()->delete();
    }
}
