<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // 1. Ambil semua payload JSON yang dikirim Midtrans
        $payload = $request->all();

        // (Opsional) Catat payload ke log laravel (storage/logs/laravel.log) untuk debugging
        Log::info('Midtrans Webhook Payload:', $payload);

        $orderId = $payload['order_id']; // Contoh: INV-20231024-001
        $statusCode = $payload['status_code'];
        $grossAmount = $payload['gross_amount'];
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $signatureKey = $payload['signature_key'];
        $transactionStatus = $payload['transaction_status'];

        // 2. Verifikasi Keamanan (Signature Key)
        // Rumus SHA512: order_id + status_code + gross_amount + server_key
        $expectedSignature = hash("sha512", $orderId . $statusCode . $grossAmount . $serverKey);

        if ($expectedSignature !== $signatureKey) {
            return response()->json(['message' => 'Invalid Signature!'], 403);
        }

        // 3. Cari Data Pesanan di Database
        $order = Order::with('items')->where('order_number', $orderId)->first();

        if (!$order) {
            return response()->json(['message' => 'Order Not Found'], 404);
        }

        // 4. Update Status Berdasarkan transaction_status dari Midtrans
        // Cegah update ganda jika status sudah paid/completed
        if ($order->payment_status === 'paid') {
            return response()->json(['message' => 'Order already paid']);
        }

        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            // PEMBAYARAN BERHASIL LUNAS
            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing' // Ubah status pesanan agar diproses Admin
            ]);

            // 5. POTONG STOK PRODUK DI SINI
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->decrement('stock', $item->quantity);
                }
            }

        } elseif ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            // PEMBAYARAN GAGAL / KADALUARSA
            $order->update([
                'payment_status' => 'failed',
                'status' => 'cancelled'
            ]);
            
        } elseif ($transactionStatus == 'pending') {
            // MENUNGGU PEMBAYARAN
            $order->update(['payment_status' => 'unpaid']);
        }

        // 6. Wajib mengembalikan response 200 OK ke Midtrans
        return response()->json(['message' => 'Webhook Successfully Handled']);
    }
}
