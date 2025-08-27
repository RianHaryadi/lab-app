<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-[#F2F2F2] flex" x-data="{ sidebarOpen: false }">
        <!-- Mobile sidebar overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
             class="fixed inset-0 z-20 bg-black bg-opacity-50 lg:hidden"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"></div>

        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 z-30 w-64 bg-[#4D2D8C] shadow-lg transform lg:translate-x-0 transition duration-300 ease-in-out"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center justify-between px-4 py-5 border-b border-[#FF714B]/20">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                        <img src="{{ asset('images/lab.jpeg') }}" alt="Logo Aplikasi" class="h-8 w-auto">
                        <span class="text-xl font-bold text-white">{{ config('app.name', 'Laravel') }}</span>
                    </a>
                    <button @click="sidebarOpen = false" class="p-1 rounded-md text-white hover:text-[#FF714B] lg:hidden">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <nav class="flex-1 overflow-y-auto py-4">
    <ul class="space-y-1 px-2">
        <li>
    <a href="{{ route('dashboard') }}" wire:navigate
       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors
       {{ request()->routeIs('dashboard') ? 'bg-[#FF714B] text-white' : 'text-white hover:bg-[#C71E64]/20' }}">
        <!-- Ikon Dashboard (Squares) -->
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 3h7v7H3V3zm11 0h7v7h-7V3zM3 14h7v7H3v-7zm11 0h7v7h-7v-7z"/>
        </svg>
        Dashboard
    </a>
</li>
        <li>
            <a href="{{ route('attendance') }}" wire:navigate
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors
               {{ request()->routeIs('attendance') ? 'bg-[#FF714B] text-white' : 'text-white hover:bg-[#C71E64]/20' }}">
                <!-- Ikon Calendar Check -->
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 
                          00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2zm5-7l2 2 4-4"/>
                </svg>
                Absensi
            </a>
        </li>
        <li>
            <a href="{{ route('todo') }}" wire:navigate
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition
               {{ request()->routeIs('todo') ? 'bg-[#FF714B] text-white' : 'text-white hover:bg-[#C71E64]/20' }}">
                <!-- Ikon Clipboard List -->
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5h6M9 9h6m-6 4h6M5 7h.01M5 11h.01M5 15h.01"/>
                </svg>
                TodoList
            </a>
        </li>
        <li>
            <a href="{{ route('schedule') }}" wire:navigate
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition
               {{ request()->routeIs('schedule') ? 'bg-[#FF714B] text-white' : 'text-white hover:bg-[#C71E64]/20' }}">
                <!-- Ikon Calendar Days -->
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 
                          002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Schedule
            </a>
        </li>
        <li>
            <a href="{{ route('task') }}" wire:navigate
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition
               {{ request()->routeIs('task') ? 'bg-[#FF714B] text-white' : 'text-white hover:bg-[#C71E64]/20' }}">
                <!-- Ikon Check Circle -->
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2l4-4m6 2a9 9 0 
                          11-18 0a9 9 0 0118 0z"/>
                </svg>
                Tugas
            </a>
        </li>
        <li>
            <a href="{{ route('project') }}" wire:navigate
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition
               {{ request()->routeIs('project') ? 'bg-[#FF714B] text-white' : 'text-white hover:bg-[#C71E64]/20' }}">
                <!-- Ikon Folder Open -->
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 7a2 2 0 012-2h6l2 2h6a2 2 0 
                          012 2v5a2 2 0 01-2 2H5a2 2 0 
                          01-2-2V7z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 15a2 2 0 012-2h14a2 2 0 
                          012 2v1a2 2 0 01-2 2H5a2 2 0 
                          01-2-2v-1z"/>
                </svg>
                Projects
            </a>
        </li>
    </ul>
</nav>


                <!-- User Profile -->
                <div class="p-4 border-t border-[#FF714B]/20">
                    <div class="flex items-center space-x-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random"
                             alt="User avatar"
                             class="w-10 h-10 rounded-full">
                        <div>
                            <p class="text-sm font-medium text-white" x-data="{ name: '{{ auth()->user()->name }}' }" x-text="name" @profile-updated.window="name = $event.detail.name"></p>
                            <p class="text-xs text-[#F2F2F2]">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <div class="mt-4 space-y-1">
                        <a href="{{ route('profile') }}" wire:navigate
                           class="block px-4 py-2 text-sm text-white hover:bg-[#C71E64]/20 rounded-lg transition-colors">
                            Profile Settings
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-sm text-white hover:bg-[#C71E64]/20 rounded-lg transition-colors">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden lg:pl-64">
            <!-- Mobile header & Desktop top bar -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-4 py-3 lg:justify-end">
                    <!-- Mobile Hamburger Menu -->
                    <button @click="sidebarOpen = true" class="p-2 rounded-md text-[#4D2D8C] hover:text-[#FF714B] lg:hidden">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>

                    <!-- Header Content (jika ada, seperti search bar atau notifikasi) -->
                    <div class="flex items-center">
                        {{-- Bisa ditambahkan search bar atau ikon notifikasi di sini --}}
                    </div>
                </div>
            </header>

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 bg-[#F2F2F2]">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts(['without-alpine' => true])
</body>
</html>