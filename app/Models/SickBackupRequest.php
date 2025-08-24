<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class SickBackupRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'sick_user_id',
        'backup_user_id',
        'date',
        'reason',
        'status',
        'requested_at',
        'approved_at',
        'rejected_at',
        'backup_notes',
        'admin_notes',
        'is_emergency',
        'backup_offers_count'
    ];

    protected $casts = [
        'date' => 'date',
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'is_emergency' => 'boolean',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the original schedule that needs backup coverage
     */
    public function originalSchedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    /**
     * Get the user who is sick and needs backup
     */
    public function sickUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sick_user_id');
    }

    /**
     * Get the user who will provide backup coverage
     */
    public function backupUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'backup_user_id');
    }

    /**
     * Scope for pending backup requests
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for approved backup requests
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope for rejected backup requests
     */
    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    /**
     * Scope for emergency backup requests
     */
    public function scopeEmergency($query)
    {
        return $query->where('is_emergency', true);
    }

    /**
     * Scope for backup requests by specific sick user
     */
    public function scopeBySickUser($query, $userId)
    {
        return $query->where('sick_user_id', $userId);
    }

    /**
     * Scope for backup requests excluding specific sick user
     */
    public function scopeExcludeSickUser($query, $userId)
    {
        return $query->where('sick_user_id', '!=', $userId);
    }

    /**
     * Scope for backup requests by backup user
     */
    public function scopeByBackupUser($query, $userId)
    {
        return $query->where('backup_user_id', $userId);
    }

    /**
     * Scope for backup requests by date
     */
    public function scopeByDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    /**
     * Scope for backup requests by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Scope for today's backup requests
     */
    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }

    /**
     * Scope for upcoming backup requests (future dates)
     */
    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', today());
    }

    /**
     * Scope for urgent backup requests (same day or emergency)
     */
    public function scopeUrgent($query)
    {
        return $query->where(function($q) {
            $q->where('is_emergency', true)
              ->orWhere('date', '<=', today()->addDay());
        });
    }

    /**
     * Scope for backup requests by user role
     */
    public function scopeByRole($query, $role)
    {
        return $query->whereHas('sickUser', function($q) use ($role) {
            $q->where('role', $role);
        });
    }

    /**
     * Scope for recent backup requests (within last 30 days)
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('requested_at', '>=', now()->subDays($days));
    }

    /**
     * Check if backup can be offered for this request
     */
    public function canOfferBackup($userId): bool
    {
        return $this->status === self::STATUS_PENDING && 
               $this->sick_user_id !== $userId &&
               $this->backup_user_id === null &&
               $this->date >= today();
    }

    /**
     * Check if request can be cancelled
     */
    public function canBeCancelled($userId): bool
    {
        return $this->sick_user_id == $userId && 
               $this->status === self::STATUS_PENDING &&
               $this->date >= today();
    }

    /**
     * Offer backup coverage for this request
     */
    public function offerBackup($userId, string $notes = null): bool
    {
        if (!$this->canOfferBackup($userId)) {
            throw new \Exception('Cannot offer backup for this request.');
        }

        $this->update([
            'backup_user_id' => $userId,
            'status' => self::STATUS_APPROVED,
            'approved_at' => now(),
            'backup_notes' => $notes
        ]);

        $this->increment('backup_offers_count');

        return true;
    }

    /**
     * Reject the backup request
     */
    public function reject(string $reason = null): bool
    {
        if ($this->status !== self::STATUS_PENDING) {
            throw new \Exception('Can only reject pending backup requests.');
        }

        $this->update([
            'status' => self::STATUS_REJECTED,
            'rejected_at' => now(),
            'admin_notes' => $reason
        ]);

        return true;
    }

    /**
     * Cancel the backup request
     */
    public function cancel(): bool
    {
        if ($this->status !== self::STATUS_PENDING) {
            throw new \Exception('Can only cancel pending backup requests.');
        }

        $this->update([
            'status' => self::STATUS_CANCELLED,
            'rejected_at' => now()
        ]);

        return true;
    }

    /**
     * Mark as emergency
     */
    public function markAsEmergency(): bool
    {
        if ($this->status !== self::STATUS_PENDING) {
            throw new \Exception('Can only mark pending requests as emergency.');
        }

        $this->update(['is_emergency' => true]);

        return true;
    }

    /**
     * Get formatted backup request details for display
     */
    public function getBackupDetailsAttribute(): array
    {
        return [
            'sick_user' => $this->sickUser ? $this->sickUser->name : 'Unknown User',
            'backup_user' => $this->backupUser ? $this->backupUser->name : 'No backup assigned',
            'schedule_title' => $this->originalSchedule ? $this->originalSchedule->title : 'Schedule not found',
            'schedule_time' => $this->getScheduleTimeRange(),
            'status_label' => ucfirst($this->status),
            'date_formatted' => $this->date ? $this->date->format('M d, Y') : 'Unknown',
            'requested_date' => $this->requested_at ? $this->requested_at->format('M d, Y H:i') : 'Unknown',
            'is_urgent' => $this->isUrgent(),
            'is_emergency' => $this->is_emergency,
            'reason' => $this->reason,
            'backup_notes' => $this->backup_notes,
            'days_until_date' => $this->date ? today()->diffInDays($this->date, false) : null,
            'is_past_due' => $this->date ? $this->date->isPast() : false
        ];
    }

    /**
     * Check if this is an urgent request
     */
    public function isUrgent(): bool
    {
        if ($this->is_emergency) {
            return true;
        }

        if (!$this->date) {
            return false;
        }

        // Urgent if date is today or tomorrow
        return $this->date <= today()->addDay();
    }

    /**
     * Check if this request is overdue (past date and still pending)
     */
    public function isOverdue(): bool
    {
        return $this->status === self::STATUS_PENDING && 
               $this->date && 
               $this->date->isPast();
    }

    /**
     * Get schedule time range as formatted string
     */
    private function getScheduleTimeRange(): ?string
    {
        if (!$this->originalSchedule) {
            return null;
        }

        $startTime = $this->originalSchedule->start_time;
        $endTime = $this->originalSchedule->end_time;

        if (!$startTime) {
            return 'All day';
        }

        if (!$endTime) {
            return Carbon::parse($startTime)->format('H:i');
        }

        return Carbon::parse($startTime)->format('H:i') . ' - ' . Carbon::parse($endTime)->format('H:i');
    }

    /**
     * Get priority level for sorting
     */
    public function getPriorityLevel(): int
    {
        if ($this->is_emergency) {
            return 1; // Highest priority
        }

        if ($this->isUrgent()) {
            return 2; // High priority
        }

        if ($this->date && $this->date <= today()->addDays(3)) {
            return 3; // Medium priority
        }

        return 4; // Normal priority
    }

    /**
     * Get priority label
     */
    public function getPriorityLabelAttribute(): string
    {
        switch ($this->getPriorityLevel()) {
            case 1:
                return 'Emergency';
            case 2:
                return 'Urgent';
            case 3:
                return 'High';
            default:
                return 'Normal';
        }
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute(): string
    {
        switch ($this->status) {
            case self::STATUS_PENDING:
                return $this->isUrgent() ? 'red' : 'yellow';
            case self::STATUS_APPROVED:
                return 'green';
            case self::STATUS_REJECTED:
                return 'red';
            case self::STATUS_CANCELLED:
                return 'gray';
            default:
                return 'gray';
        }
    }

    /**
     * Scope for high priority requests (emergency or urgent)
     */
    public function scopeHighPriority($query)
    {
        return $query->where(function($q) {
            $q->where('is_emergency', true)
              ->orWhere('date', '<=', today()->addDay());
        });
    }

    /**
     * Get statistics for backup requests
     */
    public static function getStats($userId = null, $role = null): array
    {
        $query = self::query();

        if ($userId) {
            $query->where('sick_user_id', $userId);
        }

        if ($role) {
            $query->whereHas('sickUser', function($q) use ($role) {
                $q->where('role', $role);
            });
        }

        $total = $query->count();
        $pending = $query->where('status', self::STATUS_PENDING)->count();
        $approved = $query->where('status', self::STATUS_APPROVED)->count();
        $emergency = $query->where('is_emergency', true)->count();
        $urgent = $query->where(function($q) {
            $q->where('is_emergency', true)
              ->orWhere('date', '<=', today()->addDay());
        })->count();

        return [
            'total' => $total,
            'pending' => $pending,
            'approved' => $approved,
            'rejected' => $query->where('status', self::STATUS_REJECTED)->count(),
            'cancelled' => $query->where('status', self::STATUS_CANCELLED)->count(),
            'emergency' => $emergency,
            'urgent' => $urgent,
            'approval_rate' => $total > 0 ? round(($approved / $total) * 100, 1) : 0,
        ];
    }

    /**
     * Auto-expire old pending requests (should be run via scheduled task)
     */
    public static function expireOldRequests($days = 7): int
    {
        $expiredCount = self::where('status', self::STATUS_PENDING)
            ->where('date', '<', today()->subDays($days))
            ->update([
                'status' => self::STATUS_REJECTED,
                'rejected_at' => now(),
                'admin_notes' => 'Auto-expired due to past date'
            ]);

        return $expiredCount;
    }
}