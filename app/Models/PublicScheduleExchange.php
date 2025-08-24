<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class PublicScheduleExchange extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'from_user_id',
        'status',
        'requested_at',
        'matched_at',
        'completed_at',
        'description',
        'requirements',
        'interest_count'
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'matched_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_INTERESTED = 'interested';
    const STATUS_MATCHED = 'matched';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the schedule that is being offered for public exchange
     */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * Get the user who posted the schedule for public exchange
     */
    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    /**
     * Get all direct exchange requests generated from this public exchange
     */
    public function scheduleExchanges(): HasMany
    {
        return $this->hasMany(ScheduleExchange::class, 'public_exchange_id');
    }

    /**
     * Get pending exchange requests from this public exchange
     */
    public function pendingExchanges(): HasMany
    {
        return $this->hasMany(ScheduleExchange::class, 'public_exchange_id')
                    ->where('status', ScheduleExchange::STATUS_PENDING);
    }

    /**
     * Scope for pending public exchanges
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for interested public exchanges
     */
    public function scopeInterested($query)
    {
        return $query->where('status', self::STATUS_INTERESTED);
    }

    /**
     * Scope for matched public exchanges
     */
    public function scopeMatched($query)
    {
        return $query->where('status', self::STATUS_MATCHED);
    }

    /**
     * Scope for completed public exchanges
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope for exchanges by specific user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('from_user_id', $userId);
    }

    /**
     * Scope for exchanges excluding specific user
     */
    public function scopeExcludeUser($query, $userId)
    {
        return $query->where('from_user_id', '!=', $userId);
    }

    /**
     * Scope for exchanges by user role
     */
    public function scopeByRole($query, $role)
    {
        return $query->whereHas('fromUser', function($q) use ($role) {
            $q->where('role', $role);
        });
    }

    /**
     * Scope for recent exchanges (within last 30 days)
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('requested_at', '>=', now()->subDays($days));
    }

    /**
     * Check if someone can express interest in this exchange
     */
    public function canExpressInterest($userId): bool
    {
        return $this->status === self::STATUS_PENDING && 
               $this->from_user_id !== $userId &&
               !$this->hasUserExpressedInterest($userId);
    }

    /**
     * Check if a user has already expressed interest
     */
    public function hasUserExpressedInterest($userId): bool
    {
        return $this->scheduleExchanges()
                    ->where('from_user_id', $userId)
                    ->whereIn('status', [
                        ScheduleExchange::STATUS_PENDING,
                        ScheduleExchange::STATUS_APPROVED
                    ])
                    ->exists();
    }

    /**
     * Express interest in this public exchange
     */
    public function expressInterest($userId): ScheduleExchange
    {
        if (!$this->canExpressInterest($userId)) {
            throw new \Exception('Cannot express interest in this exchange.');
        }

        // Create a direct exchange request
        $directExchange = ScheduleExchange::create([
            'schedule_id' => $this->schedule_id,
            'from_user_id' => $userId,
            'to_user_id' => $this->from_user_id,
            'target_schedule_id' => null, // Will be determined later
            'public_exchange_id' => $this->id,
            'status' => ScheduleExchange::STATUS_PENDING,
            'requested_at' => now(),
        ]);

        // Update interest count and status
        $this->increment('interest_count');
        
        if ($this->status === self::STATUS_PENDING) {
            $this->update(['status' => self::STATUS_INTERESTED]);
        }

        return $directExchange;
    }

    /**
     * Cancel the public exchange
     */
    public function cancel(): bool
    {
        if (!in_array($this->status, [self::STATUS_PENDING, self::STATUS_INTERESTED])) {
            throw new \Exception('Cannot cancel exchange in current status.');
        }

        // Cancel all related pending exchanges
        $this->scheduleExchanges()
             ->where('status', ScheduleExchange::STATUS_PENDING)
             ->update(['status' => ScheduleExchange::STATUS_CANCELLED]);

        $this->update([
            'status' => self::STATUS_CANCELLED,
            'completed_at' => now()
        ]);

        return true;
    }

    /**
     * Mark as matched when someone accepts
     */
    public function markAsMatched(): bool
    {
        if ($this->status !== self::STATUS_INTERESTED) {
            throw new \Exception('Can only mark interested exchanges as matched.');
        }

        $this->update([
            'status' => self::STATUS_MATCHED,
            'matched_at' => now()
        ]);

        return true;
    }

    /**
     * Mark as completed when exchange is finalized
     */
    public function markAsCompleted(): bool
    {
        if ($this->status !== self::STATUS_MATCHED) {
            throw new \Exception('Can only mark matched exchanges as completed.');
        }

        $this->update([
            'status' => self::STATUS_COMPLETED,
            'completed_at' => now()
        ]);

        return true;
    }

    /**
     * Get formatted public exchange details for display
     */
    public function getPublicExchangeDetailsAttribute(): array
    {
        return [
            'from_user' => $this->fromUser ? $this->fromUser->name : 'Unknown User',
            'schedule_title' => $this->schedule ? $this->schedule->title : 'Schedule not found',
            'schedule_date' => $this->schedule ? $this->schedule->date : null,
            'schedule_time' => $this->getScheduleTimeRange(),
            'status_label' => ucfirst($this->status),
            'posted_date' => $this->requested_at ? $this->requested_at->format('M d, Y H:i') : 'Unknown',
            'interest_count' => $this->interest_count,
            'description' => $this->description,
            'requirements' => $this->requirements,
            'days_since_posted' => $this->requested_at ? $this->requested_at->diffInDays(now()) : 0,
            'is_recent' => $this->requested_at ? $this->requested_at->isAfter(now()->subDays(7)) : false
        ];
    }

    /**
     * Get schedule time range as formatted string
     */
    private function getScheduleTimeRange(): ?string
    {
        if (!$this->schedule) {
            return null;
        }

        $startTime = $this->schedule->start_time;
        $endTime = $this->schedule->end_time;

        if (!$startTime) {
            return 'All day';
        }

        if (!$endTime) {
            return Carbon::parse($startTime)->format('H:i');
        }

        return Carbon::parse($startTime)->format('H:i') . ' - ' . Carbon::parse($endTime)->format('H:i');
    }

    /**
     * Check if current user can cancel this public exchange
     */
    public function canBeCancelledBy($userId): bool
    {
        return $this->from_user_id == $userId && 
               in_array($this->status, [self::STATUS_PENDING, self::STATUS_INTERESTED]);
    }

    /**
     * Get all users who have expressed interest
     */
    public function getInterestedUsers()
    {
        return User::whereIn('id', 
            $this->scheduleExchanges()
                 ->where('status', ScheduleExchange::STATUS_PENDING)
                 ->pluck('from_user_id')
        )->get();
    }

    /**
     * Get statistics for this public exchange
     */
    public function getStatsAttribute(): array
    {
        return [
            'total_interest' => $this->interest_count,
            'pending_requests' => $this->pendingExchanges()->count(),
            'days_active' => $this->requested_at ? $this->requested_at->diffInDays(now()) : 0,
            'is_popular' => $this->interest_count >= 3, // Consider popular if 3+ interested
        ];
    }
}