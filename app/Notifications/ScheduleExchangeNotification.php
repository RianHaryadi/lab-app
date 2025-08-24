<?php

// App/Notifications/ScheduleExchangeNotification.php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ScheduleExchange;
use App\Models\PublicScheduleExchange;

class ScheduleExchangeNotification extends Notification
{
    use Queueable;

    protected $exchange;
    protected $type;

    public function __construct($exchange, $type)
    {
        $this->exchange = $exchange;
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
                    ->subject('New Schedule Exchange Request')
                    ->line('You have received a new schedule exchange request.')
                    ->line('From: ' . $this->exchange->fromUser->name)
                    ->line('Schedule: ' . $this->exchange->schedule->title)
                    ->line('Date: ' . $this->exchange->schedule->date->format('M d, Y'))
                    ->action('View Request', url('/dashboard'))
                    ->line('Please review and respond to this request.');

            case 'approved':
                return $mailMessage
                    ->subject('Schedule Exchange Approved')
                    ->line('Your schedule exchange request has been approved!')
                    ->line('The schedules have been successfully swapped.')
                    ->action('View Schedule', url('/dashboard'));

            case 'rejected':
                return $mailMessage
                    ->subject('Schedule Exchange Declined')
                    ->line('Your schedule exchange request has been declined.')
                    ->line('You can try requesting exchange with other schedules.')
                    ->action('View Dashboard', url('/dashboard'));

            case 'interest':
                return $mailMessage
                    ->subject('Interest in Your Public Exchange')
                    ->line('Someone is interested in your public schedule exchange!')
                    ->line('From: ' . $this->exchange->fromUser->name)
                    ->action('View Dashboard', url('/dashboard'))
                    ->line('Contact them to finalize the exchange details.');

            case 'public_request':
                return $mailMessage
                    ->subject('New Public Schedule Exchange Available')
                    ->line('A team member has posted a schedule for public exchange.')
                    ->line('From: ' . $this->exchange->fromUser->name)
                    ->line('Schedule: ' . $this->exchange->schedule->title)
                    ->action('View Exchanges', url('/dashboard'))
                    ->line('Express interest if you want to exchange schedules.');

            default:
                return $mailMessage
                    ->subject('Schedule Exchange Notification')
                    ->line('There has been an update to a schedule exchange.')
                    ->action('View Dashboard', url('/dashboard'));
        }
    }

    public function toArray($notifiable)
    {
        $baseData = [
            'type' => $this->type,
            'exchange_id' => $this->exchange->id,
        ];

        if ($this->exchange instanceof ScheduleExchange) {
            return array_merge($baseData, [
                'from_user' => $this->exchange->fromUser->name,
                'schedule_title' => $this->exchange->schedule->title,
                'schedule_date' => $this->exchange->schedule->date,
                'message' => $this->getMessageForType()
            ]);
        } elseif ($this->exchange instanceof PublicScheduleExchange) {
            return array_merge($baseData, [
                'from_user' => $this->exchange->fromUser->name,
                'schedule_title' => $this->exchange->schedule->title,
                'schedule_date' => $this->exchange->schedule->date,
                'message' => $this->getMessageForType()
            ]);
        }

        return $baseData;
    }

    private function getMessageForType()
    {
        switch ($this->type) {
            case 'request':
                return 'New exchange request received';
            case 'approved':
                return 'Exchange request approved';
            case 'rejected':
                return 'Exchange request declined';
            case 'interest':
                return 'Someone is interested in your public exchange';
            case 'public_request':
                return 'New public exchange available';
            default:
                return 'Exchange update';
        }
    }
}