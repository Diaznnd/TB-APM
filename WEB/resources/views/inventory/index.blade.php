@extends('layouts.app')

@section('title', 'Manajemen Inventory')

@section('content')
<div class="space-y-8 pb-12">
    <!-- Unified Premium Page Header Card -->
    <div class="relative overflow-hidden p-6 md:p-8 rounded-3xl bg-gradient-to-br from-white/90 via-white/50 to-[#fdfbf7]/30 dark:from-[#111827]/90 dark:via-[#111827]/60 dark:to-slate-900/30 backdrop-blur-xl border border-slate-200/60 dark:border-white/5 shadow-xl shadow-slate-100/40 dark:shadow-none transition-all duration-300">
        <!-- Glowing background orbs for subtle premium aesthetic -->
        <div class="absolute top-0 right-0 -mt-12 -mr-12 w-48 h-48 bg-gradient-to-br from-emerald-500/10 to-teal-500/10 dark:from-emerald-500/5 dark:to-teal-500/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-10 left-1/3 w-36 h-36 bg-amber-500/5 dark:bg-amber-500/2 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-start md:justify-between gap-6">
            <!-- Left Info Block -->
            <div class="space-y-4 max-w-3xl">
                <!-- Badges Row -->
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-500/10 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 border border-emerald-500/20 dark:border-emerald-500/30">
                        <i class="fas fa-boxes animate-pulse text-[11px] text-emerald-600 dark:text-emerald-400"></i>
                        Inventory Engine Active
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-500/10 dark:bg-amber-500/20 text-amber-700 dark:text-amber-300 border border-amber-500/20 dark:border-amber-500/30">
                        <i class="fas fa-database text-[11px] text-amber-600 dark:text-amber-400"></i>
                        Total: {{ count($products) }} Produk
                    </span>
                </div>

                <!-- Main Heading & Title -->
                <div>
                    <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                        Manajemen <span class="bg-gradient-to-r from-emerald-600 via-emerald-500 to-teal-500 bg-clip-text text-transparent">Inventory Stok</span>
                    </h1>
                </div>

                <!-- Horizontal Pills for Key Insights/Meta -->
                <div class="flex flex-wrap items-center gap-x-5 gap-y-2.5 pt-2 border-t border-slate-200/50 dark:border-slate-800/60 text-xs">
                    <div class="flex items-center gap-1.5 text-slate-500 dark:text-slate-400">
                        <i class="fas fa-exclamation-triangle text-rose-500/70 dark:text-rose-400/60 text-[13px]"></i>
                        <span>Produk Minim Stok:</span>
                        <strong class="text-rose-600 dark:text-rose-400 font-semibold">{{ $products->where('stock', '<=', 10)->count() }} Items</strong>
                    </div>
                </div>
            </div>

            <!-- Right Actions Block -->
            <div class="flex flex-col items-stretch md:items-end justify-between self-stretch shrink-0 md:min-w-[180px]">
                <!-- Tambah Produk Button -->
                <div class="flex flex-col gap-3 w-full">
                    <button onclick="openProductModal()" class="group relative w-full flex items-center justify-center px-5 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 dark:from-emerald-600 dark:to-teal-600 dark:hover:from-emerald-700 dark:hover:to-teal-700 text-white font-bold rounded-2xl shadow-lg shadow-emerald-500/20 dark:shadow-none hover:shadow-xl hover:shadow-emerald-500/30 transition-all duration-300 outline-none transform active:scale-[0.98]">
                        <i class="fas fa-plus mr-2 text-sm group-hover:scale-110 transition-transform"></i>
                        <span class="text-sm tracking-wide">Tambah Produk</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLE AREA -->
    <div class="glass-card rounded-2xl shadow-sm overflow-hidden flex flex-col border border-slate-100 dark:border-slate-800 transition-all duration-300">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 flex justify-between items-center transition-colors">
            <div>
                <h3 class="font-bold text-slate-900 dark:text-white">Daftar Inventori Roti</h3>
            </div>
            <span class="px-3 py-1 rounded-lg text-xs text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 font-bold border border-emerald-200 dark:border-emerald-800/50">Database Sinkron</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-slate-400 dark:text-slate-500 text-[10px] font-bold uppercase tracking-widest border-b border-slate-100 dark:border-slate-800 transition-colors">
                        <th class="px-6 py-4">Nama Produk</th>
                        <th class="px-6 py-4 text-right">Stok</th>
                        <th class="px-6 py-4 text-right">Harga Satuan</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="productsTable" class="divide-y divide-slate-100 dark:divide-slate-800/50 transition-colors">
                    @forelse($products as $p)
                        <tr class="hover:bg-emerald-50/20 dark:hover:bg-slate-850/30 transition-colors">
                            <!-- PRODUCT -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-100 dark:border-transparent">
                                        <i class="fas fa-bread-slice text-xs"></i>
                                    </div>
                                    <span class="font-semibold text-slate-900 dark:text-white">{{ $p->name }}</span>
                                </div>
                            </td>

                            <!-- STOCK -->
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                @if($p->stock <= 10)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-black bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-800/30">
                                        <span class="h-1.5 w-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                        {{ $p->stock }} Pcs
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400">
                                        {{ $p->stock }} Pcs
                                    </span>
                                @endif
                            </td>

                            <!-- PRICE -->
                            <td class="px-6 py-4 text-right font-bold text-slate-900 dark:text-white whitespace-nowrap tabular-nums">
                                Rp {{ number_format($p->price, 0, ',', '.') }}
                            </td>

                            <!-- ACTION -->
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="inline-flex gap-2">
                                    <button onclick="editProduct({{ $p->id }})" class="inline-flex items-center gap-1 text-xs px-2.5 py-1 bg-amber-50 dark:bg-amber-950/30 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800/50 rounded-lg hover:bg-amber-100 dark:hover:bg-amber-900/40 transition">
                                        <i class="fas fa-edit text-[10px]"></i>
                                        Edit
                                    </button>
                                    <button onclick="deleteProduct({{ $p->id }})" class="inline-flex items-center gap-1 text-xs px-2.5 py-1 bg-rose-50 dark:bg-rose-950/30 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-800/50 rounded-lg hover:bg-rose-100 dark:hover:bg-rose-900/40 transition">
                                        <i class="fas fa-trash-alt text-[10px]"></i>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center text-slate-400 dark:text-slate-500">
                                <i class="fas fa-inbox text-3xl mb-3 block text-slate-200 dark:text-slate-700"></i>
                                Belum ada produk. Klik "Tambah Produk" untuk menambahkan produk baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH/EDIT PRODUK -->
<div id="productModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 backdrop-blur-sm transition-all duration-300">
    <div class="bg-white/90 dark:bg-slate-900/95 backdrop-blur-2xl border border-slate-200/80 dark:border-white/5 rounded-3xl shadow-2xl w-full max-w-lg p-6 md:p-8 relative transition-all duration-300">
        <!-- CLOSE BUTTON -->
        <button onclick="closeProductModal()" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors outline-none w-8 h-8 rounded-full flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-800">
            <i class="fas fa-times text-sm"></i>
        </button>

        <!-- TITLE -->
        <h3 id="productModalTitle" class="text-xl font-extrabold text-slate-900 dark:text-white mb-6">
            Tambah Produk
        </h3>

        <!-- FORM -->
        <div class="space-y-5">
            <!-- NAME -->
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider pl-1">
                    Nama Produk
                </label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 dark:text-slate-500 group-focus-within:text-emerald-500 transition-colors">
                        <i class="fas fa-bread-slice text-sm"></i>
                    </span>
                    <input id="prodName" type="text" placeholder="Contoh: Croissant Cokelat" class="w-full pl-11 pr-4 py-3.5 bg-slate-50/50 dark:bg-slate-950/40 border border-slate-200 dark:border-white/5 rounded-2xl text-sm text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-semibold">
                </div>
            </div>

            <!-- STOCK -->
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider pl-1">
                    Stok Tersedia
                </label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 dark:text-slate-500 group-focus-within:text-emerald-500 transition-colors">
                        <i class="fas fa-boxes text-sm"></i>
                    </span>
                    <input id="prodStock" type="number" min="0" value="0" class="w-full pl-11 pr-4 py-3.5 bg-slate-50/50 dark:bg-slate-950/40 border border-slate-200 dark:border-white/5 rounded-2xl text-sm text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-semibold">
                </div>
            </div>

            <!-- PRICE -->
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider pl-1">
                    Harga Jual (Rp)
                </label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 dark:text-slate-500 group-focus-within:text-emerald-500 transition-colors">
                        <i class="fas fa-tag text-sm"></i>
                    </span>
                    <input id="prodPrice" type="number" min="0" value="0" class="w-full pl-11 pr-4 py-3.5 bg-slate-50/50 dark:bg-slate-950/40 border border-slate-200 dark:border-white/5 rounded-2xl text-sm text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-semibold">
                </div>
            </div>
        </div>

        <!-- ACTIONS BUTTONS -->
        <div class="flex justify-end gap-3 mt-8">
            <button onclick="closeProductModal()" class="px-5 py-3 rounded-2xl border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 font-bold hover:bg-slate-100 dark:hover:bg-white/5 transition-all text-xs">
                Batal
            </button>
            <button onclick="saveProduct()" class="px-5 py-3 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-bold hover:shadow-lg shadow-emerald-500/20 dark:shadow-none hover:shadow-emerald-500/30 transition-all text-xs transform active:scale-98">
                Simpan
            </button>
        </div>
    </div>
</div>

<script>
let editingProductId = null;

function openProductModal() {
    editingProductId = null;
    document.getElementById('productModalTitle').innerText = 'Tambah Produk';
    document.getElementById('prodName').value = '';
    document.getElementById('prodStock').value = 0;
    document.getElementById('prodPrice').value = 0;
    document.getElementById('productModal').classList.remove('hidden');
    document.getElementById('productModal').classList.add('flex');
}

function closeProductModal() {
    document.getElementById('productModal').classList.remove('flex');
    document.getElementById('productModal').classList.add('hidden');
}

function editProduct(id) {
    fetch(`/inventory/products/${id}`)
    .then(response => response.json())
    .then(data => {
        const p = data.product;
        editingProductId = p.id;
        document.getElementById('productModalTitle').innerText = 'Edit Produk';
        document.getElementById('prodName').value = p.name || '';
        document.getElementById('prodStock').value = p.stock ?? 0;
        document.getElementById('prodPrice').value = p.price ?? 0;
        document.getElementById('productModal').classList.remove('hidden');
        document.getElementById('productModal').classList.add('flex');
    })
    .catch(error => {
        console.log(error);
        showToast('Gagal mengambil data produk', 'error');
    });
}

function saveProduct() {
    const payload = {
        name: document.getElementById('prodName').value,
        stock: parseInt(document.getElementById('prodStock').value) || 0,
        price: parseInt(document.getElementById('prodPrice').value) || 0,
    };

    const url = editingProductId
        ? `/inventory/products/${editingProductId}`
        : `/inventory/products`;

    const method = editingProductId ? 'PUT' : 'POST';

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(payload)
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) {
            if (data.errors) {
                const firstErrorKey = Object.keys(data.errors)[0];
                const firstErrorMessage = data.errors[firstErrorKey][0];
                throw new Error(firstErrorMessage);
            }
            throw new Error(data.message || 'Gagal menyimpan produk');
        }
        return data;
    })
    .then(data => {
        if(data.success){
            closeProductModal();
            showToast(data.message, 'success');
            setTimeout(() => {
                location.reload();
            }, 1200);
        } else {
            showToast(data.message || 'Gagal menyimpan produk', 'error');
        }
    })
    .catch(error => {
        console.log(error);
        showToast(error.message || 'Terjadi kesalahan', 'error');
    });
}

function deleteProduct(id) {
    if(!confirm('Hapus produk ini?')) return;

    fetch(`/inventory/products/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success){
            showToast(data.message, 'success');
            setTimeout(() => {
                location.reload();
            }, 1200);
        } else {
            showToast('Gagal menghapus produk', 'error');
        }
    })
    .catch(error => {
        console.log(error);
        showToast('Terjadi kesalahan', 'error');
    });
}
</script>
@endsection