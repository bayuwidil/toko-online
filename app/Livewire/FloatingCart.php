<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class FloatingCart extends Component
{
    public $cart = [];
    public $cartCount = 0;
    public $subtotal = 0;

    public function mount()
    {
        $this->loadCart();
    }

    // Mendengarkan event 'cartUpdated' dari komponen Storefront atau ProductDetail
    #[On('cartUpdated')] 
    public function loadCart()
    {
        $this->cart = session()->get('cart', []);
        
        // Menghitung total kuantitas barang
        $this->cartCount = array_sum(array_column($this->cart, 'qty'));
        
        // Menghitung subtotal harga
        $this->subtotal = array_sum(array_map(function($item) {
            return $item['price'] * $item['qty'];
        }, $this->cart));
    }

    public function removeItem($cartKey)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
            session()->put('cart', $cart);
            
            // Reload data setelah dihapus
            $this->loadCart();
        }
    }

    public function render()
    {
        return view('livewire.floating-cart');
    }
}
