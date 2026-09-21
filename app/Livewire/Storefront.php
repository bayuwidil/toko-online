<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;

class Storefront extends Component
{
    public function render()
    {
        // Mengambil produk yang aktif saja, urut dari yang terbaru
        $products = Product::where('is_active', true)->latest()->get();

        return view('livewire.storefront', [
            'products' => $products
        ]);
    }

    // Fungsi dummy untuk tombol Add to Cart
    public function addToCart($productId)
{
    $product = Product::with('variants')->find($productId);

    // Jika produk memiliki varian ukuran, paksa pembeli ke halaman detail
    if ($product->variants->count() > 0) {
        return redirect('/product/' . $product->slug);
    }

    // Logika jika tidak ada varian (langsung masuk keranjang)
    $cart = session()->get('cart', []);
    
    if (isset($cart[$product->id])) {
        $cart[$product->id]['qty'] += 1;
    } else {
        $cart[$product->id] = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'weight' => $product->weight,
            'image' => $product->image,
            'qty' => 1,
            'variant' => null
        ];
    }

    session()->put('cart', $cart);
    // 1. Trigger agar ikon cart mengupdate jumlah angkanya
    $this->dispatch('cartUpdated');

    // 2. Trigger agar panel keranjang otomatis membuka dari samping layar
    $this->dispatch('open-cart');
    session()->flash('message', 'Produk berhasil ditambahkan ke keranjang!');
}
}
