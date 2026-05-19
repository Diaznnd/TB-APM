@extends('layouts.app')

@section('title', 'Manajemen Inventory')

@section('content')

<div class="glass-card p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">

    <!-- HEADER -->
    <div class="flex items-center justify-between mb-6">

        <div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">
                Manajemen Inventory
            </h2>

            <p class="text-slate-500 dark:text-slate-400 mt-1">
                Kelola stok dan harga produk bakery.
            </p>
        </div>

        <button
            onclick="openProductModal()"
            class="bg-yellow-600 hover:bg-yellow-700 text-white px-5 py-2.5 rounded-xl font-medium transition"
        >
            + Tambah Produk
        </button>

    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-700">

        <table class="min-w-full bg-white dark:bg-slate-800">

            <!-- TABLE HEAD -->
            <thead class="bg-slate-50 dark:bg-slate-900">

                <tr>

                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600 dark:text-slate-300">
                        Produk
                    </th>

                    <th class="px-6 py-4 text-right text-sm font-semibold text-slate-600 dark:text-slate-300">
                        Stok
                    </th>

                    <th class="px-6 py-4 text-right text-sm font-semibold text-slate-600 dark:text-slate-300">
                        Harga
                    </th>

                    <th class="px-6 py-4 text-right text-sm font-semibold text-slate-600 dark:text-slate-300">
                        Aksi
                    </th>

                </tr>

            </thead>

            <!-- TABLE BODY -->
            <tbody id="productsTable">

                @forelse($products as $p)

                <tr class="border-t border-slate-100 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition">

                    <!-- PRODUCT -->
                    <td class="px-6 py-4">

                        <div class="font-semibold text-slate-800 dark:text-white">
                            {{ $p->name }}
                        </div>

                    </td>

                    <!-- STOCK -->
                    <td class="px-6 py-4 text-right">

                        @if($p->stock <= 10)

                            <span class="font-bold text-red-500">
                                {{ $p->stock }}
                            </span>

                        @else

                            <span class="font-medium text-slate-700 dark:text-slate-200">
                                {{ $p->stock }}
                            </span>

                        @endif

                    </td>

                    <!-- PRICE -->
                    <td class="px-6 py-4 text-right font-semibold text-slate-800 dark:text-white">
                        Rp {{ number_format($p->price, 0, ',', '.') }}
                    </td>

                    <!-- ACTION -->
                    <td class="px-6 py-4 text-right">

                        <button
                            onclick="editProduct({{ $p->id }})"
                            class="text-amber-600 hover:text-amber-700 font-medium mr-4"
                        >
                            Edit
                        </button>

                        <button
                            onclick="deleteProduct({{ $p->id }})"
                            class="text-red-500 hover:text-red-700 font-medium"
                        >
                            Hapus
                        </button>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="4" class="text-center py-10 text-slate-500 dark:text-slate-400">
                        Belum ada produk.
                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<!-- MODAL -->
<div
    id="productModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm"
>

    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl w-full max-w-lg p-6 relative">

        <!-- CLOSE -->
        <button
            onclick="closeProductModal()"
            class="absolute top-4 right-4 text-slate-500 hover:text-slate-800 dark:hover:text-white"
        >
            ✕
        </button>

        <!-- TITLE -->
        <h3
            id="productModalTitle"
            class="text-2xl font-bold text-slate-900 dark:text-white mb-6"
        >
            Tambah Produk
        </h3>

        <!-- FORM -->
        <div class="space-y-5">

            <!-- NAME -->
            <div>

                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Nama Produk
                </label>

                <input
                    id="prodName"
                    type="text"
                    placeholder="Contoh: Croissant"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-2 focus:ring-yellow-500 outline-none"
                >

            </div>

            <!-- STOCK -->
            <div>

                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Stock
                </label>

                <input
                    id="prodStock"
                    type="number"
                    min="0"
                    value="0"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-2 focus:ring-yellow-500 outline-none"
                >

            </div>

            <!-- PRICE -->
            <div>

                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Harga
                </label>

                <input
                    id="prodPrice"
                    type="number"
                    min="0"
                    value="0"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-2 focus:ring-yellow-500 outline-none"
                >

            </div>

        </div>

        <!-- BUTTON -->
        <div class="flex justify-end gap-3 mt-8">

            <button
                onclick="closeProductModal()"
                class="px-5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200"
            >
                Batal
            </button>

            <button
                onclick="saveProduct()"
                class="px-5 py-2.5 rounded-xl bg-yellow-600 hover:bg-yellow-700 text-white font-medium"
            >
                Simpan
            </button>

        </div>

    </div>

</div>

<script>

let editingProductId = null;

function openProductModal()
{
    editingProductId = null;

    document.getElementById('productModalTitle').innerText = 'Tambah Produk';

    document.getElementById('prodName').value = '';
    document.getElementById('prodStock').value = 0;
    document.getElementById('prodPrice').value = 0;

    document.getElementById('productModal').classList.remove('hidden');
    document.getElementById('productModal').classList.add('flex');
}

function closeProductModal()
{
    document.getElementById('productModal').classList.remove('flex');
    document.getElementById('productModal').classList.add('hidden');
}

function editProduct(id)
{
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

        alert('Gagal mengambil data produk');
    });
}

function saveProduct()
{
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

    .then(response => response.json())

    .then(data => {

        if(data.success){

            alert(data.message);

            location.reload();

        } else {

            alert('Gagal menyimpan produk');
        }
    })

    .catch(error => {

        console.log(error);

        alert('Terjadi kesalahan');
    });
}

function deleteProduct(id)
{
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

            alert(data.message);

            location.reload();

        } else {

            alert('Gagal menghapus produk');
        }
    })

    .catch(error => {

        console.log(error);

        alert('Terjadi kesalahan');
    });
}

</script>

@endsection