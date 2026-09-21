<div>
    <!-- Pesan Sukses Add to Cart -->
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 text-sm" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Koleksi Terbaru</h1>
        <p class="text-gray-500 mt-1">Temukan produk terbaik untuk kebutuhan Anda.</p>
    </div>

    <!-- Grid Produk -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse ($products as $product)
            <!-- Kartu Produk Minimalis -->
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-shadow duration-300 overflow-hidden border border-gray-100 group flex flex-col">
    
    <!-- Area Gambar & Nama (Bisa Diklik) -->
    <a href="/product/{{ $product->slug }}" class="block">
        <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden bg-gray-100 relative">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-64 object-cover object-center group-hover:scale-105 transition-transform duration-500">
            @else
                <div class="w-full h-64 flex items-center justify-center text-gray-400">No Image</div>
            @endif
        </div>
        <div class="px-5 pt-5 pb-2">
            <h3 class="text-lg font-semibold text-gray-900 truncate group-hover:text-indigo-600 transition-colors">{{ $product->name }}</h3>
            <p class="text-indigo-600 font-bold mt-1">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
        </div>
    </a>

    <!-- Area Tombol Keranjang -->
    <div class="p-5 pt-0 mt-auto">
        <button 
            wire:click="addToCart({{ $product->id }})" 
            class="w-full bg-gray-900 hover:bg-indigo-600 text-white font-medium py-2.5 px-4 rounded-xl transition-colors flex justify-center items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah ke Keranjang
        </button>
    </div>
</div>
        @empty
            <div class="col-span-full text-center py-12 text-gray-500 bg-white rounded-2xl border border-dashed border-gray-300">
                Belum ada produk yang tersedia.
            </div>
        @endforelse
    </div>
</div>
