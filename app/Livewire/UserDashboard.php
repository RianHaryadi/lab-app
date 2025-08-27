<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Schedule;
use App\Models\Todo;
use App\Models\TaskAssignment;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserDashboard extends Component
{
    public $schedules;
    public $todos;
    public $taskAssignments;
    public $projects;
    public $recentActivities;
    public $search = '';
    public $sortBy = 'name';

    public function mount()
    {
        $this->loadData();
    }

    private function loadData()
    {
        $user = Auth::user();

        // Ambil jadwal
        $this->schedules = Schedule::where('user_id', $user->id)
            ->whereDate('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->get();

        // Ambil TODOS (personal task list)
        $this->todos = Todo::with(['assignee', 'creator'])
            ->where('assigned_to', $user->id)
            ->orderBy('due_date')
            ->get();

        // Ambil TASK ASSIGNMENTS (project tasks)
        $this->taskAssignments = TaskAssignment::with(['assignee', 'creator', 'project'])
            ->where('assigned_to', $user->id)
            ->orderBy('deadline')
            ->get();

        // Ambil Recent Activities
        $this->recentActivities = $this->getRecentActivities();

        // Ambil projects dengan filtering dan sorting
        $this->projects = Project::with('users')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->sortBy === 'name', function ($query) {
                $query->orderBy('name');
            })
            ->when($this->sortBy === 'status', function ($query) {
                $query->orderBy('status');
            })
            ->when($this->sortBy === 'deadline', function ($query) {
                $query->orderBy('deadline');
            })
            ->get();
    }

    public function toggleCompleteTodo($todoId)
    {
        $todo = Todo::findOrFail($todoId);

        if ($todo->assigned_to !== Auth::id() && $todo->created_by !== Auth::id()) {
            session()->flash('error', 'Anda tidak memiliki akses untuk todo ini.');
            return;
        }

        $todo->update([
            'status' => ($todo->status === 'done') ? 'pending' : 'done'
        ]);

        $this->refreshTodos();
        $this->refreshActivities();
        session()->flash('message', 'Status todo berhasil diperbarui.');
    }

    public function completeTodo($todoId)
    {
        $todo = Todo::findOrFail($todoId);
        
        if ($todo->assigned_to !== Auth::id() && $todo->created_by !== Auth::id()) {
            session()->flash('error', 'Anda tidak memiliki akses untuk todo ini.');
            return;
        }

        $todo->update(['status' => 'done']);
        $this->refreshTodos();
        $this->refreshActivities();
        session()->flash('message', 'Todo berhasil diselesaikan.');
    }

    public function editTodo($todoId)
    {
        $todo = Todo::findOrFail($todoId);
        
        if ($todo->assigned_to !== Auth::id() && $todo->created_by !== Auth::id()) {
            session()->flash('error', 'Anda tidak memiliki akses untuk mengedit todo ini.');
            return;
        }

        return redirect()->route('todos.edit', $todo);
    }

    public function deleteTodo($todoId)
    {
        $todo = Todo::findOrFail($todoId);
        
        if ($todo->assigned_to !== Auth::id() && $todo->created_by !== Auth::id()) {
            session()->flash('error', 'Anda tidak memiliki akses untuk menghapus todo ini.');
            return;
        }

        $todo->delete();
        $this->refreshTodos();
        $this->refreshActivities();
        session()->flash('message', 'Todo berhasil dihapus dari database.');
    }

    public function toggleCompleteTask($taskId)
    {
        $task = TaskAssignment::findOrFail($taskId);
        
        if ($task->assigned_to !== Auth::id()) {
            session()->flash('error', 'Anda tidak memiliki akses untuk tugas ini.');
            return;
        }

        $task->update([
            'status' => ($task->status === 'done') ? 'pending' : 'done'
        ]);

        $this->refreshTasks();
        $this->refreshActivities();
        session()->flash('message', 'Status task berhasil diperbarui.');
    }

    public function completeTask($taskId)
    {
        $task = TaskAssignment::findOrFail($taskId);
        
        if ($task->assigned_to !== Auth::id()) {
            session()->flash('error', 'Anda tidak memiliki akses untuk tugas ini.');
            return;
        }

        $task->update(['status' => 'done']);
        $this->refreshTasks();
        $this->refreshActivities();
        session()->flash('message', 'Task berhasil diselesaikan.');
    }

    public function deleteTask($taskId)
    {
        $task = TaskAssignment::findOrFail($taskId);
        
        if ($task->assigned_to !== Auth::id() && $task->created_by !== Auth::id()) {
            session()->flash('error', 'Anda tidak memiliki akses untuk menghapus tugas ini.');
            return;
        }

        $task->delete();
        $this->refreshTasks();
        $this->refreshActivities();
        session()->flash('message', 'Task berhasil dihapus.');
    }

    private function refreshTodos()
    {
        $this->todos = Todo::with(['assignee', 'creator'])
            ->where('assigned_to', Auth::id())
            ->orderBy('due_date')
            ->get();
    }

    private function refreshTasks()
    {
        $this->taskAssignments = TaskAssignment::with(['assignee', 'creator'])
            ->where('assigned_to', Auth::id())
            ->orderBy('deadline')
            ->get();
    }

    private function refreshActivities()
    {
        $this->recentActivities = $this->getRecentActivities();
    }

    public function updatedSearch()
    {
        $this->refreshProjects();
    }

    public function updatedSortBy()
    {
        $this->refreshProjects();
    }

    private function refreshProjects()
    {
        $this->projects = Project::with('users')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->sortBy === 'name', function ($query) {
                $query->orderBy('name');
            })
            ->when($this->sortBy === 'status', function ($query) {
                $query->orderBy('status');
            })
            ->when($this->sortBy === 'deadline', function ($query) {
                $query->orderBy('deadline');
            })
            ->get();
    }
    
    private function getRecentActivities()
    {
        $activities = collect();

        // 1. Task Assignments yang baru dibuat atau diupdate
        $recentTasks = TaskAssignment::with(['assignee', 'creator', 'project'])
            ->where('updated_at', '>=', now()->subDays(7))
            ->get()
            ->map(function ($task) {
                return [
                    'id' => $task->id,
                    'type' => 'task_assignment',
                    'title' => $task->title,
                    'description' => "Task assigned to {$task->assignee->name}",
                    'user' => $task->assignee,
                    'creator' => $task->creator,
                    'status' => $task->status,
                    'date' => $task->updated_at,
                    'icon' => '📋',
                    'color' => 'bg-blue-500'
                ];
            });

        // 2. Todos yang baru dibuat atau diupdate
        $recentTodos = Todo::with(['assignee', 'creator'])
            ->where('updated_at', '>=', now()->subDays(7))
            ->get()
            ->map(function ($todo) {
                return [
                    'id' => $todo->id,
                    'type' => 'todo',
                    'title' => $todo->task, // Corrected to use 'task' attribute from model
                    'description' => "Personal todo created by {$todo->creator->name}",
                    'user' => $todo->assignee ?? $todo->creator,
                    'creator' => $todo->creator,
                    'status' => $todo->status,
                    'date' => $todo->updated_at,
                    'icon' => '✅',
                    'color' => 'bg-green-500'
                ];
            });

        // 3. Schedule activities
        $recentSchedules = Schedule::with(['user'])
            ->where('updated_at', '>=', now()->subDays(7))
            ->get()
            ->map(function ($schedule) {
                return [
                    'id' => $schedule->id,
                    'type' => 'schedule',
                    'title' => $schedule->title ?? 'Schedule Update',
                    'description' => "Schedule for {$schedule->user->name} on " . $schedule->date->format('M d'),
                    'user' => $schedule->user,
                    'creator' => $schedule->user,
                    'status' => $schedule->status ?? 'scheduled',
                    'date' => $schedule->updated_at,
                    'icon' => '📅',
                    'color' => 'bg-purple-500'
                ];
            });

        // 4. Project updates
        $recentProjects = Project::with(['users'])
            ->where('updated_at', '>=', now()->subDays(7))
            ->get()
            ->map(function ($project) {
                return [
                    'id' => $project->id,
                    'type' => 'project',
                    'title' => $project->name,
                    'description' => "Project status updated to {$project->status}",
                    'user' => $project->users->first(),
                    'creator' => $project->users->first(),
                    'status' => $project->status,
                    'date' => $project->updated_at,
                    'icon' => '🚀',
                    'color' => 'bg-indigo-500'
                ];
            });

        // 5. Tambahan: Aktivitas pertukaran jadwal
        try {
            $scheduleSwaps = \DB::table('schedule_swaps')
                ->join('users as requester', 'schedule_swaps.requester_id', '=', 'requester.id')
                ->join('users as target', 'schedule_swaps.target_id', '=', 'target.id')
                ->where('schedule_swaps.created_at', '>=', now()->subDays(7))
                ->select(
                    'schedule_swaps.*',
                    'requester.name as requester_name',
                    'target.name as target_name'
                )
                ->get()
                ->map(function ($swap) {
                    return [
                        'id' => $swap->id,
                        'type' => 'schedule_swap',
                        'title' => 'Schedule Swap Request',
                        'description' => "{$swap->requester_name} requested to swap schedule with {$swap->target_name}",
                        'user' => (object)['name' => $swap->requester_name],
                        'creator' => (object)['name' => $swap->requester_name],
                        'status' => $swap->status,
                        'date' => $swap->created_at,
                        'icon' => '🔄',
                        'color' => 'bg-orange-500'
                    ];
                });
        } catch (\Exception $e) {
            $scheduleSwaps = collect();
        }

        // Merge all activities
        $activities = $activities
            ->concat($recentTasks)
            ->concat($recentTodos)
            ->concat($recentSchedules)
            ->concat($recentProjects)
            ->concat($scheduleSwaps);

        // Sort by most recent date and take the last 10
        return $activities->sortByDesc('date')->take(10)->values();
    }


    public function render()
    {
        return view('livewire.user-dashboard', [
            'schedules' => $this->schedules,
            'todos' => $this->todos,
            'tasks' => $this->taskAssignments,
            'taskAssignments' => $this->taskAssignments,
            'projects' => $this->projects,
            'recentActivities' => $this->recentActivities,
        ]);
    }
}