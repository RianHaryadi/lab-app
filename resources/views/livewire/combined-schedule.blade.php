<div class="container mx-auto p-6">
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">My Schedule Dashboard</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-indigo-50 p-4 rounded-lg">
                <h3 class="text-sm font-medium text-indigo-600">Total Schedules</h3>
                <p class="text-2xl font-bold text-indigo-800">{{ $totalSchedules }}</p>
            </div>
            <div class="bg-emerald-50 p-4 rounded-lg">
                <h3 class="text-sm font-medium text-emerald-600">Today's Schedules</h3>
                <p class="text-2xl font-bold text-emerald-800">{{ $todaySchedules }}</p>
            </div>
            <div class="bg-amber-50 p-4 rounded-lg">
                <h3 class="text-sm font-medium text-amber-600">Pending Requests</h3>
                <p class="text-2xl font-bold text-amber-800">{{ $pendingRequestsCount }}</p>
            </div>
            <div class="bg-purple-50 p-4 rounded-lg">
                <h3 class="text-sm font-medium text-purple-600">Public Exchanges</h3>
                <p class="text-2xl font-bold text-purple-800">{{ $publicExchangeCount }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8 px-6" aria-label="Tabs">
                <button 
                    wire:click="showMySchedules"
                    class="py-4 px-1 border-b-2 font-medium text-sm {{ !$showExchangeRequests && !$showPublicExchanges && !$showBackupRequests ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    My Schedules
                </button>
                
                <button 
                    wire:click="toggleExchangeRequests"
                    class="py-4 px-1 border-b-2 font-medium text-sm relative {{ $showExchangeRequests ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Direct Requests
                    @if($pendingRequestsCount > 0)
                        <span class="bg-red-500 text-white text-xs rounded-full px-2 py-1 ml-2">{{ $pendingRequestsCount }}</span>
                    @endif
                </button>
            </nav>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded mb-6">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6">
            @if($showExchangeRequests)
                <h2 class="text-xl font-bold text-gray-800 mb-4">Direct Exchange Requests</h2>
                <p class="text-gray-600 mb-4">These are direct exchange requests sent to you by specific users.</p>
            @elseif($showPublicExchanges)
                <h2 class="text-xl font-bold text-gray-800 mb-4">Available Public Exchanges</h2>
                <p class="text-gray-600 mb-4">Team members have posted these schedules for exchange. Express interest to start a conversation.</p>
            @elseif($showBackupRequests)
                <h2 class="text-xl font-bold text-gray-800 mb-4">Backup Coverage Needed</h2>
                <p class="text-gray-600 mb-4">Team members need backup coverage due to sick leave. Help out if you're available!</p>
            @else
                <h2 class="text-xl font-bold text-gray-800 mb-4">My Schedules</h2>
                <p class="text-gray-600 mb-4">Manage your personal schedules, request exchanges, or report sick leave.</p>
            @endif

            @if($items->count() > 0)
                <div class="space-y-4">
                    @foreach($items as $item)
                        <div wire:key="item-{{ $item['id'] }}" class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-800">{{ $item['title'] }}</h3>
                                    <p class="text-gray-600 mt-1">{{ $item['description'] }}</p>
                                    
                                    <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($item['date'])->format('M d, Y') }}
                                        </span>
                                        
                                        @if($item['start_time'])
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                {{ \Carbon\Carbon::parse($item['start_time'])->format('H:i') }}
                                                @if($item['end_time'])
                                                    - {{ \Carbon\Carbon::parse($item['end_time'])->format('H:i') }}
                                                @endif
                                            </span>
                                        @endif
                                    </div>

                                    <div class="mt-2">
                                        @if($item['type'] === 'schedule')
                                            <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded">Schedule</span>
                                        @elseif($item['type'] === 'exchange')
                                            <span class="bg-amber-100 text-amber-800 text-xs font-medium px-2.5 py-0.5 rounded">Exchange Request</span>
                                        @elseif($item['type'] === 'public_exchange')
                                            <span class="bg-emerald-100 text-emerald-800 text-xs font-medium px-2.5 py-0.5 rounded">Public Exchange</span>
                                        @elseif($item['type'] === 'backup')
                                            <span class="bg-orange-100 text-orange-800 text-xs font-medium px-2.5 py-0.5 rounded">Backup Needed</span>
                                        @endif
                                    </div>
                                    
                                    <div class="mt-2">
                                        @if(isset($item['is_owner']) && $item['is_owner'])
                                            <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded">Your Schedule (Owner)</span>
                                        @elseif(isset($item['is_assigned']) && $item['is_assigned'])
                                            <span class="bg-teal-100 text-teal-800 text-xs font-medium px-2.5 py-0.5 rounded">Assigned to You</span>
                                        @endif
                                    </div>

                                    @if(isset($item['exchange_details']))
                                        <div class="mt-2 p-2 bg-gray-50 rounded text-sm">
                                            <strong>From:</strong> {{ $item['exchange_details']['from_user'] }}<br>
                                            <strong>Target:</strong> {{ $item['exchange_details']['target_schedule'] }}
                                        </div>
                                    @endif

                                    @if($item['type'] === 'backup' && isset($item['reason']))
                                        <div class="mt-2 p-2 bg-red-50 rounded text-sm">
                                            <strong>Reason:</strong> {{ $item['reason'] }}
                                        </div>
                                    @endif
                                </div>

                                <div class="flex flex-col space-y-2 ml-4">
                                    @if($item['type'] === 'schedule' && !$showExchangeRequests && !$showPublicExchanges && !$showBackupRequests)
                                        <div class="flex flex-col space-y-2">
                                            <button 
                                                wire:click="initiateSwap({{ $item['id'] }})"
                                                class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-sm">
                                                Direct Exchange
                                            </button>
                                            
                                            <!--for future use-->
                                            <!-- <button 
                                                wire:click="initiatePublicSwap({{ $item['id'] }})"
                                                class="bg-emerald-500 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded text-sm">
                                                Post for Exchange
                                            </button> -->
                                            
                                            <button 
                                                wire:click="showSickLeaveModal({{ $item['id'] }})" 
                                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm">
                                                Report Sick
                                            </button>
                                        </div>
                                    
                                    @elseif($item['type'] === 'exchange')
                                        <div class="flex flex-col space-y-2">
                                            <button 
                                                wire:click="approveExchange({{ $item['id'] }})"
                                                class="bg-emerald-500 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded text-sm">
                                                Approve
                                            </button>
                                            
                                            <button 
                                                wire:click="rejectExchange({{ $item['id'] }})"
                                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm">
                                                Reject
                                            </button>
                                        </div>
                                    
                                    @elseif($item['type'] === 'public_exchange')
                                        <button 
                                            wire:click="acceptPublicExchange({{ $item['id'] }})"
                                            class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded text-sm">
                                            Express Interest
                                        </button>
                                    
                                    @elseif($item['type'] === 'backup')
                                        <button 
                                            wire:click="takeBackup({{ $item['id'] }})"
                                            class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded text-sm">
                                            Offer Backup
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $items->links() }}
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">
                        @if($showExchangeRequests)
                            No direct exchange requests
                        @elseif($showPublicExchanges)
                            No public exchanges available
                        @elseif($showBackupRequests)
                            No backup requests at this time
                        @else
                            No schedules found
                        @endif
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if($showExchangeRequests)
                            You don't have any pending direct exchange requests.
                        @elseif($showPublicExchanges)
                            No team members have posted schedules for public exchange.
                        @elseif($showBackupRequests)
                            All team members are healthy and covered!
                        @else
                            Get started by creating your first schedule.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
    
    @if($showSwapModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Request Schedule Exchange</h3>
                        <button wire:click="resetSwap" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <p class="text-gray-600 mb-4">Select a schedule from another team member to exchange with:</p>

                    @if($availableItems && $availableItems->count() > 0)
                        <div class="space-y-2 mb-4 max-h-60 overflow-y-auto">
                            @foreach($availableItems as $availableItem)
                                <div wire:key="available-item-{{ $availableItem->id }}" class="border rounded-lg p-3 hover:bg-gray-50">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" wire:model.live="targetItemId" value="{{ $availableItem->id }}" class="mr-3">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">{{ $availableItem->title }}</h4>
                                            <div class="text-sm text-gray-600 space-y-1">
                                                <p><span class="font-medium">Team Member:</span> {{ optional($availableItem->user)->name ?? optional($availableItem->creator)->name ?? 'Unknown' }}</p>
                                                <p><span class="font-medium">Date:</span> {{ \Carbon\Carbon::parse($availableItem->date)->format('M d, Y') }}</p>
                                                @if($availableItem->start_time)
                                                    <p><span class="font-medium">Time:</span>
                                                        {{ \Carbon\Carbon::parse($availableItem->start_time)->format('H:i') }}
                                                        @if($availableItem->end_time)
                                                            - {{ \Carbon\Carbon::parse($availableItem->end_time)->format('H:i') }}
                                                        @endif
                                                    </p>
                                                @endif
                                                @if($availableItem->description)
                                                    <p><span class="font-medium">Description:</span> {{ Str::limit($availableItem->description, 50) }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No schedules available for exchange</h3>
                            <p class="mt-1 text-sm text-gray-500">There are currently no schedules from other team members available for exchange.</p>
                        </div>
                    @endif

                    <div class="flex justify-end space-x-3 mt-6">
                        <button wire:click="resetSwap" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded">
                            Cancel
                        </button>
                        <button wire:click="requestSwap" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" {{ !$targetItemId ? 'disabled' : '' }}>
                            Send Exchange Request
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($showSickLeaveModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Report Sick Leave</h3>
                        <button wire:click="resetSickLeave" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="mb-4">
                        <label for="sickReason" class="block text-sm font-medium text-gray-700 mb-2">
                            Reason for Sick Leave <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            wire:model="sickReason" 
                            id="sickReason"
                            rows="4" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            placeholder="Please provide details about your illness (minimum 10 characters)"></textarea>
                        @error('sickReason')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="bg-amber-50 border border-amber-200 rounded-md p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-amber-800">Important Notice</h3>
                                <div class="mt-2 text-sm text-amber-700">
                                    <p>Your team members will be notified about your sick leave and asked if they can provide backup coverage. Please ensure your reason is professional and accurate.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button wire:click="submitSickLeave" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Submit Sick Leave
                        </button>
                        <button wire:click="resetSickLeave" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div wire:loading class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-indigo-600"></div>
            <span class="text-gray-700">Processing...</span>
        </div>
    </div>
</div>