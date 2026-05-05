<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Bakery Management') }} - Forecasting</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); }
    </style>
</head>
<body class="text-slate-800">
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <i class="fas fa-bread-slice text-amber-600 text-2xl mr-2"></i>
                        <span class="font-bold text-xl tracking-tight text-slate-900">Bakery<span class="text-amber-600">Pro</span></span>
                    </div>
                    <div class="hidden sm:ml-8 sm:flex sm:space-x-8">
                        <a href="/" class="border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Dashboard</a>
                        <a href="/forecasting" class="border-amber-500 text-slate-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Forecasting</a>
                        <a href="#" class="border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Kasir</a>
                        <a href="#" class="border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Inventory</a>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <button type="button" class="relative inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-amber-600 shadow-sm hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                            <i class="fas fa-plus mr-2"></i>
                            <span>Transaksi Baru</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-emerald-50 border-l-4 border-emerald-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-emerald-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-emerald-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-rose-50 border-l-4 border-rose-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-rose-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-rose-700">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="bg-white border-t border-slate-200 py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-slate-500 text-sm">
                &copy; {{ date('Y') }} Bakery Management System. Integrated with ML Forecasting.
            </p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
