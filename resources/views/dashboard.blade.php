<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-bold text-3xl leading-tight bg-gradient-to-r from-[#D81B60] to-[#6A1B9A] bg-clip-text text-transparent animate-pulse">
                {{ __('Welcome back, ') }}<span class="text-[#FF5722]">{{ Auth::user()->name }}</span>
            </h2>

            <div class="flex items-center space-x-8 relative">
                <!-- Notification Icon with Dropdown -->
                <div x-data="{ open: false }" @click.away="open = false" class="relative">
                    <button @click="open = !open" class="relative text-[#FF5722] hover:text-[#D81B60] focus:outline-none transition-transform duration-300 transform hover:scale-110">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405C18.21 14.79 18 13.918 18 13V9a6 6 0 10-12 0v4c0 .918-.21 1.79-.595 2.595L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span x-show="open || {{ auth()->user()->unreadNotifications->count() > 0 }}" class="absolute -top-1 -right-1 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-[#D81B60] rounded-full notification-badge">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    </button>

                    <!-- Dropdown Notifications -->
                    <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="absolute right-0 mt-3 w-80 bg-white rounded-xl shadow-2xl py-3 ring-1 ring-gray-200/50 z-20 overflow-hidden">
                        @forelse (auth()->user()->notifications as $notification)
                            <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-5 py-3 text-sm text-[#6A1B9A] hover:bg-gray-100/80 transition-colors duration-300 {{ $notification->read_at ? '' : 'font-bold bg-gray-50' }}">
                                    <div class="flex items-center justify-between">
                                        <span class="flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-[#FF5722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $notification->data['message'] }}
                                        </span>
                                        <span class="text-xs text-[#6A1B9A]/60">{{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-xs text-[#6A1B9A]/60 mt-1">
                                        {{ $notification->data['schedule_title'] }}
                                        @if (isset($notification->data['schedule_date']))
                                            - {{ \Carbon\Carbon::parse($notification->data['schedule_date'])->format('M d, Y') }}
                                        @else
                                            - No date available
                                        @endif
                                    </p>
                                </button>
                            </form>
                        @empty
                            <div class="px-5 py-3 text-sm text-[#6A1B9A]/60 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                No new notifications
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Memanggil komponen Livewire UserDashboard -->
            <livewire:user-dashboard />
        </div>
    </div>
</x-app-layout>