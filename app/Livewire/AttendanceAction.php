<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\SessionAttendance; // Tambahkan import ini
use Livewire\WithFileUploads; // Tambahkan trait ini

class AttendanceAction extends Component
{
    // Tambahkan properti untuk logika UI yang baru
    use WithFileUploads; 

    public $todayAttendance;
    public $currentTime;
    public string $attendanceType = ''; // 'session' or 'task'
    public array $selectedSessions = [];
    public $sessionPhoto; // Untuk file upload
    public $sessions = []; // Properti untuk menyimpan daftar sesi (contoh)

    protected $rules = [
        'attendanceType' => 'required|in:session,task',
        'selectedSessions' => 'required_if:attendanceType,session|array|min:1',
        'sessionPhoto' => 'required_if:attendanceType,session|image|max:2048', // 2MB Max
    ];

    /**
     * Metode mount() dijalankan saat komponen diinisialisasi.
     * Kita akan mengambil data kehadiran hari ini.
     */
    public function mount()
    {
        $this->loadCurrentTime();
        $this->loadTodayAttendance();
        
        // Contoh data sesi, bisa diambil dari database diimplementasi selanjutnya
        $this->sessions = [
            1 => 'Sesi 1',
            2 => 'Sesi 2',
            3 => 'Sesi 3',
            4 => 'Sesi 4',
            5 => 'Sesi 5',
        ];
    }

    /**
     * Memuat waktu saat ini.
     */
    public function loadCurrentTime()
    {
        $this->currentTime = Carbon::now();
    }

    /**
     * Mengambil data kehadiran hari ini untuk pengguna yang sedang login.
     */
    public function loadTodayAttendance()
    {
        // Temukan atau buat record absensi hari ini, dengan memuat relasi
        $this->todayAttendance = Attendance::with('sessionAttendances')->firstOrCreate(
            ['user_id' => Auth::id(), 'date' => now()->toDateString()]
        );
    }
    
    /**
     * Metode untuk melakukan check-in.
     */
    public function checkIn()
    {
        // Jika sudah check-in, jangan lakukan apa-apa
        if ($this->todayAttendance->check_in_time) {
            session()->flash('status', 'Anda sudah melakukan check-in hari ini.');
            return;
        }

        // Perbarui catatan kehadiran dengan waktu check-in
        $this->todayAttendance->check_in_time = Carbon::now();
        $this->todayAttendance->status = 'present'; 
        $this->todayAttendance->save();

        $this->loadTodayAttendance();
        session()->flash('status', 'Check-in berhasil! Selamat bekerja.');
    }

    /**
     * User mengkonfirmasi pilihan kehadiran mereka (Sesi atau Tugas).
     */
    public function confirmAttendanceChoice()
    {
        $this->validateOnly('attendanceType');
        
        // Jika pengguna memilih 'task', buat record placeholder
        if ($this->attendanceType === 'task') {
            $this->todayAttendance->sessionAttendances()->create([
                'session_name' => 'Tugas di Rumah',
                'session_validated_at' => Carbon::now(), // Auto-validated
            ]);
        }
        
        $this->loadTodayAttendance();
    }

    /**
     * Pengguna mengirimkan sesi yang dipilih dan bukti foto.
     */
    public function submitSessions()
    {
        $this->validate([
            'selectedSessions' => 'required|array|min:1',
            'sessionPhoto' => 'required|image|max:2048', // Maksimal 2MB
        ]);

        // Pindahkan foto dari folder sementara ke folder 'public/session-proofs'
        $photoPath = $this->sessionPhoto->store('session-proofs', 'public');

        // Simpan data ke database untuk setiap sesi yang dipilih
        foreach ($this->selectedSessions as $sessionNumber) {
            // Perlu model SessionAttendance dan relasi di model Attendance
            $this->todayAttendance->sessionAttendances()->create([
                'session_name' => 'Sesi ' . $sessionNumber,
                'proof_photo_path' => $photoPath, // Simpan path foto
                'session_validated_at' => now(), // Otomatis validasi
            ]);
        }

        // Kosongkan kembali form dan muat ulang data untuk memperbarui UI
        $this->reset(['selectedSessions', 'sessionPhoto', 'attendanceType']);
        $this->loadTodayAttendance();
    }

    /**
     * Final Step: User checks out for the day.
     */
    public function checkOut()
    {
        if (!$this->todayAttendance || $this->todayAttendance->check_out_time) {
            session()->flash('status', 'Anda sudah melakukan check-out hari ini.');
            return;
        }

        $this->todayAttendance->check_out_time = Carbon::now();
        $this->todayAttendance->save();

        $this->loadTodayAttendance();
        session()->flash('status', 'Check-out berhasil! Selamat beristirahat.');
    }

    public function render()
    {
        return view('livewire.attendance-action');
    }
}
