<div class="max-w-4xl mx-auto">
    <!-- Daftar Tugas -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-xl mb-8 border border-gray-100 transition-all duration-300">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Daftar Tugas</h2>
        
        @if ($tasks->isEmpty())
            <div class="flex items-center justify-center py-12 text-gray-500">
                <svg class="w-12 h-12 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <p class="text-lg">Belum ada tugas yang dibuat.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer hover:text-gray-800 transition-colors">
                                Judul
                                <span class="ml-1 text-gray-400">↕</span>
                            </th>
                            <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer hover:text-gray-800 transition-colors">
                                Ditugaskan ke
                                <span class="ml-1 text-gray-400">↕</span>
                            </th>
                            <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer hover:text-gray-800 transition-colors">
                                Batas Waktu
                                <span class="ml-1 text-gray-400">↕</span>
                            </th>
                            <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer hover:text-gray-800 transition-colors">
                                Status
                                <span class="ml-1 text-gray-400">↕</span>
                            </th>
                            <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer hover:text-gray-800 transition-colors">
                                Dibuat oleh
                                <span class="ml-1 text-gray-400">↕</span>
                            </th>
                            <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($tasks as $task)
                            <tr wire:key="{{ $task->id }}" class="hover:bg-gray-50 transition-all duration-200 transform hover:scale-[1.01]">
                                <td class="px-4 sm:px-6 py-4 text-sm font-medium text-gray-900 break-words max-w-xs">{{ $task->title }}</td>
                                <td class="px-4 sm:px-6 py-4 text-sm text-gray-600">{{ $task->assignee->name ?? 'N/A' }}</td>
                                <td class="px-4 sm:px-6 py-4 text-sm text-gray-600">{{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}</td>
                                <td class="px-4 sm:px-6 py-4">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($task->status === 'pending') bg-red-200 text-red-900
                                        @elseif($task->status === 'in-progress') bg-yellow-200 text-yellow-900
                                        @else bg-green-200 text-green-900
                                        @endif
                                    ">
                                        {{ ucfirst(str_replace('-', ' ', $task->status)) }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 text-sm text-gray-600">{{ $task->creator->name ?? 'N/A' }}</td>
                                <td class="px-4 sm:px-6 py-4 text-right text-sm font-medium space-x-2">
                                    <button wire:click="edit({{ $task->id }})" class="relative text-blue-600 hover:text-blue-800 transition-colors group" title="Edit Tugas">
                                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        <span class="absolute hidden group-hover:block text-xs bg-gray-800 text-white py-1 px-2 rounded -top-8 -left-2">Edit</span>
                                    </button>
                                    @if($task->status !== 'completed')
                                        <button wire:click="complete({{ $task->id }})" wire:confirm="Apakah Anda yakin ingin menandai tugas ini sebagai selesai?" class="relative text-green-600 hover:text-green-800 transition-colors group" title="Tandai Selesai">
                                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span class="absolute hidden group-hover:block text-xs bg-gray-800 text-white py-1 px-2 rounded -top-8 -left-2">Selesai</span>
                                        </button>
                                    @endif
                                    <button wire:click="delete({{ $task->id }})" wire:confirm="Apakah Anda yakin ingin menghapus tugas ini?" class="relative text-red-600 hover:text-red-800 transition-colors group" title="Hapus Tugas">
                                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        <span class="absolute hidden group-hover:block text-xs bg-gray-800 text-white py-1 px-2 rounded -top-8 -left-2">Hapus</span>
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