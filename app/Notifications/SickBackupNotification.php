<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\SickBackupRequest;

class SickBackupNotification extends Notification
{
    use Queueable;

    protected $backupRequest;
    protected $type;

    public function __construct(SickBackupRequest $backupRequest, $type)
    {
        $this->backupRequest = $backupRequest;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['database']; // Adjust based on your notification channels (e.g., 'mail', 'database')
    }

    public function toArray($notifiable)
    {
        $message = $this->type === 'request'
            ? 'A team member has requested sick leave and needs backup for their schedule.'
            : 'Your sick leave backup request has been approved.';

        return [
            'message' => $message,
            'schedule_title' => optional($this->backupRequest->originalSchedule)->title ?? 'Unknown Schedule',
            'schedule_date' => $this->backupRequest->date ?? now()->toDateString(), // Ensure schedule_date is included
            'type' => $this->type,
        ];
    }
}