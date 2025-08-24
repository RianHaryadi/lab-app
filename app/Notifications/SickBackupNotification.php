<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
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
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $mailMessage = new MailMessage();

        switch ($this->type) {
            case 'request':
                return $mailMessage
                    ->subject('Backup Coverage Needed - Team Member Sick')
                    ->line('A team member needs backup coverage due to illness.')
                    ->line('Team Member: ' . $this->backupRequest->sickUser->name)
                    ->line('Schedule: ' . $this->backupRequest->originalSchedule->title)
                    ->line('Date: ' . $this->backupRequest->date->format('M d, Y'))
                    ->line('Reason: ' . $this->backupRequest->reason)
                    ->action('Offer Backup', url('/dashboard'))
                    ->line('Your help would be greatly appreciated!');

            case 'accepted':
                return $mailMessage
                    ->subject('Backup Coverage Confirmed')
                    ->line('Great news! Someone has offered to cover your shift.')
                    ->line('Backup by: ' . $this->backupRequest->backupUser->name)
                    ->line('Schedule: ' . $this->backupRequest->originalSchedule->title)
                    ->line('Date: ' . $this->backupRequest->date->format('M d, Y'))
                    ->action('View Details', url('/dashboard'))
                    ->line('Get well soon!');

            default:
                return $mailMessage
                    ->subject('Sick Leave Update')
                    ->line('There has been an update to a sick leave request.')
                    ->action('View Dashboard', url('/dashboard'));
        }
    }

    public function toArray($notifiable)
    {
        return [
            'type' => $this->type,
            'backup_request_id' => $this->backupRequest->id,
            'sick_user' => $this->backupRequest->sickUser->name,
            'schedule_title' => $this->backupRequest->originalSchedule->title,
            'date' => $this->backupRequest->date,
            'reason' => $this->backupRequest->reason,
            'backup_user' => $this->backupRequest->backupUser ? $this->backupRequest->backupUser->name : null,
            'message' => $this->getMessageForType()
        ];
    }

    private function getMessageForType()
    {
        switch ($this->type) {
            case 'request':
                return 'Backup coverage needed';
            case 'accepted':
                return 'Backup coverage confirmed';
            default:
                return 'Sick leave update';
        }
    }
}