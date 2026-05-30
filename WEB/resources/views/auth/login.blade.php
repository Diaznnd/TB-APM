<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Golden Tulip Bakery Management System</title>
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
    <style>
        /* Floating animation for illustration */
        .float-anim {
            animation: floatY 6s ease-in-out infinite;
        }
        @keyframes floatY {
            0%, 100% { transform: translateY(0); }
            50%      { transform: translateY(-14px); }
        }
        /* Vertical text for Welcome label */
        .vertical-text {
            writing-mode: vertical-rl;
            text-orientation: mixed;
            transform: rotate(180deg);
        }
    </style>
</head>
<body class="font-sans antialiased h-full flex items-center justify-center bg-gradient-to-br from-[#f5ead6] via-[#eddcc2] to-[#d9c7a8] dark:from-slate-950 dark:via-slate-900 dark:to-[#1a120b] transition-colors duration-500 overflow-hidden relative m-0">

    <!-- Background Decorative Shapes (matching reference soft diagonal bg) -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-0 right-0 w-[60%] h-full bg-[#f0e2cb]/50 dark:bg-slate-800/10 transform skew-x-[-12deg] origin-top-right"></div>
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-amber-500/8 dark:bg-amber-500/3 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-[#d3a15c]/8 dark:bg-[#d3a15c]/3 rounded-full blur-3xl"></div>
    </div>

    <!-- Theme Toggle -->
    <div class="absolute top-6 right-6 z-30">
        <button id="theme-toggle" class="w-10 h-10 rounded-xl bg-white/70 dark:bg-slate-800/70 backdrop-blur-md border border-white/40 dark:border-white/5 flex items-center justify-center text-slate-600 dark:text-slate-300 transition-all duration-300 shadow-sm hover:scale-105 active:scale-95" title="Ubah Mode Layar">
            <i id="theme-toggle-icon" class="fas fa-moon text-base"></i>
        </button>
    </div>

    <!-- ================================================ -->
    <!-- Main Login Card (Split: Left Accent + Right Form) -->
    <!-- ================================================ -->
    <div class="relative z-10 w-full max-w-[860px] mx-6">
        <div class="flex bg-white dark:bg-slate-900 rounded-3xl shadow-[0_30px_80px_-15px_rgba(180,140,80,0.25)] dark:shadow-[0_30px_80px_-15px_rgba(0,0,0,0.6)] overflow-hidden min-h-[520px] transition-all duration-300 border border-white/60 dark:border-white/5">

            <!-- ================================= -->
            <!-- LEFT PANEL — Accent & Branding    -->
            <!-- ================================= -->
            <div class="hidden md:flex w-[45%] relative overflow-hidden">
                <!-- Amber/Gold gradient base -->
                <div class="absolute inset-0 bg-gradient-to-br from-[#d3a15c] via-[#c58744] to-[#a06c30] dark:from-[#92400e] dark:via-[#78350f] dark:to-[#451a03]"></div>

                <!-- Curved/diagonal edge (SVG clip that bleeds into the right panel) -->
                <svg class="absolute top-0 -right-1 h-full w-24 z-10" viewBox="0 0 100 600" preserveAspectRatio="none">
                    <path d="M0,0 L100,0 L100,600 L0,600 C60,480 80,360 40,300 C0,240 60,120 0,0 Z" fill="white" class="dark:fill-slate-900 transition-colors duration-300"/>
                </svg>

                <!-- Decorative subtle circles -->
                <div class="absolute -top-16 -left-16 w-44 h-44 bg-white/10 rounded-full blur-xl"></div>
                <div class="absolute -bottom-20 -left-10 w-52 h-52 bg-white/5 rounded-full blur-2xl"></div>
                <div class="absolute top-1/2 right-16 w-20 h-20 bg-yellow-300/10 rounded-full blur-lg"></div>

                <!-- Content: Vertical Welcome + Logo + Illustration -->
                <div class="relative z-20 flex flex-col h-full w-full p-8 pb-6">
                    
                    <!-- Top: Logo & Brand -->
                    <div class="flex items-center gap-3 self-start">
                        <div class="w-11 h-11 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center text-white text-xl shadow-sm border border-white/15">
                            <i class="fas fa-bread-slice"></i>
                        </div>
                        <div>
                            <span class="text-white font-extrabold text-sm tracking-tight block leading-tight">Golden Tulip Bakery</span>
                        </div>
                    </div>

                    <!-- Center: Illustration + Vertical Welcome Text -->
                    <div class="flex items-center gap-0 -ml-4 flex-1 justify-center">
                        <!-- Vertical "Welcome" Text -->
                        <div class="vertical-text text-white/90 font-extrabold text-2xl tracking-[6px] uppercase select-none mr-2">
                            Welcome
                        </div>
                        <!-- Illustration -->
                        <div class="float-anim max-w-[240px]">
                            <img src="{{ asset('images/bakery_illustration.png') }}" alt="Bakery Illustration" class="w-full h-auto drop-shadow-[0_15px_30px_rgba(0,0,0,0.2)]" />
                        </div>
                    </div>

                    <!-- Bottom: Tagline -->
                    <p class="text-black/40 text-[9px] font-bold uppercase tracking-[2px] text-left leading-relaxed">
                        &copy; {{ date('Y') }} Golden Tulip Bakery
                    </p>
                </div>
            </div>

            <!-- ================================= -->
            <!-- RIGHT PANEL — Login Form          -->
            <!-- ================================= -->
            <div class="flex-1 flex flex-col justify-center px-8 sm:px-14 py-10 relative">
                
                <!-- Mobile-only branding (hidden on desktop) -->
                <div class="flex flex-col items-center text-center mb-7 md:hidden">
                    <div class="w-14 h-14 bg-gradient-to-br from-[#d3a15c] to-[#c58744] rounded-2xl flex items-center justify-center text-white text-2xl shadow-lg shadow-amber-500/20 dark:shadow-[0_4px_15px_rgba(211,161,92,0.3)] mb-3">
                        <i class="fas fa-bread-slice"></i>
                    </div>
                    <h1 class="text-xl font-extrabold tracking-tight text-slate-800 dark:text-white">Golden Tulip Bakery</h1>
                    <div class="w-8 h-0.5 bg-gradient-to-r from-[#d3a15c] to-[#c58744] rounded-full mt-2"></div>
                </div>

                <!-- Login Title -->
                <h1 class="text-2xl font-extrabold text-slate-800 dark:text-white tracking-tight text-center md:text-left mb-8">
                    LOGIN
                </h1>

                <!-- Form -->
                <form action="{{ route('login.submit') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Username -->
                    <div class="space-y-1.5">
                        <label for="username" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 pl-0.5">
                            Username
                        </label>
                        <div class="relative group">
                            <input type="text" name="username" id="username" value="{{ old('username') }}" placeholder="Masukkan username" required
                                class="block w-full px-4 py-3 bg-transparent border-b-2 border-slate-200 dark:border-slate-700 text-sm text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 focus:outline-none focus:border-amber-500 dark:focus:border-amber-500 transition-all font-medium rounded-none" />
                            <span class="absolute inset-y-0 right-0 flex items-center pr-1 text-slate-300 dark:text-slate-600">
                                <i class="fas fa-user text-sm"></i>
                            </span>
                        </div>
                        @error('username')
                            <p class="text-[10px] text-rose-500 font-bold pl-0.5 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="space-y-1.5">
                        <label for="password" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 pl-0.5">
                            Password
                        </label>
                        <div class="relative group">
                            <input type="password" name="password" id="password" placeholder="Masukkan password" required
                                class="block w-full px-4 pr-10 py-3 bg-transparent border-b-2 border-slate-200 dark:border-slate-700 text-sm text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 focus:outline-none focus:border-amber-500 dark:focus:border-amber-500 transition-all font-medium rounded-none" />
                            <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 flex items-center pr-1 text-slate-300 dark:text-slate-600 hover:text-slate-500 dark:hover:text-slate-400 transition-colors">
                                <i class="fas fa-eye text-sm" id="eye-icon"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-[10px] text-rose-500 font-bold pl-0.5 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-center pt-2">
                        <button type="submit" class="group px-12 py-3 bg-gradient-to-r from-[#d3a15c] to-[#c58744] hover:from-[#c58744] hover:to-[#a06c30] text-white font-bold rounded-full shadow-lg shadow-amber-500/20 dark:shadow-[0_4px_15px_rgba(211,161,92,0.1)] hover:shadow-xl hover:shadow-amber-500/30 transition-all duration-300 outline-none transform active:scale-[0.97] text-sm tracking-wide">
                            Login
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
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
                themeToggleIcon.className = 'fas fa-sun text-base text-amber-400';
            } else {
                themeToggleIcon.className = 'fas fa-moon text-base text-slate-600';
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

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed top-6 right-6 z-[9999] flex flex-col gap-3 pointer-events-none" style="max-width: 400px;"></div>

    <style>
        @keyframes toastSlideIn {
            0%   { opacity: 0; transform: translateX(100%); }
            100% { opacity: 1; transform: translateX(0); }
        }
        @keyframes toastSlideOut {
            0%   { opacity: 1; transform: translateX(0); max-height: 200px; margin-bottom: 12px; }
            70%  { opacity: 0; transform: translateX(100%); max-height: 200px; margin-bottom: 12px; }
            100% { opacity: 0; transform: translateX(100%); max-height: 0; margin-bottom: 0; padding: 0; }
        }
        @keyframes toastProgress {
            0%   { width: 100%; }
            100% { width: 0%; }
        }
        .toast-enter { animation: toastSlideIn 0.4s cubic-bezier(0.22, 1, 0.36, 1) forwards; }
        .toast-exit  { animation: toastSlideOut 0.5s cubic-bezier(0.22, 1, 0.36, 1) forwards; }
        .toast-progress-bar { animation: toastProgress linear forwards; }
    </style>

    <script>
        function showToast(message, type = 'success', duration = 5000) {
            const container = document.getElementById('toast-container');
            if (!container) return;
            const isDark = document.documentElement.classList.contains('dark');
            const isSuccess = type === 'success';
            const toast = document.createElement('div');
            toast.className = 'toast-enter pointer-events-auto relative overflow-hidden rounded-2xl shadow-xl border transition-all';
            toast.style.cssText = `
                background: ${isDark ? (isSuccess ? 'rgba(6,78,59,0.9)' : 'rgba(127,29,29,0.9)') : (isSuccess ? 'rgba(240,253,244,0.97)' : 'rgba(255,241,242,0.97)')};
                backdrop-filter: blur(16px);
                border-color: ${isDark ? (isSuccess ? 'rgba(16,185,129,0.2)' : 'rgba(244,63,94,0.2)') : (isSuccess ? 'rgba(16,185,129,0.25)' : 'rgba(244,63,94,0.25)')};
            `;
            const iconClass = isSuccess ? 'fa-check-circle' : 'fa-exclamation-circle';
            const iconColor = isDark ? (isSuccess ? 'color:#34d399' : 'color:#fb7185') : (isSuccess ? 'color:#10b981' : 'color:#f43f5e');
            const titleColor = isDark ? (isSuccess ? 'color:#a7f3d0' : 'color:#fecdd3') : (isSuccess ? 'color:#065f46' : 'color:#9f1239');
            const msgColor = isDark ? (isSuccess ? 'color:#6ee7b7' : 'color:#fda4af') : (isSuccess ? 'color:#047857' : 'color:#be123c');
            const progressColor = isSuccess ? '#10b981' : '#f43f5e';
            toast.innerHTML = `
                <div class="flex items-start gap-3 p-4 pr-10">
                    <i class="fas ${iconClass} text-lg mt-0.5 shrink-0" style="${iconColor}"></i>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold uppercase tracking-wider mb-0.5" style="${titleColor}">${isSuccess ? 'Berhasil' : 'Gagal'}</p>
                        <p class="text-sm font-medium leading-relaxed" style="${msgColor}">${message}</p>
                    </div>
                </div>
                <button onclick="dismissToast(this.parentElement)" class="absolute top-3 right-3 w-7 h-7 rounded-lg flex items-center justify-center transition-all hover:scale-110" style="background: ${isDark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.05)'}; ${titleColor}">
                    <i class="fas fa-times text-xs"></i>
                </button>
                <div class="h-[3px] w-full" style="background: ${isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.03)'}">
                    <div class="toast-progress-bar h-full rounded-full" style="background: ${progressColor}; animation-duration: ${duration}ms;"></div>
                </div>
            `;
            container.appendChild(toast);
            const timer = setTimeout(() => dismissToast(toast), duration);
            toast._timer = timer;
        }
        function dismissToast(toast) {
            if (!toast || toast.classList.contains('toast-exit')) return;
            clearTimeout(toast._timer);
            toast.classList.remove('toast-enter');
            toast.classList.add('toast-exit');
            toast.addEventListener('animationend', () => toast.remove());
        }
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                showToast(@json(session('success')), 'success');
            @endif
            @if($errors->has('login_error'))
                showToast(@json($errors->first('login_error')), 'error');
            @endif
        });
    </script>
</body>
</html>
