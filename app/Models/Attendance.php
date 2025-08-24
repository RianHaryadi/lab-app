<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'check_in_time',
        'check_out_time',
        'status',
        'backup_by',
        'notes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function backupUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'backup_by');
    }

    /**
     * Dapatkan catatan sesi untuk kehadiran ini.
     */
    public function sessionAttendances(): HasMany
    {
        return $this->hasMany(SessionAttendance::class);
    }
}
