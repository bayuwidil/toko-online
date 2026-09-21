<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Storefront;
use App\Livewire\ProductDetail;
use App\Livewire\Checkout;


// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', Storefront::class);
Route::get('/product/{slug}', ProductDetail::class)->name('product.detail');

Route::get('/checkout', Checkout::class)->name('checkout');
Route::get('/undefined/checkout', function () {
    $type = request('type', 'buyNow');

    return redirect()->route('checkout', ['type' => $type]);
});

Route::get('/payment/success', function () {
    return view('payment.success');
})->name('payment.success');

Route::get('/payment/pending', function () {
    return view('payment.pending');
})->name('payment.pending');

Route::get('/payment/failed', function () {
    return view('payment.failed');
})->name('payment.failed');