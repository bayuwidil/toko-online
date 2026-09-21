<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransNotificationController extends Controller
{
    public function handle(Request $request)
    {
        Config::$serverKey =
            env('MIDTRANS_SERVER_KEY');

        Config::$isProduction =
            (bool) env(
                'MIDTRANS_IS_PRODUCTION',
                false
            );

        Config::$isSanitized = true;

        Config::$is3ds = true;

        try {

            $notification = new Notification();

            $orderNumber =
                $notification->getOrderId();

            $transactionStatus =
                $notification->getTransactionStatus();

            $fraudStatus =
                $notification->getFraudStatus();

            $paymentType =
                $notification->getPaymentType();

            $transactionId =
                $notification->getTransactionId();

            $order = Order::where(
                'order_number',
                $orderNumber
            )->first();

            if (!$order) {

                Log::warning(
                    'Midtrans order tidak ditemukan',
                    [
                        'order_number' =>
                            $orderNumber,
                    ]
                );

                return response()->json([
                    'message' =>
                        'Order tidak ditemukan'
                ], 404);
            }

            /*
             * SUCCESS
             */
            if (
                $transactionStatus === 'settlement'
            ) {

                $order->update([

                    'payment_status' =>
                        'paid',

                    'status' =>
                        'processing',
                ]);
            }

            /*
             * Credit Card
             */
            elseif (
                $transactionStatus === 'capture'
            ) {

                if (
                    $fraudStatus === 'accept'
                    || !$fraudStatus
                ) {

                    $order->update([

                        'payment_status' =>
                            'paid',

                        'status' =>
                            'processing',
                    ]);
                }
            }

            /*
             * PENDING
             */
            elseif (
                $transactionStatus === 'pending'
            ) {

                $order->update([

                    'payment_status' =>
                        'pending',

                    'status' =>
                        'pending',
                ]);
            }

            /*
             * FAILED
             */
            elseif (
                in_array(
                    $transactionStatus,
                    [
                        'deny',
                        'cancel',
                        'expire',
                    ]
                )
            ) {

                $order->update([

                    'payment_status' =>
                        'failed',

                    'status' =>
                        'cancelled',
                ]);
            }

            Log::info(
                'Midtrans notification processed',
                [
                    'order_number' =>
                        $orderNumber,

                    'transaction_status' =>
                        $transactionStatus,

                    'payment_type' =>
                        $paymentType,

                    'transaction_id' =>
                        $transactionId,
                ]
            );

            return response()->json([
                'message' => 'OK'
            ]);

        } catch (\Throwable $e) {

            Log::error(
                'Midtrans notification error',
                [
                    'message' =>
                        $e->getMessage(),

                    'payload' =>
                        $request->all(),
                ]
            );

            return response()->json([
                'message' =>
                    'Notification error'
            ], 500);
        }
    }
}