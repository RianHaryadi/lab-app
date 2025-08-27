<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Todo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserTodo extends Component
{
    public $todos;

    /**
     * Inisialisasi komponen, load daftar todo
     */
    public function mount()
    {
        $this->loadTodos();
    }

    /**
     * Ambil daftar todo untuk user login
     */
    public function loadTodos()
    {
        $this->todos = Todo::where('assigned_to', Auth::id())
            ->orderBy('status')
            ->orderBy('due_date')
            ->get();
    }

    /**
     * Tandai todo sebagai selesai
     */
    public function complete($todoId)
    {
        $todo = Todo::find($todoId);
        if ($todo && $todo->assigned_to === Auth::id()) {
            $todo->update(['status' => 'done']);
            $this->loadTodos();
            session()->flash('message', 'Tugas berhasil ditandai selesai.');
        } else {
            session()->flash('error', 'Tugas tidak ditemukan atau Anda tidak memiliki izin.');
        }
    }

    /**
     * Tandai todo sebagai pending lagi
     */
    public function reopen($todoId)
    {
        $todo = Todo::find($todoId);
        if ($todo && $todo->assigned_to === Auth::id()) {
            $todo->update(['status' => 'pending']);
            $this->loadTodos();
            session()->flash('message', 'Tugas berhasil dibuka kembali.');
        } else {
            session()->flash('error', 'Tugas tidak ditemukan atau Anda tidak memiliki izin.');
        }
    }

    /**
     * Metode untuk menghapus todo
     */
    public function delete($todoId)
    {
        $todo = Todo::find($todoId);
        if ($todo && ($todo->assigned_to === Auth::id() || $todo->created_by === Auth::id())) {
            $todo->delete();
            $this->loadTodos();
            session()->flash('message', 'Tugas berhasil dihapus.');
        } else {
            session()->flash('error', 'Tugas tidak ditemukan atau Anda tidak memiliki izin.');
        }
    }

    /**
     * Metode untuk mengedit todo (contoh redirect)
     */
    public function edit($todoId)
    {
        $todo = Todo::find($todoId);
        if ($todo && ($todo->assigned_to === Auth::id() || $todo->created_by === Auth::id())) {
            // Contoh: redirect ke halaman edit
            return redirect()->route('todos.edit', $todoId);
        } else {
            session()->flash('error', 'Tugas tidak ditemukan atau Anda tidak memiliki izin untuk mengedit.');
        }
    }

    public function render()
    {
        return view('livewire.user-todo');
    }
}