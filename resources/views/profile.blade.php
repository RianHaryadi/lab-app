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
                
                <!-- Profile Sidebar / Navigation -->
                <div class="w-full md:w-80 flex-shrink-0">
                    <div class="bg-white rounded-2xl shadow-sm p-6 sticky top-6 border border-gray-100">
                        <div class="flex flex-col items-center">
                            <div class="relative mb-4 group">
                                <div class="w-24 h-24 rounded-full bg-gradient-to-r from-indigo-100 to-purple-100 overflow-hidden shadow-inner">
                                    <img class="w-full h-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random&color=fff" alt="{{ auth()->user()->name }}">
                                </div>
                                <button class="absolute bottom-0 right-0 bg-white p-2 rounded-full shadow-md hover:bg-gray-50 transition-colors border border-gray-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900">{{ auth()->user()->name }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ auth()->user()->email }}</p>
                            
                            <div class="mt-6 w-full space-y-3">
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-sm text-gray-500">Member since</span>
                                    </div>
                                    <span class="text-sm font-medium">{{ auth()->user()->created_at->format('M d, Y') }}</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                             </div>
                            </div>
                        </div>
                        
                        <nav class="mt-8 space-y-2">
                            <button @click="activeTab = 'info'" 
                                    :class="{ 
                                        'bg-indigo-50 text-indigo-700 border-indigo-200': activeTab === 'info', 
                                        'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-transparent': activeTab !== 'info' 
                                    }" 
                                    class="w-full flex items-center px-4 py-3 text-sm font-medium rounded-lg border transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" /></svg>
                                Profile Information
                            </button>
                            <button @click="activeTab = 'password'" 
                                    :class="{ 
                                        'bg-indigo-50 text-indigo-700 border-indigo-200': activeTab === 'password', 
                                        'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-transparent': activeTab !== 'password' 
                                    }" 
                                    class="w-full flex items-center px-4 py-3 text-sm font-medium rounded-lg border transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" /></svg>
                                Change Password
                            </button>
                            <button @click="activeTab = 'delete'" 
                                    :class="{ 
                                        'bg-indigo-50 text-indigo-700 border-indigo-200': activeTab === 'delete', 
                                        'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-transparent': activeTab !== 'delete' 
                                    }" 
                                    class="w-full flex items-center px-4 py-3 text-sm font-medium rounded-lg border transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                Delete Account
                            </button>
                        </nav>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="flex-1 space-y-6">
                    
                    <!-- Profile Information Panel -->
                    <div x-show="activeTab === 'info'" x-transition>
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">Profile Information</h3>
                                        <p class="mt-1 text-sm text-gray-500">Update your account's profile information and email address</p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                </div>
                            </div>
                            <div class="p-6">
                                @if (Route::has('profile.edit'))
                                    @include('profile.partials.update-profile-information-form')
                                @else
                                    <livewire:profile.update-profile-information-form />
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Password Update Panel -->
                    <div x-show="activeTab === 'password'" x-transition>
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                                <h3 class="text-lg font-semibold text-gray-900">Update Password</h3>
                                <p class="mt-1 text-sm text-gray-500">Ensure your account is using a long, random password to stay secure</p>
                            </div>
                            <div class="p-6">
                                @if (Route::has('profile.edit'))
                                    @include('profile.partials.update-password-form')
                                @else
                                    <livewire:profile.update-password-form />
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Account Deletion Panel -->
                    <div x-show="activeTab === 'delete'" x-transition>
                        <div class="bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden">
                            <div class="px-6 py-5 border-b border-gray-200 bg-red-50">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 p-2 rounded-full bg-red-100 text-red-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-lg font-semibold text-red-800">Delete Account</h3>
                                        <p class="mt-1 text-sm text-red-600">Once your account is deleted, all of its resources and data will be permanently deleted</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                @if (Route::has('profile.edit'))
                                    @include('profile.partials.delete-user-form')
                                @else
                                    <livewire:profile.delete-user-form />
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>