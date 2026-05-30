<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RotiKita Bakery Management System</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Inline Dark Mode controller to match app settings
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="font-sans antialiased h-full flex items-center justify-center bg-gradient-to-br from-[#fdfbf7] via-[#f7f0e3] to-[#ebdcb9] dark:from-slate-950 dark:via-slate-900 dark:to-[#1a120b] transition-colors duration-500 overflow-hidden relative">
    
    <!-- Decorative Glowing Ambient Orbs for Warm Premium Bakery Aesthetic -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-amber-500/10 dark:bg-amber-500/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-orange-500/10 dark:bg-orange-500/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-yellow-500/[0.04] dark:bg-amber-500/[0.02] rounded-full blur-[100px] pointer-events-none"></div>

    <!-- Theme Toggle at Top Right -->
    <div class="absolute top-6 right-6 z-20">
        <button id="theme-toggle" class="w-11 h-11 rounded-2xl bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-white/40 dark:border-white/5 flex items-center justify-center text-slate-700 dark:text-slate-200 transition-all duration-300 shadow-sm hover:scale-105 active:scale-95" title="Ubah Mode Layar">
            <i id="theme-toggle-icon" class="fas fa-moon text-lg"></i>
        </button>
    </div>

    <!-- Login Container -->
    <div class="relative w-full max-w-[420px] px-6 py-12 sm:px-0 z-10">
        
        <!-- Main Card Container -->
        <div class="relative overflow-hidden rounded-3xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-2xl border border-white/60 dark:border-white/5 shadow-[0_25px_60px_-15px_rgba(211,161,92,0.25)] dark:shadow-[0_25px_60px_-15px_rgba(0,0,0,0.7)] p-8 sm:p-10 transition-all duration-300">
            
            <!-- Branding Header -->
            <div class="flex flex-col items-center text-center mb-8">
                <!-- Glowing Icon container -->
                <div class="w-16 h-16 bg-gradient-to-br from-[#d3a15c] to-[#c58744] dark:from-[#f97316] dark:to-[#ea580c] rounded-2xl flex items-center justify-center text-white text-3xl shadow-lg shadow-amber-500/20 dark:shadow-[0_8px_25px_rgba(249,115,22,0.4)] mb-4 transform hover:rotate-6 transition-all duration-300">
                    <i class="fas fa-bread-slice"></i>
                </div>
                
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-800 dark:text-white leading-none">
                    RotiKita
                </h1>
                <p class="text-xs text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest mt-2">
                    Bakery Management System
                </p>
                <div class="w-12 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full mt-4"></div>
            </div>

            <!-- Display Session Messages / Success Redirects -->
            @if(session('success'))
                <div class="mb-5 bg-emerald-500/10 dark:bg-emerald-500/5 border border-emerald-500/20 rounded-2xl p-4 flex items-start gap-3 transition-all">
                    <i class="fas fa-check-circle text-emerald-500 dark:text-emerald-400 text-lg mt-0.5 shrink-0"></i>
                    <p class="text-xs text-emerald-700 dark:text-emerald-300 font-medium leading-relaxed">
                        {{ session('success') }}
                    </p>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('login.submit') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Error Messages -->
                @if($errors->has('login_error'))
                    <div class="bg-rose-500/10 dark:bg-rose-500/5 border border-rose-500/20 rounded-2xl p-4 flex items-start gap-3 transition-all">
                        <i class="fas fa-circle-exclamation text-rose-500 dark:text-rose-400 text-lg mt-0.5 shrink-0"></i>
                        <p class="text-xs text-rose-700 dark:text-rose-300 font-bold leading-normal">
                            {{ $errors->first('login_error') }}
                        </p>
                    </div>
                @endif

                <!-- Username Input -->
                <div class="space-y-1.5">
                    <label for="username" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider pl-1">
                        Username
                    </label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 dark:text-slate-500 group-focus-within:text-amber-500 transition-colors">
                            <i class="fas fa-user text-sm"></i>
                        </span>
                        <input type="text" name="username" id="username" value="{{ old('username') }}" placeholder="Ujang" required
                            class="block w-full pl-11 pr-4 py-3.5 bg-slate-50/50 dark:bg-slate-950/40 border border-slate-200 dark:border-white/5 rounded-2xl text-sm text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all font-semibold" />
                    </div>
                    @error('username')
                        <p class="text-[10px] text-rose-500 font-bold pl-1 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Input -->
                <div class="space-y-1.5">
                    <label for="password" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider pl-1">
                        Password
                    </label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 dark:text-slate-500 group-focus-within:text-amber-500 transition-colors">
                            <i class="fas fa-lock text-sm"></i>
                        </span>
                        <input type="password" name="password" id="password" placeholder="Admin123" required
                            class="block w-full pl-11 pr-12 py-3.5 bg-slate-50/50 dark:bg-slate-950/40 border border-slate-200 dark:border-white/5 rounded-2xl text-sm text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all font-semibold" />
                        <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 dark:text-slate-600 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                            <i class="fas fa-eye text-sm" id="eye-icon"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-[10px] text-rose-500 font-bold pl-1 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember session statement (visual only for UX completeness) -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-amber-500 border-slate-300 dark:border-white/5 focus:ring-amber-500/20 bg-slate-50 dark:bg-slate-950/50" checked>
                        <span class="text-xs text-slate-400 dark:text-slate-500 font-medium">Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="group relative w-full flex items-center justify-center px-6 py-4 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-bold rounded-2xl shadow-lg shadow-amber-500/10 dark:shadow-none hover:shadow-xl hover:shadow-amber-500/20 transition-all duration-300 outline-none transform active:scale-[0.98] mt-3">
                    <span class="text-sm tracking-wide">LOGIN</span>
                    <i class="fas fa-arrow-right ml-2.5 text-xs group-hover:translate-x-1 transition-transform"></i>
                </button>
            </form>
        </div>

        <!-- Footer Copyright Info -->
        <p class="text-center text-[10px] text-slate-400/80 dark:text-slate-600/80 mt-8 font-medium">
            &copy; {{ date('Y') }} RotiKita. Hak Cipta Dilindungi.
        </p>
    </div>

    <!-- Scripts for Password Visibility & Theme Control -->
    <script>
        // Password Visibility Toggle
        const passwordField = document.getElementById('password');
        const togglePasswordBtn = document.getElementById('toggle-password');
        const eyeIcon = document.getElementById('eye-icon');

        togglePasswordBtn.addEventListener('click', () => {
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });

        // Theme Toggle Functionality
        const themeToggle = document.getElementById('theme-toggle');
        const themeToggleIcon = document.getElementById('theme-toggle-icon');

        function updateThemeIcon() {
            if (document.documentElement.classList.contains('dark')) {
                themeToggleIcon.className = 'fas fa-sun text-lg text-amber-400';
            } else {
                themeToggleIcon.className = 'fas fa-moon text-lg text-slate-700';
            }
        }

        updateThemeIcon();

        themeToggle.addEventListener('click', () => {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
            updateThemeIcon();
        });
    </script>
</body>
</html>
