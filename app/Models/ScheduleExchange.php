<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ScheduleExchange extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'from_user_id',
        'to_user_id',
        'target_schedule_id',
        'public_exchange_id',
        'status',
        'requested_at',
        'approved_at',
        'rejected_at',
        'notes',
        'rejection_reason'
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the schedule that is being offered for exchange
     */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * Get the target schedule that user wants in return
     */
    public function targetSchedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class, 'target_schedule_id');
    }

    /**
     * Get the user who initiated the exchange request
     */
    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    /**
     * Get the user who receives the exchange request
     */
    public function toUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    /**
     * Get the related public exchange if this came from a public request
     */
    public function publicExchange(): BelongsTo
    {
        return $this->belongsTo(PublicScheduleExchange::class, 'public_exchange_id');
    }

    /**
     * Scope for pending exchanges
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for approved exchanges
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope for rejected exchanges
     */
    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    /**
     * Scope for exchanges by specific user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('from_user_id', $userId)
                    ->orWhere('to_user_id', $userId);
    }

    /**
     * Scope for exchanges to specific user
     */
    public function scopeToUser($query, $userId)
    {
        return $query->where('to_user_id', $userId);
    }

    /**
     * Scope for exchanges from specific user
     */
    public function scopeFromUser($query, $userId)
    {
        return $query->where('from_user_id', $userId);
    }

    /**
     * Check if the exchange can be approved
     */
    public function canBeApproved(): bool
    {
        return $this->status === self::STATUS_PENDING && 
               $this->targetSchedule !== null;
    }

    /**
     * Check if the exchange can be rejected
     */
    public function canBeRejected(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Approve the exchange and perform the swap
     */
    public function approveAndSwap(): bool
    {
        if (!$this->canBeApproved()) {
            throw new \Exception('Exchange cannot be approved in current status or missing target schedule.');
        }

        try {
            DB::transaction(function () {
                // Get the schedules
                $schedule1 = $this->schedule;
                $schedule2 = $this->targetSchedule;

                if (!$schedule1 || !$schedule2) {
                    throw new \Exception('One or both schedules not found.');
                }

                // Perform the swap
                $this->performScheduleSwap($schedule1, $schedule2);

                // Update the exchange status
                $this->update([
                    'status' => self::STATUS_APPROVED,
                    'approved_at' => now()
                ]);

                // Update related public exchange if exists
                if ($this->publicExchange) {
                    $this->publicExchange->update([
                        'status' => 'completed',
                        'completed_at' => now()
                    ]);
                }
            });

            return true;
        } catch (\Exception $e) {
            \Log::error('Error approving exchange: ' . $e->getMessage(), [
                'exchange_id' => $this->id,
                'error' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Reject the exchange
     */
    public function reject(string $reason = null): bool
    {
        if (!$this->canBeRejected()) {
            throw new \Exception('Exchange cannot be rejected in current status.');
        }

        $this->update([
            'status' => self::STATUS_REJECTED,
            'rejected_at' => now(),
            'rejection_reason' => $reason
        ]);

        // Update related public exchange if exists
        if ($this->publicExchange) {
            $this->publicExchange->update(['status' => 'pending']);
        }

        return true;
    }

    /**
     * Cancel the exchange (by requester)
     */
    public function cancel(): bool
    {
        if ($this->status !== self::STATUS_PENDING) {
            throw new \Exception('Only pending exchanges can be cancelled.');
        }

        $this->update([
            'status' => self::STATUS_CANCELLED,
            'rejected_at' => now()
        ]);

        return true;
    }

    /**
     * Perform the actual schedule swap
     */
    private function performScheduleSwap($schedule1, $schedule2): void
    {
        // Store original values
        $originalCreatedBy1 = $schedule1->created_by;
        $originalUserId1 = $schedule1->user_id ?? null;
        
        $originalCreatedBy2 = $schedule2->created_by;
        $originalUserId2 = $schedule2->user_id ?? null;

        // Swap created_by fields
        $schedule1->update(['created_by' => $originalCreatedBy2]);
        $schedule2->update(['created_by' => $originalCreatedBy1]);

        // Swap user_id fields if they exist
        if ($originalUserId1 !== null && $originalUserId2 !== null) {
            $schedule1->update(['user_id' => $originalUserId2]);
            $schedule2->update(['user_id' => $originalUserId1]);
        }

        \Log::info('Schedule swap completed', [
            'exchange_id' => $this->id,
            'schedule1_id' => $schedule1->id,
            'schedule2_id' => $schedule2->id,
            'swapped_users' => [
                'schedule1_new_owner' => $originalCreatedBy2,
                'schedule2_new_owner' => $originalCreatedBy1
            ]
        ]);
    }

    /**
     * Get formatted exchange details for display
     */
    public function getExchangeDetailsAttribute(): array
    {
        return [
            'from_user' => $this->fromUser ? $this->fromUser->name : 'Unknown User',
            'to_user' => $this->toUser ? $this->toUser->name : 'Unknown User',
            'offered_schedule' => $this->schedule ? $this->schedule->title : 'Schedule not found',
            'target_schedule' => $this->targetSchedule ? $this->targetSchedule->title : 'No target schedule',
            'status_label' => ucfirst($this->status),
            'requested_date' => $this->requested_at ? $this->requested_at->format('M d, Y H:i') : 'Unknown',
            'is_from_public' => $this->publicExchange !== null
        ];
    }

    /**
     * Check if current user can approve this exchange
     */
    public function canBeApprovedBy($userId): bool
    {
        return $this->to_user_id == $userId && $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if current user can reject this exchange
     */
    public function canBeRejectedBy($userId): bool
    {
        return $this->to_user_id == $userId && $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if current user can cancel this exchange
     */
    public function canBeCancelledBy($userId): bool
    {
        return $this->from_user_id == $userId && $this->status === self::STATUS_PENDING;
    }
}