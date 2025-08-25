<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TaskAssignment;
use App\Models\User;

class TaskAssignmentComponent extends Component
{
    public $taskId, $title, $description, $assigned_to, $deadline, $status;
    public $isEditing = false;
    public $users;
    public $tasks;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'assigned_to' => 'required|exists:users,id',
        'deadline' => 'required|date',
        'status' => 'required|in:pending,in-progress,completed',
    ];

    public function mount()
    {
        $this->users = User::all();
        $this->loadTasks();
    }

    public function loadTasks()
    {
        $this->tasks = TaskAssignment::with(['assignee', 'creator'])->orderBy('deadline', 'asc')->get();
    }

    private function resetForm()
    {
        $this->taskId = null;
        $this->title = '';
        $this->description = '';
        $this->assigned_to = '';
        $this->deadline = '';
        $this->status = 'pending';
        $this->isEditing = false;
        $this->resetValidation();
    }

    public function create()
    {
        $this->resetForm();
    }

    public function store()
    {
        $this->validate();

        TaskAssignment::create([
            'title' => $this->title,
            'description' => $this->description,
            'assigned_to' => $this->assigned_to,
            'deadline' => $this->deadline,
            'status' => $this->status,
            'created_by' => auth()->id(),
        ]);

        $this->resetForm();
        $this->loadTasks();
        session()->flash('message', 'Tugas berhasil dibuat!');
    }

    public function edit($id)
    {
        $task = TaskAssignment::findOrFail($id);
        $this->taskId = $task->id;
        $this->title = $task->title;
        $this->description = $task->description;
        $this->assigned_to = $task->assigned_to;
        $this->deadline = $task->deadline->format('Y-m-d');
        $this->status = $task->status;
        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();

        $task = TaskAssignment::findOrFail($this->taskId);
        $task->update([
            'title' => $this->title,
            'description' => $this->description,
            'assigned_to' => $this->assigned_to,
            'deadline' => $this->deadline,
            'status' => $this->status,
        ]);

        $this->resetForm();
        $this->loadTasks();
        session()->flash('message', 'Tugas berhasil diperbarui!');
    }

    public function delete($id)
    {
        TaskAssignment::findOrFail($id)->delete();
        $this->loadTasks();
        session()->flash('message', 'Tugas berhasil dihapus!');
    }

    public function complete($id)
    {
        $task = TaskAssignment::findOrFail($id);
        $task->update([
            'status' => 'done',
        ]);

        $this->loadTasks();
        session()->flash('message', 'Tugas berhasil ditandai sebagai selesai!');
    }

    public function render()
    {
        return view('livewire.task-assignment-component');
    }
}