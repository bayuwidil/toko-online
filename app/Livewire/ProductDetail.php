<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;

class ProductDetail extends Component
{
    public $product;
    public $qty = 1;
    public $selectedVariant = null; // Menyimpan ID Varian yang dipilih
    public $currentPrice;

    public function mount($slug)
    {
        $this->product = Product::with('variants')->where('slug', $slug)->firstOrFail();
        $this->currentPrice = $this->product->price;
    }

    public function updatedSelectedVariant($variantId)
    {
        // Ubah harga secara real-time jika varian dipilih
        if ($variantId) {
            $variant = $this->product->variants->find($variantId);
            $this->currentPrice = $this->product->price + $variant->price_adjustment;
        } else {
            $this->currentPrice = $this->product->price;
        }
    }

    public function addToCart()
    {
        // Validasi: Jika produk punya varian, pembeli wajib memilih
        if ($this->product->variants->count() > 0 && !$this->selectedVariant) {
            session()->flash('error', 'Silakan pilih varian/ukuran terlebih dahulu.');
            return;
        }

        $cartKey = $this->product->id . '-' . ($this->selectedVariant ?? 'base');
        $cart = session()->get('cart', []);

        $variantName = null;
        if ($this->selectedVariant) {
            $variantData = $this->product->variants->find($this->selectedVariant);
            $variantName = $variantData->name;
            
            // Cek stok varian
            if ($this->qty > $variantData->stock) {
                session()->flash('error', 'Stok varian tidak mencukupi.');
                return;
            }
        }

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['qty'] += $this->qty;
        } else {
            $cart[$cartKey] = [
                'id' => $this->product->id,
                'name' => $this->product->name . ($variantName ? ' - ' . $variantName : ''),
                'price' => $this->currentPrice,
                'weight' => $this->product->weight,
                'image' => $this->product->image,
                'qty' => $this->qty,
                'variant_id' => $this->selectedVariant
            ];
        }

        session()->put('cart', $cart);
        // 1. Trigger agar ikon cart mengupdate jumlah angkanya
        $this->dispatch('cartUpdated');

        // 2. Trigger agar panel keranjang otomatis membuka dari samping layar
        $this->dispatch('open-cart');
        session()->flash('message', 'Berhasil ditambahkan ke keranjang!');
    }

    public function render()
    {
        return view('livewire.product-detail');
    }
}
