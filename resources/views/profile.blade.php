<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Account Settings</h1>
                <p class="mt-1 text-sm text-gray-500">Manage your profile and security settings</p>
            </div>
            <button class="flex items-center space-x-2 px-4 py-2 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="text-sm font-medium">Settings</span>
            </button>
        </div>
    </x-slot>

    <div class="py-8" x-data="{ activeTab: 'info' }">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-8">
                
                <!-- Profile Sidebar -->
                <div class="w-full md:w-80 flex-shrink-0">
                    <div class="bg-white rounded-2xl shadow-sm p-6 sticky top-6 border border-gray-100">
                        <div class="flex flex-col items-center">
                            <div class="relative mb-4 group">
                                <div class="w-24 h-24 rounded-full bg-gradient-to-r from-indigo-100 to-purple-100 overflow-hidden shadow-inner">
                                    <img class="w-full h-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random&color=fff" alt="{{ auth()->user()->name }}">
                                </div>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900">{{ auth()->user()->name }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ auth()->user()->email }}</p>
                        </div>

                        <nav class="mt-8 space-y-2">
                            <button @click="activeTab = 'info'" 
                                    :class="{ 'bg-indigo-50 text-indigo-700 border-indigo-200': activeTab === 'info' }" 
                                    class="w-full flex items-center px-4 py-3 text-sm font-medium rounded-lg border">
                                Profile Information
                            </button>
                            <button @click="activeTab = 'password'" 
                                    :class="{ 'bg-indigo-50 text-indigo-700 border-indigo-200': activeTab === 'password' }" 
                                    class="w-full flex items-center px-4 py-3 text-sm font-medium rounded-lg border">
                                Change Password
                            </button>
                            <button @click="activeTab = 'delete'" 
                                    :class="{ 'bg-indigo-50 text-indigo-700 border-indigo-200': activeTab === 'delete' }" 
                                    class="w-full flex items-center px-4 py-3 text-sm font-medium rounded-lg border">
                                Delete Account
                            </button>
                        </nav>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="flex-1 space-y-6">
                    
                    <!-- Profile Info -->
                    <div x-show="activeTab === 'info'" x-transition>
                        <div class="bg-white rounded-2xl shadow-sm border">
                            <div class="px-6 py-5 border-b bg-gray-50">
                                <h3 class="text-lg font-semibold text-gray-900">Profile Information</h3>
                                <p class="mt-1 text-sm text-gray-500">Update your account's profile information and email address</p>
                            </div>
                            <div class="p-6">
                                @include('profile.partials.update-profile-information-form')
                            </div>
                        </div>
                    </div>

                    <!-- Update Password -->
                    <div x-show="activeTab === 'password'" x-transition>
                        <div class="bg-white rounded-2xl shadow-sm border">
                            <div class="px-6 py-5 border-b bg-gray-50">
                                <h3 class="text-lg font-semibold text-gray-900">Update Password</h3>
                                <p class="mt-1 text-sm text-gray-500">Ensure your account is using a long, random password</p>
                            </div>
                            <div class="p-6">
                                @include('profile.partials.update-password-form')
                            </div>
                        </div>
                    </div>

                    <!-- Delete Account -->
                    <div x-show="activeTab === 'delete'" x-transition>
                        <div class="bg-white rounded-2xl shadow-sm border border-red-200">
                            <div class="px-6 py-5 border-b bg-red-50">
                                <h3 class="text-lg font-semibold text-red-800">Delete Account</h3>
                                <p class="mt-1 text-sm text-red-600">Once your account is deleted, all resources and data will be permanently deleted</p>
                            </div>
                            <div class="p-6">
                                @include('profile.partials.delete-user-form')
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
