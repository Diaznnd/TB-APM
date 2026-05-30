@extends('layouts.app')

@section('title', 'Transaksi')

@section('content')
<div class="space-y-8 pb-12">
    <!-- Unified Premium Page Header Card -->
    <div class="relative overflow-hidden p-6 md:p-8 rounded-3xl bg-gradient-to-br from-white/90 via-white/50 to-[#fdfbf7]/30 dark:from-[#111827]/90 dark:via-[#111827]/60 dark:to-slate-900/30 backdrop-blur-xl border border-slate-200/60 dark:border-white/5 shadow-xl shadow-slate-100/40 dark:shadow-none transition-all duration-300">
        <!-- Glowing background orbs for subtle premium aesthetic -->
        <div class="absolute top-0 right-0 -mt-12 -mr-12 w-48 h-48 bg-gradient-to-br from-[#d3a15c]/10 to-[#c58744]/10 dark:from-[#d3a15c]/5 dark:to-[#c58744]/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-10 left-1/3 w-36 h-36 bg-emerald-500/5 dark:bg-emerald-500/2 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-start md:justify-between gap-6">
            <!-- Left Info Block -->
            <div class="space-y-4 max-w-3xl">
                <!-- Badges Row -->
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-500/10 dark:bg-amber-500/20 text-amber-700 dark:text-amber-300 border border-amber-500/20 dark:border-amber-500/30">
                        <i class="fas fa-cash-register animate-pulse text-[11px] text-amber-600 dark:text-amber-400"></i>
                        POS Engine Active
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-500/10 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 border border-emerald-500/20 dark:border-emerald-500/30">
                        <i class="fas fa-clock text-[11px] text-emerald-600 dark:text-emerald-400"></i>
                        Kasir Aktif
                    </span>
                </div>

                <!-- Main Heading & Title -->
                <div>
                    <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                        Kasir & <span class="bg-gradient-to-r from-[#d3a15c] to-[#c58744] bg-clip-text text-transparent">Point of Sale</span>
                    </h1>
                </div>
            </div>

            <!-- Right Actions Block -->
            <div class="flex flex-col items-stretch md:items-end justify-between self-stretch shrink-0 md:min-w-[180px]">
                <!-- Riwayat Button -->
                <div class="flex flex-col gap-3 w-full">
                    <a href="{{ route('transactions.history') }}" class="group relative w-full flex items-center justify-center px-5 py-3 bg-gradient-to-r from-[#d3a15c] to-[#c58744] hover:from-[#c58744] hover:to-[#a06c30] text-white font-bold rounded-2xl shadow-lg shadow-amber-500/20 dark:shadow-none hover:shadow-xl hover:shadow-amber-500/30 transition-all duration-300 outline-none transform active:scale-[0.98]">
                        <i class="fas fa-history mr-2.5 text-sm group-hover:rotate-6 transition-transform"></i>
                        <span class="text-sm tracking-wide">Riwayat Transaksi</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- POS MAIN WORKSPACE -->
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- PRODUCT PANEL (Left) -->
        <div class="flex-1 space-y-6">
            <!-- Search Product -->
            <div class="relative group">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 dark:text-slate-500 group-focus-within:text-amber-500 transition-colors">
                    <i class="fas fa-search text-sm"></i>
                </span>
                <input type="text" id="searchProduct" placeholder="Cari produk bakery..." class="w-full pl-11 pr-4 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/5 rounded-2xl text-sm text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-650 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all font-semibold shadow-sm">
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="productContainer">
                @foreach($products as $product)
                <div class="glass-card rounded-2xl p-5 border border-slate-100 dark:border-slate-800 hover:shadow-md hover:scale-[1.01] transition-all duration-300 product-item flex flex-col justify-between min-h-[200px]" data-name="{{ strtolower($product->name) }}">
                    <div>
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="font-bold text-slate-900 dark:text-white text-base leading-tight">{{ $product->name }}</h3>
                                <p class="text-amber-600 dark:text-amber-400 font-bold mt-1.5 text-xs tabular-nums">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </p>
                            </div>

                            @if($product->stock > 0)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-black bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50 uppercase tracking-wider">
                                Ready
                            </span>
                            @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-black bg-rose-50 dark:bg-rose-950/30 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-800/50 uppercase tracking-wider">
                                Habis
                            </span>
                            @endif
                        </div>

                        <!-- Stock details -->
                        <div class="flex justify-between items-center text-xs mt-4 mb-5 pb-3 border-b border-slate-100 dark:border-slate-800/60">
                            <span class="text-slate-400 dark:text-slate-500 font-medium">Stok Tersedia</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 tabular-nums">{{ $product->stock }} Pcs</span>
                        </div>
                    </div>

                    @if($product->stock > 0)
                    <button onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, {{ $product->stock }})" class="w-full bg-gradient-to-r from-[#d3a15c] to-[#c58744] hover:from-[#c58744] hover:to-[#a06c30] text-white py-2.5 rounded-xl font-bold hover:shadow-lg shadow-amber-500/10 dark:shadow-none transition transform active:scale-95 text-xs outline-none">
                        Tambah ke Keranjang
                    </button>
                    @else
                    <button disabled class="w-full bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-600 py-2.5 rounded-xl cursor-not-allowed font-bold text-xs border border-slate-200/50 dark:border-white/5 outline-none">
                        Stok Habis
                    </button>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <!-- CART PANEL (Right) -->
        <div class="w-full lg:w-[360px] shrink-0">
            <div class="glass-card p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 sticky top-24 transition-all duration-300">
                <!-- Cart Header -->
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-base font-extrabold text-slate-900 dark:text-white">Detail Pesanan</h2>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 border border-amber-200/50 dark:border-amber-800/30">
                        <span id="cartCount">0</span> Item
                    </span>
                </div>

                <!-- Cart Items Container -->
                <div id="cartItems" class="space-y-3 max-h-[350px] overflow-y-auto pr-1"></div>

                <!-- Empty State -->
                <div id="emptyCart" class="text-center py-12 text-slate-450 dark:text-slate-555">
                    <i class="fas fa-shopping-basket text-4xl text-slate-200 dark:text-slate-800 mb-3 block"></i>
                    <p class="text-xs font-semibold">Keranjang masih kosong</p>
                </div>

                <hr class="my-5 border-slate-100 dark:border-slate-800/60">

                <!-- Summary Row -->
                <div class="flex justify-between items-center mb-2">
                    <span class="text-xs text-slate-400 dark:text-slate-550 font-bold uppercase tracking-wider">Total</span>
                    <span id="total" class="font-black text-2xl text-slate-900 dark:text-white tabular-nums">Rp 0</span>
                </div>

                <!-- Checkout Trigger -->
                <button onclick="checkout()" class="w-full mt-4 bg-gradient-to-r from-[#d3a15c] to-[#c58744] hover:from-[#c58744] hover:to-[#a06c30] text-white py-3.5 rounded-xl font-bold hover:shadow-lg shadow-amber-500/20 dark:shadow-none transition transform active:scale-95 text-xs outline-none">
                    Selesaikan Transaksi
                </button>
            </div>
        </div>
    </div>
</div>

<!-- PAYMENT CONFIRMATION MODAL -->
<div id="paymentModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 backdrop-blur-sm transition-all duration-300">
    <div class="bg-white/95 dark:bg-slate-900/95 backdrop-blur-2xl border border-slate-200/80 dark:border-white/5 rounded-3xl shadow-2xl w-full max-w-lg p-6 md:p-8 relative transition-all duration-300">
        <!-- CLOSE BUTTON -->
        <button onclick="closePaymentModal()" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors outline-none w-8 h-8 rounded-full flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-800">
            <i class="fas fa-times text-sm"></i>
        </button>

        <!-- TITLE -->
        <h2 class="text-xl font-extrabold text-slate-900 dark:text-white mb-6">
            Konfirmasi Pembayaran
        </h2>

        <!-- Items list in invoice style -->
        <div class="space-y-2.5 max-h-[220px] overflow-y-auto mb-6 pr-1 divide-y divide-slate-100 dark:divide-slate-800/60" id="paymentItems"></div>

        <!-- PAYMENT METHOD -->
        <div class="mt-6">
            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3 pl-1">
                Metode Pembayaran
            </label>
            <div class="grid grid-cols-3 gap-3">
                <button type="button" onclick="selectPayment('cash')" id="payment-cash" class="payment-method border-2 border-amber-500 bg-amber-50 dark:bg-amber-950/30 text-amber-700 dark:text-amber-400 py-3 rounded-2xl font-bold transition-all text-xs outline-none shadow-sm">
                    <i class="fas fa-money-bill-wave mr-1 text-sm"></i> Cash
                </button>
                <button type="button" onclick="selectPayment('card')" id="payment-card" class="payment-method border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-350 py-3 rounded-2xl font-bold transition-all text-xs hover:bg-slate-50 dark:hover:bg-white/5 outline-none">
                    <i class="fas fa-credit-card mr-1 text-sm"></i> Card
                </button>
                <button type="button" onclick="selectPayment('qris')" id="payment-qris" class="payment-method border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-350 py-3 rounded-2xl font-bold transition-all text-xs hover:bg-slate-50 dark:hover:bg-white/5 outline-none">
                    <i class="fas fa-qrcode mr-1 text-sm"></i> QRIS
                </button>
            </div>
        </div>

        <div class="border-t border-slate-100 dark:border-slate-800/80 pt-4 mt-6">
            <div class="flex justify-between items-center">
                <span class="text-xs text-slate-400 dark:text-slate-550 font-bold uppercase tracking-wider">Total Bayar</span>
                <span id="paymentTotal" class="font-black text-2xl text-slate-900 dark:text-white tabular-nums">Rp 0</span>
            </div>
        </div>

        <!-- Checkout Action Button -->
        <button onclick="confirmCheckout()" class="w-full mt-6 bg-gradient-to-r from-[#d3a15c] to-[#c58744] hover:from-[#c58744] hover:to-[#a06c30] text-white py-3.5 rounded-2xl font-bold hover:shadow-lg shadow-[#d3a15c]/20 dark:shadow-none transition transform active:scale-98 text-sm outline-none">
            Konfirmasi Pembayaran
        </button>
    </div>
</div>

<!-- FLOATING TOAST -->
<div id="toast" class="fixed top-24 right-5 hidden z-50 transition-all duration-300 ease-out" style="transform: translateY(-10px); opacity: 0;">
    <div id="toastBox" class="px-5 py-3.5 rounded-2xl shadow-xl text-white font-bold text-xs flex items-center gap-2">
        Pesan
    </div>
</div>

<script>
let cart = [];
let paymentMethod = 'cash';

function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const box = document.getElementById('toastBox');

    box.innerHTML = type === 'success' 
        ? `<i class="fas fa-check-circle text-base"></i> ${message}`
        : `<i class="fas fa-exclamation-circle text-base"></i> ${message}`;

    if(type === 'success'){
        box.className = 'px-5 py-3.5 rounded-2xl shadow-xl text-white font-bold text-xs bg-emerald-500 flex items-center gap-2';
    } else {
        box.className = 'px-5 py-3.5 rounded-2xl shadow-xl text-white font-bold text-xs bg-rose-500 flex items-center gap-2';
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

function selectPayment(method) {
    paymentMethod = method;

    document.querySelectorAll('.payment-method').forEach(btn => {
        btn.classList.remove('border-amber-500', 'bg-amber-50', 'dark:bg-amber-950/30', 'text-amber-700', 'dark:text-amber-400', 'border-2');
        btn.classList.add('border-slate-200', 'dark:border-white/10', 'text-slate-600', 'dark:text-slate-350', 'border');
    });

    const active = document.getElementById('payment-' + method);
    active.classList.remove('border-slate-200', 'dark:border-white/10', 'text-slate-600', 'dark:text-slate-350', 'border');
    active.classList.add('border-amber-500', 'bg-amber-50', 'dark:bg-amber-950/30', 'text-amber-700', 'dark:text-amber-400', 'border-2');
}

function addToCart(id, name, price, stock) {
    let existing = cart.find(item => item.id === id);

    if(existing){
        if(existing.qty >= stock){
            showToast('Stok produk tidak mencukupi', 'error');
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

function renderCart() {
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
            <div class="border border-slate-100 dark:border-slate-800 rounded-xl p-3 bg-slate-50/50 dark:bg-slate-950/20 hover:border-slate-200 dark:hover:border-slate-700 transition-all duration-200">
                <div class="flex justify-between items-start gap-4">
                    <div class="min-w-0">
                        <h4 class="font-bold text-slate-800 dark:text-slate-250 text-xs truncate">${item.name}</h4>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5 tabular-nums">
                            Rp ${item.price.toLocaleString('id-ID')}
                        </p>
                    </div>
                    <button onclick="removeItem(${index})" class="text-slate-400 hover:text-rose-500 transition-colors w-5 h-5 flex items-center justify-center rounded-full hover:bg-slate-100 dark:hover:bg-slate-800">
                        <i class="fas fa-times text-[10px]"></i>
                    </button>
                </div>

                <div class="flex items-center gap-3.5 mt-3">
                    <button onclick="decreaseQty(${index})" class="w-6 h-6 rounded-lg bg-slate-100 dark:bg-slate-850 hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold transition-all text-xs flex items-center justify-center outline-none">
                        <i class="fas fa-minus text-[9px]"></i>
                    </button>
                    <input type="number" min="1" max="${item.stock}" value="${item.qty}" onchange="updateQty(${index}, this.value)" onkeyup="if(event.key==='Enter') this.blur()" class="w-12 text-center bg-transparent border-b border-slate-200 dark:border-slate-800 focus:border-amber-500 focus:outline-none font-extrabold text-slate-800 dark:text-slate-200 text-xs tabular-nums [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none p-0 focus:ring-0">
                    <button onclick="increaseQty(${index})" class="w-6 h-6 rounded-lg bg-amber-500 hover:bg-amber-600 text-white font-bold transition-all text-xs flex items-center justify-center outline-none">
                        <i class="fas fa-plus text-[9px]"></i>
                    </button>
                </div>
            </div>
        `;
    });

    document.getElementById('total').innerText = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('cartCount').innerText = totalItem;
}

function increaseQty(index) {
    if(cart[index].qty >= cart[index].stock){
        showToast('Stok produk telah mencapai batas', 'error');
        return;
    }
    cart[index].qty++;
    renderCart();
}

function decreaseQty(index) {
    cart[index].qty--;
    if(cart[index].qty <= 0){
        cart.splice(index, 1);
    }
    renderCart();
}

function removeItem(index) {
    cart.splice(index, 1);
    renderCart();
}

function updateQty(index, value) {
    let qty = parseInt(value);
    if (isNaN(qty) || qty <= 0) {
        qty = 1;
    }
    
    if (qty > cart[index].stock) {
        showToast('Stok produk tidak mencukupi, diset ke batas maksimum', 'error');
        qty = cart[index].stock;
    }
    
    cart[index].qty = qty;
    renderCart();
}

function checkout() {
    if(cart.length === 0){
        showToast('Keranjang pesanan masih kosong', 'error');
        return;
    }

    let paymentItems = document.getElementById('paymentItems');
    let paymentTotal = document.getElementById('paymentTotal');

    paymentItems.innerHTML = '';
    let total = 0;

    cart.forEach(item => {
        total += item.price * item.qty;
        paymentItems.innerHTML += `
            <div class="flex justify-between py-2 items-center text-xs">
                <div class="min-w-0">
                    <p class="font-bold text-slate-800 dark:text-slate-200 truncate">${item.name}</p>
                    <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5 tabular-nums">
                        ${item.qty} x Rp ${item.price.toLocaleString('id-ID')}
                    </p>
                </div>
                <p class="font-black text-slate-900 dark:text-white pl-4 shrink-0 tabular-nums">
                    Rp ${(item.price * item.qty).toLocaleString('id-ID')}
                </p>
            </div>
        `;
    });

    paymentTotal.innerText = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('paymentModal').classList.remove('hidden');
    document.getElementById('paymentModal').classList.add('flex');
}

function closePaymentModal() {
    document.getElementById('paymentModal').classList.remove('flex');
    document.getElementById('paymentModal').classList.add('hidden');
}

function confirmCheckout() {
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
        showToast('Terjadi kesalahan koneksi server', 'error');
    });
}

renderCart();

document.getElementById('searchProduct').addEventListener('keyup', function(){
    let keyword = this.value.toLowerCase();
    document.querySelectorAll('.product-item').forEach(item => {
        let name = item.dataset.name;
        item.style.display = name.includes(keyword) ? 'flex' : 'none';
    });
});
</script>
@endsection