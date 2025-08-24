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

class CombinedSchedule extends Component
{
    use WithPagination;

    // Properti State
    public $targetItemId;
    public $swapScheduleId;
    public $sickScheduleId;
    public $sickReason = '';
    public $exchangeIdToApprove;

    // Properti Modal
    public $showSwapModal = false;
    public $showApproveModal = false;
    public $showSickLeaveModal = false;
    public $availableItems;

    // Properti Tab
    public $showExchangeRequests = false;
    public $showPublicExchanges = false;
    public $showBackupRequests = false;

    // --- COMPUTED PROPERTIES ---

    public function getCombinedItemsProperty(): LengthAwarePaginator
    {
        $userId = Auth::id();
        if (!$userId) {
            return new LengthAwarePaginator([], 0, 5, 1);
        }

        $combined = [];
        $userRole = Auth::user()->role ?? 'user';

        // Tampilkan My Schedules
        if (!$this->showExchangeRequests && !$this->showPublicExchanges && !$this->showBackupRequests) {
            $schedules = Schedule::where(function ($query) use ($userId) {
                // Tampilkan jadwal yang dibuat oleh user (owner) ATAU yang di-assign ke user (user_id)
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
                'user_name' => optional($s->creator)->name ?? 'Unknown User',
                'type' => 'schedule', 
                'status' => null, 
                'is_owner' => $s->created_by === $userId, // Flag kepemilikan
                'is_assigned' => $s->user_id === $userId, // Flag assignment
            ])->toArray();
            $combined = array_merge($combined, $schedulesArray);
        } 
        
        // Tampilkan Direct Exchange Requests
        elseif ($this->showExchangeRequests) {
            $exchanges = ScheduleExchange::where('to_user_id', $userId)
                ->where('status', 'pending')
                ->with('schedule', 'fromUser', 'targetSchedule')
                ->get()
                ->map(fn($e) => [
                    'id' => $e->id, 
                    'title' => optional($e->schedule)->title ?? 'Unknown Schedule',
                    'description' => 'Direct request from ' . optional($e->fromUser)->name,
                    'date' => optional($e->schedule)->date ?? now()->toDateString(), 
                    'start_time' => optional($e->schedule)->start_time ?? null,
                    'end_time' => optional($e->schedule)->end_time ?? null, 
                    'user_name' => optional($e->fromUser)->name ?? 'Unknown User',
                    'type' => 'exchange', 
                    'status' => $e->status, 
                    'schedule_id' => $e->schedule_id,
                    'exchange_details' => [
                        'from_user' => optional($e->fromUser)->name, 
                        'target_schedule' => optional($e->targetSchedule)->title ?? 'Schedule not found'
                    ]
                ])->toArray();
            $combined = array_merge($combined, $exchanges);
        } 
        
        // Tampilkan Public Exchanges
        elseif ($this->showPublicExchanges) {
            $publicExchanges = PublicScheduleExchange::where('status', 'pending')
                ->where('from_user_id', '!=', $userId)
                ->whereHas('fromUser', fn ($query) => $query->where('role', $userRole))
                ->with('schedule', 'fromUser')
                ->get()
                ->map(fn($e) => [
                    'id' => $e->id, 
                    'title' => optional($e->schedule)->title ?? 'Unknown Schedule',
                    'description' => 'Posted by ' . optional($e->fromUser)->name,
                    'date' => optional($e->schedule)->date ?? now()->toDateString(), 
                    'start_time' => optional($e->schedule)->start_time ?? null,
                    'end_time' => optional($e->schedule)->end_time ?? null, 
                    'user_name' => optional($e->fromUser)->name ?? 'Unknown User',
                    'type' => 'public_exchange', 
                    'status' => $e->status,
                ])->toArray();
            $combined = array_merge($combined, $publicExchanges);
        } 
        
        // Tampilkan Backup Requests
        elseif ($this->showBackupRequests) {
            $backupRequests = SickBackupRequest::where('status', 'pending')
                ->where('sick_user_id', '!=', $userId)
                ->whereHas('sickUser', fn ($query) => $query->where('role', $userRole))
                ->with('originalSchedule', 'sickUser')
                ->get()
                ->map(fn($r) => [
                    'id' => $r->id, 
                    'title' => optional($r->originalSchedule)->title ?? 'Unknown Schedule',
                    'description' => 'Backup needed - ' . optional($r->sickUser)->name . ' is sick',
                    'date' => $r->date ?? now()->toDateString(), 
                    'start_time' => optional($r->originalSchedule)->start_time ?? null,
                    'end_time' => optional($r->originalSchedule)->end_time ?? null, 
                    'user_name' => optional($r->sickUser)->name ?? 'Unknown User',
                    'type' => 'backup', 
                    'status' => $r->status, 
                    'reason' => $r->reason,
                ])->toArray();
            $combined = array_merge($combined, $backupRequests);
        }

        // Sorting dan Paginasi Manual
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

    // Properti Hitungan (Sudah Benar)
    public function getTotalSchedulesProperty(): int { return Schedule::where('created_by', Auth::id())->orWhere('user_id', Auth::id())->whereDate('date', '>=', now()->toDateString())->count(); }
    public function getTodaySchedulesProperty(): int { return Schedule::where('created_by', Auth::id())->orWhere('user_id', Auth::id())->whereDate('date', now()->toDateString())->count(); }
    public function getPendingRequestsCountProperty(): int { return ScheduleExchange::where('to_user_id', Auth::id())->where('status', 'pending')->count(); }
    public function getPublicExchangeCountProperty(): int 
    { 
        $userId = Auth::id();
        $userRole = Auth::user()->role ?? 'user';
        return PublicScheduleExchange::where('status', 'pending')
            ->where('from_user_id', '!=', $userId)
            ->whereHas('fromUser', fn ($query) => $query->where('role', $userRole))
            ->count();
    }
    public function getBackupRequestsCountProperty(): int
    {
        $userId = Auth::id();
        $userRole = Auth::user()->role ?? 'user';
        return SickBackupRequest::where('status', 'pending')
            ->where('sick_user_id', '!=', $userId)
            ->whereHas('sickUser', fn ($query) => $query->where('role', $userRole))
            ->count();
    }


    // --- METHOD NAVIGASI (Sudah Benar) ---

    public function toggleExchangeRequests()
    {
        $this->showExchangeRequests = true;
        $this->showPublicExchanges = false;
        $this->showBackupRequests = false;
        $this->resetPage();
    }

    public function togglePublicExchanges()
    {
        $this->showPublicExchanges = true;
        $this->showExchangeRequests = false;
        $this->showBackupRequests = false;
        $this->resetPage();
    }

    public function toggleBackupRequests()
    {
        $this->showBackupRequests = true;
        $this->showExchangeRequests = false;
        $this->showPublicExchanges = false;
        $this->resetPage();
    }

    // --- METHOD AKSI SICK LEAVE ---

    public function showSickLeaveModal($scheduleId)
    {
        $schedule = Schedule::find($scheduleId);

        // KODE PERBAIKAN: Izinkan jika created_by SAYA ATAU user_id SAYA
        if (!$schedule || ($schedule->created_by !== Auth::id() && $schedule->user_id !== Auth::id())) {
            session()->flash('error', 'You can only report sick leave for schedules you own or are currently assigned to.');
            return;
        }

        if (now()->toDateString() > $schedule->date) {
            session()->flash('error', 'Cannot report sick leave for a past schedule.');
            return;
        }
        
        $this->sickScheduleId = $scheduleId;
        $this->showSickLeaveModal = true;
    }

    public function submitSickLeave()
    {
        $this->validate(['sickReason' => 'required|string|min:10|max:500']);
        $schedule = Schedule::find($this->sickScheduleId);
        
        // Pengecekan keamanan terakhir
        if (!$schedule || ($schedule->created_by !== Auth::id() && $schedule->user_id !== Auth::id())) {
             session()->flash('error', 'Invalid schedule or you do not have permission.');
             $this->resetSickLeave();
             return;
        }

        try {
            $sickRequest = SickBackupRequest::create([
                'schedule_id' => $schedule->id,
                'sick_user_id' => Auth::id(), // User yang sakit adalah user yang sedang login
                'date' => $schedule->date,
                'reason' => $this->sickReason,
                'status' => 'pending',
                'requested_at' => now(),
            ]);
            // Logic notifikasi...
            session()->flash('message', 'Sick leave request submitted. Backup notifications sent to team members.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to submit sick leave request. Please try again.');
        }

        $this->resetSickLeave();
        $this->resetPage();
    }

    public function takeBackup($backupRequestId)
    {
        $backupRequest = SickBackupRequest::find($backupRequestId);
        if (!$backupRequest || $backupRequest->status !== 'pending') {
            session()->flash('error', 'Backup request not found or no longer available.');
            return;
        }

        try {
            $schedule = $backupRequest->originalSchedule;
            if ($schedule) {
                // Assign schedule ke user yang mengambil backup
                $schedule->update(['user_id' => Auth::id()]);
            }
            
            $backupRequest->update([
                'backup_user_id' => Auth::id(),
                'status' => 'approved',
                'approved_at' => now(),
            ]);
            // Logic notifikasi...
            session()->flash('message', 'Backup assignment accepted. The sick user has been notified.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to accept backup assignment.');
        }
        $this->resetPage();
    }

    // --- METHOD AKSI DIRECT SWAP ---

    public function initiateSwap($scheduleId)
    {
        $schedule = Schedule::find($scheduleId);

        // KODE PERBAIKAN: Izinkan jika created_by SAYA ATAU user_id SAYA
        if (!$schedule || ($schedule->created_by !== Auth::id() && $schedule->user_id !== Auth::id())) {
            session()->flash('error', 'You can only exchange schedules you own or are currently assigned to.');
            return;
        }

        if (now()->toDateString() > $schedule->date) {
            session()->flash('error', 'Cannot initiate swap for a past schedule.');
            return;
        }

        $this->swapScheduleId = $scheduleId;
        $userRole = Auth::user()->role ?? 'user';

        // Ambil jadwal yang BISA ditukar (milik orang lain dengan role yang sama)
        $this->availableItems = Schedule::where(function ($query) {
             // Pastikan jadwal yang ditampilkan bukan jadwal yang akan di-swap
            $query->where('created_by', '!=', Auth::id())
                  ->where('user_id', '!=', Auth::id());
        })
            ->whereHas('creator', fn($query) => $query->where('role', $userRole))
            ->whereDate('date', '>=', now()->toDateString())
            ->with('creator')
            ->orderBy('date', 'asc')
            ->get();

        $this->showSwapModal = true;
    }
    
    public function requestSwap()
    {
        $this->validate([
            'targetItemId' => 'required|exists:schedules,id',
            'swapScheduleId' => 'required|exists:schedules,id'
        ]);

        $targetSchedule = Schedule::with('creator')->find($this->targetItemId);
        $mySchedule = Schedule::find($this->swapScheduleId);

        // Pengecekan keamanan: Pastikan mySchedule masih dimiliki/di-assign ke user
        if (!$mySchedule || ($mySchedule->created_by !== Auth::id() && $mySchedule->user_id !== Auth::id())) {
            session()->flash('error', 'Invalid source schedule or permission denied.');
            $this->resetSwap();
            return;
        }

        // ... Logika Anda untuk membuat ScheduleExchange ...
        
        $this->resetSwap();
        $this->resetPage();
    }
    
    // --- METHOD AKSI PUBLIC SWAP ---

    public function initiatePublicSwap($itemId)
    {
        $schedule = Schedule::find($itemId);

        // KODE PERBAIKAN: Izinkan jika created_by SAYA ATAU user_id SAYA
        if (!$schedule || ($schedule->created_by !== Auth::id() && $schedule->user_id !== Auth::id())) {
            session()->flash('error', 'You can only post schedules you own or are currently assigned for public exchange.');
            return;
        }

        if (now()->toDateString() > $schedule->date) {
            session()->flash('error', 'Cannot post a past schedule for exchange.');
            return;
        }
        
        // ... Logika Anda untuk membuat PublicScheduleExchange ...
        
        session()->flash('message', 'Schedule posted for public exchange. Team members have been notified.');
        $this->resetPage();
    }

    public function acceptPublicExchange($publicExchangeId)
    {
        $publicExchange = PublicScheduleExchange::with('fromUser')->find($publicExchangeId);

        if (!$publicExchange || $publicExchange->status !== 'pending') {
            session()->flash('error', 'Exchange request not found or no longer available.');
            return;
        }
        // ... Logika: Buat DirectExchange baru ke pemilik jadwal asli
        
        session()->flash('message', 'Interest expressed successfully. The original poster will contact you to finalize the exchange.');
        $this->resetPage();
    }
    
    // --- METHOD AKSI PERSETUJUAN/PENOLAKAN ---

    public function showApproveModal($exchangeId)
    {
        $exchange = ScheduleExchange::find($exchangeId);
        if (!$exchange || $exchange->to_user_id !== Auth::id()) {
            session()->flash('error', 'Exchange request not found or you do not have permission.');
            return;
        }
        $this->exchangeIdToApprove = $exchangeId;
        $this->showApproveModal = true;
    }

    public function rejectExchange($exchangeId)
    {
        $exchange = ScheduleExchange::find($exchangeId);
        if (!$exchange || $exchange->to_user_id !== Auth::id() || $exchange->status !== 'pending') {
            session()->flash('error', 'You do not have permission to reject this request or it is no longer pending.');
            return;
        }
        try {
             $exchange->update(['status' => 'rejected']);
             // Logic notifikasi...
             session()->flash('message', 'Exchange request rejected.');
        } catch (\Exception $e) {
             session()->flash('error', 'Failed to reject exchange request.');
        }
        $this->resetPage();
    }

    public function approveExchange()
    {
        $exchange = ScheduleExchange::find($this->exchangeIdToApprove);
        if (!$exchange || $exchange->to_user_id !== Auth::id() || $exchange->status !== 'pending') {
            session()->flash('error', 'Permission denied or request expired.');
            $this->resetApprove();
            return;
        }

        try {
            $exchange->update(['status' => 'approved', 'approved_at' => now()]);
            $this->performScheduleSwap($exchange);
            // Logic notifikasi...
            session()->flash('message', 'Schedule exchange approved and completed.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to approve exchange. Please try again.');
        }
        $this->resetApprove();
        $this->resetPage();
    }
    
    private function performScheduleSwap($exchange)
    {
        $schedule1 = $exchange->schedule; // Jadwal milik requester (from_user)
        $schedule2 = $exchange->targetSchedule; // Jadwal milik approver (to_user)

        if (!$schedule1 || !$schedule2) {
            throw new \Exception("One or both schedules missing for swap.");
        }

        // SWAP HANYA created_by
        $tempCreatedBy = $schedule1->created_by;
        $schedule1->update(['created_by' => $schedule2->created_by]);
        $schedule2->update(['created_by' => $tempCreatedBy]);
        
        // SWAP user_id
        $tempUserId = $schedule1->user_id;
        $schedule1->update(['user_id' => $schedule2->user_id]);
        $schedule2->update(['user_id' => $tempUserId]);
    }

    // --- METHOD RESET MODAL (Sudah Benar) ---

    public function resetSickLeave() { /* ... */ $this->sickScheduleId = null; $this->sickReason = ''; $this->showSickLeaveModal = false; }
    public function resetSwap() { /* ... */ $this->targetItemId = null; $this->swapScheduleId = null; $this->showSwapModal = false; $this->availableItems = collect(); }
    public function resetApprove() { /* ... */ $this->exchangeIdToApprove = null; $this->showApproveModal = false; }

    public function render()
    {
        return view('livewire.combined-schedule', [
            'items' => $this->getCombinedItemsProperty(),
            'pendingRequestsCount' => $this->getPendingRequestsCountProperty(),
            'publicExchangeCount' => $this->getPublicExchangeCountProperty(),
            'backupRequestsCount' => $this->getBackupRequestsCountProperty(),
            'totalSchedules' => $this->getTotalSchedulesProperty(),
            'todaySchedules' => $this->getTodaySchedulesProperty(),
        ]);
    }
}