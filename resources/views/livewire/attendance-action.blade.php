<div x-data="{ 
        currentTime: '{{ $currentTime->format('H:i:s') }}',
        sessionPhoto: null,
        init() {
            setInterval(() => {
                let d = new Date();
                this.currentTime = d.toLocaleTimeString('id-ID', { hour12: false });
            }, 1000);
        }
    }"
    class="relative bg-white rounded-3xl shadow-2xl p-8 md:p-12 max-w-4xl mx-auto border border-gray-200 overflow-hidden transform transition-all duration-300 hover:shadow-3xl"
>
    <!-- Decorative Accents -->
    <div class="absolute top-0 left-0 w-24 h-24 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full -translate-x-1/2 -translate-y-1/2 opacity-50"></div>
    <div class="absolute bottom-0 right-0 w-32 h-32 bg-gradient-to-tl from-blue-100 to-indigo-100 rounded-full translate-x-1/2 translate-y-1/2 opacity-50"></div>

    <!-- Header: Title and Time -->
    <div class="relative flex flex-col md:flex-row justify-between items-start md:items-center pb-8 border-b-2 border-gray-100">
        <div>
            <h2 class="text-5xl font-extrabold text-gray-900 tracking-wide animate-pulse">Absensi Harian</h2>
            <p class="text-lg text-gray-600 mt-3">{{ $currentTime->format('l, j F Y') }}</p>
        </div>
        <div class="mt-6 md:mt-0">
            <div class="text-6xl font-mono font-bold text-indigo-700 animate-bounce" x-text="currentTime"></div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="relative mt-10 space-y-10">
        <!-- View 1: Before Check-In -->
        @if (!$todayAttendance->check_in_time)
            <div class="text-center py-14 animate-fade-in">
                <div class="mx-auto w-36 h-36 bg-indigo-50 rounded-full flex items-center justify-center mb-8 border-4 border-indigo-200 shadow-lg transform hover:scale-105 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-2xl text-gray-700 mb-10 font-semibold">Anda belum melakukan absen hari ini.</p>
                <button wire:click="checkIn" wire:loading.attr="disabled" class="relative w-full md:w-auto bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-5 px-12 rounded-full transition-all duration-300 transform hover:scale-110 shadow-2xl disabled:opacity-60 overflow-hidden">
                    <span wire:loading.remove wire:target="checkIn">ABSEN SEKARANG</span>
                    <span wire:loading wire:target="checkIn" class="flex items-center justify-center">
                        <svg class="animate-spin -ml-1 mr-3 h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses...
                    </span>
                    <span class="absolute inset-0 bg-white/30 opacity-0 hover:opacity-100 transition-opacity duration-300"></span>
                </button>
            </div>
        @else
            <!-- View 2 & 3: After Check-In -->
            <div class="space-y-10">
                <!-- Check-in Status -->
                <div class="p-8 bg-emerald-50 rounded-3xl text-center shadow-xl">
                    <div class="flex items-center justify-center space-x-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-emerald-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <p class="font-bold text-emerald-800 text-2xl">Berhasil absen pada pukul {{ \Carbon\Carbon::parse($todayAttendance->check_in_time)->format('H:i') }}</p>
                    </div>
                    <p class="text-lg text-emerald-700 mt-4">Silakan pilih aktivitas Anda untuk hari ini.</p>
                </div>

                <!-- Activity Selection -->
                <div class="space-y-6">
                    <label class="flex items-center p-6 border-2 border-gray-200 rounded-2xl cursor-pointer transition-all hover:bg-indigo-50 hover:shadow-2xl bg-white" :class="{'border-indigo-500 bg-indigo-50 shadow-2xl': $wire.attendanceType === 'session'}">
                        <input type="radio" wire:model.live="attendanceType" value="session" class="h-6 w-6 text-indigo-600 border-gray-300 focus:ring-indigo-500" aria-label="Mengikuti Sesi Laboratorium">
                        <span class="ml-5 text-xl font-semibold text-gray-900">Mengikuti Sesi Laboratorium</span>
                    </label>
                    <label class="flex items-center p-6 border-2 border-gray-200 rounded-2xl cursor-pointer transition-all hover:bg-emerald-50 hover:shadow-2xl bg-white" :class="{'border-emerald-500 bg-emerald-50 shadow-2xl': $wire.attendanceType === 'task'}">
                        <input type="radio" wire:model.live="attendanceType" value="task" class="h-6 w-6 text-emerald-600 border-gray-300 focus:ring-emerald-500" aria-label="Mengerjakan Tugas di Rumah">
                        <span class="ml-5 text-xl font-semibold text-gray-900">Mengerjakan Tugas di Rumah</span>
                    </label>
                    @error('attendanceType') 
                        <span class="flex items-center text-base text-red-600 bg-red-50 p-4 rounded-xl mt-3 animate-pulse">
                            <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </span>
                    @endif
                </div>

                <!-- Laboratory Session Form -->
                <div x-show="$wire.attendanceType === 'session'" x-transition.opacity class="space-y-8 p-8 border-l-8 border-indigo-400 bg-indigo-50 rounded-3xl shadow-2xl">
                    <h3 class="font-bold text-gray-900 text-2xl">Pilih Sesi yang Diikuti:</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-6">
                        @foreach(range(1, 5) as $sessionNumber)
                            <label class="flex items-center p-5 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-indigo-100 transition bg-white hover:shadow-xl">
                                <input type="checkbox" wire:model="selectedSessions" value="{{ $sessionNumber }}" class="h-6 w-6 rounded text-indigo-600 focus:ring-indigo-500" aria-label="Sesi {{ $sessionNumber }}">
                                <span class="ml-4 text-lg font-medium text-gray-900">Sesi {{ $sessionNumber }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('selectedSessions') 
                        <span class="flex items-center text-base text-red-600 bg-red-50 p-4 rounded-xl animate-pulse">
                            <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </span>
                    @endif

                    <div class="mt-6">
                        <label for="sessionPhoto" class="block text-lg font-medium text-gray-900">Unggah Bukti Kehadiran:</label>
                        <div class="mt-4 flex items-center space-x-6">
                            <label for="sessionPhoto" class="flex-1 cursor-pointer">
                                <div class="flex items-center justify-between px-6 py-4 bg-white border-2 border-gray-200 rounded-xl hover:bg-gray-50 transition">
                                    <span class="text-lg text-gray-600 truncate" x-text="sessionPhoto ? sessionPhoto.name : 'Pilih file...'"></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </div>
                                <input type="file" wire:model="sessionPhoto" id="sessionPhoto" accept="image/*" capture="environment" class="hidden" @change="sessionPhoto = $event.target.files[0]">
                            </label>
                            <div x-show="sessionPhoto" class="w-24 h-24 rounded-xl overflow-hidden shadow-lg">
                                <img :src="sessionPhoto ? URL.createObjectURL(sessionPhoto) : ''" alt="Preview" class="object-cover w-full h-full">
                            </div>
                        </div>
                        <div wire:loading wire:target="sessionPhoto" class="text-lg text-gray-600 mt-4 flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Mengunggah...
                        </div>
                        @error('sessionPhoto') 
                            <span class="flex items-center text-base text-red-600 bg-red-50 p-4 rounded-xl mt-3 animate-pulse">
                                <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </span>
                        @endif
                    </div>
                    
                    <button wire:click="submitSessions" wire:loading.attr="disabled" class="w-full mt-6 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-5 px-10 rounded-full transition-all duration-300 shadow-2xl disabled:opacity-60">
                        KIRIM SESI
                    </button>
                </div>

                <!-- Homework Task Button -->
                <div x-show="$wire.attendanceType === 'task'" x-transition.opacity>
                    <button wire:click="confirmAttendanceChoice" wire:loading.attr="disabled" class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold py-5 px-10 rounded-full transition-all duration-300 shadow-2xl disabled:opacity-60">
                        KONFIRMASI TUGAS RUMAH
                    </button>
                </div>
            </div>
        @endif
    </div>

    <!-- Status and Check-Out Section -->
    @if ($todayAttendance && $todayAttendance->sessionAttendances->count() > 0)
    <div class="space-y-10 mt-10">
        <!-- Check-In & Check-Out Times -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 text-center">
            <div class="bg-gradient-to-br from-emerald-50 to-white p-8 rounded-3xl border-2 border-emerald-200 shadow-2xl">
                <p class="text-lg font-medium text-emerald-700">ABSEN MASUK</p>
                <p class="text-4xl font-bold text-emerald-800">{{ \Carbon\Carbon::parse($todayAttendance->check_in_time)->format('H:i:s') }}</p>
            </div>
            <div class="bg-gradient-to-br from-gray-50 to-white p-8 rounded-3xl border-2 {{ $todayAttendance->check_out_time ? 'border-red-200' : 'border-gray-200' }} shadow-2xl">
                <p class="text-lg font-medium {{ $todayAttendance->check_out_time ? 'text-red-700' : 'text-gray-600' }}">
                    {{ $todayAttendance->check_out_time ? 'CHECK OUT' : 'Belum Check Out' }}
                </p>
                <p class="text-4xl font-bold {{ $todayAttendance->check_out_time ? 'text-red-800' : 'text-gray-400' }}">
                    {{ $todayAttendance->check_out_time ? \Carbon\Carbon::parse($todayAttendance->check_out_time)->format('H:i:s') : '--:--:--' }}
                </p>
            </div>
        </div>

        <!-- Recorded Activities -->
        <div>
            <h3 class="font-bold text-gray-900 text-2xl mb-6">Aktivitas Hari Ini</h3>
            <ul class="space-y-6">
                @foreach ($todayAttendance->sessionAttendances as $session)
                    <li class="flex items-center justify-between p-6 bg-white rounded-2xl border-2 border-gray-200 shadow-xl hover:shadow-2xl transition-all">
                        <span class="font-medium text-xl text-gray-900">{{ $session->session_name }}</span>
                        @if ($session->session_validated_at)
                            <span class="flex items-center text-base font-semibold text-emerald-600 bg-emerald-100 px-5 py-3 rounded-full">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Tervalidasi
                            </span>
                        @else
                            <span class="flex items-center text-base font-semibold text-amber-600 bg-amber-100 px-5 py-3 rounded-full">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Menunggu
                            </span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Check-Out Action Button -->
        @if (!$todayAttendance->check_out_time)
            <button wire:click="checkOut" wire:loading.attr="disabled" class="w-full bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white font-bold py-5 px-10 rounded-full transition-all duration-300 shadow-2xl disabled:opacity-60">
                <span wire:loading.remove wire:target="checkOut">CHECK OUT</span>
                <span wire:loading wire:target="checkOut" class="flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-3 h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memproses...
                </span>
            </button>
        @else
            <div class="text-center bg-gray-50 p-8 rounded-3xl border-2 border-gray-200 shadow-2xl">
                <p class="font-bold text-gray-900 text-2xl">Absensi hari ini telah selesai.</p>
            </div>
        @endif
    </div>
    @endif
</div>