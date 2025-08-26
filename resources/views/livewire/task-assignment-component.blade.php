<div class="max-w-4xl mx-auto">
    <!-- Daftar Tugas -->
    <div class="bg-gradient-to-br from-[#F2F2F2] to-[#C71E64]/5 p-6 sm:p-8 rounded-3xl shadow-2xl mb-8 border border-[#C71E64]/20 transition-all duration-500 hover:shadow-3xl">
        <h2 class="text-3xl font-extrabold mb-8 text-[#4D2D8C] tracking-tight animate-fade-in">Daftar Tugas</h2>
        
        @if ($tasks->isEmpty())
            <div class="flex flex-col items-center justify-center py-12 text-[#4D2D8C]/80 bg-[#C71E64]/5 rounded-2xl shadow-inner animate-fade-in">
                <svg class="w-16 h-16 mb-4 text-[#C71E64] animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <p class="text-xl font-semibold">Belum ada tugas yang dibuat.</p>
                <p class="mt-2 text-sm text-[#4D2D8C]/60">Tambahkan tugas baru untuk memulai!</p>
            </div>
        @else
            <div class="overflow-x-auto animate-fade-in">
                <table class="min-w-full divide-y divide-[#C71E64]/20">
                    <thead class="bg-gradient-to-r from-[#C71E64]/10 to-[#FF714B]/10">
                        <tr>
                            <th scope="col" class="px-4 sm:px-6 py-4 text-left text-sm font-semibold text-[#4D2D8C] uppercase tracking-wider cursor-pointer hover:text-[#4D2D8C]/90 transition-colors">
                                Judul
                                <span class="ml-1 text-[#C71E64]/80">↕</span>
                            </th>
                            <th scope="col" class="px-4 sm:px-6 py-4 text-left text-sm font-semibold text-[#4D2D8C] uppercase tracking-wider cursor-pointer hover:text-[#4D2D8C]/90 transition-colors">
                                Ditugaskan ke
                                <span class="ml-1 text-[#C71E64]/80">↕</span>
                            </th>
                            <th scope="col" class="px-4 sm:px-6 py-4 text-left text-sm font-semibold text-[#4D2D8C] uppercase tracking-wider cursor-pointer hover:text-[#4D2D8C]/90 transition-colors">
                                Batas Waktu
                                <span class="ml-1 text-[#C71E64]/80">↕</span>
                            </th>
                            <th scope="col" class="px-4 sm:px-6 py-4 text-left text-sm font-semibold text-[#4D2D8C] uppercase tracking-wider cursor-pointer hover:text-[#4D2D8C]/90 transition-colors">
                                Status
                                <span class="ml-1 text-[#C71E64]/80">↕</span>
                            </th>
                            <th scope="col" class="px-4 sm:px-6 py-4 text-left text-sm font-semibold text-[#4D2D8C] uppercase tracking-wider cursor-pointer hover:text-[#4D2D8C]/90 transition-colors">
                                Dibuat oleh
                                <span class="ml-1 text-[#C71E64]/80">↕</span>
                            </th>
                            <th scope="col" class="px-4 sm:px-6 py-4 text-left text-sm font-semibold text-[#4D2D8C] uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-[#F2F2F2] divide-y divide-[#C71E64]/20">
                        @foreach ($tasks as $task)
                            <tr wire:key="{{ $task->id }}" class="hover:bg-[#C71E64]/5 transition-all duration-300 transform hover:scale-[1.01] sm:table-row flex flex-col sm:flex-row sm:items-center border-b border-[#C71E64]/10">
                                <td class="px-4 sm:px-6 py-4 text-sm font-medium text-[#4D2D8C] break-words max-w-xs sm:table-cell">Judul: {{ $task->title }}</td>
                                <td class="px-4 sm:px-6 py-4 text-sm text-[#4D2D8C]/80 sm:table-cell">Ditugaskan: {{ $task->assignee->name ?? 'N/A' }}</td>
                                <td class="px-4 sm:px-6 py-4 text-sm text-[#4D2D8C]/80 sm:table-cell">Batas Waktu: {{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}</td>
                                <td class="px-4 sm:px-6 py-4 sm:table-cell">
                                    <span class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full
                                        @if($task->status === 'pending') bg-[#C71E64]/20 text-[#C71E64]
                                        @elseif($task->status === 'in-progress') bg-[#FF714B]/20 text-[#FF714B]
                                        @else bg-[#FF714B]/30 text-[#FF714B]
                                        @endif
                                    ">
                                        @if($task->status === 'pending')
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @elseif($task->status === 'in-progress')
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 7l5 5-5 5"></path>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        @endif
                                        {{ ucfirst(str_replace('-', ' ', $task->status)) }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 text-sm text-[#4D2D8C]/80 sm:table-cell">Dibuat oleh: {{ $task->creator->name ?? 'N/A' }}</td>
                                <td class="px-4 sm:px-6 py-4 text-right text-sm font-medium space-x-3 sm:table-cell flex justify-end">
                                    @if($task->status !== 'completed')
                                        <button wire:click="complete({{ $task->id }})" wire:confirm="Apakah Anda yakin ingin menandai tugas ini sebagai selesai?" class="relative bg-[#FF714B]/10 text-[#FF714B] hover:bg-[#FF714B]/20 p-2 rounded-full transition-all duration-300 transform hover:scale-110 group" title="Tandai Selesai" aria-label="Tandai Selesai">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span class="absolute hidden group-hover:block text-xs bg-[#4D2D8C] text-white py-1 px-3 rounded-full -top-10 left-1/2 transform -translate-x-1/2 transition-all duration-200">Selesai</span>
                                        </button>
                                    @endif
                                    <button wire:click="delete({{ $task->id }})" wire:confirm="Apakah Anda yakin ingin menghapus tugas ini?" class="relative bg-[#C71E64]/10 text-[#C71E64] hover:bg-[#C71E64]/20 p-2 rounded-full transition-all duration-300 transform hover:scale-110 group" title="Hapus Tugas" aria-label="Hapus Tugas">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        <span class="absolute hidden group-hover:block text-xs bg-[#4D2D8C] text-white py-1 px-3 rounded-full -top-10 left-1/2 transform -translate-x-1/2 transition-all duration-200">Hapus</span>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>