<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'RotiKita Bakery Management System') }} - Forecasting</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Check local storage or system preference
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
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="font-sans antialiased bg-[#fdfbf7] dark:bg-slate-950 text-[#6d462d] dark:text-slate-100 flex min-h-screen m-0 transition-colors duration-300">
    <!-- Sidebar -->
    <aside class="w-[280px] max-lg:w-[80px] bg-white dark:bg-[#111827] text-slate-500 dark:text-[#9ca3af] flex flex-col h-screen sticky top-0 border-r border-slate-200 dark:border-white/5 shrink-0 z-[100] transition-all duration-300">
        <!-- Sidebar Header -->
        <div class="p-8 px-6 flex items-center gap-[14px] bg-white dark:bg-[#111827] border-b border-slate-100 dark:border-white/5 max-lg:justify-center max-lg:px-0">
            <div class="w-11 h-11 bg-gradient-to-br from-[#d3a15c] to-[#c58744] dark:from-[#f97316] dark:to-[#ea580c] rounded-xl flex items-center justify-center text-white text-xl shadow-sm dark:shadow-[0_4px_15px_rgba(249,115,22,0.4)] shrink-0">
                <i class="fas fa-bread-slice"></i>
            </div>
            <div class="flex flex-col max-lg:hidden overflow-hidden transition-all">
                <span class="text-slate-800 dark:text-white font-extrabold text-xl tracking-tight leading-none">RotiKita</span>
                <span class="text-[0.7rem] text-slate-400 dark:text-[#9ca3af] font-medium mt-1">Bakery Management System</span>
            </div>
        </div>
        
        <!-- Sidebar Content -->
        <div class="flex-1 py-6 px-3.5 overflow-y-auto max-lg:px-2">
            <!-- Menu Utama -->
            <div class="mb-7">
                <span class="text-[0.65rem] font-extrabold text-slate-400 dark:text-[#4b5563] px-3 mb-3.5 block tracking-[1.5px] uppercase max-lg:hidden">Menu Utama</span>
                <ul class="list-none p-0 m-0">
                    <li class="mb-1 relative group">
                        @php $isActive = request()->routeIs('dashboard') || request()->is('/'); @endphp
                        @if($isActive)
                            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-7 bg-[#f97316] rounded-r z-10 hidden dark:block"></div>
                        @endif
                        <a href="{{ route('dashboard') }}" class="flex items-center p-3 dark:p-2 px-4 dark:px-3.5 rounded-xl transition-all duration-200 gap-3.5 {{ $isActive ? 'bg-[#d3a15c] dark:bg-[#3f2b1d] text-white shadow-md dark:shadow-none shadow-[#d3a15c]/20' : 'text-slate-600 dark:text-[#9ca3af] hover:bg-slate-50 dark:hover:bg-white/5 hover:text-[#c58744] dark:hover:text-white' }} max-lg:justify-center max-lg:px-0">
                            <i class="fas fa-chart-pie w-[22px] text-center text-lg opacity-80 shrink-0"></i>
                            <span class="text-sm font-semibold max-lg:hidden">Dashboard</span>
                        </a>
                    </li>
                    <li class="mb-1.5 relative group">
                        @php $isActive = request()->routeIs('transactions.index'); @endphp
                        @if($isActive)
                            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-7 bg-[#f97316] rounded-r z-10 hidden dark:block"></div>
                        @endif
                        <a href="{{ route('transactions.index') }}" class="flex items-center p-3 dark:p-2 px-4 dark:px-3.5 rounded-xl transition-all duration-200 gap-3.5 {{ $isActive ? 'bg-[#d3a15c] dark:bg-[#3f2b1d] text-white shadow-md dark:shadow-none shadow-[#d3a15c]/20' : 'text-slate-600 dark:text-[#9ca3af] hover:bg-slate-50 dark:hover:bg-white/5 hover:text-[#c58744] dark:hover:text-white' }} max-lg:justify-center max-lg:px-0">
                            <i class="fas fa-cash-register w-[22px] text-center text-lg opacity-80 shrink-0"></i>
                            <span class="text-sm font-semibold max-lg:hidden">Transactions</span>
                        </a>
                    </li>
                    <li class="mb-1.5 relative group">
                        @php $isActive = request()->routeIs('inventory.index'); @endphp
                        @if($isActive)
                            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-7 bg-[#f97316] rounded-r z-10 hidden dark:block"></div>
                        @endif
                        <a href="{{ route('inventory.index') }}" class="flex items-center p-3 dark:p-2 px-4 dark:px-3.5 rounded-xl transition-all duration-200 gap-3.5 {{ $isActive ? 'bg-[#d3a15c] dark:bg-[#3f2b1d] text-white shadow-md dark:shadow-none shadow-[#d3a15c]/20' : 'text-slate-600 dark:text-[#9ca3af] hover:bg-slate-50 dark:hover:bg-white/5 hover:text-[#c58744] dark:hover:text-white' }} max-lg:justify-center max-lg:px-0">
                            <i class="fas fa-boxes w-[22px] text-center text-lg opacity-80 shrink-0"></i>
                            <span class="text-sm font-semibold max-lg:hidden">Inventory</span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Machine Learning -->
            <div class="mb-7">
                <span class="text-[0.65rem] font-extrabold text-slate-400 dark:text-[#4b5563] px-3 mb-3.5 block tracking-[1.5px] uppercase max-lg:hidden">Machine Learning</span>
                <ul class="list-none p-0 m-0">
                    <li class="mb-1.5 relative group">
                        @php $isActive = request()->is('forecasting*'); @endphp
                        @if($isActive)
                            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-7 bg-[#f97316] rounded-r z-10 hidden dark:block"></div>
                        @endif
                        <a href="/forecasting" class="flex items-center p-3 dark:p-2 px-4 dark:px-3.5 rounded-xl transition-all duration-200 gap-3.5 {{ $isActive ? 'bg-[#d3a15c] dark:bg-[#3f2b1d] text-white shadow-md dark:shadow-none shadow-[#d3a15c]/20' : 'text-slate-600 dark:text-[#9ca3af] hover:bg-slate-50 dark:hover:bg-white/5 hover:text-[#c58744] dark:hover:text-white' }} max-lg:justify-center max-lg:px-0">
                            <i class="fas fa-brain w-[22px] text-center text-lg opacity-80 shrink-0"></i>
                            <span class="text-sm font-semibold max-lg:hidden">Forecasting</span>
                            <span class="py-0.5 px-2 rounded-md text-[0.65rem] font-black ml-auto tracking-tighter bg-emerald-500 text-white shadow-[0_2px_8px_rgba(16,185,129,0.3)] max-lg:hidden">ML</span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Administrasi -->
            <div class="mb-7">
                <span class="text-[0.65rem] font-extrabold text-slate-400 dark:text-[#4b5563] px-3 mb-3.5 block tracking-[1.5px] uppercase max-lg:hidden">Administrasi</span>
                <ul class="list-none p-0 m-0">
                    <li class="mb-1.5 relative group">
                        @php $isActive = request()->routeIs('laporan.*'); @endphp
                        @if($isActive)
                            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-7 bg-[#f97316] rounded-r z-10 hidden dark:block"></div>
                        @endif
                        <a href="{{ route('laporan.index') }}" class="flex items-center p-3 dark:p-2 px-4 dark:px-3.5 rounded-xl transition-all duration-200 gap-3.5 {{ $isActive ? 'bg-[#d3a15c] dark:bg-[#3f2b1d] text-white shadow-md dark:shadow-none shadow-[#d3a15c]/20' : 'text-slate-600 dark:text-[#9ca3af] hover:bg-slate-50 dark:hover:bg-white/5 hover:text-[#c58744] dark:hover:text-white' }} max-lg:justify-center max-lg:px-0">
                            <i class="fas fa-file-invoice w-[22px] text-center text-lg opacity-80 shrink-0"></i>
                            <span class="text-sm font-semibold max-lg:hidden">Laporan</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Sidebar Footer -->
        <div class="p-6 border-t border-slate-100 dark:border-slate-800 bg-white dark:bg-black/10 max-lg:px-2">
            <div class="flex items-center gap-3.5 bg-[#fdfbf7] dark:bg-transparent p-3 dark:p-0 rounded-2xl border border-[#f2e7d0] dark:border-transparent">
                <div class="w-[42px] h-[42px] bg-[#d3a15c] dark:bg-[#f97316] rounded-full flex items-center justify-center text-white font-bold text-lg shadow-sm dark:shadow-[0_4px_10px_rgba(249,115,22,0.3)] shrink-0">
                    A
                </div>
                <div class="flex flex-col flex-1 max-lg:hidden overflow-hidden">
                    <span class="text-slate-800 dark:text-white text-[0.9rem] font-bold truncate">Admin</span>
                    <span class="text-[0.75rem] text-slate-500 dark:text-[#9ca3af] truncate">Pemilik Toko</span>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="m-0 p-0 max-lg:hidden">
                    @csrf
                    <button type="submit" class="bg-transparent border-none text-slate-400 dark:text-[#9ca3af] cursor-pointer text-xl transition-all duration-200 p-2 rounded-lg hover:text-[#c58744] dark:hover:text-red-500 hover:bg-[#f9f5ec] dark:hover:bg-red-500/10" title="Keluar Sistem">
                        <i class="fas fa-arrow-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-y-auto h-screen transition-colors duration-300">
        <!-- Persistent Top Header -->
        <header class="sticky top-0 z-[90] glass-card border-b border-slate-200 dark:border-slate-800 px-8 py-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">@yield('title', 'LAPORAN')</h2>
            </div>
            
            <div class="flex items-center gap-6">
                <!-- Date -->
                <div class="flex flex-col items-end">
                    <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest leading-none mb-1">Tanggal</span>
                    <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</span>
                </div>
                
                <!-- Separator -->
                <div class="h-8 w-px bg-slate-200 dark:bg-slate-800"></div>
                
                <!-- Time -->
                <div class="flex flex-col items-end min-w-[60px]">
                    <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest leading-none mb-1">Waktu</span>
                    <span id="live-clock" class="text-sm font-black text-amber-600 dark:text-amber-500 tabular-nums">--:--:--</span>
                </div>

                <!-- Separator -->
                <div class="h-8 w-px bg-slate-200 dark:bg-slate-800"></div>

                <!-- Dark Mode Toggle Button -->
                <button id="theme-toggle" class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 flex items-center justify-center text-slate-700 dark:text-slate-200 transition-all duration-200 shadow-sm border border-slate-200/50 dark:border-white/5" title="Ubah Mode Layar">
                    <i id="theme-toggle-icon" class="fas fa-moon text-lg"></i>
                </button>
            </div>
        </header>

        <main class="p-10 max-w-[1400px] mx-auto w-full max-lg:p-6">
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 dark:bg-emerald-950/30 border-l-4 border-emerald-400 p-4 rounded-r-lg shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-emerald-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-emerald-700 dark:text-emerald-300 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-rose-50 dark:bg-rose-950/30 border-l-4 border-rose-400 p-4 rounded-r-lg shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-rose-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-rose-700 dark:text-rose-300 font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="py-8 mt-auto border-t border-slate-200 dark:border-slate-800">
            <div class="max-w-7xl mx-auto px-8">
                <p class="text-center text-slate-400 dark:text-slate-600 text-[10px] font-bold tracking-widest uppercase">
                    &copy; {{ date('Y') }} ROTIKITA BAKERY MANAGEMENT SYSTEM. INTEGRATED WITH ML FORECASTING.
                </p>
            </div>
        </footer>
    </div>

    @stack('scripts')
    
    <script>
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            
            const clockElement = document.getElementById('live-clock');
            if (clockElement) {
                clockElement.textContent = `${hours}:${minutes}:${seconds}`;
            }
        }
        
        setInterval(updateClock, 1000);
        updateClock(); // Initial call

        // Theme Toggle Logic
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeToggleIcon = document.getElementById('theme-toggle-icon');

        function updateIcon() {
            if (document.documentElement.classList.contains('dark')) {
                themeToggleIcon.className = 'fas fa-sun text-amber-500 text-lg transition-transform duration-300 rotate-180';
                themeToggleBtn.setAttribute('title', 'Ubah ke Mode Terang');
            } else {
                themeToggleIcon.className = 'fas fa-moon text-slate-700 text-lg transition-transform duration-300 rotate-0';
                themeToggleBtn.setAttribute('title', 'Ubah ke Mode Gelap');
            }
        }

        // Initialize icon on page load
        updateIcon();

        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', function() {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
                updateIcon();
            });
        }
    </script>
    
    <style>
        .glass-card { 
            background: rgba(255, 255, 255, 0.95); 
            backdrop-filter: blur(10px); 
            border: 1px solid rgba(255, 255, 255, 0.5); 
        }
        
        .dark .glass-card {
            background: rgba(17, 24, 39, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</body>
</html>
