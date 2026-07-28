<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GameVersionUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $gameName;
    public $newVersion;

    /**
     * Create a new notification instance.
     */
    public function __construct($gameName, $newVersion)
    {
        $this->gameName = $gameName;
        $this->newVersion = $newVersion;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Game Version Update',
            'message' => "A new update ({$this->newVersion}) has been released for {$this->gameName}. Check your modpacks for compatibility!",
            'type' => 'game_update',
            'icon' => 'fa-solid fa-cloud-arrow-down'
        ];
    }
}
