<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('session_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_id')->constrained('attendances')->onDelete('cascade');
            $table->enum('session_name', ['Sesi 1', 'Sesi 2', 'Sesi 3', 'Sesi 4', 'Sesi 5', 'Tugas di Rumah']);
            $table->string('proof_photo_path')->nullable();
            $table->dateTime('session_validated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_attendances');
    }
};