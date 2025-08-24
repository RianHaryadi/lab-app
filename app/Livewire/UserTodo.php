<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Todo;
use Illuminate\Support\Facades\Auth;

class UserTodo extends Component
{
    public $todos;

    /**
     * Metode mount() dijalankan saat komponen diinisialisasi.
     * Kita akan mengambil semua tugas untuk user yang sedang login.
     */
    public function mount()
    {
        $this->loadTodos();
    }

    /**
     * Memuat daftar tugas untuk user yang sedang login.
     */
    public function loadTodos()
    {
        $this->todos = Todo::where('assigned_to', Auth::id())
            ->orderBy('status')
            ->orderBy('due_date')
            ->get();
    }

    /**
     * Mengupdate status tugas.
     * @param int $todoId
     * @param string $newStatus
     */
    public function updateTodoStatus($todoId, $newStatus)
    {
        $todo = Todo::find($todoId);

        if ($todo && $todo->assigned_to === Auth::id()) {
            $todo->status = $newStatus;
            $todo->save();
            $this->loadTodos(); // Muat ulang daftar tugas untuk memperbarui tampilan
        }
    }

    public function render()
    {
        return view('livewire.user-todo');
    }
}
