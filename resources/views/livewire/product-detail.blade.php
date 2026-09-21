<div class="max-w-7xl mx-auto py-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col md:flex-row">
        <!-- Gambar Produk -->
        <div class="md:w-1/2 bg-gray-50">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-auto object-cover">
            @else
                <div class="w-full h-96 flex items-center justify-center text-gray-400">No Image</div>
            @endif
        </div>
        
        <!-- Detail Produk -->
        <div class="md:w-1/2 p-8 md:p-12">
            @if (session()->has('message'))
                <div class="mb-4 text-green-700 bg-green-100 p-3 rounded-lg">{{ session('message') }}</div>
            @endif
            @if (session()->has('error'))
                <div class="mb-4 text-red-700 bg-red-100 p-3 rounded-lg">{{ session('error') }}</div>
            @endif

            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
            <p class="text-2xl text-indigo-600 font-black mb-6">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            
            <div class="prose text-gray-600 mb-8">
                {!! $product->description !!}
            </div>

            <!-- Tampilkan Pemilihan Varian Jika Ada -->
@if($product->variants->count() > 0)
<div class="mb-6">
    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Opsi (Ukuran / Warna)</label>
    <div class="flex flex-wrap gap-3">
        @foreach($product->variants as $variant)
            <label class="cursor-pointer">
                <input type="radio" wire:model.live="selectedVariant" value="{{ $variant->id }}" class="peer sr-only">
                <div class="px-4 py-2 border rounded-lg text-sm font-medium transition-colors 
                            peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600
                            hover:bg-gray-50 text-gray-700 border-gray-300">
                    {{ $variant->name }} 
                    @if($variant->price_adjustment > 0)
                        (+Rp{{ number_format($variant->price_adjustment, 0, ',', '.') }})
                    @endif
                </div>
            </label>
        @endforeach
    </div>
</div>
@endif

<!-- Bagian Harga Dinamis (Mengganti harga statis yang lama) -->
<p class="text-2xl text-indigo-600 font-black mb-6">Rp {{ number_format($currentPrice, 0, ',', '.') }}</p>

            <div class="flex items-center space-x-4 mb-6">
                <div class="w-24">
                    <label class="block text-sm text-gray-500 mb-1">Kuantitas</label>
                    <input type="number" wire:model="qty" min="1" max="{{ $product->stock }}" class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="pt-6">
                    <span class="text-sm text-gray-500">Tersisa: {{ $product->stock }} stok</span>
                </div>
            </div>

            <button wire:click="addToCart" class="w-full bg-gray-900 hover:bg-indigo-600 text-white font-bold py-3 px-6 rounded-xl transition flex justify-center items-center gap-2">
                Tambah ke Keranjang
            </button>
            
            <a href="{{ route('checkout', ['type' => 'buyNow']) }}" class="block text-center mt-4 text-indigo-600 font-semibold hover:underline">
                Lanjut ke Pembayaran &rarr;
            </a>
        </div>
    </div>
</div>