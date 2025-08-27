<form method="POST" action="{{ route('profile.update') }}" class="max-w-md mx-auto p-6 sm:p-8 bg-white rounded-2xl shadow-xl border border-[#C71E64]/20 space-y-6 animate-fade-in">
    @csrf
    @method('PUT')

    <!-- Name -->
    <div>
        <label class="block text-sm font-medium text-[#4D2D8C] mb-2" for="name">Name</label>
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
        <label class="block text-sm font-medium text-[#4D2D8C] mb-2" for="email">Email</label>
        <input id="email" class="mt-1 block w-full rounded-lg border-[#F2F2F2]/50 focus:border-[#C71E64] focus:ring focus:ring-[#C71E64]/30 transition-all duration-300 bg-[#F2F2F2]/10 px-4 py-2 text-[#4D2D8C] placeholder-[#4D2D8C]/50" 
               type="email" name="email" 
               value="{{ old('email', auth()->user()->email) }}" 
               placeholder="Enter your email">
        @error('email')
            <p class="text-sm text-[#FF714B] mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex justify-end">
        <x-primary-button class="bg-gradient-to-r from-[#FF714B] to-[#C71E64] hover:from-[#C71E64] hover:to-[#4D2D8C] text-white px-6 py-2 rounded-lg transition-all duration-300 transform hover:-translate-y-1 shadow-md hover:shadow-lg">
            Save
        </x-primary-button>
    </div>
</form>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.5s ease-out;
    }
</style>