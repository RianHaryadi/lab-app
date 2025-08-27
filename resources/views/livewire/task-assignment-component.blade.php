<div class="max-w-5xl mx-auto">
    {{-- Flash message --}}
    @if (session('message'))
        <div class="mb-4 rounded-xl bg-green-50 text-green-700 px-4 py-3 border border-green-200">
            {{ session('message') }}
        </div>
    @endif

    <!-- Daftar Tugas -->
    <div class="bg-gradient-to-br from-[#F2F2F2] to-[#C71E64]/5 p-6 sm:p-8 rounded-3xl shadow-2xl mb-8 border border-[#C71E64]/20 transition-all duration-500 hover:shadow-3xl">
        <h2 class="text-3xl font-extrabold mb-8 text-[#4D2D8C] tracking-tight">📋 Daftar Tugas</h2>

        @if ($tasks->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-[#4D2D8C]/80 bg-[#C71E64]/5 rounded-2xl shadow-inner">
                <svg class="w-16 h-16 mb-4 text-[#C71E64] animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 
                          002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 
                          0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="text-xl font-semibold">Belum ada tugas</p>
                <p class="mt-2 text-sm text-[#4D2D8C]/60">Tambahkan tugas baru untuk memulai 🚀</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($tasks as $task)
                    @php
                        $isCompleted = $task->status === 'done';
                    @endphp

                    <div
                        wire:key="{{ $task->id }}"
                        class="bg-white rounded-2xl shadow-md p-5 border border-[#C71E64]/10 flex flex-col sm:flex-row sm:items-center sm:justify-between hover:shadow-lg transition-all duration-300
                               {{ $isCompleted ? 'opacity-90 bg-gray-50' : 'hover:scale-[1.01]' }}"
                    >
                        <!-- Info utama -->
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-[#4D2D8C] {{ $isCompleted ? 'line-through decoration-2' : '' }}">
                                {{ $task->title }}
                            </h3>
                            <p class="text-sm text-[#4D2D8C]/70">
                                Ditugaskan ke:
                                <span class="font-medium">{{ $task->assignee->name ?? 'N/A' }}</span>
                            </p>
                            <p class="text-sm text-[#4D2D8C]/70">
                                Batas waktu:
                                <span class="font-medium">
                                    {{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}
                                </span>
                            </p>
                            <p class="text-sm text-[#4D2D8C]/70">
                                Dibuat oleh:
                                <span class="font-medium">{{ $task->creator->name ?? 'N/A' }}</span>
                            </p>
                        </div>

                        <!-- Status + Aksi -->
                        <div class="mt-3 sm:mt-0 flex items-center space-x-3">
                            <span class="px-3 py-1 inline-flex items-center text-xs font-semibold rounded-full
                                @if($task->status === 'pending') bg-yellow-100 text-yellow-700
                                @elseif($task->status === 'in-progress') bg-blue-100 text-blue-700
                                @else bg-green-100 text-green-700
                                @endif">
                                @if($task->status === 'pending')
                                    ⏳ Pending
                                @elseif($task->status === 'in-progress')
                                    🔄 In Progress
                                @else
                                    ✅ Completed
                                @endif
                            </span>

                            {{-- Tombol aksi hilang jika sudah completed --}}
                            @if(!$isCompleted)
                                <div class="flex space-x-2">
                                    <button
                                        wire:click="complete({{ $task->id }})"
                                        wire:confirm="Apakah Anda yakin ingin menandai tugas ini sebagai selesai?"
                                        class="bg-green-100 text-green-700 hover:bg-green-200 p-2 rounded-full transition transform hover:scale-110"
                                        title="Tandai Selesai"
                                        aria-label="Tandai Selesai"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>

                                    <button
                                        wire:click="delete({{ $task->id }})"
                                        wire:confirm="Apakah Anda yakin ingin menghapus tugas ini?"
                                        class="bg-red-100 text-red-700 hover:bg-red-200 p-2 rounded-full transition transform hover:scale-110"
                                        title="Hapus Tugas"
                                        aria-label="Hapus Tugas"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>