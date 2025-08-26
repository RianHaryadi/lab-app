<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ScheduleExchange;
use App\Models\PublicScheduleExchange;

class ScheduleExchangeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $exchange;
    protected $type;

    public function __construct($exchange, string $type)
    {
        $this->exchange = $exchange;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $message = $this->getMessageForType();
        return (new MailMessage)
            ->subject('Pemberitahuan Pertukaran Jadwal')
            ->line($message)
            ->line('Jadwal: ' . ($this->exchange->schedule->title ?? 'Jadwal Tidak Dikenal'))
            ->line('Tanggal: ' . ($this->exchange->schedule->date ?? 'Tanggal Tidak Dikenal'))
            ->action('Lihat Dashboard', url('/dashboard'));
    }

    public function toArray($notifiable)
    {
        $baseData = [
            'type' => $this->type,
            'exchange_id' => $this->exchange->id,
        ];

        \Log::info('Generating notification data', [
            'exchange_type' => get_class($this->exchange),
            'exchange_id' => $this->exchange->id,
            'from_user' => optional($this->exchange->fromUser)->name,
            'schedule_title' => optional($this->exchange->schedule)->title,
            'schedule_date' => optional($this->exchange->schedule)->date,
        ]);

        if ($this->exchange instanceof ScheduleExchange) {
            return array_merge($baseData, [
                'from_user' => $this->exchange->fromUser->name,
                'schedule_title' => $this->exchange->schedule->title,
                'schedule_date' => $this->exchange->schedule->date,
                'message' => $this->getMessageForType(),
            ]);
        } elseif ($this->exchange instanceof PublicScheduleExchange) {
            return array_merge($baseData, [
                'from_user' => $this->exchange->fromUser->name,
                'schedule_title' => $this->exchange->schedule->title,
                'schedule_date' => $this->exchange->schedule->date,
                'message' => $this->getMessageForType(),
            ]);
        }

        return $baseData;
    }

    private function getMessageForType()
    {
        return match ($this->type) {
            'request' => 'Permintaan pertukaran jadwal baru diterima',
            'public_request' => 'Permintaan pertukaran jadwal publik baru diposting',
            'approved' => 'Permintaan pertukaran jadwal disetujui',
            'rejected' => 'Permintaan pertukaran jadwal ditolak',
            default => 'Jenis notifikasi tidak dikenal',
        };
    }
}