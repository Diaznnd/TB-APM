@extends('layouts.app')

@section('title', 'Transaksi')

@section('content')

<div class="flex justify-between items-center mb-6">

    <div>
        <h1 class="text-3xl font-bold text-slate-800 dark:text-slate-100">
            Point of Sale
        </h1>

        <p class="text-slate-500 dark:text-slate-400 mt-1">
            Kelola transaksi dan stok produk
        </p>
    </div>

    <a href="{{ route('transactions.history') }}"
       class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2.5 rounded-xl transition">
        Riwayat Transaksi
    </a>

</div>

<div class="flex gap-6">

    <!-- MENU -->
    <div class="w-3/4">

        <!-- SEARCH -->
        <div class="mb-6">

            <input
                type="text"
                id="searchProduct"
                placeholder="Cari produk..."
                class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-3 text-slate-700 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-yellow-500"
            >

        </div>

        <div class="grid grid-cols-3 gap-6" id="productContainer">

            @foreach($products as $product)

            <div
                class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-5 hover:shadow-lg transition product-item"
                data-name="{{ strtolower($product->name) }}"
            >

                <div class="mb-4">

                    <div class="flex items-start justify-between">

                        <div>
                            <h3 class="font-bold text-lg text-slate-800 dark:text-slate-100">
                                {{ $product->name }}
                            </h3>

                            <p class="text-yellow-600 font-semibold mt-1">
                                Rp {{ number_format($product->price,0,',','.') }}
                            </p>
                        </div>

                        @if($product->stock > 0)

                        <span class="bg-emerald-100 text-emerald-700 text-xs px-3 py-1 rounded-full font-semibold">
                            Ready
                        </span>

                        @else

                        <span class="bg-red-100 text-red-700 text-xs px-3 py-1 rounded-full font-semibold">
                            Habis
                        </span>

                        @endif

                    </div>

                </div>

                <div class="mb-4">

                    <div class="flex justify-between items-center text-sm">

                        <span class="text-slate-500 dark:text-slate-400">
                            Stok Tersedia
                        </span>

                        <span class="font-bold text-slate-700 dark:text-slate-200">
                            {{ $product->stock }}
                        </span>

                    </div>

                </div>

                @if($product->stock > 0)

                <button
                    onclick="addToCart(
                        {{ $product->id }},
                        '{{ $product->name }}',
                        {{ $product->price }},
                        {{ $product->stock }}
                    )"
                    class="w-full bg-yellow-600 hover:bg-yellow-700 text-white py-3 rounded-xl font-semibold transition"
                >
                    Tambah ke Keranjang
                </button>

                @else

                <button
                    disabled
                    class="w-full bg-slate-300 dark:bg-slate-700 text-slate-500 py-3 rounded-xl cursor-not-allowed font-semibold"
                >
                    Stok Habis
                </button>

                @endif

            </div>

            @endforeach

        </div>

    </div>

    <!-- CART -->
    <div class="w-1/4">

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-100 dark:border-slate-700 sticky top-4">

            <div class="flex items-center justify-between mb-5">

                <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100">
                    Order Details
                </h2>

                <span class="bg-yellow-100 text-yellow-700 text-xs px-3 py-1 rounded-full">
                    <span id="cartCount">0</span> Item
                </span>

            </div>

            <div id="cartItems" class="space-y-4"></div>

            <div id="emptyCart" class="text-center py-10 text-slate-400">
                Keranjang masih kosong
            </div>

            <hr class="my-5">

            <div class="flex justify-between items-center mb-2">

                <span class="text-slate-500 dark:text-slate-400">
                    Total
                </span>

                <span id="total"
                      class="font-bold text-2xl text-slate-800 dark:text-slate-100">
                    Rp 0
                </span>

            </div>

            <button
                onclick="checkout()"
                class="w-full mt-6 bg-yellow-600 hover:bg-yellow-700 text-white py-3 rounded-xl font-semibold transition"
            >
                Selesaikan Transaksi
            </button>

        </div>

    </div>

</div>

<!-- PAYMENT MODAL -->
<div
    id="paymentModal"
    class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50"
>

    <div class="bg-white dark:bg-slate-900 rounded-2xl w-[500px] p-6 shadow-xl">

        <div class="flex justify-between items-center mb-5">

            <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100">
                Konfirmasi Pembayaran
            </h2>

            <button
                onclick="closePaymentModal()"
                class="text-slate-400 hover:text-red-500 text-xl"
            >
                ✕
            </button>

        </div>

        <div class="space-y-3 max-h-[300px] overflow-auto mb-5" id="paymentItems"></div>

        <!-- PAYMENT METHOD -->
        <div class="mt-5">

            <label class="block mb-2 font-semibold text-slate-700 dark:text-slate-200">
                Metode Pembayaran
            </label>

            <div class="grid grid-cols-3 gap-3">

                <button
                    type="button"
                    onclick="selectPayment('cash')"
                    id="payment-cash"
                    class="payment-method border-2 border-yellow-500 bg-yellow-50 text-yellow-700 py-3 rounded-xl font-semibold transition"
                >
                    Cash
                </button>

                <button
                    type="button"
                    onclick="selectPayment('card')"
                    id="payment-card"
                    class="payment-method border border-slate-300 py-3 rounded-xl font-semibold transition"
                >
                    Card
                </button>

                <button
                    type="button"
                    onclick="selectPayment('qris')"
                    id="payment-qris"
                    class="payment-method border border-slate-300 py-3 rounded-xl font-semibold transition"
                >
                    QRIS
                </button>

            </div>

        </div>

        <div class="border-t pt-4 mt-5">

            <div class="flex justify-between text-lg font-bold">

                <span>Total Bayar</span>

                <span id="paymentTotal">
                    Rp 0
                </span>

            </div>

        </div>

        <button
            onclick="confirmCheckout()"
            class="w-full mt-6 bg-yellow-600 hover:bg-yellow-700 text-white py-3 rounded-xl font-semibold"
        >
            Konfirmasi Pembayaran
        </button>

    </div>

</div>

<!-- TOAST -->
<div
    id="toast"
    class="fixed top-24 right-5 hidden z-50 transition-all duration-300 ease-out"
    style="transform: translateY(-10px); opacity: 0;"
>
    <div
        id="toastBox"
        class="px-5 py-3 rounded-xl shadow-lg text-white font-semibold"
    >
        Pesan
    </div>
</div>

<script>

let cart = [];
let paymentMethod = 'cash';

function showToast(message, type = 'success')
{
    const toast = document.getElementById('toast');
    const box = document.getElementById('toastBox');

    box.innerText = message;

    if(type === 'success'){
        box.className =
            'px-5 py-3 rounded-xl shadow-lg text-white font-semibold bg-emerald-500';
    } else {
        box.className =
            'px-5 py-3 rounded-xl shadow-lg text-white font-semibold bg-red-500';
    }

    toast.classList.remove('hidden');

    setTimeout(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0)';
    }, 10);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-10px)';

        setTimeout(() => {
            toast.classList.add('hidden');
        }, 300);

    }, 2500);
}

function selectPayment(method)
{
    paymentMethod = method;

    document.querySelectorAll('.payment-method')
    .forEach(btn => {

        btn.classList.remove(
            'border-yellow-500',
            'bg-yellow-50',
            'text-yellow-700'
        );

    });

    const active =
        document.getElementById('payment-' + method);

    active.classList.add(
        'border-yellow-500',
        'bg-yellow-50',
        'text-yellow-700'
    );
}

function addToCart(id, name, price, stock)
{
    let existing = cart.find(item => item.id === id);

    if(existing){

        if(existing.qty >= stock){
            showToast('Stok tidak cukup', 'error');
            return;
        }

        existing.qty++;

    } else {

        cart.push({
            id,
            name,
            price,
            qty: 1,
            stock
        });
    }

    renderCart();
}

function renderCart()
{
    let container = document.getElementById('cartItems');
    let empty = document.getElementById('emptyCart');

    container.innerHTML = '';

    let total = 0;
    let totalItem = 0;

    if(cart.length <= 0){
        empty.style.display = 'block';
    } else {
        empty.style.display = 'none';
    }

    cart.forEach((item, index) => {

        total += item.price * item.qty;
        totalItem += item.qty;

        container.innerHTML += `
            <div class="border border-slate-200 rounded-xl p-3">

                <div class="flex justify-between items-start">

                    <div>

                        <h4 class="font-semibold">
                            ${item.name}
                        </h4>

                        <p class="text-sm text-slate-500 mt-1">
                            Rp ${item.price.toLocaleString()}
                        </p>

                    </div>

                    <button
                        onclick="removeItem(${index})"
                        class="text-red-500"
                    >
                        ✕
                    </button>

                </div>

                <div class="flex items-center gap-3 mt-4">

                    <button
                        onclick="decreaseQty(${index})"
                        class="w-8 h-8 rounded-lg bg-slate-300 text-black font-bold"
                    >
                        -
                    </button>

                    <span class="font-semibold">
                        ${item.qty}
                    </span>

                    <button
                        onclick="increaseQty(${index})"
                        class="w-8 h-8 rounded-lg bg-yellow-500 text-white font-bold"
                    >
                        +
                    </button>

                </div>

            </div>
        `;
    });

    document.getElementById('total').innerText =
        'Rp ' + total.toLocaleString();

    document.getElementById('cartCount').innerText =
        totalItem;
}

function increaseQty(index)
{
    if(cart[index].qty >= cart[index].stock){
        showToast('Stok habis', 'error');
        return;
    }

    cart[index].qty++;

    renderCart();
}

function decreaseQty(index)
{
    cart[index].qty--;

    if(cart[index].qty <= 0){
        cart.splice(index,1);
    }

    renderCart();
}

function removeItem(index)
{
    cart.splice(index,1);
    renderCart();
}

function checkout()
{
    if(cart.length === 0){
        showToast('Keranjang kosong', 'error');
        return;
    }

    let paymentItems = document.getElementById('paymentItems');
    let paymentTotal = document.getElementById('paymentTotal');

    paymentItems.innerHTML = '';

    let total = 0;

    cart.forEach(item => {

        total += item.price * item.qty;

        paymentItems.innerHTML += `
            <div class="flex justify-between border-b pb-2">

                <div>
                    <p class="font-semibold">${item.name}</p>
                    <p class="text-sm text-slate-500">
                        ${item.qty} x Rp ${item.price.toLocaleString()}
                    </p>
                </div>

                <p class="font-semibold">
                    Rp ${(item.price * item.qty).toLocaleString()}
                </p>

            </div>
        `;
    });

    paymentTotal.innerText =
        'Rp ' + total.toLocaleString();

    document.getElementById('paymentModal')
        .classList.remove('hidden');

    document.getElementById('paymentModal')
        .classList.add('flex');
}

function closePaymentModal()
{
    document.getElementById('paymentModal')
        .classList.remove('flex');

    document.getElementById('paymentModal')
        .classList.add('hidden');
}

function confirmCheckout()
{
    fetch("{{ route('transactions.store') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            cart: cart,
            metode_bayar: paymentMethod
        })
    })
    .then(res => res.json())
    .then(data => {

        if(data.success){

            closePaymentModal();

            showToast(data.message, 'success');

            setTimeout(() => {
                location.reload();
            }, 1000);

        } else {

            showToast(data.message, 'error');
        }
    })
    .catch(() => {
        showToast('Terjadi kesalahan', 'error');
    });
}

renderCart();

document.getElementById('searchProduct')
.addEventListener('keyup', function(){

    let keyword = this.value.toLowerCase();

    document.querySelectorAll('.product-item')
    .forEach(item => {

        let name = item.dataset.name;

        item.style.display =
            name.includes(keyword)
            ? 'block'
            : 'none';

    });

});

</script>

@endsection