<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-2xl leading-tight bg-gradient-to-r from-[#C71E64] to-[#4D2D8C] bg-clip-text text-transparent">
                {{ __('Welcome back, ') }}<span class="text-[#FF714B]">{{ Auth::user()->name }}</span>
            </h2>
            <div class="flex items-center space-x-6 relative">
                <!-- Notification Icon with Dropdown -->
                <div x-data="{ open: false }" @click.away="open = false" class="relative">
                    <button @click="open = !open" class="relative text-[#FF714B] hover:text-[#C71E64] focus:outline-none transition-colors duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405C18.21 14.79 18 13.918 18 13V9a6 6 0 10-12 0v4c0 .918-.21 1.79-.595 2.595L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <!-- Badge Notifikasi -->
                        <span x-show="open || {{ auth()->user()->unreadNotifications->count() > 0 }}" class="absolute top-0 right-0 inline-flex items-center justify-center w-4 h-4 text-xs font-bold text-white bg-[#C71E64] rounded-full">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    </button>

                    <!-- Dropdown Notifikasi -->
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-72 bg-white rounded-2xl shadow-xl py-2 ring-1 ring-[#F2F2F2]/50 z-10">
                        @forelse (auth()->user()->notifications as $notification)
                            <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-[#4D2D8C] hover:bg-[#F2F2F2]/20 transition-colors duration-300 {{ $notification->read_at ? '' : 'font-bold' }}">
                                    <div class="flex items-center justify-between">
                                        <span>{{ $notification->data['message'] }}</span>
                                        <span class="text-xs text-[#4D2D8C]/50">{{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-xs text-[#4D2D8C]/50 mt-1">{{ $notification->data['schedule_title'] }} - {{ \Carbon\Carbon::parse($notification->data['schedule_date'])->format('M d, Y') }}</p>
                                </button>
                            </form>
                        @empty
                            <div class="px-4 py-2 text-sm text-[#4D2D8C]/50">No new notifications</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <!-- Sisanya dari dashboard.blade.php tetap sama -->
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-[#F2F2F2]">
        <!-- Main Content Area -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Activity Feed -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="p-6 border-b border-[#F2F2F2]/50 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-[#4D2D8C]">Recent Activity</h3>
                    <a href="#" class="text-sm font-medium text-[#C71E64] hover:text-[#FF714B] transition-colors duration-300">View All</a>
                </div>
                <div class="divide-y divide-[#F2F2F2]/50">
                    <!-- Activity Item -->
                    <div class="p-6 flex items-start hover:bg-[#F2F2F2]/20 transition-colors duration-300">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name=John+Doe&background=random" alt="">
                        </div>
                        <div class="ml-4 flex-1">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900">John Doe</p>
                                <p class="text-xs text-[#4D2D8C]/70">2h ago</p>
                            </div>
                            <p class="text-sm text-[#4D2D8C]/50 mt-1">Created a new project "Website Redesign"</p>
                            <div class="mt-2 flex space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#FF714B]/10 text-[#FF714B]">Design</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#C71E64]/10 text-[#C71E64]">Urgent</span>
                            </div>
                        </div>
                    </div>
                    <!-- More activity items -->
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="space-y-6">
                <!-- Projects Progress -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-6 border-b border-[#F2F2F2]/50">
                        <h3 class="text-lg font-medium text-[#4D2D8C]">Projects Progress</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-[#4D2D8C]">Website Redesign</span>
                                    <span class="text-[#C71E64]">72%</span>
                                </div>
                                <div class="w-full bg-[#F2F2F2]/50 rounded-full h-2">
                                    <div class="bg-[#FF714B] h-2 rounded-full" style="width: 72%"></div>
                                </div>
                            </div>
                            <!-- More progress bars -->
                        </div>
                    </div>
                </div>

                <!-- Upcoming Tasks -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-6 border-b border-[#F2F2F2]/50">
                        <h3 class="text-lg font-medium text-[#4D2D8C]">Upcoming Tasks</h3>
                    </div>
                    <div class="p-6">
                        <ul class="space-x-4">
                            <li class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 relative mt-0.5">
                                    <input type="checkbox" class="h-5 w-5 rounded border-[#F2F2F2]/50 text-[#C71E64] focus:ring-[#FF714B]">
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-[#4D2D8C]">Client meeting</p>
                                    <p class="text-xs text-[#4D2D8C]/50 mt-1">Today, 2:00 PM</p>
                                </div>
                            </li>
                            <!-- More tasks -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Projects -->
        <div class="mt-8 bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="p-6 border-b border-[#F2F2F2]/50 flex justify-between items-center">
                <h3 class="text-lg font-medium text-[#4D2D8C]">Recent Projects</h3>
                <a href="#" class="text-sm font-medium text-[#C71E64] hover:text-[#FF714B] transition-colors duration-300">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#F2F2F2]/50">
                    <thead class="bg-[#F2F2F2]/50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#4D2D8C]/70 uppercase tracking-wider">Project</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#4D2D8C]/70 uppercase tracking-wider">Team</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#4D2D8C]/70 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#4D2D8C]/70 uppercase tracking-wider">Due Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-[#F2F2F2]/50">
                        <tr class="hover:bg-[#F2F2F2]/20 transition-colors duration-300">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-[#FF714B]/10 rounded-md flex items-center justify-center text-[#FF714B]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2H5a1 1 0 010-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-[#4D2D8C]">Website Redesign</div>
                                        <div class="text-sm text-[#4D2D8C]/50">Marketing</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex -space-x-2">
                                    <img class="h-8 w-8 rounded-full border-2 border-white" src="https://randomuser.me/api/portraits/women/12.jpg" alt="">
                                    <img class="h-8 w-8 rounded-full border-2 border-white" src="https://randomuser.me/api/portraits/men/34.jpg" alt="">
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-[#FF714B]/10 text-[#FF714B]">Active</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#4D2D8C]/50">Jun 15, 2023</td>
                        </tr>
                        <!-- More project rows -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>