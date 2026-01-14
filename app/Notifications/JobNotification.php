<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class JobNotification extends Notification
{
    use Queueable;

    private $details;

    public function __construct($details)
    {
        $this->details = $details;
    }

    public function via($notifiable)
    {
        return ['database']; // শুধু ডাটাবেসে সেভ হবে
    }

    public function toArray($notifiable)
    {
        return [
            'title' => $this->details['title'],
            'message' => $this->details['message'],
            'link' => $this->details['link'] ?? '#',
            'type' => $this->details['type'] ?? 'info', // e.g., 'success', 'warning'
        ];
    }
}