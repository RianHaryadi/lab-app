<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\ScheduleExchange;

class ScheduleExchangeNotification extends Notification
{
    use Queueable;

    protected $exchange;
    protected $type;

    public function __construct(ScheduleExchange $exchange, $type)
    {
        $this->exchange = $exchange;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['database']; // Adjust based on your notification channels
    }

    public function toArray($notifiable)
    {
        $message = match ($this->type) {
            'request' => 'You have a new schedule exchange request.',
            'approved' => 'Your schedule exchange request has been approved.',
            'rejected' => 'Your schedule exchange request has been rejected.',
            'public_request' => 'A new public schedule exchange is available.',
            default => 'Schedule exchange notification.',
        };

        return [
            'message' => $message,
            'schedule_title' => optional($this->exchange->schedule)->title ?? 'Unknown Schedule',
            'schedule_date' => optional($this->exchange->schedule)->date ?? now()->toDateString(), // Ensure schedule_date is included
            'type' => $this->type,
        ];
    }
}