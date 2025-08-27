<div class="max-w-6xl mx-auto p-6 space-y-8 font-sans">
    {{-- Projects Table --}}
    <div class="bg-white shadow-xl rounded-2xl p-8">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
            <h2 class="text-3xl font-bold text-gray-800">My Projects List</h2>
            <div class="flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-4">
                <input type="text" wire:model.live="search" placeholder="Search projects..." class="px-5 py-3 rounded-xl border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                
                {{-- New Status Filter Dropdown --}}
                <select wire:model.live="statusFilter" class="px-5 py-3 rounded-xl border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                    <option value="all">All Projects</option>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="done">Done</option>
                </select>

                <select wire:model.live="sortBy" class="px-5 py-3 rounded-xl border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                    <option value="name">Sort by Name</option>
                    <option value="status">Sort by Status</option>
                    <option value="deadline">Sort by Deadline</option>
                </select>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="bg-green-100 text-green-700 p-4 rounded-xl mb-4 font-medium">
                {{ session('message') }}
            </div>
        @endif

        <div wire:loading class="text-center text-gray-500 py-4">Loading projects...</div>

        @if ($projects->isEmpty())
            <div class="text-center text-gray-500 py-6">No projects found.</div>
        @else
            <div class="overflow-x-auto shadow-inner rounded-xl">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-medium text-gray-700 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-4 text-left text-sm font-medium text-gray-700 uppercase tracking-wider">Users</th>
                            <th class="px-6 py-4 text-left text-sm font-medium text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-medium text-gray-700 uppercase tracking-wider">Deadline</th>
                            <th class="px-6 py-4 text-left text-sm font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($projects as $project)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $project->name }}</td>
                                <td class="px-6 py-4">
                                    @foreach($project->users as $user)
                                        <span class="inline-block px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium mr-1 mb-1" title="User: {{ $user->name }}">{{ $user->name }}</span>
                                    @endforeach
                                    @if($project->users->isEmpty())
                                        <span class="text-gray-400 text-sm">No users assigned</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{-- Corrected logic to handle 'active' status --}}
                                    @if($project->status === 'pending')
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-medium">Pending</span>
                                    @elseif($project->status === 'in_progress')
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">In Progress</span>
                                    @elseif($project->status === 'active')
                                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">Active</span>
                                    @else
                                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Done</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $project->deadline?->format('d M Y H:i') ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($project->status !== 'done')
                                    <button 
                                        wire:click="markAsDone({{ $project->id }})" 
                                        class="px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-full shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200"
                                        wire:confirm="Are you sure you want to mark this project as done?"
                                    >
                                        Done
                                    </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
    </style>
</div>
