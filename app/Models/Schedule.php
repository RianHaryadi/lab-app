<?php

// Update App/Models/Schedule.php to add relationships
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'date',
        'start_time',
        'end_time',
        'created_by',
        'user_id',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // Existing relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // New relationships for exchange functionality
    public function exchangeRequests()
    {
        return $this->hasMany(ScheduleExchange::class);
    }

    public function targetExchangeRequests()
    {
        return $this->hasMany(ScheduleExchange::class, 'target_schedule_id');
    }

    public function publicExchanges()
    {
        return $this->hasMany(PublicScheduleExchange::class);
    }

    public function sickBackupRequests()
    {
        return $this->hasMany(SickBackupRequest::class);
    }
}