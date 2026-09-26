<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Storefront;
use App\Livewire\ProductDetail;
use App\Livewire\Checkout;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SalesReportController;

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




Route::get(
    '/payment/success',
    [PaymentController::class, 'success']
)->name('payment.success');

Route::get(
    '/payment/pending',
    [PaymentController::class, 'pending']
)->name('payment.pending');

Route::get(
    '/payment/failed',
    [PaymentController::class, 'failed']
)->name('payment.failed');

Route::get(
    '/payment/invoice',
    [PaymentController::class, 'invoice']
)->name('payment.invoice');



Route::get(
    '/admin/sales-report/pdf',
    [SalesReportController::class, 'pdf']
)->name('filament.admin.sales-report.pdf');