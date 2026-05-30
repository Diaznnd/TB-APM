<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Golden Tulip Bakery Management System') }} - Forecasting</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Check local storage or system preference
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        // Check sidebar collapse state
        if (localStorage.getItem('sidebar-collapsed') === 'true') {
            document.documentElement.classList.add('sidebar-collapsed');
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
    <!-- Sidebar (Responsive & Theme Adaptive) -->
    <aside id="sidebar" class="w-[280px] bg-white dark:bg-[#111827] text-slate-600 dark:text-[#9ca3af] flex flex-col h-screen sticky top-0 border-r border-slate-200/80 dark:border-white/5 shrink-0 z-[100] transition-all duration-300">
        <!-- Sidebar Header / Brand (Click to expand if collapsed) -->
        <div id="sidebar-brand" class="p-8 px-6 flex items-center gap-[14px] bg-white dark:bg-[#111827] border-b border-slate-200/80 dark:border-white/5 cursor-pointer hover:bg-slate-50 dark:hover:bg-white/5 transition-all duration-200">
            <div class="w-11 h-11 bg-gradient-to-br from-[#d3a15c] to-[#c58744] rounded-xl flex items-center justify-center text-white text-xl shadow-sm dark:shadow-[0_4px_15px_rgba(211,161,92,0.4)] shrink-0">
                <i class="fas fa-bread-slice"></i>
            </div>
            <div class="sidebar-text flex flex-col overflow-hidden transition-all duration-300">
                <span class="text-slate-800 dark:text-white font-extrabold text-lg tracking-tight leading-none truncate">Golden Tulip Bakery</span>
                <span class="text-[0.7rem] text-slate-400 dark:text-[#9ca3af]/60 font-medium mt-1 truncate">Bakery Management System</span>
            </div>
            <!-- Wrap Toggle Button -->
            <button id="sidebar-toggle" type="button" class="sidebar-text ml-auto text-slate-400 dark:text-[#9ca3af]/60 hover:text-slate-800 dark:hover:text-white cursor-pointer focus:outline-none p-1.5 hover:bg-slate-100 dark:hover:bg-white/5 rounded-lg transition-all" title="Sembunyikan Menu">
                <i class="fas fa-chevron-left text-xs"></i>
            </button>
        </div>
        
        <!-- Sidebar Content -->
        <div class="flex-1 py-6 px-3.5 overflow-y-auto">
            <!-- Menu Utama -->
            <div class="mb-7">
                <span class="sidebar-text text-[0.65rem] font-extrabold text-slate-400 dark:text-[#4b5563] px-3 mb-3.5 block tracking-[1.5px] uppercase">Menu Utama</span>
                <ul class="list-none p-0 m-0">
                    <li class="mb-1 relative group">
                        @php $isActive = request()->routeIs('dashboard') || request()->is('/'); @endphp
                        @if($isActive)
                            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-7 bg-[#d3a15c] rounded-r z-10 block"></div>
                        @endif
                        <a href="{{ route('dashboard') }}" class="flex items-center p-3 dark:p-2 px-4 dark:px-3.5 rounded-xl transition-all duration-200 gap-3.5 {{ $isActive ? 'bg-[#f5e9d3] dark:bg-[#3f2b1d] text-[#78350f] dark:text-white shadow-sm font-bold' : 'text-slate-600 dark:text-[#9ca3af] hover:bg-slate-50 dark:hover:bg-white/5 hover:text-[#c58744] dark:hover:text-white' }}">
                            <i class="fas fa-chart-pie w-[22px] text-center text-lg opacity-80 shrink-0"></i>
                            <span class="sidebar-text text-sm font-semibold">Dashboard</span>
                        </a>
                    </li>
                    <li class="mb-1.5 relative group">
                        @php $isActive = request()->routeIs('transactions.index'); @endphp
                        @if($isActive)
                            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-7 bg-[#d3a15c] rounded-r z-10 block"></div>
                        @endif
                        <a href="{{ route('transactions.index') }}" class="flex items-center p-3 dark:p-2 px-4 dark:px-3.5 rounded-xl transition-all duration-200 gap-3.5 {{ $isActive ? 'bg-[#f5e9d3] dark:bg-[#3f2b1d] text-[#78350f] dark:text-white shadow-sm font-bold' : 'text-slate-600 dark:text-[#9ca3af] hover:bg-slate-50 dark:hover:bg-white/5 hover:text-[#c58744] dark:hover:text-white' }}">
                            <i class="fas fa-cash-register w-[22px] text-center text-lg opacity-80 shrink-0"></i>
                            <span class="sidebar-text text-sm font-semibold">Transactions</span>
                        </a>
                    </li>
                    <li class="mb-1.5 relative group">
                        @php $isActive = request()->routeIs('inventory.index'); @endphp
                        @if($isActive)
                            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-7 bg-[#d3a15c] rounded-r z-10 block"></div>
                        @endif
                        <a href="{{ route('inventory.index') }}" class="flex items-center p-3 dark:p-2 px-4 dark:px-3.5 rounded-xl transition-all duration-200 gap-3.5 {{ $isActive ? 'bg-[#f5e9d3] dark:bg-[#3f2b1d] text-[#78350f] dark:text-white shadow-sm font-bold' : 'text-slate-600 dark:text-[#9ca3af] hover:bg-slate-50 dark:hover:bg-white/5 hover:text-[#c58744] dark:hover:text-white' }}">
                            <i class="fas fa-boxes w-[22px] text-center text-lg opacity-80 shrink-0"></i>
                            <span class="sidebar-text text-sm font-semibold">Inventory</span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Machine Learning -->
            <div class="mb-7">
                <span class="sidebar-text text-[0.65rem] font-extrabold text-slate-400 dark:text-[#4b5563] px-3 mb-3.5 block tracking-[1.5px] uppercase">Machine Learning</span>
                <ul class="list-none p-0 m-0">
                    <li class="mb-1.5 relative group">
                        @php $isActive = request()->is('forecasting*'); @endphp
                        @if($isActive)
                            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-7 bg-[#d3a15c] rounded-r z-10 block"></div>
                        @endif
                        <a href="/forecasting" class="flex items-center p-3 dark:p-2 px-4 dark:px-3.5 rounded-xl transition-all duration-200 gap-3.5 {{ $isActive ? 'bg-[#f5e9d3] dark:bg-[#3f2b1d] text-[#78350f] dark:text-white shadow-sm font-bold' : 'text-slate-600 dark:text-[#9ca3af] hover:bg-slate-50 dark:hover:bg-white/5 hover:text-[#c58744] dark:hover:text-white' }}">
                            <i class="fas fa-brain w-[22px] text-center text-lg opacity-80 shrink-0"></i>
                            <span class="sidebar-text text-sm font-semibold">Forecasting</span>
                            <span class="sidebar-text py-0.5 px-2 rounded-md text-[0.65rem] font-black ml-auto tracking-tighter bg-emerald-500 text-white shadow-[0_2px_8px_rgba(16,185,129,0.3)]">ML</span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Administrasi -->
            <div class="mb-7">
                <span class="sidebar-text text-[0.65rem] font-extrabold text-slate-400 dark:text-[#4b5563] px-3 mb-3.5 block tracking-[1.5px] uppercase">Administrasi</span>
                <ul class="list-none p-0 m-0">
                    <li class="mb-1.5 relative group">
                        @php $isActive = request()->routeIs('laporan.*'); @endphp
                        @if($isActive)
                            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-7 bg-[#d3a15c] rounded-r z-10 block"></div>
                        @endif
                        <a href="{{ route('laporan.index') }}" class="flex items-center p-3 dark:p-2 px-4 dark:px-3.5 rounded-xl transition-all duration-200 gap-3.5 {{ $isActive ? 'bg-[#f5e9d3] dark:bg-[#3f2b1d] text-[#78350f] dark:text-white shadow-sm font-bold' : 'text-slate-600 dark:text-[#9ca3af] hover:bg-slate-50 dark:hover:bg-white/5 hover:text-[#c58744] dark:hover:text-white' }}">
                            <i class="fas fa-file-invoice w-[22px] text-center text-lg opacity-80 shrink-0"></i>
                            <span class="sidebar-text text-sm font-semibold">Laporan</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Sidebar Footer -->
        <div class="p-6 border-t border-slate-200 dark:border-white/5 bg-slate-50/50 dark:bg-black/10">
            <div class="sidebar-footer-container flex items-center gap-3.5 bg-transparent p-0 rounded-2xl border border-transparent transition-all duration-300">
                <div class="w-[42px] h-[42px] bg-gradient-to-br from-[#d3a15c] to-[#c58744] rounded-full flex items-center justify-center text-white font-bold text-lg shadow-sm dark:shadow-[0_4px_10px_rgba(211,161,92,0.3)] shrink-0">
                    A
                </div>
                <div class="sidebar-text flex flex-col flex-1 overflow-hidden transition-all duration-300">
                    <span class="text-slate-800 dark:text-white text-[0.9rem] font-bold truncate">Admin</span>
                    <span class="text-[0.75rem] text-slate-500 dark:text-[#9ca3af] truncate">Pemilik Toko</span>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="m-0 p-0 shrink-0">
                    @csrf
                    <button type="submit" class="bg-transparent border-none text-slate-400 dark:text-[#9ca3af] cursor-pointer text-xl transition-all duration-200 p-2 rounded-lg hover:text-red-500 hover:bg-red-500/10" title="Keluar Sistem">
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

            @yield('content')
        </main>

        <footer class="py-8 mt-auto border-t border-slate-200 dark:border-slate-800">
            <div class="max-w-7xl mx-auto px-8">
                <p class="text-center text-slate-400 dark:text-slate-600 text-[10px] font-bold tracking-widest uppercase">
                    &copy; {{ date('Y') }} GOLDEN TULIP BAKERY MANAGEMENT SYSTEM. INTEGRATED WITH ML FORECASTING.
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

        // Sidebar Collapse Logic
        const sidebar = document.getElementById('sidebar');
        const sidebarBrand = document.getElementById('sidebar-brand');
        const sidebarToggle = document.getElementById('sidebar-toggle');

        function toggleSidebar(collapse) {
            if (collapse) {
                document.documentElement.classList.add('sidebar-collapsed');
                localStorage.setItem('sidebar-collapsed', 'true');
            } else {
                document.documentElement.classList.remove('sidebar-collapsed');
                localStorage.setItem('sidebar-collapsed', 'false');
            }
        }

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function(e) {
                e.stopPropagation(); // Mencegah terpicunya event klik brand
                toggleSidebar(true);
            });
        }

        if (sidebarBrand) {
            sidebarBrand.addEventListener('click', function() {
                if (document.documentElement.classList.contains('sidebar-collapsed')) {
                    toggleSidebar(false);
                }
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

        /* Sidebar Collapsed Styles */
        .sidebar-collapsed #sidebar {
            width: 80px !important;
        }
        .sidebar-collapsed .sidebar-text {
            display: none !important;
        }
        .sidebar-collapsed #sidebar-brand {
            justify-content: center !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        /* Center navigation items when collapsed */
        .sidebar-collapsed #sidebar a {
            justify-content: center !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        /* Center and scale icons slightly when collapsed for better usability */
        .sidebar-collapsed #sidebar a i {
            margin-right: 0 !important;
            font-size: 1.25rem !important;
        }
        /* Vertical stacking of avatar and logout button in collapsed footer */
        .sidebar-collapsed .sidebar-footer-container {
            flex-direction: column !important;
            gap: 16px !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .sidebar-collapsed .sidebar-footer-container form {
            display: block !important;
        }
    </style>

    <!-- ======================================= -->
    <!-- Toast Notification Container & System   -->
    <!-- ======================================= -->
    <div id="toast-container" class="fixed top-6 right-6 z-[9999] flex flex-col gap-3 pointer-events-none" style="max-width: 400px;"></div>

    <style>
        /* Toast Animations */
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
        .toast-enter {
            animation: toastSlideIn 0.4s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        }
        .toast-exit {
            animation: toastSlideOut 0.5s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        }
        .toast-progress-bar {
            animation: toastProgress linear forwards;
        }
    </style>

    <script>
        // Global Toast System
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
            const iconColor = isDark
                ? (isSuccess ? 'color:#34d399' : 'color:#fb7185')
                : (isSuccess ? 'color:#10b981' : 'color:#f43f5e');
            const titleColor = isDark
                ? (isSuccess ? 'color:#a7f3d0' : 'color:#fecdd3')
                : (isSuccess ? 'color:#065f46' : 'color:#9f1239');
            const msgColor = isDark
                ? (isSuccess ? 'color:#6ee7b7' : 'color:#fda4af')
                : (isSuccess ? 'color:#047857' : 'color:#be123c');
            const progressColor = isSuccess ? '#10b981' : '#f43f5e';

            toast.innerHTML = `
                <div class="flex items-start gap-3 p-4 pr-10">
                    <i class="fas ${iconClass} text-lg mt-0.5 shrink-0" style="${iconColor}"></i>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold uppercase tracking-wider mb-0.5" style="${titleColor}">
                            ${isSuccess ? 'Berhasil' : 'Gagal'}
                        </p>
                        <p class="text-sm font-medium leading-relaxed" style="${msgColor}">
                            ${message}
                        </p>
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

            // Auto dismiss
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

        // Auto-fire toasts from Laravel session flash data
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                showToast(@json(session('success')), 'success');
            @endif
            @if(session('error'))
                showToast(@json(session('error')), 'error');
            @endif
        });
    </script>
</body>
</html>
