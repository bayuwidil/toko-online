<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Transaction;

class PaymentController extends Controller
{
    public function success(Request $request)
    {
        $orderNumber = $request->query('order_id');

        if (!$orderNumber) {
            return redirect('/')
                ->with('error', 'Nomor pesanan tidak ditemukan.');
        }

        $order = Order::with('items')
            ->where('order_number', $orderNumber)
            ->first();

        if (!$order) {
            return redirect('/')
                ->with('error', 'Pesanan tidak ditemukan.');
        }

        $this->syncOrderStatusFromMidtrans($order);

        return view('payment.success', [
            'order' => $order->fresh(),
        ]);
    }

    private function syncOrderStatusFromMidtrans(Order $order): void
    {
        if (!in_array($order->payment_status, ['unpaid', 'pending'], true)) {
            return;
        }

        try {
            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = (bool) env('MIDTRANS_IS_PRODUCTION', false);
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $status = Transaction::status($order->order_number);
            $transactionStatus = $status->transaction_status ?? null;
            $fraudStatus = $status->fraud_status ?? null;

            if (
                $transactionStatus === 'settlement'
                || (
                    $transactionStatus === 'capture'
                    && (
                        $fraudStatus === 'accept'
                        || $fraudStatus === null
                    )
                )
            ) {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                ]);

                return;
            }

            if ($transactionStatus === 'pending') {
                $order->update([
                    'payment_status' => 'pending',
                    'status' => 'pending',
                ]);

                return;
            }

            if (in_array($transactionStatus, ['deny', 'cancel', 'expire'], true)) {
                $order->update([
                    'payment_status' => 'failed',
                    'status' => 'cancelled',
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning('Midtrans status sync failed', [
                'order_number' => $order->order_number,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Halaman pembayaran pending
     */
    public function pending(Request $request)
    {
        $orderNumber = $request->query('order_id');

        $order = Order::with('items')
            ->where('order_number', $orderNumber)
            ->first();

        return view('payment.pending', [
            'order' => $order,
        ]);
    }

    /**
     * Halaman pembayaran gagal
     */
    public function failed(Request $request)
    {
        $orderNumber = $request->query('order_id');

        $order = Order::with('items')
            ->where('order_number', $orderNumber)
            ->first();

        return view('payment.failed', [
            'order' => $order,
        ]);
    }

    /**
     * Halaman nota / invoice
     */
    public function invoice(Request $request)
    {
        $orderNumber = $request->query('order_id');

        if (!$orderNumber) {
            abort(404);
        }

        $order = Order::with('items')
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('payment.invoice', [
            'order' => $order,
        ]);
    }

    /**
     * Notification dari Midtrans
     */
    public function notification(Request $request)
    {
        try {

            Config::$serverKey =
                env('MIDTRANS_SERVER_KEY');

            Config::$isProduction =
                (bool) env(
                    'MIDTRANS_IS_PRODUCTION',
                    false
                );

            Config::$isSanitized = true;

            Config::$is3ds = true;

            /*
            |--------------------------------------------------------------------------
            | Notification Midtrans
            |--------------------------------------------------------------------------
            */

            $notification = new Notification();

            $transactionStatus =
                $notification->transaction_status;

            $fraudStatus =
                $notification->fraud_status ?? null;

            $orderNumber =
                $notification->order_id;

            $paymentType =
                $notification->payment_type ?? null;

            /*
            |--------------------------------------------------------------------------
            | Cari order
            |--------------------------------------------------------------------------
            */

            $order = Order::where(
                'order_number',
                $orderNumber
            )->first();

            if (!$order) {

                Log::warning(
                    'Midtrans Order Tidak Ditemukan',
                    [
                        'order_id' => $orderNumber,
                    ]
                );

                return response()->json([
                    'message' => 'Order not found',
                ], 404);
            }

            /*
            |--------------------------------------------------------------------------
            | Simpan informasi pembayaran
            |--------------------------------------------------------------------------
            */

            $order->payment_type = $paymentType;


            if (
                $transactionStatus === 'settlement'
                ||
                ($transactionStatus === 'capture' && $fraudStatus === 'accept')
            ) {

                DB::transaction(function () use ($order) {

                    $order->refresh();

                    // Sudah pernah dikurangi sebelumnya
                    if ($order->stock_deducted) {
                        return;
                    }

                    $order->load('items');

                    foreach ($order->items as $item) {

                        $product = \App\Models\Product::where('id', $item->product_id)
                            ->lockForUpdate()
                            ->first();

                        if (!$product) {
                            throw new \Exception(
                                "Produk ID {$item->product_id} tidak ditemukan."
                            );
                        }

                        if ($product->stock < $item->quantity) {
                            throw new \Exception(
                                "Stok produk {$product->name} tidak mencukupi."
                            );
                        }

                        $product->decrement('stock', $item->quantity);
                    }

                    $order->payment_status = 'paid';
                    $order->status = 'processing';
                    $order->stock_deducted = true;

                    $order->save();
                });
            }

            Log::info(
                'Midtrans Notification Berhasil',
                [
                    'order_id' =>
                        $orderNumber,

                    'transaction_status' =>
                        $transactionStatus,

                    'payment_type' =>
                        $paymentType,

                    'payment_status' =>
                        $order->payment_status,
                ]
            );

            return response()->json([
                'message' => 'OK',
            ]);

        } catch (\Throwable $e) {

            Log::error(
                'Midtrans Notification Error',
                [
                    'message' =>
                        $e->getMessage(),

                    'trace' =>
                        $e->getTraceAsString(),
                ]
            );

            return response()->json([
                'message' => 'Notification error',
            ], 500);
        }
    }
}
