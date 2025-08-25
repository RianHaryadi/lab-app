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
    <div class="min-h-screen bg-gray-100 flex" x-data="{ sidebarOpen: false }">
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
        <aside class="fixed inset-y-0 left-0 z-30 w-64 bg-white shadow-lg transform lg:translate-x-0 transition duration-300 ease-in-out"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center justify-between px-4 py-5 border-b">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                        <img src="{{ asset('images/lab.jpeg') }}" alt="Logo Aplikasi" class="h-8 w-auto">
                        <span class="text-xl font-bold text-gray-800">{{ config('app.name', 'Laravel') }}</span>
                    </a>
                    <button @click="sidebarOpen = false" class="p-1 rounded-md text-gray-500 hover:text-gray-700 lg:hidden">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <nav class="flex-1 overflow-y-auto py-4">
                    <ul class="space-y-1 px-2">
                        <li>
                            <a href="{{ route('dashboard') }}" wire:navigate
                               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors
                               {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-100' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('attendance') }}" wire:navigate
                               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors
                               {{ request()->routeIs('attendance') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-100' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h6M9 7h6m-6 0V5a2 2 0 012-2h6a2 2 0 012 2v2m-6 0h6m-6 0v6a2 2 0 002 2h6m-6 0h6" /></svg>
                                Absensi
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('todo') }}" wire:navigate
                                class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition
                                {{ request()->routeIs('todo') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-100' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                TodoList
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('schedule') }}" wire:navigate
                                class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition
                                {{ request()->routeIs('schedule') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-100' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                Schedule
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('task') }}" wire:navigate
                                class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition
                                {{ request()->routeIs('task') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-100' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                Tugas
                            </a>
                        </li>
                    </ul>
                </nav>

                <!-- User Profile -->
                <div class="p-4 border-t">
                    <div class="flex items-center space-x-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random"
                             alt="User avatar"
                             class="w-10 h-10 rounded-full">
                        <div>
                            <p class="text-sm font-medium text-gray-900" x-data="{ name: '{{ auth()->user()->name }}' }" x-text="name" @profile-updated.window="name = $event.detail.name"></p>
                            <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <div class="mt-4 space-y-1">
                        <a href="{{ route('profile') }}" wire:navigate
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            Profile Settings
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
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
                    <button @click="sidebarOpen = true" class="p-2 rounded-md text-gray-500 hover:text-gray-600 lg:hidden">
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
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts(['without-alpine' => true])
</body>
</html>