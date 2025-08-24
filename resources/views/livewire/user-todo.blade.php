<div class="bg-white overflow-hidden shadow-2xl rounded-2xl p-6 lg:p-10 transform transition-all duration-300 hover:shadow-3xl">
    <h2 class="text-3xl font-extrabold mb-6 text-indigo-900 tracking-wide animate-pulse">Daftar Tugas Saya</h2>
    
    @if($todos->isEmpty())
        <div class="text-center py-12 bg-gray-50 rounded-xl shadow-inner animate-fade-in">
            <svg class="mx-auto h-16 w-16 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M18 10h.01" />
            </svg>
            <h3 class="mt-4 text-lg font-semibold text-gray-700">Tidak ada tugas saat ini.</h3>
            <p class="mt-2 text-sm text-gray-500">Kerja bagus! Hubungi admin jika ada kesalahan.</p>
        </div>
    @else
        <ul class="space-y-6">
            @foreach($todos as $todo)
                <li class="bg-white border-2 border-gray-100 rounded-xl p-5 shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <div>
                        <div class="font-semibold text-xl text-gray-900">{{ $todo->task }}</div>
                        <div class="text-sm text-gray-600">
                            @if($todo->due_date)
                                Batas waktu: {{ \Carbon\Carbon::parse($todo->due_date)->translatedFormat('d F Y') }}
                            @else
                                Batas waktu: Tidak ditentukan
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        @if ($todo->status === 'pending')
                            <button wire:click="updateTodoStatus({{ $todo->id }}, 'in_progress')" class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-bold py-2 px-4 rounded-full transition-all duration-300 transform hover:scale-105">
                                Mulai
                            </button>
                        @elseif ($todo->status === 'in_progress')
                            <button wire:click="updateTodoStatus({{ $todo->id }}, 'done')" class="bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-bold py-2 px-4 rounded-full transition-all duration-300 transform hover:scale-105">
                                Selesai
                            </button>
                        @else
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800 shadow-inner animate-pulse">
                                Selesai
                            </span>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>