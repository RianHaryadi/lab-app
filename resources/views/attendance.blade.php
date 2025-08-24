<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Absensi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
           
                    <!-- Memanggil komponen Livewire AttendanceAction -->
                    <livewire:attendance-action />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
