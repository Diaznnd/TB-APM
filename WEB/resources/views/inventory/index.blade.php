@extends('layouts.app')

@section('title', 'Manajemen Inventory')

@section('content')
<div class="glass-card p-10 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
    <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">Halaman Inventory</h2>
    <p class="text-slate-500 dark:text-slate-400">Selamat datang di modul Manajemen Inventory. Pantau stok bahan baku dan produk jadi Anda di sini.</p>
    
    <div class="mt-8 p-12 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-2xl flex flex-col items-center justify-center text-center">
        <div class="w-16 h-16 bg-slate-50 dark:bg-slate-900/50 rounded-full flex items-center justify-center mb-4">
            <i class="fas fa-boxes text-2xl text-slate-300"></i>
        </div>
        <p class="text-slate-400 font-medium text-sm">Konten inventory akan ditambahkan di sini.</p>
    </div>
</div>
@endsection
