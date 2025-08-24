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
        Schema::create('public_schedule_exchanges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained('schedules')->onDelete('cascade');
            $table->foreignId('from_user_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', [
                'pending',      // Posted and waiting for interest
                'interested',   // Someone expressed interest
                'matched',      // Matched with someone for exchange
                'completed',    // Exchange completed successfully
                'cancelled'     // Cancelled by original poster
            ])->default('pending');
            $table->timestamp('requested_at');
            $table->timestamp('matched_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('description')->nullable();
            $table->text('requirements')->nullable(); // Any specific requirements for exchange
            $table->integer('interest_count')->default(0); // Track number of people interested
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['status', 'from_user_id']);
            $table->index(['schedule_id', 'status']);
            $table->index('requested_at');
            $table->index('from_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public_schedule_exchanges');
    }
};