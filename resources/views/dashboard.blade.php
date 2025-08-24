<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Welcome back, ') }}<span class="text-indigo-600">{{ Auth::user()->name }}</span>
            </h2>
            <div class="flex items-center space-x-6">

    <!-- Notification Icon -->
    <button class="relative text-gray-600 hover:text-gray-800 focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405C18.21 14.79 18 13.918 18 13V9a6 6 0 10-12 0v4c0 
                .918-.21 1.79-.595 2.595L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
       
    </button>
</div>


        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <!-- Main Content Area -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Activity Feed -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Recent Activity</h3>
                    <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">View All</a>
                </div>
                <div class="divide-y divide-gray-200">
                    <!-- Activity Item -->
                    <div class="p-6 flex items-start hover:bg-gray-50 transition-colors">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name=John+Doe&background=random" alt="">
                        </div>
                        <div class="ml-4 flex-1">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900">John Doe</p>
                                <p class="text-xs text-gray-500">2h ago</p>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">Created a new project "Website Redesign"</p>
                            <div class="mt-2 flex space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Design</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Urgent</span>
                            </div>
                        </div>
                    </div>
                    <!-- More activity items -->
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="space-y-6">
                <!-- Projects Progress -->
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Projects Progress</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span>Website Redesign</span>
                                    <span>72%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-indigo-600 h-2 rounded-full" style="width: 72%"></div>
                                </div>
                            </div>
                            <!-- More progress bars -->
                        </div>
                    </div>
                </div>

                <!-- Upcoming Tasks -->
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Upcoming Tasks</h3>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 relative mt-0.5">
                                    <input type="checkbox" class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Client meeting</p>
                                    <p class="text-xs text-gray-500 mt-1">Today, 2:00 PM</p>
                                </div>
                            </li>
                            <!-- More tasks -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Projects -->
        <div class="mt-8 bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Recent Projects</h3>
                <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Team</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-md flex items-center justify-center text-indigo-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2H5a1 1 0 010-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">Website Redesign</div>
                                        <div class="text-sm text-gray-500">Marketing</div>
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
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Jun 15, 2023</td>
                        </tr>
                        <!-- More project rows -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>