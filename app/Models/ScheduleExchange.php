<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleExchange extends Model
{
    protected $fillable = [
        'schedule_id',
        'target_schedule_id',
        'from_user_id',
        'to_user_id',
        'status',
        'requested_at',
        'approved_at',
        'rejected_at',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    public function targetSchedule()
    {
        return $this->belongsTo(Schedule::class, 'target_schedule_id');
    }
}