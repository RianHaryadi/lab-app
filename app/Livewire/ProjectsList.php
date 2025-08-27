<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ProjectsList extends Component
{
    // Properti publik yang dapat diakses dari view Blade.
    public $projects;
    public $search = '';
    public $sortBy = 'name';
    public $statusFilter = 'all'; // Properti baru untuk filter status

    // Metode mount() dipanggil saat komponen pertama kali dibuat.
    public function mount()
    {
        $this->loadProjects();
    }

    // Metode yang dipanggil saat properti $search diperbarui.
    public function updatedSearch()
    {
        $this->loadProjects();
    }

    // Metode yang dipanggil saat properti $sortBy diperbarui.
    public function updatedSortBy()
    {
        $this->loadProjects();
    }

    // Metode yang dipanggil saat properti $statusFilter diperbarui.
    public function updatedStatusFilter()
    {
        $this->loadProjects();
    }

    // Metode utama untuk mengambil dan memfilter data proyek.
    public function loadProjects()
    {
        // Mendapatkan pengguna yang sedang terautentikasi.
        $user = Auth::user();

        // Jika ada pengguna yang login, ambil proyek-proyek mereka.
        if ($user) {
            $query = $user->projects()->with('users');

            // Terapkan filter pencarian jika properti $search tidak kosong.
            if ($this->search) {
                $query->where('name', 'like', '%' . $this->search . '%');
            }
            
            // Terapkan filter status jika properti $statusFilter bukan 'all'.
            if ($this->statusFilter !== 'all') {
                $query->where('status', $this->statusFilter);
            }

            // Terapkan pengurutan berdasarkan properti $sortBy.
            if ($this->sortBy === 'name') {
                $query->orderBy('name');
            } elseif ($this->sortBy === 'status') {
                $query->orderBy('status');
            } elseif ($this->sortBy === 'deadline') {
                $query->orderBy('deadline');
            }

            $this->projects = $query->get();
        } else {
            // Jika tidak ada pengguna yang login, tampilkan daftar kosong.
            $this->projects = collect();
        }
    }

    // Aksi untuk menandai proyek sebagai selesai.
    // Menggunakan Route Model Binding, Livewire secara otomatis menemukan proyek berdasarkan ID.
    public function markAsDone(Project $project)
    {
        // Perbarui status proyek menjadi 'done'.
        $project->update(['status' => 'done']);

        // Set pesan flash untuk ditampilkan kepada pengguna.
        Session::flash('message', 'Project marked as done!');

        // Muat ulang daftar proyek untuk memperbarui tampilan.
        $this->loadProjects();
    }

    // Metode render() menampilkan view Blade.
    public function render()
    {
        return view('livewire.projects-list');
    }
}
