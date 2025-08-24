<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sick_backup_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained('schedules')->onDelete('cascade');
            $table->foreignId('sick_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('backup_user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->date('date');
            $table->text('reason');
            $table->enum('status', [
                'pending',      // Waiting for backup
                'approved',     // Backup found and approved
                'rejected',     // No backup available or rejected
                'cancelled'     // Cancelled by sick user
            ])->default('pending');
            $table->timestamp('requested_at');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('backup_notes')->nullable(); // Notes from backup user
            $table->text('admin_notes')->nullable();  // Notes from admin if any
            $table->boolean('is_emergency')->default(false); // Mark urgent requests
            $table->integer('backup_offers_count')->default(0); // Track number of backup offers
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['status', 'date']);
            $table->index(['sick_user_id', 'status']);
            $table->index(['backup_user_id', 'status']);
            $table->index(['date', 'status']);
            $table->index('requested_at');
            $table->index('is_emergency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sick_backup_requests');
    }
};