<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Schedule;
use App\Models\Todo;
use Illuminate\Support\Facades\Auth;

class UserDashboard extends Component
{
    // Properti publik untuk menyimpan data jadwal dan tugas
    public $schedules;
    public $todos;

    /**
     * Metode mount() dijalankan saat komponen diinisialisasi.
     * Kita akan mengambil data awal di sini.
     */
    public function mount()
    {
        $user = Auth::user();

        // Mengambil jadwal yang akan datang untuk pengguna yang sedang login
        $this->schedules = Schedule::where('user_id', $user->id)
            ->whereDate('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->get();

        // Mengambil tugas yang belum selesai yang ditugaskan kepada pengguna ini
        $this->todos = Todo::where('assigned_to', $user->id)
            ->where('status', '!=', 'done')
            ->orderBy('due_date')
            ->get();
    }

    /**
     * Metode render() mengembalikan tampilan komponen.
     */
    public function render()
    {
        return view('livewire.user-dashboard');
    }
}
