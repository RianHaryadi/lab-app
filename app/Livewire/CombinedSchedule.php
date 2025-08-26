<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Schedule;
use App\Models\ScheduleExchange;
use App\Models\SickBackupRequest;
use App\Models\PublicScheduleExchange;
use App\Models\User;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Notifications\ScheduleExchangeNotification;
use App\Notifications\SickBackupNotification;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CombinedSchedule extends Component
{
    use WithPagination;

    public $targetItemId;
    public $swapScheduleId;
    public $sickScheduleId;
    public $sickReason = '';
    
    public $showSwapModal = false;
    public $showSickLeaveModal = false;
    public $availableItems;

    public $showExchangeRequests = false;
    public $showPublicExchanges = false;
    public $showBackupRequests = false;

    protected $rules = [
        'sickReason' => 'required|string|min:10|max:500',
        'targetItemId' => 'required|exists:schedules,id',
        'swapScheduleId' => 'required|exists:schedules,id',
    ];

    public function mount()
    {
        $this->availableItems = collect();
    }

    public function getItemsProperty(): LengthAwarePaginator
    {
        $userId = Auth::id();
        if (!$userId) {
            return new LengthAwarePaginator([], 0, 5, 1);
        }

        $combined = [];
        $userType = Auth::user()->user_type ?? null;

        if (!$this->showExchangeRequests && !$this->showPublicExchanges && !$this->showBackupRequests) {
            $schedules = Schedule::where(function ($query) use ($userId) {
                $query->where('created_by', $userId)
                    ->orWhere('user_id', $userId);
            })
            ->whereDate('date', '>=', now()->toDateString())
            ->with('creator')
            ->get();

            $schedulesArray = $schedules->map(fn($s) => [
                'id' => $s->id,
                'title' => $s->title,
                'description' => $s->description,
                'date' => $s->date,
                'start_time' => $s->start_time ?? null,
                'end_time' => $s->end_time ?? null,
                'user_name' => optional($s->creator)->name ?? 'Pengguna Tidak Dikenal',
                'type' => 'schedule',
                'status' => null,
                'is_owner' => $s->created_by === $userId,
                'is_assigned' => $s->user_id === $userId,
            ])->toArray();
            $combined = array_merge($combined, $schedulesArray);
        } elseif ($this->showExchangeRequests) {
            $exchanges = ScheduleExchange::where('to_user_id', $userId)
                ->where('status', 'pending')
                ->with('schedule', 'fromUser', 'targetSchedule')
                ->get()
                ->map(fn($e) => [
                    'id' => $e->id,
                    'title' => optional($e->schedule)->title ?? 'Jadwal Tidak Dikenal',
                    'description' => 'Permintaan langsung dari ' . optional($e->fromUser)->name,
                    'date' => optional($e->schedule)->date ?? now()->toDateString(),
                    'start_time' => optional($e->schedule)->start_time ?? null,
                    'end_time' => optional($e->schedule)->end_time ?? null,
                    'user_name' => optional($e->fromUser)->name ?? 'Pengguna Tidak Dikenal',
                    'type' => 'exchange',
                    'status' => $e->status,
                    'schedule_id' => $e->schedule_id,
                    'exchange_details' => [
                        'from_user' => optional($e->fromUser)->name,
                        'target_schedule' => optional($e->targetSchedule)->title ?? 'Jadwal tidak ditemukan'
                    ]
                ])->toArray();
            $combined = array_merge($combined, $exchanges);
        } elseif ($this->showPublicExchanges) {
            $publicExchanges = PublicScheduleExchange::where('status', 'pending')
                ->where('from_user_id', '!=', $userId)
                ->whereHas('fromUser', fn ($query) => $query->where('user_type', $userType))
                ->with('schedule', 'fromUser')
                ->get()
                ->map(fn($e) => [
                    'id' => $e->id,
                    'title' => optional($e->schedule)->title ?? 'Jadwal Tidak Dikenal',
                    'description' => 'Diposting oleh ' . optional($e->fromUser)->name,
                    'date' => optional($e->schedule)->date ?? now()->toDateString(),
                    'start_time' => optional($e->schedule)->start_time ?? null,
                    'end_time' => optional($e->schedule)->end_time ?? null,
                    'user_name' => optional($e->fromUser)->name ?? 'Pengguna Tidak Dikenal',
                    'type' => 'public_exchange',
                    'status' => $e->status,
                ])->toArray();
            $combined = array_merge($combined, $publicExchanges);
        } elseif ($this->showBackupRequests) {
            $backupRequests = SickBackupRequest::where('status', 'pending')
                ->where('sick_user_id', '!=', $userId)
                ->whereHas('sickUser', fn ($query) => $query->where('user_type', $userType))
                ->with('originalSchedule', 'sickUser')
                ->get()
                ->map(fn($r) => [
                    'id' => $r->id,
                    'title' => optional($r->originalSchedule)->title ?? 'Jadwal Tidak Dikenal',
                    'description' => 'Diperlukan cadangan - ' . optional($r->sickUser)->name . ' sedang sakit',
                    'date' => $r->date ?? now()->toDateString(),
                    'start_time' => optional($r->originalSchedule)->start_time ?? null,
                    'end_time' => optional($r->originalSchedule)->end_time ?? null,
                    'user_name' => optional($r->sickUser)->name ?? 'Pengguna Tidak Dikenal',
                    'type' => 'backup',
                    'status' => $r->status,
                    'reason' => $r->reason,
                ])->toArray();
            $combined = array_merge($combined, $backupRequests);
        }

        usort($combined, function ($a, $b) {
            return strcmp($a['date'] . ($a['start_time'] ?? '00:00:00'), $b['date'] . ($b['start_time'] ?? '00:00:00'));
        });

        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 5;
        $offset = ($page * $perPage) - $perPage;
        $itemsForCurrentPage = array_slice($combined, $offset, $perPage);

        return new LengthAwarePaginator(
            $itemsForCurrentPage,
            count($combined),
            $perPage,
            $page,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );
    }

    public function getTotalSchedulesProperty(): int 
    {
        return Schedule::where(function ($query) {
            $query->where('created_by', Auth::id())->orWhere('user_id', Auth::id());
        })->whereDate('date', '>=', now()->toDateString())->count();
    }
    
    public function getTodaySchedulesProperty(): int 
    {
        return Schedule::where(function ($query) {
            $query->where('created_by', Auth::id())->orWhere('user_id', Auth::id());
        })->whereDate('date', now()->toDateString())->count();
    }
    
    public function getPendingRequestsCountProperty(): int 
    {
        return ScheduleExchange::where('to_user_id', Auth::id())
            ->where('status', 'pending')
            ->count();
    }
    
    public function getPublicExchangeCountProperty(): int
    {
        $userId = Auth::id();
        $userType = Auth::user()->user_type ?? null;
        return PublicScheduleExchange::where('status', 'pending')
            ->where('from_user_id', '!=', $userId)
            ->whereHas('fromUser', fn ($query) => $query->where('user_type', $userType))
            ->count();
    }
    
    public function getBackupRequestsCountProperty(): int
    {
        $userId = Auth::id();
        $userType = Auth::user()->user_type ?? null;
        return SickBackupRequest::where('status', 'pending')
            ->where('sick_user_id', '!=', $userId)
            ->whereHas('sickUser', fn ($query) => $query->where('user_type', $userType))
            ->count();
    }

    public function showMySchedules(): void
    {
        $this->showExchangeRequests = false;
        $this->showPublicExchanges = false;
        $this->showBackupRequests = false;
        $this->resetPage();
    }

    public function toggleExchangeRequests(): void
    {
        $this->showExchangeRequests = true;
        $this->showPublicExchanges = false;
        $this->showBackupRequests = false;
        $this->resetPage();
    }

    public function togglePublicExchanges(): void
    {
        $this->showPublicExchanges = true;
        $this->showExchangeRequests = false;
        $this->showBackupRequests = false;
        $this->resetPage();
    }

    public function toggleBackupRequests(): void
    {
        $this->showBackupRequests = true;
        $this->showExchangeRequests = false;
        $this->showPublicExchanges = false;
        $this->resetPage();
    }

    public function showSickLeaveModal($scheduleId): void
    {
        $schedule = Schedule::find($scheduleId);

        if (!$schedule || ($schedule->created_by !== Auth::id() && $schedule->user_id !== Auth::id())) {
            session()->flash('error', 'Anda hanya dapat melaporkan cuti sakit untuk jadwal yang Anda buat atau ditugaskan kepada Anda.');
            return;
        }

        if (now()->toDateString() > $schedule->date) {
            session()->flash('error', 'Tidak dapat melaporkan cuti sakit untuk jadwal yang sudah lewat.');
            return;
        }
        
        $this->sickScheduleId = $scheduleId;
        $this->showSickLeaveModal = true;
    }

    public function submitSickLeave(): void
    {
        $this->validate(['sickReason' => 'required|string|min:10|max:500']);
        
        $schedule = Schedule::find($this->sickScheduleId);
        
        if (!$schedule || ($schedule->created_by !== Auth::id() && $schedule->user_id !== Auth::id())) {
            session()->flash('error', 'Jadwal tidak valid atau Anda tidak memiliki izin.');
            $this->resetSickLeave();
            return;
        }

        try {
            $sickRequest = SickBackupRequest::create([
                'schedule_id' => $schedule->id,
                'sick_user_id' => Auth::id(),
                'date' => $schedule->date,
                'reason' => $this->sickReason,
                'status' => 'pending',
                'requested_at' => now(),
            ]);

            $userType = Auth::user()->user_type ?? null;
            $teamMembers = User::where('user_type', $userType)
                ->where('id', '!=', Auth::id())
                ->get();

            foreach ($teamMembers as $member) {
                $member->notify(new SickBackupNotification($sickRequest));
            }

            session()->flash('message', 'Permintaan cuti sakit berhasil dikirim. Notifikasi cadangan telah dikirim ke anggota tim.');
        } catch (\Exception $e) {
            \Log::error('Error mengirim cuti sakit untuk jadwal ID: ' . $this->sickScheduleId . ' - ' . $e->getMessage());
            session()->flash('error', 'Gagal mengirim permintaan cuti sakit. Silakan coba lagi.');
        }

        $this->resetSickLeave();
        $this->resetPage();
    }

    public function takeBackup($backupRequestId): void
    {
        $backupRequest = SickBackupRequest::find($backupRequestId);
        
        if (!$backupRequest || $backupRequest->status !== 'pending') {
            session()->flash('error', 'Permintaan cadangan tidak ditemukan atau sudah tidak tersedia.');
            return;
        }

        $sickUser = $backupRequest->sickUser;
        $currentUserType = Auth::user()->user_type;
        if ($sickUser->user_type !== $currentUserType) {
            session()->flash('error', 'Anda hanya dapat mengambil cadangan dari pengguna dengan user_type yang sama.');
            return;
        }

        try {
            $schedule = $backupRequest->originalSchedule;
            if ($schedule) {
                $schedule->update(['user_id' => Auth::id()]);
            }
            
            $backupRequest->update([
                'backup_user_id' => Auth::id(),
                'status' => 'approved',
                'approved_at' => now(),
            ]);

            if ($sickUser) {
                $sickUser->notify(new SickBackupNotification($sickRequest));
            }

            session()->flash('message', 'Penugasan cadangan diterima. Pengguna yang sakit telah diberi tahu.');
        } catch (\Exception $e) {
            \Log::error('Error mengambil cadangan untuk permintaan ID: ' . $backupRequestId . ' - ' . $e->getMessage());
            session()->flash('error', 'Gagal menerima penugasan cadangan.');
        }
        
        $this->resetPage();
    }

    public function initiateSwap($scheduleId): void
    {
        $schedule = Schedule::find($scheduleId);

        if (!$schedule || ($schedule->created_by !== Auth::id() && $schedule->user_id !== Auth::id())) {
            session()->flash('error', 'Anda hanya dapat menukar jadwal yang Anda buat atau yang ditugaskan kepada Anda.');
            return;
        }

        if (now()->toDateString() > $schedule->date) {
            session()->flash('error', 'Tidak dapat memulai pertukaran untuk jadwal yang sudah lewat.');
            return;
        }

        $this->swapScheduleId = $scheduleId;
        $currentUserId = Auth::id();
        $currentUserType = Auth::user()->user_type;

        $allSchedules = Schedule::where('id', '!=', $scheduleId)
            ->whereDate('date', '>=', now()->toDateString())
            ->with(['creator', 'user'])
            ->whereHas('user', function ($query) use ($currentUserType) {
                $query->where('user_type', $currentUserType);
            })
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        $this->availableItems = $allSchedules->filter(function ($item) use ($currentUserId) {
            if ($item->created_by == $currentUserId && $item->user_id == $currentUserId) {
                return false;
            }
            return true;
        });

        if ($this->availableItems->isEmpty()) {
            session()->flash('error', 'Tidak ada jadwal yang tersedia untuk ditukar dengan pengguna yang memiliki user_type yang sama.');
            return;
        }

        $this->showSwapModal = true;
    }
    
    public function requestSwap(): void
    {
        $this->validate([
            'targetItemId' => 'required|exists:schedules,id',
            'swapScheduleId' => 'required|exists:schedules,id'
        ]);

        $targetSchedule = Schedule::with('creator', 'user')->find($this->targetItemId);
        $mySchedule = Schedule::find($this->swapScheduleId);

        if (!$mySchedule || ($mySchedule->created_by !== Auth::id() && $mySchedule->user_id !== Auth::id())) {
            session()->flash('error', 'Jadwal sumber tidak valid atau Anda tidak memiliki izin.');
            $this->resetSwap();
            return;
        }

        if ($targetSchedule->created_by === Auth::id() || $targetSchedule->user_id === Auth::id()) {
            session()->flash('error', 'Tidak dapat menukar dengan jadwal Anda sendiri.');
            $this->resetSwap();
            return;
        }

        $currentUserType = Auth::user()->user_type;
        $targetUser = $targetSchedule->user ?: $targetSchedule->creator;
        if ($targetUser->user_type !== $currentUserType) {
            session()->flash('error', 'Anda hanya dapat menukar jadwal dengan pengguna yang memiliki user_type yang sama.');
            $this->resetSwap();
            return;
        }

        if (now()->toDateString() > $targetSchedule->date || now()->toDateString() > $mySchedule->date) {
            session()->flash('error', 'Tidak dapat menukar jadwal yang sudah lewat.');
            $this->resetSwap();
            return;
        }

        try {
            $existingExchange = ScheduleExchange::where([
                ['schedule_id', $mySchedule->id],
                ['target_schedule_id', $targetSchedule->id],
                ['status', 'pending']
            ])->orWhere([
                ['schedule_id', $targetSchedule->id],
                ['target_schedule_id', $mySchedule->id],
                ['status', 'pending']
            ])->first();

            if ($existingExchange) {
                session()->flash('error', 'Permintaan pertukaran untuk jadwal ini sudah tertunda.');
                $this->resetSwap();
                return;
            }

            $targetUserId = $targetSchedule->user_id ?: $targetSchedule->created_by;

            $exchange = ScheduleExchange::create([
                'schedule_id' => $mySchedule->id,
                'target_schedule_id' => $targetSchedule->id,
                'from_user_id' => Auth::id(),
                'to_user_id' => $targetUserId,
                'status' => 'pending',
                'requested_at' => now(),
            ]);

            $targetUser = User::find($targetUserId);
            if ($targetUser) {
                \Log::info('Sending Direct Exchange Request notification', [
                    'to_user_id' => $targetUser->id,
                    'exchange_id' => $exchange->id,
                    'schedule_id' => $exchange->schedule_id,
                    'target_schedule_id' => $exchange->target_schedule_id,
                    'from_user_id' => $exchange->from_user_id,
                ]);
                try {
                    $targetUser->notify(new ScheduleExchangeNotification($exchange, 'request'));
                    \Log::info('Notification sent successfully to user ID: ' . $targetUser->id);
                } catch (\Exception $e) {
                    \Log::error('Failed to send notification to user ID: ' . $targetUser->id . ' - ' . $e->getMessage());
                    session()->flash('error', 'Gagal mengirim notifikasi ke pengguna tujuan.');
                    $this->resetSwap();
                    return;
                }
            } else {
                \Log::error('Target user not found for exchange ID: ' . $exchange->id);
                session()->flash('error', 'Pengguna tujuan tidak ditemukan.');
                $this->resetSwap();
                return;
            }

            session()->flash('message', 'Permintaan pertukaran berhasil dikirim. Menunggu persetujuan dari ' . ($targetUser->name ?? 'pengguna tujuan') . '.');
        } catch (\Exception $e) {
            \Log::error('Error meminta pertukaran untuk jadwal ID: ' . $this->swapScheduleId . ' - ' . $e->getMessage());
            session()->flash('error', 'Gagal mengirim permintaan pertukaran. Silakan coba lagi.');
        }
        
        $this->resetSwap();
        $this->resetPage();
    }

    public function initiatePublicSwap($itemId): void
    {
        $schedule = Schedule::find($itemId);

        if (!$schedule || ($schedule->created_by !== Auth::id() && $schedule->user_id !== Auth::id())) {
            session()->flash('error', 'Anda hanya dapat memposting jadwal yang Anda buat atau yang ditugaskan untuk pertukaran publik.');
            return;
        }

        if (now()->toDateString() > $schedule->date) {
            session()->flash('error', 'Tidak dapat memposting jadwal yang sudah lewat untuk pertukaran.');
            return;
        }

        try {
            $existingPublicExchange = PublicScheduleExchange::where([
                ['schedule_id', $schedule->id],
                ['status', 'pending']
            ])->first();

            if ($existingPublicExchange) {
                session()->flash('error', 'Jadwal ini sudah diposting untuk pertukaran publik.');
                return;
            }

            $publicExchange = PublicScheduleExchange::create([
                'schedule_id' => $schedule->id,
                'from_user_id' => Auth::id(),
                'status' => 'pending',
                'posted_at' => now(),
            ]);

            $currentUserType = Auth::user()->user_type;
            $teamMembers = User::where('user_type', $currentUserType)
                ->where('id', '!=', Auth::id())
                ->get();

            foreach ($teamMembers as $member) {
                $member->notify(new ScheduleExchangeNotification($publicExchange, 'public_request'));
            }

            session()->flash('message', 'Jadwal berhasil diposting untuk pertukaran publik. Anggota tim dengan user_type yang sama telah diberi tahu.');
        } catch (\Exception $e) {
            \Log::error('Error memposting pertukaran publik untuk jadwal ID: ' . $itemId . ' - ' . $e->getMessage());
            session()->flash('error', 'Gagal memposting jadwal untuk pertukaran publik. Silakan coba lagi.');
        }
        
        $this->resetPage();
    }

    public function acceptPublicExchange($publicExchangeId): void
    {
        $publicExchange = PublicScheduleExchange::with('fromUser', 'schedule')->find($publicExchangeId);

        if (!$publicExchange || $publicExchange->status !== 'pending') {
            session()->flash('error', 'Permintaan pertukaran tidak ditemukan atau sudah tidak tersedia.');
            return;
        }

        if ($publicExchange->from_user_id === Auth::id()) {
            session()->flash('error', 'Anda tidak dapat menerima pertukaran publik Anda sendiri.');
            return;
        }

        $currentUserType = Auth::user()->user_type;
        $fromUser = $publicExchange->fromUser;
        if ($fromUser->user_type !== $currentUserType) {
            session()->flash('error', 'Anda hanya dapat menerima pertukaran dari pengguna dengan user_type yang sama.');
            return;
        }

        try {
            $publicExchange->update([
                'status' => 'accepted',
                'accepted_by' => Auth::id(),
                'accepted_at' => now()
            ]);

            $mySchedules = Schedule::where(function ($query) {
                $query->where('created_by', Auth::id())->orWhere('user_id', Auth::id());
            })
            ->whereDate('date', '>=', now()->toDateString())
            ->first();

            if ($mySchedules) {
                $directExchange = ScheduleExchange::create([
                    'schedule_id' => $mySchedules->id,
                    'target_schedule_id' => $publicExchange->schedule_id,
                    'from_user_id' => Auth::id(),
                    'to_user_id' => $publicExchange->from_user_id,
                    'status' => 'pending',
                    'requested_at' => now(),
                ]);

                $originalPoster = $publicExchange->fromUser;
                if ($originalPoster) {
                    $originalPoster->notify(new ScheduleExchangeNotification($directExchange, 'request'));
                }
            }

            session()->flash('message', 'Minat berhasil diungkapkan. Pemosting asli akan menghubungi Anda untuk menyelesaikan pertukaran.');
        } catch (\Exception $e) {
            \Log::error('Error menerima pertukaran publik ID: ' . $publicExchangeId . ' - ' . $e->getMessage());
            session()->flash('error', 'Gagal menerima pertukaran publik. Silakan coba lagi.');
        }
        
        $this->resetPage();
    }
    
    public function approveExchange($exchangeId): void
    {
        $exchange = ScheduleExchange::find($exchangeId);
        
        if (!$exchange || $exchange->to_user_id !== Auth::id() || $exchange->status !== 'pending') {
            \Log::error('Pertukaran tidak valid atau izin ditolak untuk ID: ' . $exchangeId);
            session()->flash('error', 'Izin ditolak atau permintaan sudah kedaluwarsa.');
            return;
        }

        try {
            \Log::info('Menyetujui pertukaran ID: ' . $exchangeId);
            $exchange->update([
                'status' => 'approved', 
                'approved_at' => now()
            ]);
            
            $this->performScheduleSwap($exchange);
            
            $requester = $exchange->fromUser;
            if ($requester) {
                $requester->notify(new ScheduleExchangeNotification($exchange, 'approved'));
            }
            
            session()->flash('message', 'Pertukaran jadwal disetujui dan selesai.');
        } catch (\Exception $e) {
            \Log::error('Error menyetujui pertukaran ID: ' . $exchangeId . ' - ' . $e->getMessage());
            session()->flash('error', 'Gagal menyetujui pertukaran: ' . $e->getMessage());
        }
        
        $this->resetPage();
    }

    public function rejectExchange($exchangeId): void
    {
        $exchange = ScheduleExchange::find($exchangeId);
        
        if (!$exchange || $exchange->to_user_id !== Auth::id() || $exchange->status !== 'pending') {
            \Log::error('Pertukaran tidak valid atau izin ditolak untuk ID: ' . $exchangeId);
            session()->flash('error', 'Anda tidak memiliki izin untuk menolak permintaan ini atau sudah tidak tertunda.');
            return;
        }
        
        try {
            $exchange->update([
                'status' => 'rejected', 
                'rejected_at' => now()
            ]);
            
            $requester = $exchange->fromUser;
            if ($requester) {
                $requester->notify(new ScheduleExchangeNotification($exchange, 'rejected'));
            }
            
            session()->flash('message', 'Permintaan pertukaran ditolak.');
        } catch (\Exception $e) {
            \Log::error('Error menolak pertukaran ID: ' . $exchangeId . ' - ' . $e->getMessage());
            session()->flash('error', 'Gagal menolak permintaan pertukaran.');
        }
        
        $this->resetPage();
    }
    
    private function performScheduleSwap($exchange): void
    {
        $schedule1 = $exchange->schedule;
        $schedule2 = $exchange->targetSchedule;

        if (!$schedule1 || !$schedule2) {
            throw new \Exception("Salah satu atau kedua jadwal tidak ditemukan untuk pertukaran.");
        }
        
        $tempUserId1 = $schedule1->user_id;
        $tempUserId2 = $schedule2->user_id;

        $schedule1->update(['user_id' => $tempUserId2]);
        $schedule2->update(['user_id' => $tempUserId1]);
    }

    public function resetSickLeave(): void
    { 
        $this->sickScheduleId = null; 
        $this->sickReason = ''; 
        $this->showSickLeaveModal = false;
        $this->resetValidation();
    }
    
    public function resetSwap(): void
    { 
        $this->targetItemId = null; 
        $this->swapScheduleId = null; 
        $this->showSwapModal = false; 
        $this->availableItems = collect();
        $this->resetValidation();
    }
    
    public function render()
    {
        return view('livewire.combined-schedule', [
            'items' => $this->items,
            'pendingRequestsCount' => $this->pendingRequestsCount,
            'publicExchangeCount' => $this->publicExchangeCount,
            'backupRequestsCount' => $this->backupRequestsCount,
            'totalSchedules' => $this->totalSchedules,
            'todaySchedules' => $this->todaySchedules,
        ]);
    }
}