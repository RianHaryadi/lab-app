<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight bg-gradient-to-r from-[#C71E64] to-[#4D2D8C] bg-clip-text text-transparent">
            {{ __('Profile Settings') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-[#F2F2F2]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl">
                <div class="p-6 sm:p-8 bg-white border-b border-[#C71E64]/20 animate-fade-in">
                    <h3 class="text-lg font-medium text-[#4D2D8C] mb-6">Edit Profile</h3>
                    <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-[#4D2D8C] mb-2">Name</label>
                            <input id="name" class="mt-1 block w-full rounded-lg border-[#F2F2F2]/50 focus:border-[#C71E64] focus:ring focus:ring-[#C71E64]/30 transition-all duration-300 bg-[#F2F2F2]/10 px-4 py-2 text-[#4D2D8C] placeholder-[#4D2D8C]/50" 
                                   type="text" name="name" 
                                   value="{{ old('name', auth()->user()->name) }}" 
                                   placeholder="Enter your name">
                            @error('name')
                                <p class="text-sm text-[#FF714B] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-[#4D2D8C] mb-2">Email</label>
                            <input id="email" class="mt-1 block w-full rounded-lg border-[#F2F2F2]/50 focus:border-[#C71E64] focus:ring focus:ring-[#C71E64]/30 transition-all duration-300 bg-[#F2F2F2]/10 px-4 py-2 text-[#4D2D8C] placeholder-[#4D2D8C]/50" 
                                   type="email" name="email" 
                                   value="{{ old('email', auth()->user()->email) }}" 
                                   placeholder="Enter your email">
                            @error('email')
                                <p class="text-sm text-[#FF714B] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#FF714B] to-[#C71E64] hover:from-[#C71E64] hover:to-[#4D2D8C] text-white rounded-lg transition-all duration-300 transform hover:-translate-y-1 shadow-md hover:shadow-lg">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.5s ease-out;
    }
</style>
</x-app-layout>

