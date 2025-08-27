<form method="POST" action="{{ route('password.update') }}" class="max-w-md mx-auto p-6 sm:p-8 bg-white rounded-2xl shadow-xl border border-[#C71E64]/20 space-y-6 animate-fade-in">
    @csrf
    @method('PUT')

    <!-- Current Password -->
    <div>
        <label for="current_password" class="block text-sm font-medium text-[#4D2D8C] mb-2">Current Password</label>
        <input id="current_password" name="current_password" type="password" required 
               class="mt-1 block w-full rounded-lg border-[#F2F2F2]/50 focus:border-[#C71E64] focus:ring focus:ring-[#C71E64]/30 transition-all duration-300 bg-[#F2F2F2]/10 px-4 py-2 text-[#4D2D8C] placeholder-[#4D2D8C]/50"
               placeholder="Enter current password">
        @error('current_password')
            <p class="text-sm text-[#FF714B] mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- New Password -->
    <div class="mt-4">
        <label for="password" class="block text-sm font-medium text-[#4D2D8C] mb-2">New Password</label>
        <input id="password" name="password" type="password" required 
               class="mt-1 block w-full rounded-lg border-[#F2F2F2]/50 focus:border-[#C71E64] focus:ring focus:ring-[#C71E64]/30 transition-all duration-300 bg-[#F2F2F2]/10 px-4 py-2 text-[#4D2D8C] placeholder-[#4D2D8C]/50"
               placeholder="Enter new password">
        @error('password')
            <p class="text-sm text-[#FF714B] mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Confirm Password -->
    <div class="mt-4">
        <label for="password_confirmation" class="block text-sm font-medium text-[#4D2D8C] mb-2">Confirm Password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" required 
               class="mt-1 block w-full rounded-lg border-[#F2F2F2]/50 focus:border-[#C71E64] focus:ring focus:ring-[#C71E64]/30 transition-all duration-300 bg-[#F2F2F2]/10 px-4 py-2 text-[#4D2D8C] placeholder-[#4D2D8C]/50"
               placeholder="Confirm new password">
        @error('password_confirmation')
            <p class="text-sm text-[#FF714B] mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex justify-end">
        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#FF714B] to-[#C71E64] hover:from-[#C71E64] hover:to-[#4D2D8C] text-white rounded-lg transition-all duration-300 transform hover:-translate-y-1 shadow-md hover:shadow-lg">
            Update Password
        </button>
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