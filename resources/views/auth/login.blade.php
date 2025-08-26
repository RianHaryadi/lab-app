<x-guest-layout class="min-h-screen flex items-center justify-center py-12 px-4 relative">
    <div class="absolute top-0 left-0 w-40 h-40 bg-[#FF714B] opacity-15 rounded-full -translate-x-20 -translate-y-20 animate-pulse"></div>
    <div class="absolute bottom-0 right-0 w-48 h-48 bg-[#C71E64] opacity-15 rounded-full translate-x-24 translate-y-24 animate-pulse" style="animation-delay: 1s;"></div>
    <div class="absolute top-1/4 left-1/3 w-32 h-32 bg-[#4D2D8C] opacity-10 rounded-full animate-float"></div>
    <div class="absolute bottom-1/3 right-1/4 w-24 h-24 bg-[#FF714B] opacity-10 rounded-full animate-float" style="animation-delay: 2.5s;"></div>

    <div class="w-full max-w-md bg-white shadow-2xl p-8 rounded-2xl transform transition-all duration-500 hover:shadow-3xl z-10 animate-fade-in-up">
        <div class="flex justify-center mb-6">
            <img src="{{ asset('images/lab.jpeg') }}" alt="Logo Anda" class="w-16 h-16 rounded-full object-cover">
        </div>

        <div class="text-center mb-8">
            <h2 class="text-4xl font-extrabold bg-gradient-to-r from-[#C71E64] to-[#4D2D8C] bg-clip-text text-transparent">Welcome Back</h2>
            <p class="text-gray-600 mt-3 text-sm">Sign in to continue your journey with us</p>
        </div>

        <x-auth-session-status class="mb-6 p-4 rounded-lg bg-[#F2F2F2] text-[#4D2D8C] border border-[#FF714B]/20" :status="session('status')" />

        <form id="loginForm" method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
            <div>
                <x-input-label for="email" :value="__('Email')" class="mb-2 text-[#4D2D8C] font-semibold" />
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-[#FF714B]"></i>
                    </div>
                    <x-text-input 
                        id="email" 
                        class="block mt-1 w-full border-[#F2F2F2] pl-10 focus:border-[#C71E64] focus:ring focus:ring-[#C71E64]/30 transition duration-300 bg-[#F2F2F2]/50 focus:shadow-md" 
                        type="email" 
                        name="email" 
                        :value="old('email')" 
                        required 
                        autofocus 
                        autocomplete="username" 
                        placeholder="Enter your email"
                    />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-[#C71E64]" />
            </div>

            <div>
                <x-input-label for="password" :value="__('Password')" class="mb-2 text-[#4D2D8C] font-semibold" />
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-[#FF714B]"></i>
                    </div>
                    <x-text-input 
                        id="password" 
                        class="block mt-1 w-full border-[#F2F2F2] pl-10 focus:border-[#C71E64] focus:ring focus:ring-[#C71E64]/30 transition duration-300 bg-[#F2F2F2]/50 focus:shadow-md" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="current-password" 
                        placeholder="Enter your password"
                    />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-[#C71E64]" />
            </div>

            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center">
                    <div class="relative flex items-center">
                        <input id="remember_me" type="checkbox" class="hidden peer" name="remember">
                        <div class="w-5 h-5 border border-[#FF714B] rounded-md flex items-center justify-center peer-checked:bg-[#C71E64] peer-checked:border-[#C71E64] transition duration-300">
                            <svg class="w-3 h-3 text-white hidden peer-checked:block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="ms-2 text-sm text-gray-600">Remember me</span>
                    </div>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-[#C71E64] hover:text-[#4D2D8C] font-medium transition duration-300 hover:underline">
                        Forgot Password?
                    </a>
                @endif
            </div>

            <div>
                <x-primary-button id="loginButton" class="w-full justify-center py-3 bg-gradient-to-r from-[#FF714B] to-[#C71E64] hover:from-[#C71E64] hover:to-[#4D2D8C] text-white transition-all duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-xl border-0 active:scale-95">
                    <span id="buttonText"><i class="fas fa-sign-in-alt mr-2"></i> {{ __('Log in') }}</span>
                    <i id="loadingSpinner" class="fas fa-spinner fa-spin text-white" style="display: none;"></i>
                </x-primary-button>
            </div>
        </form>

        <div class="mt-8 flex items-center">
            <div class="w-full border-t border-[#F2F2F2]"></div>
            <span class="px-3 text-sm text-gray-500 bg-white">OR</span>
            <div class="w-full border-t border-[#F2F2F2]"></div>
        </div>
    </div>
    
    <style>
        html, body {
            overflow: hidden;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-pulse { animation: pulse 8s ease-in-out infinite; }
        .animate-fade-in-up {
            animation: fade-in-up 0.8s ease-out;
        }

        /* Atur tampilan saat loading */
        #loginButton.loading #buttonText {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        #loginButton.loading #loadingSpinner {
            display: inline-block;
            opacity: 1;
            transition: opacity 0.3s ease;
        }

        #loadingSpinner {
            opacity: 0;
            transition: opacity 0.3s ease;
        }
    </style>

    <script>
        const loginForm = document.getElementById('loginForm');
        const loginButton = document.getElementById('loginButton');
        const buttonText = document.getElementById('buttonText');
        const loadingSpinner = document.getElementById('loadingSpinner');

        if (!loginForm || !loginButton || !buttonText || !loadingSpinner) {
            console.error('One or more elements not found:', {
                loginForm,
                loginButton,
                buttonText,
                loadingSpinner
            });
        }

        loginForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Mencegah submit default agar bisa dikontrol

            // Tambahkan kelas loading ke tombol
            loginButton.classList.add('loading');
            loginButton.disabled = true;

            console.log('Form submitted, loading started');

            // Simulasi proses login (ganti dengan logika submit asli)
            setTimeout(() => {
                // Setelah proses selesai, kembalikan ke kondisi awal
                loginButton.classList.remove('loading');
                loginButton.disabled = false;

                console.log('Loading finished, submitting form');
                this.submit(); // Submit form secara manual
            }, 2000); // Durasi loading 2 detik (bisa disesuaikan)
        });
    </script>
</x-guest-layout>