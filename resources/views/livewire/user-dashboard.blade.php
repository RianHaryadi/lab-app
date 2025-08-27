<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #4D2D8C;
            --secondary: #C71E64;
            --accent: #FF714B;
            --light: #F2F2F2;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 35px 60px -12px rgba(0, 0, 0, 0.25);
        }

        .gradient-card {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 50%, var(--accent) 100%);
            border-radius: 2rem;
            padding: 2px;
            position: relative;
            overflow: hidden;
        }

        .gradient-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .gradient-inner {
            background: white;
            border-radius: calc(2rem - 2px);
            padding: 2rem;
            height: 100%;
            position: relative;
            z-index: 1;
        }

        .floating-animation {
            animation: floating 6s ease-in-out infinite;
        }

        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .stagger-animation {
            animation: staggerFadeIn 0.8s ease-out forwards;
            opacity: 0;
        }

        .stagger-animation:nth-child(1) { animation-delay: 0.1s; }
        .stagger-animation:nth-child(2) { animation-delay: 0.2s; }
        .stagger-animation:nth-child(3) { animation-delay: 0.3s; }
        .stagger-animation:nth-child(4) { animation-delay: 0.4s; }

        @keyframes staggerFadeIn {
            from {
                opacity: 0;
                transform: translateY(30px) rotateX(15deg);
            }
            to {
                opacity: 1;
                transform: translateY(0) rotateX(0deg);
            }
        }

        .neon-text {
            text-shadow: 0 0 20px rgba(77, 45, 140, 0.5);
        }

        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: var(--secondary) transparent;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(var(--secondary), var(--accent));
            border-radius: 10px;
        }

        .pulse-ring {
            animation: pulseRing 2s cubic-bezier(0.455, 0.03, 0.515, 0.955) infinite;
        }

        @keyframes pulseRing {
            0% {
                transform: scale(0.8);
                opacity: 1;
            }
            100% {
                transform: scale(2.4);
                opacity: 0;
            }
        }

        .morphing-bg {
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes fade-in {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.5s ease-out;
        }
        @keyframes pulse-icon {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .animate-pulse-icon {
            animation: pulse-icon 1.5s ease-in-out infinite;
        }
    </style>
    @livewireStyles
</head>
<body class="morphing-bg font-sans text-gray-800">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            
            <div class="xl:col-span-2">
                <div class="glass-card p-8 stagger-animation">
                    <div class="flex justify-between items-center mb-8">
                        <h3 class="text-3xl font-bold text-gray-900 flex items-center">
                            <span class="mr-3">⚡</span>
                            Recent Activity
                        </h3>
                        <a href="#" class="group flex items-center text-sm font-semibold text-purple-600 hover:text-purple-800 transition-all duration-300">
                            View All
                            <svg class="ml-2 w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                    
                    <div class="space-y-6 custom-scrollbar max-h-96 overflow-y-auto">
                        @if ($recentActivities->isEmpty())
                            <div class="text-center text-gray-500 py-16 space-y-4">
                                <div class="text-6xl">✨</div>
                                <p class="text-lg">No recent activity yet</p>
                                <p class="text-sm text-gray-400">Your recent actions will appear here</p>
                            </div>
                        @else
                            @foreach ($recentActivities as $index => $activity)
                                <div class="flex items-start space-x-4 p-4 rounded-xl transition-all duration-300 transform hover:scale-105" 
                                     style="animation-delay: {{ $index * 0.1 }}s; background: linear-gradient(to right, {{ $activity['color'] }}10, #fff)">
                                    <div class="relative">
                                        <div class="w-14 h-14 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg {{ $activity['color'] }}">
                                            {{ $activity['icon'] }}
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-2">
                                            <p class="text-gray-900 font-semibold truncate">{{ $activity['title'] }}</p>
                                            <span class="text-gray-400 text-sm whitespace-nowrap ml-2">{{ \Carbon\Carbon::parse($activity['date'])->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm text-gray-600 mb-2 leading-relaxed">{{ $activity['description'] }}</p>
                                        <div class="flex items-center space-x-2">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                @if($activity['status'] === 'pending') bg-yellow-100 text-yellow-700
                                                @elseif($activity['status'] === 'in_progress' || $activity['status'] === 'scheduled') bg-blue-100 text-blue-700
                                                @else bg-green-100 text-green-700
                                                @endif
                                            ">
                                                {{ ucfirst(str_replace('_', ' ', $activity['status'])) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="glass-card p-8 stagger-animation">
                <h3 class="text-3xl font-bold text-gray-900 mb-8 flex items-center">
                    <span class="mr-3">🎯</span>
                    Project Tasks
                </h3>
                
                @if($taskAssignments->isEmpty())
                    <div class="text-center text-gray-500 py-16 space-y-4">
                        <div class="text-6xl">📝</div>
                        <p class="text-lg">No project tasks yet</p>
                        <p class="text-sm text-gray-400">Tasks will appear here once assigned</p>
                    </div>
                @else
                    <div class="space-y-4 custom-scrollbar max-h-96 overflow-y-auto">
                        @foreach ($taskAssignments as $task)
                            @php $isCompleted = $task->status === 'done'; @endphp
                            <div wire:key="task-{{ $task->id }}" 
                                 class="bg-gradient-to-r from-gray-50 to-white rounded-xl p-5 border border-gray-100 transition-all duration-300 hover:shadow-md transform hover:scale-105 {{ $isCompleted ? 'opacity-70' : '' }}">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h4 class="font-bold text-gray-900 text-lg mb-2 {{ $isCompleted ? 'line-through' : '' }}">
                                            {{ $task->title }}
                                        </h4>
                                        <div class="space-y-1 text-sm text-gray-600">
                                            <p class="flex items-center">
                                                <span class="mr-2">👤</span>
                                                {{ $task->assignee->name ?? 'N/A' }}
                                            </p>
                                            <p class="flex items-center">
                                                <span class="mr-2">📅</span>
                                                {{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2 ml-4">
                                        @if(!$isCompleted)
                                            <button wire:click="toggleCompleteTask({{ $task->id }})" 
                                                    class="p-2 rounded-full bg-green-100 text-green-700 hover:bg-green-200 transition-all duration-300 transform hover:scale-110" 
                                                    title="Mark Complete">
                                                ✅
                                            </button>
                                        @endif
                                        <button wire:click="deleteTask({{ $task->id }})" 
                                                class="p-2 rounded-full bg-red-100 text-red-700 hover:bg-red-200 transition-all duration-300 transform hover:scale-110" 
                                                title="Delete Task">
                                            🗑️
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="glass-card p-8 stagger-animation">
            <h2 class="text-4xl font-bold mb-8 text-gray-900 flex items-center">
                <span class="mr-4">📝</span>
                Personal TodoList
                <span class="ml-4 text-2xl animate-pulse">✨</span>
            </h2>
            
            @if ($todos->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 text-gray-600">
                    <div class="text-8xl mb-6 floating-animation">📋</div>
                    <h3 class="text-2xl font-bold mb-2">No todos yet!</h3>
                    <p class="text-lg text-gray-500">Add your first todo to get started</p>
                    <div class="mt-6 w-32 h-1 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full"></div>
                </div>
            @else
                <div class="overflow-hidden rounded-2xl shadow-lg">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="min-w-full">
                            <thead class="bg-gradient-to-r from-purple-600 via-pink-600 to-red-600 text-white">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">
                                        📝 Todo Title
                                    </th>
                                    <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">
                                        ⏰ Due Date
                                    </th>
                                    <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">
                                        🎯 Status
                                    </th>
                                    <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">
                                        👤 Creator
                                    </th>
                                    <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">
                                        ⚡ Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($todos as $index => $todo)
                                    <tr wire:key="todo-{{ $todo->id }}" 
                                        class="hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 transition-all duration-300 transform hover:scale-[1.02]"
                                        style="animation-delay: {{ $index * 0.1 }}s">
                                        
                                        <td class="px-6 py-5">
                                            <div class="space-y-2">
                                                <span class="font-semibold text-lg {{ $todo->status === 'done' ? 'line-through text-gray-500' : 'text-gray-900' }}">
                                                    {{ $todo->task }}
                                                </span>
                                                @if($todo->description)
                                                    <p class="text-sm text-gray-600 max-w-xs">{{ Str::limit($todo->description, 60) }}</p>
                                                @endif
                                            </div>
                                        </td>
                                        
                                        <td class="px-6 py-5">
                                            @if($todo->due_date)
                                                <div class="space-y-1">
                                                    <div class="font-medium text-gray-900">
                                                        {{ \Carbon\Carbon::parse($todo->due_date)->format('d M Y') }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ \Carbon\Carbon::parse($todo->due_date)->diffForHumans() }}
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-gray-400 italic">No due date</span>
                                            @endif
                                        </td>
                                        
                                        <td class="px-6 py-5">
                                            <span class="px-4 py-2 inline-flex items-center text-sm font-semibold rounded-full
                                                @if($todo->status === 'pending') bg-yellow-100 text-yellow-800 border border-yellow-200
                                                @elseif($todo->status === 'in-progress') bg-blue-100 text-blue-800 border border-blue-200
                                                @else bg-green-100 text-green-800 border border-green-200
                                                @endif
                                            ">
                                                @if($todo->status === 'pending')
                                                    <span class="mr-2">⏳</span> Pending
                                                @elseif($todo->status === 'in-progress')
                                                    <span class="mr-2">🔄</span> In Progress
                                                @else
                                                    <span class="mr-2">✅</span> Completed
                                                @endif
                                            </span>
                                        </td>
                                        
                                        <td class="px-6 py-5">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold">
                                                    {{ strtoupper(substr($todo->creator->name ?? 'U', 0, 1)) }}
                                                </div>
                                                <span class="font-medium text-gray-900">{{ $todo->creator->name ?? 'Unknown' }}</span>
                                            </div>
                                        </td>
                                        
                                        <td class="px-6 py-5">
                                            <div class="flex items-center space-x-2">
                                                @if(Auth::id() === $todo->created_by || Auth::id() === $todo->assigned_to)
                                                    <button wire:click="editTodo({{ $todo->id }})" 
                                                            class="p-2 bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-all duration-300 transform hover:scale-110" 
                                                            title="Edit Todo">
                                                        ✏️
                                                    </button>
                                                @endif

                                                @if($todo->status !== 'done')
                                                    <button wire:click="completeTodo({{ $todo->id }})" 
                                                            wire:confirm="Mark this todo as complete?"
                                                            class="p-2 bg-green-100 text-green-700 rounded-full hover:bg-green-200 transition-all duration-300 transform hover:scale-110" 
                                                            title="Mark Complete">
                                                        ✅
                                                    </button>
                                                @else
                                                    <button wire:click="toggleCompleteTodo({{ $todo->id }})" 
                                                            wire:confirm="Revert this todo to pending?"
                                                            class="p-2 bg-yellow-100 text-yellow-700 rounded-full hover:bg-yellow-200 transition-all duration-300 transform hover:scale-110" 
                                                            title="Undo Complete">
                                                        ↩️
                                                    </button>
                                                @endif

                                                @if(Auth::id() === $todo->created_by || Auth::id() === $todo->assigned_to)
                                                    <button wire:click="deleteTodo({{ $todo->id }})" 
                                                            wire:confirm="Delete this todo permanently?"
                                                            class="p-2 bg-red-100 text-red-700 rounded-full hover:bg-red-200 transition-all duration-300 transform hover:scale-110" 
                                                            title="Delete Todo">
                                                        🗑️
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <div class="glass-card p-8 stagger-animation">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 space-y-4 lg:space-y-0">
                <h2 class="text-4xl font-bold text-gray-900 flex items-center">
                    <span class="mr-4">🚀</span>
                    All Projects
                </h2>
                <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 w-full lg:w-auto">
                    <input type="text" 
                            wire:model.live="search" 
                            placeholder="🔍 Search projects..." 
                            class="px-6 py-3 rounded-full border-2 border-gray-200 shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300 bg-white/80 backdrop-blur">
                    
                    {{-- Status Filter Dropdown --}}
                    <select wire:model.live="statusFilter" 
                            class="px-6 py-3 rounded-full border-2 border-gray-200 shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300 bg-white/80 backdrop-blur">
                        <option value="all">All Projects</option>
                        <option value="active">Active</option>
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="done">Done</option>
                    </select>

                    <select wire:model.live="sortBy" 
                            class="px-6 py-3 rounded-full border-2 border-gray-200 shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300 bg-white/80 backdrop-blur">
                        <option value="name">📝 Sort by Name</option>
                        <option value="status">📊 Sort by Status</option>
                        <option value="deadline">📅 Sort by Deadline</option>
                    </select>
                </div>
            </div>

            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-200 text-green-800 p-4 rounded-xl mb-6 font-medium flex items-center animate-fade-in">
                    <span class="mr-2">✅</span>
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="bg-red-100 border border-red-200 text-red-800 p-4 rounded-xl mb-6 font-medium flex items-center animate-fade-in">
                    <span class="mr-2">❌</span>
                    {{ session('error') }}
                </div>
            @endif

            <div wire:loading class="text-center text-gray-500 py-12 text-lg flex items-center justify-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-500 mr-3"></div>
                Loading projects...
            </div>

            @if ($projects->isEmpty())
                <div class="text-center text-gray-500 py-16 space-y-4">
                    <div class="text-6xl floating-animation">📂</div>
                    <p class="text-xl">No projects found</p>
                    <p class="text-gray-400">Start by creating your first project</p>
                </div>
            @else
                <div class="overflow-hidden rounded-2xl shadow-lg">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="min-w-full">
                            <thead class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">
                                        🚀 Project Name
                                    </th>
                                    <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">
                                        👥 Team
                                    </th>
                                    <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">
                                        📊 Status
                                    </th>
                                    <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">
                                        📅 Deadline
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($projects as $project)
                                    <tr class="hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 transition-all duration-300 transform hover:scale-[1.01]">
                                        <td class="px-6 py-5 flex items-center">
                                            <div class="flex flex-col">
                                                <span class="font-semibold text-lg text-gray-900">{{ $project->name }}</span>
                                                <span class="text-sm text-gray-500 flex items-center mt-1">
                                                    {{ $project->description ?? '' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5">
                                            <div class="flex -space-x-3">
                                                @foreach($project->users->take(4) as $user)
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&size=40" 
                                                            alt="{{ $user->name }}" 
                                                            class="w-10 h-10 rounded-full border-3 border-white shadow-lg transform hover:scale-125 hover:z-10 transition-all duration-300" 
                                                            title="{{ $user->name }}">
                                                @endforeach
                                                @if($project->users->count() > 4)
                                                    <div class="w-10 h-10 rounded-full border-3 border-white bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-600 shadow-lg">
                                                        +{{ $project->users->count() - 4 }}
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-5">
                                            @if($project->status === 'pending')
                                                <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                    ⏳ Pending
                                                </span>
                                            @elseif($project->status === 'in_progress')
                                                <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                                    🔄 In Progress
                                                </span>
                                            @elseif($project->status === 'active')
                                                <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                                                    🟢 Active
                                                </span>
                                            @else
                                                <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                                    ✅ Done
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-5 text-gray-600">
                                            <div class="flex items-center space-x-2">
                                                <span>📅</span>
                                                <span class="font-medium">{{ $project->deadline?->format('d M Y') ?? 'No deadline' }}</span>
                                            </div>
                                            @if($project->deadline)
                                                <div class="text-xs text-gray-400 mt-1">
                                                    {{ \Carbon\Carbon::parse($project->deadline)->diffForHumans() }}
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

    @livewireScripts
</body>
</html>