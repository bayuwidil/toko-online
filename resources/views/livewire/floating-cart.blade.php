<div x-data="{ open: false }" @open-cart.window="open = true">
    
    <!-- Ikon Cart untuk Navbar (Tetap di posisinya) -->
    <button @click="open = true" class="relative text-gray-600 hover:text-indigo-600 transition flex items-center focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
        </svg>
        
        <!-- Badge Angka -->
        @if($cartCount > 0)
        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] font-bold rounded-full h-5 w-5 flex items-center justify-center transition-all shadow-sm">
            {{ $cartCount }}
        </span>
        @endif
    </button>

    <!-- Memindahkan elemen Cart ke luar Navbar agar tidak terpotong -->
    <template x-teleport="body">
        <div class="relative z-[100]" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
            
            <!-- Latar Belakang Gelap (Backdrop) -->
            <div x-show="open" 
                 x-transition:enter="ease-in-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in-out duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="open = false" 
                 class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" 
                 style="display: none;"></div>

            <div class="fixed inset-0 overflow-hidden" style="pointer-events: none;">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                        
                        <!-- Panel Keranjang Layar Penuh (Diperbesar ke max-w-md) -->
                        <div x-show="open"
                             @click.away="open = false"
                             x-transition:enter="transform transition ease-in-out duration-300 sm:duration-500"
                             x-transition:enter-start="translate-x-full"
                             x-transition:enter-end="translate-x-0"
                             x-transition:leave="transform transition ease-in-out duration-300 sm:duration-500"
                             x-transition:leave-start="translate-x-0"
                             x-transition:leave-end="translate-x-full"
                             class="pointer-events-auto w-screen max-w-md flex flex-col bg-white shadow-2xl" 
                             style="display: none;">

                            <!-- Header Panel -->
                            <div class="px-6 py-6 flex items-center justify-between border-b border-gray-100">
                                <h2 class="text-2xl font-bold text-gray-900">Keranjang Belanja</h2>
                                <button @click="open = false" class="text-gray-400 hover:text-red-500 transition p-2 bg-gray-50 hover:bg-red-50 rounded-full focus:outline-none">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>

                            <!-- Daftar Barang -->
                            <div class="flex-1 overflow-y-auto px-6 py-4 space-y-6">
                                @forelse($cart as $key => $item)
                                    <div class="flex items-start gap-4 group">
                                        <!-- Gambar Produk -->
                                        <div class="flex-shrink-0 w-20 h-20 bg-gray-100 rounded-xl overflow-hidden border border-gray-100">
                                            @if($item['image'])
                                                <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-xs text-gray-400">No Image</div>
                                            @endif
                                        </div>
                                        
                                        <!-- Detail Produk -->
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-base font-semibold text-gray-900 line-clamp-2 leading-tight">{{ $item['name'] }}</h3>
                                            <p class="text-indigo-600 text-base font-black mt-1">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                            <p class="text-sm text-gray-500 font-medium mt-1">Qty: {{ $item['qty'] }}</p>
                                        </div>
                                        
                                        <!-- Tombol Hapus -->
                                        <button wire:click="removeItem('{{ $key }}')" class="text-gray-300 hover:text-red-500 transition p-2 rounded-lg">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                @empty
                                    <!-- Keranjang Kosong -->
                                    <div class="h-full flex flex-col items-center justify-center text-gray-400 pb-12">
                                        <svg class="w-24 h-24 mb-6 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                        <p class="text-lg font-medium text-gray-500">Keranjang masih kosong</p>
                                        <button @click="open = false" class="mt-4 text-indigo-600 font-bold hover:underline">Mulai Belanja Sekarang</button>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Footer / Checkout Button -->
                            @if(count($cart) > 0)
                            <div class="border-t border-gray-100 px-6 py-6 bg-gray-50">
                                <div class="flex justify-between items-center mb-6">
                                    <span class="text-base font-bold text-gray-500 uppercase tracking-wider">Subtotal</span>
                                    <span class="text-2xl font-black text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>
                                <a href="{{ route('checkout', ['type' => 'buyNow']) }}" class="flex items-center justify-center w-full bg-gray-900 hover:bg-indigo-600 text-white text-lg font-bold py-4 px-6 rounded-xl transition-all duration-200 shadow-lg hover:shadow-indigo-500/30">
                                    Lanjut ke Checkout &rarr;
                                </a>
                                <p class="text-center text-xs text-gray-400 mt-4">Ongkos kirim akan dihitung di langkah selanjutnya.</p>
                            </div>
                            @endif
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>