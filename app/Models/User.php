<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable; // Import Notifiable trait

class User extends Authenticatable
{
    use HasFactory, Notifiable; // Add Notifiable trait here

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'role', // Add role if not already present
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get all schedules created by this user
     */
    public function createdSchedules()
    {
        return $this->hasMany(Schedule::class, 'created_by');
    }

    /**
     * Get all schedules assigned to this user
     */
    public function assignedSchedules()
    {
        return $this->hasMany(Schedule::class, 'user_id');
    }

    /**
     * Get all exchange requests sent by this user
     */
    public function sentExchangeRequests()
    {
        return $this->hasMany(ScheduleExchange::class, 'from_user_id');
    }

    /**
     * Get all exchange requests received by this user
     */
    public function receivedExchangeRequests()
    {
        return $this->hasMany(ScheduleExchange::class, 'to_user_id');
    }

    /**
     * Get all public exchange requests by this user
     */
    public function publicExchangeRequests()
    {
        return $this->hasMany(PublicScheduleExchange::class, 'from_user_id');
    }

    /**
     * Get all sick backup requests where this user is sick
     */
    public function sickRequests()
    {
        return $this->hasMany(SickBackupRequest::class, 'sick_user_id');
    }

    /**
     * Get all backup assignments where this user provides backup
     */
    public function backupAssignments()
    {
        return $this->hasMany(SickBackupRequest::class, 'backup_user_id');
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Get users with the same role
     */
    public static function withSameRole(string $role)
    {
        return static::where('role', $role);
    }

    /**
     * Get projects associated with the user
     */

public function projects()
{
    return $this->belongsToMany(Project::class, 'project_user', 'user_id', 'project_id');
}

}